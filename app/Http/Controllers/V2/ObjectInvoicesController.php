<?php

namespace App\Http\Controllers\V2;

use App\Actions\Subscriptions\CreateInvoice;
use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Membership;
use App\Models\SchoolGroup;
use App\Models\Subscription;
use App\Models\TaxClass;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObjectInvoicesController extends Controller
{
    private $route;
    private $model;
    private $subs;

    public function __construct(Request $request) {
        $this->middleware('auth:api');
        if ($request->route()) {
            $name = explode('.', $request->route()->getName())[0];
            switch ($name) {
                case 'school_group':
                    $this->route = 'school_group';
                    $this->model = SchoolGroup::class;
                    $this->subs = 'subscriptions';
                    break;
                case 'membership':
                    $this->route = 'membership';
                    $this->model = Membership::class;

            }
        }
    }

    public function store(Request $request, $object_id) {
        $object = $this->model::findOrFail($object_id);
        if ($this->route === 'school_group') {
            $createInvoice = new CreateInvoice();
        }
        if ($this->route === 'membership') {
            $createInvoice = new \App\Actions\Membership\CreateInvoice();
        }
        $invoices = $createInvoice->execute($object);
        return JsonResource::make($invoices);
    }
}
