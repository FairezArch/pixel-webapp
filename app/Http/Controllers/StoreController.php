<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DataTables $dataTables)
    {
        //
        if (request()->ajax()) {
            $model = Store::query();
            return $dataTables->eloquent($model)
                ->addColumn('status', function (Store $store) {
                    if ($store->status) {
                        $status = 'Aktif';
                        $class = 'active';
                    } else {
                        $status = 'Tidak Aktif';
                        $class = 'in-active';
                    }

                    return '<div class="status__wrapper ' . $class . '">' . $status . '</div>';
                })
                ->addColumn('action', function (Store $store) {
                    $btn = '';
                    if (auth()->user()->can('main_shop_create')) {
                        $editPath = asset('/assets/images/edit-icon.png');
                        $btn = '<div class="action__wrapper"><a href="' . route("branch-store.edit", ["branch_store" => $store->id]) . '"><img src="' . $editPath . '" alt="Edit Icon"></a>';
                    }

                    if (auth()->user()->can('main_shop_update')) {
                        $deletePath = asset('/assets/images/delete-icon.png');
                        $btn = $btn .
                            '<a href="javascript:void(0)" data-id="' . $store->id . '" data-toggle="modal"
                                    data-target="#deleteConfirmationModal" id="btnDelete"><img src="' . $deletePath . '" alt="Delete Icon"></a></div>';
                    }

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        }

        return view('pages.store');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $channels = Channel::where('status', 1)->get();

        $promoters = User::whereHas('has_role', function ($query) {
            $query->where('model_has_roles.role_id', 3);
        })->whereDoesntHave('store')->get();

        $frontliners = User::whereHas('has_role', function ($query) {
            $query->where('model_has_roles.role_id', 4);
        })->whereDoesntHave('store')->get();

        return view('pages.store-create', compact('channels', 'promoters', 'frontliners'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStoreRequest $request)
    {
        //
        Store::StoreData($request);

        return redirect()->route('branch-store.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($channelID)
    {
        $stores = Store::select('id', 'name as text')->where('channel_id', $channelID)->where('status', 1)->get();
        return $this->success($stores);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $branch_store)
    {
        //
        $channels = Channel::where('status', 1)->get();

        $promoters = User::where('id', '!=', 1)->whereDoesntHave('store')->orWhere(function ($query) use ($branch_store) {
            $query->where('store_id', $branch_store->id)
                ->where('store_id', '!=', 0);
        })->whereHas('has_role', function ($query) {
            $query->where('model_has_roles.role_id', 3);
        })->get();

        $frontliners =  User::where('id', '>', 1)->whereDoesntHave('store')->orWhere(function ($query) use ($branch_store) {
            $query->where('store_id', $branch_store->id)
                ->where('store_id', '!=', 0);
        })->whereHas('has_role', function ($query) {
            $query->where('model_has_roles.role_id', 4);
        })->get();

        // dd($promoters, $frontliners);

        //  $promoters = User::whereHas('has_role', function ($query) {
        //     $query->where('model_has_roles.role_id', 3);
        // })->whereNotIn('users.id', $arr_promoter)->get();

        // $frontliners = User::whereHas('has_role', function ($query) {
        //     $query->where('model_has_roles.role_id', 4);
        // })->whereNotIn('users.id', $arr_frontliner)->get();

        // dd($promoters,$branch_store);

        return view('pages.store-edit', compact('branch_store', 'channels', 'promoters', 'frontliners'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStoreRequest $request, Store $branch_store)
    {
        //
        Store::UpdateData($branch_store, $request);

        return redirect()->route('branch-store.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $branch_store)
    {
        //
        Store::DeleteData($branch_store);

        $res = ['success' => true, 'messages' => "Store deleted successfully"];
        return response()->json($res);
    }
}
