<?php

namespace App\Http\Controllers\V2;

use App\Actions\SaveDocument;
use App\Http\Resources\DocumentCollection;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class DocumentController extends Controller
{
    private $route = 'league';

    private $model = 'App\Models\League';

    public function __construct(Request $request)
    {
        //$this->middleware('auth:api');
        if ($request->route()) {
            $name = explode('.', $request->route()->getName())[0];
            switch ($name) {
                case 'league':
                    $this->route = 'league';
                    $this->model = 'App\Models\League';
                    break;
                case 'league_group':
                    $this->route = 'league_group';
                    $this->model = 'App\Models\League\Group';
                    break;
                case 'tournament':
                    $this->route = 'tournament';
                    $this->model = 'App\Models\Tournament';
                    break;
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id = null)
    {
        if ($id) {
            $documents = QueryBuilder::for($this->model::where('id', $id)->first()->documents())
                ->paginate($request->input('limit'))
                ->appends($request->query());
        } else {
            $documents = QueryBuilder::for(Document::class)
                ->paginate($request->input('limit'))
                ->appends($request->query());
        }

        return DocumentCollection::make($documents);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null)
    {
        $validated = $request->validate([
            'file' => 'required|file',
            'title' => 'sometimes',
        ]);
        $d = SaveDocument::save($validated['file'], ['title' => $validated['title']]);
        if ($id) {
            $object = $this->model::find($id);
            $object->documents()->attach($d->id);
        }

        return DocumentResource::make($d);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        return DocumentResource::make($document);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document, $id = null)
    {
        $validate = $request->validate([
            'title' => 'sometimes',
            'description' => 'sometimes',
        ]);

        $document->update($validate);

        return DocumentResource::make($document);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        $document->delete();

        return response()->noContent();
    }
}
