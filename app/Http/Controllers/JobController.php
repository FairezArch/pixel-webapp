<?php

namespace App\Http\Controllers;

use App\Models\Age;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\JobUser;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if($this->isAPI()){
            $jobs = JobUser::where('status',1)->get();
            $ages = Age::all();
            return $this->success(compact('jobs','ages'));
        }

        $jobs = JobUser::all();

        return view('pages.job', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('pages.job-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreJobRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJobRequest $request)
    {
        //
        JobUser::StoreData($request);

        return redirect()->route('job.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(JobUser $job)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(JobUser $job)
    {
        //
        return view('pages.job-edit', compact('job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateJobRequest  $request
     * @param  \App\Models\JobUser  $job
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateJobRequest $request, JobUser $job)
    {
        //
        JobUser::UpdateData($job,$request);

        return redirect()->route('job.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobUser  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobUser $job)
    {
        //
        JobUser::DeleteData($job);

        $res = [
            'success' => true,
            'messages' => "Job deleted successfully"
        ];

        return response()->json($res);
    }
}
