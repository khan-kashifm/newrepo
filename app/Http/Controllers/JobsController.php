<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobType;
use App\Models\Category;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class JobsController extends Controller
{
    public function index(Request $request)

    {
        $categories = Category::where('status', 1)->get();
        $jobTypes = JobType::where('status', 1)->get();

        $jobs = Job::where('status', 1);

        //Search using keyword

        if (!empty($request->keyword)) {
            $jobs = $jobs->where(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->keyword . '%');
                $query->orWhere('keywords', 'like', '%' . $request->keyword . '%');
            });
        }

        //Search using location

        if (!empty($request->location)) {
            $jobs = $jobs->Where('location', $request->location);
        }

        //Search using category

        if (!empty($request->category)) {
            $jobs = $jobs->Where('category_id', $request->category);
        }


        //Search using JobType
        $jobTypeArray = [];
        if (!empty($request->jobType)) {
            $jobTypeArray = explode(',', $request->jobType);
            // $jobTypeArray = array_map('intval', explode(',', $request->jobType));
            $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
        }

        //Search using Experience

        if (!empty($request->experience)) {

            $jobs = $jobs->where('experience', $request->experience);
        }

        $jobs = $jobs->with('jobType')->orderBy('created_at', 'DESC')->paginate(9);



        return view("front.jobs", [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'jobs' => $jobs,
            'jobTypeArray' => $jobTypeArray,
        ]);
    }



    public function detail($id)
    {
        // dd($id);
        $job = Job::where([
            'id' => $id,
            'status' => 1
        ])
            ->with(['jobType', 'category'])
            ->first();


        // dd($job);
        if ($job == null) {
            abort(404);
        }

        return view("front.jobdetail", [
            'job' => $job
        ]);
    }

    public function applyJob(Request $request)
    {
        $id = $request->id;

        $job = Job::where('id', $id)->first();

        if ($job == null) {
            return  response()->json([
                'status' => 'false',
                'message' => 'job doest not exist'
            ]);
        }

        // for own job posted
        $employer_id = $job->user_id;

        if ($employer_id == Auth::user()->id) {
            session()->flash('error', 'You can not apply on your own Job');
            return response()->json([
                'status' => 'false',
                'message' => 'You can not apply on your own Job.',

            ]);
        }

       //you can not apply on a job twice
       $jobApplicationcount=JobApplication::where([
        'user_id'=>Auth::user()->id,
        'job_id'=>$id
       ])->count();

       if($jobApplicationcount>0){
        $message='You have already applied for this Job.';
        session()->flash('error',$message);
        return response()->json([
            'status'=>false,
            'message'=>$message
        ]);


       }


        $application=new JobApplication();
        $application->job_id=$id;
        $application->user_id=Auth::user()->id;
        $application->employer_id=$employer_id;
        $application->applied_date=now();
        $application->save();

        session()->flash('success', 'You have successfully applied for this Job.');
        return response()->json([
            'status' => 'true',
            'message' => 'You have successfully applied for this Job.',

        ]);

    }
}
