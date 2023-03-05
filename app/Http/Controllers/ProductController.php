<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Color;
use App\Models\Product;
use App\Models\CategoryProduct;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DataTables $dataTables)
    {
        if ($this->isAPI()) {
            // $sales = Sale::select('product_id', DB::raw('quantity as qty'))->groupBy('product_id')->orderBy('qty','DESC')->get();
            // $idprod = [];
            // foreach($sales as $sale){
            //     array_push($idprod,$sale->product_id);
            // }

            // $products = CategoryProduct::with(['products' => function($query) use ($idprod) {
            //     $query->whereIn('id',$idprod);
            //     $query->where('status',1);
            // }])->where('status',1)->get();

            // $products = CategoryProduct::with(['products' => function($query){
            //     $query->when(request('details') === "true", function($query){
            //         $query->orderBy('name');
            //         $query->where('status',1);
            //     }, function($query){
            //         $query->where('status',1);
            //         $query->withSum('sales','quantity');
            //         $query->orderByDESC('sales_sum_quantity');
            //     });
            // }])->where('status',1)->get();

            // if(request('details') === "true"){
            //     $products = CategoryProduct::where('id',request('id'))->with(['products' => function($query){
            //         $query->orderBy('name');
            //         $query->where('status',1);
            //     }])->where('status',1)->get();
            // }else{
            //     $products = CategoryProduct::with(['products' => function($query){
            //         $query->where('status',1);
            //         $query->withSum('sales','quantity');
            //         $query->orderByDESC('sales_sum_quantity');
            //         $query->take('10');
            //     }])->where('status',1)->get();
            // }

            if (request('details') === "true") {
                $products = CategoryProduct::where('id', request('id'))->with(['products' => function ($query) {
                    $query->orderBy('name');
                    $query->where('status', 1);
                }])->where('status', 1)->get();
            } else {
                $products = CategoryProduct::with(['products' => function ($query) {
                    $query->where('status', 1);
                    $query->withaSum('sales', 'quantity');
                    $query->orderByDESC('sales_sum_quantity');
                }])->where('status', 1)->get();
                $products = $products->map(function ($list) {
                    if ($list->products) {
                        return [
                            'id' => $list->id,
                            'name' => $list->name,
                            'status' => $list->status,
                            'products' => $list->products->take(10)
                        ];
                    }
                });
            }

            return $this->success($products);
        }

        if (request()->ajax()) {
            $model = Product::with('category_product');
            return $dataTables->eloquent($model)
                ->addColumn('image', function (Product $product) {
                    if ($product->filename) {
                        $path = asset('/storage/product/' . $product->filename);
                    } else {
                        $path = asset('/assets/images/image-default.png');
                    }
                    return '<img src="' . $path . '" alt="Image Product">';
                })
                ->addColumn('price', function (Product $product) {
                    return "Rp. " . number_format($product->price, 2);
                })
                ->addColumn('action', function (Product $product) {
                    $btn = '';

                    if (auth()->user()->can('product_update')) {
                        $editPath = asset('/assets/images/edit-icon.png');
                        $btn = '<div class="action__wrapper"><a href="' . route("product.edit", ["product" => $product->id]) . '"><img src="' . $editPath . '" alt="Edit Icon"></a>';
                    }

                    if (auth()->user()->can('product_delete')) {
                        $deletePath = asset('/assets/images/delete-icon.png');
                        $btn = $btn .
                            '<a href="javascript:void(0)" data-id="' . $product->id . '" data-toggle="modal"
                                        data-target="#deleteConfirmationModal" id="btnDelete"><img src="' . $deletePath . '" alt="Delete Icon"></a></div>';
                    }

                    return $btn;
                })
                ->rawColumns(['image', 'price', 'action'])
                ->toJson();
        }

        return view('pages.product');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = CategoryProduct::where('status', 1)->get();
        // $colors = Product::GetColors();
        $colors = Color::where('status', 1)->get();
        return view('pages.product-create', compact('categories', 'colors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        Product::StoreData($request);

        return redirect()->route('product.index');
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
        if ($this->isAPI()) {
            $product = Product::findOrfail($id);
            $product->colors = Color::whereIn('id', $product->color_ids)->where('status', 1)->get();
            $product->path = asset('/storage/product/');

            return $this->success($product);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = CategoryProduct::where('status', 1)->get();
        // $colors = Product::GetColors();

        $colors = Color::where('status',1)->get();

        return view('pages.product-edit', compact('product', 'categories', 'colors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // dd($request->price);
        Product::UpdateData($product, $request);

        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        Product::DeleteData($product);

        $res = [
            'success' => true,
            'messages' => "Product deleted successfully"
        ];

        return response()->json($res);
    }
}
