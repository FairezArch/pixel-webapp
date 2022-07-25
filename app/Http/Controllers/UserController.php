<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Store;
use App\Models\Region;
use App\Models\Channel;
use App\Models\Attendance;
use App\Models\ModelHasRole;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class UserController extends Controller
{
    
    protected $idRoles;

    public function __construct(array $idRoles = [1])
    {
        # code...
        $this->idRoles = $idRoles;
    }

    public function checkRole(int $roleID)
    {
        # code...
        if($roleID == 1){
            return Role::get();
        }else{
            return Role::whereNotIn('id', $this->idRoles)->get();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DataTables $dataTables)
    {   
        if (request()->ajax()) {
            $model = User::with(['store.channel.region'])->where('users.id', '!=', 1)->select('*', 'users.name as user_name', 'users.filename as user_filename');
            return $dataTables->eloquent($model)
                ->addColumn('nameTemp', function (User $user) {
                    $name = $user->user_name;
                    return '<a href="' . route('user.show', ['user' => $user->id]) . '">' . $name . '</a>';
                })
                ->addColumn('image', function (User $user) {
                    if ($user->user_filename) {
                        $path = asset('/storage/employee/' . $user->user_filename);
                    } else {
                        $path = asset('/assets/images/image-default.png');
                    }
                    return '<img src="' . $path . '" alt="Employee Image">';
                })
                ->addColumn('regional', function (User $user) {
                    if ($user->store) {
                        if ($user->store->channel) {
                            return $user->store->channel->region->name;
                        }
                    }
                })
                ->addColumn('branchStore', function (User $user) {
                    if ($user->store) {
                        return $user->store->name;
                    }
                })
                ->addColumn('mainStore', function (User $user) {
                    if ($user->store) {
                        return $user->store->channel->name;
                    }
                })
                ->addColumn('status', function (User $user) {
                    if ($user->status) {
                        $status = 'Aktif';
                        $class = 'active';
                    } else {
                        $status = 'Tidak Aktif';
                        $class = 'in-active';
                    }

                    return '<div class="status__wrapper ' . $class . '">' . $status . '</div>';
                })
                ->addColumn('action', function (User $user) {
                    $btn = '';

                    if (auth()->user()->can('employee_update')) {
                        $editPath = asset('/assets/images/edit-icon.png');
                        $btn = '<div class="action__wrapper"><a href="' . route("user.edit", ["user" => $user->id]) . '"><img src="' . $editPath . '" alt="Edit Icon"></a>';
                    }

                    if (auth()->user()->can('employee_delete')) {
                        $deletePath = asset('/assets/images/delete-icon.png');
                        $btn = $btn .
                            '<a href="javascript:void(0)" data-id="' . $user->id . '" data-toggle="modal"
                                        data-target="#deleteConfirmationModal" id="btnDelete"><img src="' . $deletePath . '" alt="Delete Icon"></a></div>';
                    }
                    return $btn;
                })
                ->rawColumns(['nameTemp', 'image', 'status', 'action'])
                ->toJson();
        }

        $branch_stores = Store::where('status', 1)->get();
        $main_stores = Channel::where('status', 1)->get();

        return view('pages.employee', compact('branch_stores', 'main_stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $ids = [3,4];
        // $roles = Role::where('id', '>=', $this->getMinimumLevel())->get();
        $roles = $this->checkRole(Auth::user()->roles->first()->id);

        if (request()->ajax()) {
            $main_stores = Channel::select('id', 'region_id', 'name')->where('status', 1)->get();
            $main_stores->map(function ($store) {
                $store->text = $store->name;
                unset($store->name);
            });

            $branch_stores = Store::select('id', 'channel_id', 'name')->where('status', 1)->get();
            $branch_stores->map(function ($store) {
                $store->text = $store->name;
                unset($store->name);
            });

            $main_stores = $main_stores->groupBy('region_id');
            $branch_stores = $branch_stores->groupBy('channel_id');

            return response()->json([
                'main_stores' => $main_stores,
                'branch_stores' => $branch_stores
            ]);
        }

        $regionals = Region::where('status', 1)->get();

        return view('pages.employee-create', compact('regionals', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request)

    {
        User::CreateDataEmployee($request);

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $limit = 10;
        $attendance = Attendance::with('user')->where('user_id', $user->id)->take($limit)->get();
        $sales = Sale::with(['user', 'product'])->where('user_id', $user->id)->take($limit)->get();

        return view('pages.employee-show', compact('user', 'attendance', 'sales'));
    }

    public function findByStore($storeID)
    {
        $employees = User::select('id', 'name as text')->where('store_id', $storeID)->where('status', 1)->get();
        return $this->success($employees);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // $ids = [3,4];
        // $roles = Role::where('id', '>=', $this->getMinimumLevel())->get();
        $roles = $this->checkRole(Auth::user()->roles->first()->id);

        if (request()->ajax()) {
            $main_stores = Channel::select('id', 'region_id', 'name')->where('status', 1)->get();
            $main_stores->map(function ($store) {
                $store->text = $store->name;
                unset($store->name);
            });

            $branch_stores = Store::select('id', 'channel_id', 'name')->where('status', 1)->get();
            $branch_stores->map(function ($store) {
                $store->text = $store->name;
                unset($store->name);
            });

            $main_stores = $main_stores->groupBy('region_id');
            $branch_stores = $branch_stores->groupBy('channel_id');

            return response()->json([
                'main_stores' => $main_stores,
                'branch_stores' => $branch_stores
            ]);
        }

        $regionals = Region::where('status', 1)->get();
        $main_stores = Channel::where('status', 1)->get();
        $stores = Store::where('status', 1)->get();

        return view('pages.employee-edit', compact(['user', 'regionals', 'roles', 'main_stores', 'stores']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, User $user)
    {
        User::UpdateDataEmployee($user, $request);

        return redirect()->route('user.index');
    }

    public function reset(User $user)
    {
        $user->update([
            'device_id' => null
        ]);

        $res = ['success' => true, 'messages' => "Employee's device_id reset successfully"];
        return response()->json($res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        User::DeleteDataEmployee($user);

        $res = ['success' => true, 'messages' => "Employee deleted successfully"];
        return response()->json($res);
    }

    public function profile(Request $request)
    {
        # code...
        if ($this->isAPI()) {
            $user = $request->user();
            $modelRole = ModelHasRole::where('model_id', $user->id)->first();
            $user->store;
            $user->job = Role::find($modelRole->role_id)->name;
            return $this->success($user);
        }
    }

    public function profile_update(Request $request, User $profile)
    {
        # code...
        if ($this->isAPI()) {
            $user = User::ProfileUpdate($profile, $request);
            return $this->success($user);
        }
    }
}
