<?php

namespace App\Http\Controllers\V2\Shop;

use App\Http\Controllers\V2\Controller;
use Illuminate\Http\Request;
use App\Models\Shop\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{

    public function index(Request $request) {
        $products = QueryBuilder::for(Product::class)
            ->allowedFilters([
                'type', 'name', 'status','special',
                AllowedFilter::exact('club', 'club_id'),
                AllowedFilter::exact('category', 'category_id'),
            ])
        ->allowedIncludes(['club', 'category'])
            ->allowedSorts([
                AllowedSort::callback('distance', function ($query, $direction) use ($request) {
                    $direction = $direction ? 'DESC' : 'ASC';
                    $query->join('clubs', 'shop_products.club_id', '=', 'clubs.id')->orderByRaw("ST_Distance_Sphere(point(clubs.longitude, clubs.latitude), point(?, ?)) $direction", [
                        $request->input('longitude'),
                        $request->input('latitude'),
                    ]);
                }),
        ])
            ->select('shop_products.*');
        if (!$request->input('filter.special')) {
            $products->whereNull('special');
        }
        $products = $products
            ->paginate($request->input('limit'))
            ->appends($request->query());

        return JsonResource::collection($products);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'description' => 'sometimes',
            'special' => 'sometimes',
            'status' => 'sometimes',
            'type' => 'sometimes',
            'tax_percent' => 'sometimes',
            'image' => 'sometimes',
            'category.id' => 'sometimes|exists:shop_categories,id',
            'stock' => 'sometimes|integer',
            'sku' => 'sometimes',
            'waiting_list' => 'sometimes|boolean',
            'wish_list' => 'sometimes|array',
        ]);

        if (!isset($validated['tax_percent'])) {
            $validated['tax_percent'] = 25;
        }

        if (!isset($validated['type'])) {
            $validated['type'] = 'simple';
        }

        if (isset($validated['category'])) {
            $validated['category_id'] = $validated['category']['id'];
            unset($validated['category']);
        }

        $validated['club_id'] = $request->get('club')->id;
        $image = false;
        if (isset($validated['image'])) {
            $image = $validated['image'];
        }
        unset($validated['image']);
        /** @var Product $product */
        $product = Product::create($validated);

        if ($image) {
            $product->images()->attach($image, ['main' => true]);
        }

        return JsonResource::make($product);
    }

    public function show(Product $product) {
        return JsonResource::make($product);
    }

    public function update(Request $request, Product $product) {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'description' => 'sometimes',
            'special' => 'sometimes',
            'status' => 'sometimes',
            'type' => 'sometimes',
            'tax_percent' => 'sometimes',
            'category.id' => 'sometimes|exists:shop_categories,id',
            'image' => 'sometimes',
            'stock' => 'sometimes|integer',
            'sku' => 'sometimes',
            'waiting_list' => 'sometimes|boolean',
            'wish_list' => 'sometimes|array',
        ]);

        if(isset($validated['category'])) {
            $validated['category_id'] = $validated['category']['id'];
            unset($validated['category']);
        }

        $image = false;
        if (isset($validated['image'])) {
            $image = $validated['image'];
        }
        unset($validated['image']);

        if ($validated['wish_list']) {
            $wish_list = collect($product->wish_list)->map(function($item) {
                return $item['id'];
            });
            foreach ($validated['wish_list'] as $item) {
                if (is_array($item)) {
                    $wish_list->add($item['id']);
                } else {
                    $wish_list->add($item);
                }
            }

            $validated['wish_list'] = $wish_list->unique()->toArray();
        }

        $product->update($validated);

        if ($image) {
            $product->images()->sync([$image => ['main' => true]]);
        }

        return JsonResource::make($product);
    }

    public function destroy(Request $request, Product $product) {
        $product->delete();

        return response()->noContent();
    }
}
