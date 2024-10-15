<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->orderBy('name', 'ASC')->take(8)->get();
        $FeaturedJobs = Job::where('status', 1)->orderBy('created_at', 'DESC')->with('jobType')->where('isFeatured', 1)->take(6)->get();

        $latestJobs = Job::where('status', 1)->orderBy('created_at', 'DESC')->with('jobType')->take(6)->get();


        return view("front.home", [
            'categories' => $categories,
            'FeaturedJobs' => $FeaturedJobs,
            'latestJobs' => $latestJobs,

        ]);
    }


    public function welcome()
    {
        return view("welcome");
    }
}
