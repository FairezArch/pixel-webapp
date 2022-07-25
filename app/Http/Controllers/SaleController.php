<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Models\Channel;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\GetSaleRequest;

class SaleController extends Controller
{
    public function index(GetSaleRequest $request, DataTables $dataTables)
    {
        if ($this->isAPI()) {
            return $this->success(Sale::GetData($request));
        }
        if (request()->ajax()) {
            $model = Sale::with(['user', 'store.channel.region'])
                ->when(request('mainStore'), function ($query) {
                    $query->whereHas('store.channel', function ($query) {
                        $query->where('id', request('mainStore'));
                    });
                })
                ->when(request('branchStore'), function ($query) {
                    $query->where('store_id', request('branchStore'));
                })
                ->when(request('minDate'), function ($query) {
                    $query->whereDate('sales.created_at', '>=', request('minDate'));
                })
                ->when(request('maxDate'), function ($query) {
                    $query->whereDate('sales.created_at', '<=', request('maxDate'));
                });

            return $dataTables->eloquent($model)
                ->addColumn('image', function (Sale $sale) {
                    if ($sale->photo_filename) {
                        $path = asset('/storage/sale_photo/' . $sale->photo_filename);
                    } else {
                        $path = asset('/assets/images/image-default.png');
                    }
                    return '<img src="' . $path . '" alt="Sale Image">';
                })
                ->addColumn('name', function (Sale $sale) {
                    if ($sale->user) {
                        return $sale->user->name;
                    }
                })
                ->addColumn('regional', function (Sale $sale) {
                    if ($sale->store) {
                        return $sale->store->channel->region->name;
                    }
                })
                ->addColumn('branchStore', function (Sale $sale) {
                    if ($sale->store) {
                        return $sale->store->name;
                    }
                })
                ->addColumn('mainStore', function (Sale $sale) {
                    if ($sale->store) {
                        return $sale->store->channel->name;
                    }
                })
                ->editColumn('nominal', function (Sale $sale) {
                    $converted = number_format($sale->nominal, 2);
                    return 'Rp' . $converted;
                })
                ->rawColumns(['image'])
                ->toJson();
        }

        $branch_stores = Store::where('status', 1)->get();
        $main_stores = Channel::where('status', 1)->get();

        return view('pages.sales', compact('branch_stores', 'main_stores'));
    }

    public function create()
    {
        //


    }

    public function store(StoreSaleRequest $request)
    {
        if (empty($request->products)) {
            return $this->fail('Tolong tambahkan produk');
        }
        if (!($request->customer_gender == 'l' || $request->customer_gender == 'p'))
            return $this->fail('Gender isn\'t recognized');
        $data = Sale::StoreData($request);
        return $this->success($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
