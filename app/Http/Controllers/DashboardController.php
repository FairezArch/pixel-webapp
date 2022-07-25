<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bestStores = Sale::with('store')->get()->groupBy('store_id');
        $bestStores = $bestStores->mapWithKeys(function ($sale, $key) {
            return [
                $key => [
                    'name' => $sale[0]->store->name,
                    'text' => 'Rp' . number_format($sale->sum('nominal'), 2)
                ]
            ];
        })->values();

        $bestEmployees = Sale::with('user')->get()->groupBy('user_id');
        $bestEmployees = $bestEmployees->mapWithKeys(function ($sale, $key) {
            return [
                $key => [
                    'name' => $sale[0]->user->name,
                    'text' => 'Rp' . number_format($sale->sum('nominal'), 2),
                    'image' => $sale[0]->user->filename
                ]
            ];
        })->values();

        return view('pages.dashboard', compact('bestStores', 'bestEmployees'));
    }

    public function saleChart()
    {
        if (request()->ajax()) {
            $sale = Sale::select('nominal', 'created_at')->when(request('monthFilter'), function ($query) {
                $query->whereMonth('created_at', Carbon::parse(request('monthFilter')));
            }, function ($query) {
                $query->whereMonth('created_at', now());
            })->orderBy('created_at')->get()->groupBy('created_at');

            $sale = $sale->mapWithKeys(function ($list, $key) {
                return [
                    $key => [
                        'x' => Carbon::parse($key)->format('m/d/Y'),
                        'y' => $list->sum('nominal')
                    ]
                ];
            })->values();

            return $this->success($sale);
        }
    }

    public function busyChart()
    {
        if (request()->ajax()) {
            $sale = Sale::select('created_at')->when(request('monthFilter'), function ($query) {
                $query->whereMonth('created_at', Carbon::parse(request('monthFilter')));
            }, function ($query) {
                $query->whereMonth('created_at', now());
            })->orderBy('created_at')->get()->groupBy(function ($query) {
                return Carbon::parse($query->created_at)->format('m/d/Y H:00');
            });

            $sale = $sale->mapWithKeys(function ($list, $key) {
                return [
                    $key => [
                        'x' => Carbon::parse($key)->format('m/d/Y H:i'),
                        'y' => $list->count()
                    ]
                ];
            })->values();

            return $this->success($sale);
        }
    }

    public function productChart()
    {
        if (request()->ajax()) {
            $sale = Product::whereHas('sales', function ($query) {
                $query->latest();
            })->take(5)->get();

            $sale = $sale->map(function ($list) {
                $sum = $list->sales->sum('quantity');

                return [
                    'labels' => $list->name,
                    'sum' => $sum
                ];
            });

            $labels = $sale->mapToGroups(function ($list) {
                return [$list['labels']];
            });

            $values = $sale->mapToGroups(function ($list) {
                return [$list['sum']];
            });

            $data = [
                'labels' => $labels,
                'values' => $values
            ];

            return $this->success($data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
