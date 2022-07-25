<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Store;
use App\Models\Target;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, DataTables $dataTables)
    {
        if ($this->isAPI()) {
            return $this->success(Target::GetCurrent($request));
        }
        if (request()->ajax()) {
            $model = Target::with(['user' => function ($query) {
                $query->select('*', 'users.filename as user_filename');
            }, 'user.store.channel'])
                ->when(request('mainStore'), function ($query) {
                    $query->whereHas('user.store.channel', function ($query) {
                        $query->where('name', request('mainStore'));
                    });
                })
                ->when(request('branchStore'), function ($query) {
                    $query->whereHas('user.store', function ($query) {
                        $query->where('name', request('branchStore'));
                    });
                });

            return $dataTables->eloquent($model)
                ->addColumn('image', function (Target $target) {
                    if ($target->user) {
                        if ($target->user->user_filename) {
                            $path = asset('/storage/employee/' . $target->user->user_filename);
                        } else {
                            $path = asset('/assets/images/image-default.png');
                        }

                        return '<img src="' . $path . '" alt="Employee Image">';
                    }
                })
                ->addColumn('name', function (Target $target) {
                    if ($target->user) {
                        return $target->user->name;
                    }
                })
                ->editColumn('nominal', function (Target $target) {
                    $converted = number_format($target->nominal, 2);
                    return 'Rp' . $converted;
                })
                ->addColumn('action', function (Target $target) {
                    $btn = '';
                    if (auth()->user()->can('target_update')) {
                        $editPath = asset('/assets/images/edit-icon.png');
                        $btn = '<div class="action__wrapper"><a href="' . route("target.edit", ["target" => $target->id]) . '"><img src="' . $editPath . '" alt="Edit Icon"></a>';
                    }

                    if (auth()->user()->can('target_delete')) {
                        $deletePath = asset('/assets/images/delete-icon.png');
                        $btn = $btn .
                            '<a href="javascript:void(0)" data-id="' . $target->id . '" data-toggle="modal"
                                    data-target="#deleteConfirmationModal" id="btnDelete"><img src="' . $deletePath . '" alt="Delete Icon"></a></div>';
                    }

                    return $btn;
                })
                ->rawColumns(['image', 'action'])
                ->toJson();
        }

        $branch_stores = Store::where('status', 1)->get();
        $main_stores = Channel::where('status', 1)->get();

        return view('pages.target', compact('branch_stores', 'main_stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::Where('id', '>', 1)->whereDoesntHave('targets')->get();
        return view('pages.target-create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Target::StoreData($request);

        return redirect()->route('target.index');
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
    public function edit(Target $target)
    {
        // $users = User::whereDoesntHave('targets')->orWhere('id', $target->id)->get();
        $users = User::where('id','>',1)->get();
        return view('pages.target-edit', compact('target', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Target $target)
    {
        Target::StoreData($request);

        return redirect()->route('target.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Target $target)
    {
        Target::DeleteData($target);

        $res = [
            'success' => true,
            'messages' => "Target deleted successfully"
        ];

        return response()->json($res);
    }
}
