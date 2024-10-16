<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use DB;
use App\Models\Job;
use App\Models\User;
use App\Models\JobType;
use App\Models\Category;
use App\Models\SavedJobs;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;


class AccountController extends Controller
{
    public function registration()
    {
        return view("front.account.registration");
    }

    public function forgotPassword()
    {
        return view("front.account.forgot-password");
    }

    public function processForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if($validator->fails()){
            return redirect()->route("account.forgotPassword")->withInput()->withErrors($validator);
        }

        $token=Str::random(60);

        \DB::table('password_reset_tokens')->where('email',$request->email)->delete();

       \DB::table('password_reset_tokens')->insert([
        'email'=>$request->email,
        'token'=>$token,
        'created_at'=>now()
       ]);

       // Send Email Here

       $user=User::where('email',$request->email)->first(); 
       $mailData=[
        'token'=>$token,
        'user'=>$user,
        'subject'=>'Reset Password'
       ];

       Mail::to($request->email)->send(new ResetPasswordEmail($mailData));

       return redirect()->route("account.forgotPassword")->with('success','Reset Password Link has been send to you Inbox');
    }

    public function resetPassword($tokenString)
    {

        \DB::table('password_reset_tokens')->where('token',$tokenString)->first();

        if($tokenString==null){
            return redirect()->route("account.forgotPassword")->with('error','Invalid Token');
        }

        return view('front.account.reset-password',[
            'tokenString'=>$tokenString
        ]);
    }

    public function processResetPassword(Request $request)
    {

       $token= \DB::table('password_reset_tokens')->where('token',$request->token)->first();

        if($token==null){
            return redirect()->route("account.forgotPassword")->with('error','Invalid Token');
        }
        
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'confirm_new_password' => 'required|same:new_password',

        ]);

        if($validator->fails()){
            return redirect()->route("account.resetPassword",$request->token)->withErrors($validator);
        }

        User::where('email',$token->email)->update([
            'password'=>Hash::make($request->new_password)
        ]);
        return redirect()->route("account.login")->with('success','Your Password is Successfully Changed');

    }

    public function processRegistration(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $user = $request->only('name', 'email');
        $user['password'] = bcrypt($request->password);

        $user = User::create($user);



        // // dd($user);

        // Check if user creation is successful
        if ($user) {
            return redirect()->route('account.registration')->withSuccess("Registration Successful");
        } else {
            return redirect()->route('registration')->with('error', 'Failed');
        }

        // $validator = Validator::make($request->all(), [ 
        //     "name"=> "required",
        //     "email"=> "required",
        //     "password"=> "required|same:confirm_password",
        //     "confirm_password"=> "required",
        // ]);

        // $users = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password'=> Hash::make($request->password),
        // ]);
        // return redirect()->route('account.registration')->withSuccess('Account Created!!');


        // if ($validator->passes()) {


        //     $user = new User;
        //     $user->name = $request->name;
        //     $user->email = $request->email;
        //     $user->password = Hash::make($request->password);
        //     $user->save();

        //     session ()->flash("success","You have registered Successfully");


        // return response()->json( [
        //         'status'=>true,
        //         'errors'=>[]
        //     ]);

        // }else
        // {
        //     return response()->json( [
        //             'status'=>false,
        //             'errors'=>$validator->errors()
        //         ]);
        // }
    }
    public function login()
    {
        return view("front.account.login");
    }


    public function authenticate(Request $request)

    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password]))
                return redirect()->route('account.profile')->withSuccess('Logged in');
            else {
                return redirect()->route('account.login')->with('error', 'Either email or password incorrect');
            }
        } else {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function profile()
    {
        $id = Auth::user()->id;

        $user = User::find($id);
        //    dd($user);

        return view('front.account.profile', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id . ',id',
        ]);
        $id = Auth::user()->id;
        $user = User::findOrFail($id);

        if ($validator->passes()) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;
            $user->save();

            Session()->flash('success', 'Updated Successfully.');
            return redirect()->route('account.profile')->withSuccess('Updated Sucessfully');
        } else {
            return redirect()->route('account.profile')->withError('Not Updated');
        }
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }

    public function updateProfilePic(Request $request)
    {

        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);

        if ($validator->passes()) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id . '-' . time() . '.' . $ext;
            $image->move(public_path('/profile_pic/'), $imageName);

            // Create a small thumbnail
            $sourcePath = public_path('/profile_pic/' . $imageName);
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($sourcePath);

            // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
            $image->cover(150, 150);
            $image->toPng()->save(public_path('/profile_pic/thumb/' . $imageName));

            // Delete old profile pic
            File::delete(public_path('/profile_pic/thumb/' . Auth::user()->image));
            File::delete(public_path('/profile_pic/' . Auth::user()->image));


            User::where('id', $id)->update([
                'image' => $imageName
            ]);
            Session()->flash('success', 'Profile Pic Updated Successfully');


            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function createJob()
    {
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();

        $jobTypes = JobType::orderBy('id', 'ASC')->where('status', 1)->get();

        return view('front.account.job.create', [
            'categories' => $categories,
            'jobTypes' => $jobTypes
        ]);
    }



    public function saveJob(Request $request)
    {
        // dd($request->all());
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

            $job = new Job();

            $job->title = $request->title;
            $job->category_id = $request->category_id;
            $job->job_type_id = $request->job_type;
            $job->user_id = Auth::user()->id;
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
            $job->save();

            Session()->flash('success', 'Job Saved Successfully.');
            return redirect()->route('account.myjobs')->withSuccess('Job Saved Sucessfully');
        } else {
            return redirect()->route('account.createJob')->withError('Not Created');
        }
    }

    public function myJobs()
    {

        $jobs = Job::where('user_id', Auth::user()->id)->with('jobType')->orderBy('created_at', 'DESC')->paginate(10);

        // dd($jobs);
        return view("front.account.job.myjobs", [
            'jobs' => $jobs
        ]);
    }

    public function editJob(Request $request, $id)
    {
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $jobTypes = JobType::orderBy('id', 'ASC')->where('status', 1)->get();

        $job = Job::where(
            [
                'user_id' => Auth::user()->id,
                'id' => $id,
            ]
        )->first();

        if ($job == null) {
            abort(404);
        }


        return view("front.account.job.edit", [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'job' => $job
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
                    'user_id' => Auth::user()->id,
                    'id' => $id,
                ]
            )->first();


            $job->title = $request->title;
            $job->category_id = $request->category_id;
            $job->job_type_id = $request->job_type;
            $job->user_id = Auth::user()->id;
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
            $job->save();

            $jobs = Job::where('user_id', Auth::user()->id)->with('jobType')->paginate(10);
            return view("front.account.job.myjobs", [
                'jobs' => $jobs
            ]);
        } else {
            return redirect()->route('account.updateJob')->withError('Not Created');
        }
    }

    public function deleteJob(Request $request)
    {
        // dd($request->all());

        $job = Job::where([
            ['user_id', '=', Auth::user()->id],
            ['id', '=', $request->jobId]
        ])->first();


        if ($job === null) {

            session()->flash('error', 'Either Job deleted or not found');
            return response()->json([
                'status' => 'false',
                'message' => 'Job not found or already deleted'
            ]);
        }

        Job::where('id', $request->jobId)->delete();


        session()->flash('success', 'Job deleted successfully');
    }


    public function myJobApplications()
    {

        $jobApplications = JobApplication::where('user_id', Auth::user()->id)
            ->with('job', 'job.jobType', 'job.Applications')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        // dd($jobs);
        return view("front.account.job.my-job-applications", [
            'jobApplications' => $jobApplications
        ]);
    }


    public function removeJobs(Request $request)
    {
        // dd($request->all());

        $jobApplication = JobApplication::where([
            'job_id' => $request->id,
            'user_id' => Auth::user()->id

        ])->first();

        //   dd($request->all());

        if ($jobApplication === null) {

            session()->flash('error', 'Either Job removed or not found');
            return response()->json([
                'status' => 'false',
                'message' => 'Job not found or already deleted'
            ]);
        }
        JobApplication::where('job_id', $request->id)->delete();

        session()->flash('success', 'Job Application removed Successfully');
    }

    public function savedJobs()
    {

        $savedJobs = SavedJobs::where('user_id', Auth::user()->id)
            ->with('job', 'job.jobType', 'job.Applications')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        // dd($jobs);
        return view("front.savedjobs", [
            'savedJobs' => $savedJobs,
        ]);
    }


    public function removeSavedJob(Request $request)
    {
        // dd($request->all);

        $savedJob = SavedJobs::where([
            'job_id' => $request->id,
            'user_id' => Auth::user()->id

        ])->first();

        //   dd($request->all());

        if ($savedJob === null) {

            session()->flash('error', 'Either Job removed or not found');
            return response()->json([
                'status' => 'false',
                'message' => 'Job not found or already deleted'
            ]);
        }
        SavedJobs::where('job_id', $request->id)->delete();

        session()->flash('success', 'Job Application removed Successfully');
    }
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),

            ]);
        }
        if (Hash::check($request->old_password, Auth::user()->password) == false) {
            session()->flash('error', 'Your Old Password is incorrect');
            return response()->json([
                'status' => true,
            ]);
        }

        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        session()->flash('success', 'Your Password Changed Successfully');
        return response()->json([
            'status' => true,

        ]);
    }







    // public function deleteJob(Request $request)
    // {
    //     $job = Job::where([
    //         'user_id',
    //         Auth::user()->id,
    //         'id' => $request->jobId

    //     ])->first();

    //     if ($job == null) {
    //         session()->flash('error', 'Either Job deleted or not found');
    //         return response()->json([
    //             'status' => 'true'
    //         ]);
    //     }

    //     Job::where('id',$request->jobId)->delete();
    //     session()->flash('success', 'Job deleted Successfully');
    //         return response()->json([
    //             'status' => 'true'
    //         ]);
    // }





    // public function destroy($id)
    // {

    //     Job::destroy($id);
    //     return redirect()->route("account.myjobs")->withSuccess('Job Deleted Successfully');

    // }
}














    // public function updateProfilePic(Request $request, $id)
    // {
    //     $user = User::findOrFail($id); // Use findOrFail for better error handling

    //     if ($request->hasFile('image')) {
    //         $imageName = time() . '.' . $request->image->extension();
    //         $request->image->move(public_path('assets'), $imageName);
    //         $user->image = $imageName;
    //         $user->save(); // Don't forget to save the user

    //     }
    //     return view("front.account.profile");
    // }


    // public function processLogin(Request $request)

    // {
    //     $request->validate([
    //         'email' => 'required',
    //         'password' => 'required'
    //     ]);
    //     $credentials = $request->only('email', 'password');
    //     if (Auth::attempt($credentials)) {
    //         return redirect()->route('home')->withSuccess('Logged in');
    //     }
    //     return redirect()->route('account.login')->withErrors("error", "Login details are not valid");
    // }
