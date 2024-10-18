<?php

namespace App\Http\Controllers\admin;

use App\Models\Job;
use App\Models\JobType;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::orderBy('id', 'ASC')
            ->with(['User'])
            ->paginate(4);

        return view("admin.jobs.list", [
            'jobs' => $jobs,
        ]);
    }

    public function deleteJob(Request $request)
    {
        // dd($request->all());

        $job = Job::where([
            ['id', '=', $request->id]
        ])->first();


        if ($job === null) {

            session()->flash('error', 'Either Job deleted or not found');
            return response()->json([
                'status' => 'false',
                'message' => 'Job not found or already deleted'
            ]);
        }

        Job::where('id', $request->id)->delete();


        session()->flash('success', 'Job deleted successfully');
    }


    public function editJob(Request $request, $id)
    {

        $job = Job::findOrFail($id);
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $jobTypes = JobType::orderBy('id', 'ASC')->where('status', 1)->get();


        if ($job == null) {
            abort(404);
        }
        return view("admin.jobs.edit", [
            'job' => $job,
            'categories' => $categories,
            'jobTypes' => $jobTypes,

        ]);
    }

    public function updateJob(Request $request, $id)
    {
        $rules = [
            'title' => 'required',
            'category_id' => 'required',
            'job_type' => 'required',
            'vacancy' => 'required',
            'location' => 'required',
            'description' => 'required',
            'company_name' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $job = Job::where(
                [
                    'id' => $id,
                ]
            )->first();


            $job->title = $request->title;
            $job->category_id = $request->category_id;
            $job->job_type_id = $request->job_type;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;

            $job->status = $request->status;
            $job->isFeatured = (!empty($request->isFeatured)) ? $request->isFeatured:0;

            $job->save();

            $jobs = Job::orderBy('id', 'ASC')->paginate(4);
            return redirect()->route("admin.jobs", [
                'jobs' => $jobs
            ]);
        } else {
            return redirect()->route('admin.editJob')->withError('Not Created');
        }
    }

    public function adminjobApplications()
    {
        $jobApplications = JobApplication::orderBy('created_at', 'ASC')
        ->with('job','user','employer')
        ->paginate(5);

        return view("admin.jobapplications.jobapplications", [
            'jobApplications' => $jobApplications,
        ]);
    }
}
