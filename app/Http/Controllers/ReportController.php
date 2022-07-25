<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Color;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Region;
use App\Models\Sale;
use App\Models\Store;
use App\Models\Target;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    public function sale(DataTables $dataTables)
    {
        if (request()->ajax()) {
            $model = Sale::with(['user', 'store.channel.region', 'product', 'color'])
                ->when(request('mainStore'), function ($query) {
                    $query->whereHas('store.channel', function ($query) {
                        $query->where('id', request('mainStore'));
                    })->select('*', 'sales.created_at as sale_created_at');
                })
                ->when(request('branchStore'), function ($query) {
                    $query->where('store_id', request('branchStore'))
                        ->select('*', 'sales.created_at as sale_created_at');
                })
                ->when(request('employee'), function ($query) {
                    $query->where('user_id', request('employee'))
                        ->select('*', 'sales.created_at as sale_created_at');
                })
                ->when(request('region'), function ($query) {
                    $query->whereHas('store.channel.region', function ($query) {
                        $query->where('id', request('region'));
                    })->select('*', 'sales.created_at as sale_created_at');
                })
                ->when(request('product'), function ($query) {
                    $split = explode('-', request('product'));
                    $product_id = $split[0];
                    if (count($split) > 1) {
                        $color_id = $split[1];
                    } else {
                        $color_id = null;
                    }

                    $query->has('product')->where('product_id', $product_id)->where(function ($query) use ($color_id) {
                        if ($color_id) {
                            $query->where('color_id', $color_id);
                        }
                    })->select('*', 'sales.created_at as sale_created_at');
                })
                ->when(request('minDate'), function ($query) {
                    $query->whereDate('sales.created_at', '>=', request('minDate'));
                })
                ->when(request('maxDate'), function ($query) {
                    $query->whereDate('sales.created_at', '<=', request('maxDate'));
                })
                ->unless(request('minDate') || request('maxDate'), function ($query) {
                    $query->whereMonth('sales.created_at', now());
                });

            return $dataTables->eloquent($model)
                ->editColumn('created_at', function (Sale $sale) {
                    if (!$sale->sale_created_at) {
                        return Carbon::parse($sale->created_at)->format('d/m/Y H:i');
                    } else {
                        return Carbon::parse($sale->sale_created_at)->format('d/m/Y H:i');
                    }
                })
                ->addColumn('product', function (Sale $sale) {
                    if ($sale->product) {
                        if ($sale->color) {
                            return $sale->product->name . ' ' . '(' . strtoupper($sale->color->name) . ')';
                        } else {
                            return $sale->product->name;
                        }
                    }
                })
                ->addColumn('total', function () use ($model) {
                    $converted = number_format($model->sum('nominal'), 2);
                    return 'Rp ' . $converted;
                })
                ->editColumn('nominal', function (Sale $sale) {
                    $converted = number_format($sale->nominal, 2);
                    return 'Rp' . $converted;
                })
                ->addIndexColumn()
                ->toJson();
        }

        $productsRaw = Product::select('id', 'name', 'color_ids')->where('status', 1)->get();

        $products = [];

        foreach ($productsRaw as $list) {
            if ($list->color_ids) {
                $colors = Color::whereIn('id', $list->color_ids)->get();
                for ($i = 0; $i < count($colors); $i++) {
                    $products[] = [
                        'product_id' => $list->id,
                        'color_id' => $colors[$i]->id,
                        'color' => $colors[$i]->name,
                        'name' => $list->name
                    ];
                }
            } else {
                $products[] = [
                    'product_id' => $list->id,
                    'color_id' => null,
                    'color' => null,
                    'name' => $list->name
                ];
            }
        }

        $regions = Region::select('id', 'name')->where('status', 1)->get();

        $total = Sale::select('nominal')->whereMonth('created_at', now())->sum('nominal');
        $total = number_format($total, 2);
        $total = 'Rp' . $total;

        return view('pages.sale-report', compact('products', 'regions', 'total'));
    }

    public function customer(DataTables $dataTables)
    {
        if (request()->ajax()) {
            $model = Customer::with(['sales', 'job', 'age']);

            return $dataTables->eloquent($model)
                ->addColumn('order', function (Customer $customer) {
                    if ($customer->sales) {
                        $sale = $customer->sales;
                        return $sale->count();
                    } else {
                        return 0;
                    }
                })
                ->addColumn('total', function () use ($model) {
                    return $model->count();
                })
                ->addIndexColumn()
                ->toJson();
        }

        $total = Customer::whereMonth('created_at', now())->count();

        return view('pages.customer-report', compact('total'));
    }

    function busyData()
    {
        $model = Sale::with(['store.channel.region', 'product'])
            ->when(request('mainStore'), function ($query) {
                $query->whereHas('store.channel', function ($query) {
                    $query->where('id', request('mainStore'));
                })->select('*', 'sales.created_at as sale_created_at');
            })
            ->when(request('branchStore'), function ($query) {
                $query->where('store_id', request('branchStore'))
                    ->select('*', 'sales.created_at as sale_created_at');
            })
            ->when(request('region'), function ($query) {
                $query->whereHas('store.channel.region', function ($query) {
                    $query->where('id', request('region'));
                })->select('*', 'sales.created_at as sale_created_at');
            })
            ->when(request('product'), function ($query) {
                $split = explode('-', request('product'));
                $product_id = $split[0];
                if (count($split) > 1) {
                    $color_id = $split[1];
                } else {
                    $color_id = null;
                }

                $query->has('product')->where('product_id', $product_id)->where(function ($query) use ($color_id) {
                    if ($color_id) {
                        $query->where('color_id', $color_id);
                    }
                })->select('*', 'sales.created_at as sale_created_at');
            })
            ->when(request('minDate'), function ($query) {
                $query->whereDate('sales.created_at', '>=', request('minDate'));
            })
            ->when(request('maxDate'), function ($query) {
                $query->whereDate('sales.created_at', '<=', request('maxDate'));
            })
            ->unless(request('minDate') || request('maxDate'), function ($query) {
                $query->whereMonth('sales.created_at', now());
            })->get()->sortBy('created_at');

        return $model;
    }

    public function busy(DataTables $dataTables)
    {
        if (request()->ajax()) {
            $model = $this->busyData();

            return $dataTables->of($model)
                ->editColumn('created_at', function (Sale $sale) {
                    if ($sale->sale_created_at) {
                        return Carbon::parse($sale->sale_created_at)->format('m/d/Y H:i');
                    } else {
                        return Carbon::parse($sale->created_at)->format('m/d/Y H:i');
                    }
                })
                ->addColumn('product', function (Sale $sale) {
                    if ($sale->color) {
                        return $sale->product->name . ' ' . '(' . strtoupper($sale->color->name) . ')';
                    } else {
                        return $sale->product->name;
                    }
                })
                ->addColumn('nominal_raw', function (Sale $sale) {
                    return $sale->nominal;
                })
                ->editColumn('nominal', function (Sale $sale) {
                    $converted = number_format($sale->nominal, 2);
                    return 'Rp' . $converted;
                })
                ->addIndexColumn()
                ->toJson();
        }

        $regions = Region::select('id', 'name')->where('status', 1)->get();
        $productsRaw = Product::select('id', 'name', 'color_ids')->where('status', 1)->get();

        $products = [];

        foreach ($productsRaw as $list) {
            if ($list->color_ids) {
                $colors = Color::whereIn('id', $list->color_ids)->get();
                for ($i = 0; $i < count($colors); $i++) {
                    $products[] = [
                        'product_id' => $list->id,
                        'color_id' => $colors[$i]->id,
                        'color' => $colors[$i]->name,
                        'name' => $list->name
                    ];
                }
            } else {
                $products[] = [
                    'product_id' => $list->id,
                    'color_id' => null,
                    'color' => null,
                    'name' => $list->name
                ];
            }
        }

        return view('pages.busy-report', compact('regions', 'products'));
    }

    public function busyChart()
    {
        if (request()->ajax()) {
            $sale = $this->busyData();

            return response()->json($sale);
        }
    }

    public function target(DataTables $dataTables)
    {
        if (request()->ajax()) {
            $model = Target::with(['user.sales'])
                ->when(request('date'), function ($query) {
                    $query->whereDate('targets.created_at', '<=', request('date'));
                })
                ->unless(request('date'), function ($query) {
                    $query->whereDate('targets.created_at', '<=', now());
                })->orderByDesc('created_at')->get()->unique('user_id')->sortBy('id');

            return $dataTables->of($model)
                ->addColumn('percentage', function (Target $target) {
                    if ($target->user) {

                        if (request('date')) {
                            $percentage = $target->GetPercentage($target, request('date'));
                        } else {
                            $percentage = $target->GetPercentage($target, null);
                        }

                        // if ($percentage['percentage'] > 100) {
                        //     $percentage['percentage'] = 100;
                        // }

                        return $percentage['percentage'] .  '%/100%';
                    }
                })
                ->addColumn('status', function (Target $target) {
                    if ($target->user) {
                        if (request('date')) {
                            $percentage = $target->GetPercentage($target, request('date'));
                        } else {
                            $percentage = $target->GetPercentage($target, null);
                        }

                        $targetText = $percentage['currentSales'] . '/' . $percentage['currentTarget'];

                        return $targetText;
                    }
                })
                ->addIndexColumn()
                ->toJson();
        }

        $employees = User::whereHas('has_role', function ($query) {
            $query->where('model_has_roles.role_id', 3)->orWhere('model_has_roles.role_id', 4);
        })->select('name')->get();

        return view('pages.target-report', compact('employees'));
    }
}
