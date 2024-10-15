<?php

namespace App\Http\Controllers;

use App\Models\JobType;
use App\Models\Job;
use App\Models\Category;
use Illuminate\Http\Request;


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

        if (!empty($request->jobType)) {
            $jobTypeArray = explode(',',$request->jobType);
            $jobs=$jobs->whereIn('job_type_id',$jobTypeArray);
        }

        //Search using Experience

        if (!empty($request->experience)) {
          
            $jobs=$jobs->where('experience',$request->experience);
        }

        $jobs = $jobs->with('jobType')->orderBy('created_at', 'DESC')->paginate(9);



        return view("front.jobs", [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'jobs' => $jobs,
        ]);
    }
}
