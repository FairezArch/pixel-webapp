<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Channel;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\AttendanceRequest;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, DataTables $dataTables)
    {
        if ($this->isAPI()) {
            $data = Attendance::GetData($request);
            return $this->success($data);
        }

        if (request()->ajax()) {
            $model = Attendance::with(['user' => function ($query) {
                $query->select('*', 'users.filename as user_filename');
            }, 'user.store.channel'])
                ->when(request('minDate'), function ($query) {
                    $query->whereDate('created_at', '>=', request('minDate'));
                })
                ->when(request('maxDate'), function ($query) {
                    $query->whereDate('created_at', '<=', request('maxDate'));
                })
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
                ->addColumn('image', function (Attendance $attendance) {
                    if ($attendance->user) {
                        if ($attendance->user->user_filename) {
                            $path = asset('/storage/employee/' . $attendance->user->user_filename);
                        } else {
                            $path = asset('/assets/images/image-default.png');
                        }

                        return '<img src="' . $path . '" alt="Employee Image">';
                    }
                })
                ->addColumn('name', function (Attendance $attendance) {
                    if ($attendance->user) {
                        return $attendance->user->name;
                    }
                })
                ->editColumn('clock_in', function (Attendance $attendance) {
                    if ($attendance->clock_in) {
                        $formatedDate = Carbon::parse($attendance->clock_in)->format('H:i:s');
                        return $formatedDate;
                    }
                })
                ->editColumn('clock_out', function (Attendance $attendance) {
                    if ($attendance->clock_out) {
                        $formatedDate = Carbon::parse($attendance->clock_out)->format('H:i:s');
                        return $formatedDate;
                    }
                })
                ->addColumn('clockInStatus', function (Attendance $attendance) {
                    if ($attendance->clock_in) {
                        if ($attendance->in_location) {
                            $status = '<div class="clock-in-status">Didalam</div>';
                        } else {
                            $status = '<div class="clock-out-status">Diluar</div>';
                        }
                    } else {
                        return;
                    }

                    return $status;
                })
                ->addColumn('clockOutStatus', function (Attendance $attendance) {
                    if ($attendance->clock_out) {
                        if ($attendance->out_location) {
                            $status = '<div class="clock-in-status">Didalam</div>';
                        } else {
                            $status = '<div class="clock-out-status">Diluar</div>';
                        }
                    } else {
                        return;
                    }

                    return $status;
                })
                ->rawColumns(['image', 'clockInStatus', 'clockOutStatus'])
                ->toJson();
        }

        $branch_stores = Store::where('status', 1)->get();
        $main_stores = Channel::where('status', 1)->get();
        return view('pages.attendance', compact('branch_stores', 'main_stores'));
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
    public function store(AttendanceRequest $request)
    {
        $data = Attendance::Attend($request);
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
