<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = QueryBuilder::for(Company::class)
            ->allowedFilters( 'vat_id',
                AllowedFilter::exact('country', 'country_id'),
                AllowedFilter::callback('search', function($query, $value) {
                    $query->where('name', 'like', "%{$value}%")->orWhere('vat_id', 'like', "%{$value}%");
                }),
                AllowedFilter::callback('name', function($query, $value) {
                    $query->where('name', 'like', "%{$value}%");
                })
            )
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return CompanyResource::collection($companies);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'vat_id' => ['required'],
            'address' => ['nullable'],
            'address2' => ['nullable'],
            'city' => ['nullable'],
            'postal_code' => ['nullable'],
            'phone' => ['nullable'],
            'email' => ['nullable', 'email', 'max:254'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
        ]);

        return new CompanyResource(Company::create($data));
    }

    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => ['required'],
            'vat_id' => ['required'],
            'address' => ['nullable'],
            'address2' => ['nullable'],
            'city' => ['nullable'],
            'postal_code' => ['nullable'],
            'phone' => ['nullable'],
            'email' => ['nullable', 'email', 'max:254'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
        ]);

        $company->update($data);

        return new CompanyResource($company);
    }

    public function destroy(Company $company)
    {
        //$company->delete();

        return response()->noContent();
    }
}
