<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use Illuminate\Http\Request;
use App\Models\JobType;
use App\Models\Category;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class JobsController extends Controller
{
    //this method will show jobs page
    public function index(Request $request){

    $categories = Category::where('status',1)->get();
    $jobTypes = jobType::where('status',1)->get();

   $jobs= Job::where('status',1);

   //search using keywords
   if (!empty($request->keyword)) {
    $jobs =$jobs->where(function($query) use ($request) {
    $query->orWhere('title','like','%'.$request->keyword .'%');
    $query->orWhere('keywords','like','%'. $request->keyword .'%');
   });
   }
   //search using location
   if(!empty($request->location)) {
    $jobs = $jobs->where('location', $request->location);
   }
   //search using category
   if(!empty($request->category)) {
    $jobs = $jobs->where('category_id', $request->category);
   }
   //search using Job Type
   
   if(!empty($request->jobType)) {
    $jobTypeArray = explode(',', $request->jobType);
    $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
   }else {
    $jobTypeArray = [];
   }
   //search using experience
   if(!empty($request->experience)) {
    $jobs = $jobs->where('experience', $request->experience);
   }
   

   $jobs= $jobs->with(['jobType','category']);

   if($request->sort == '0') {
   $jobs= $jobs->orderBy('created_at','ASC');
   }else {
    $jobs= $jobs->orderBy('created_at','DESC');
   }
  $jobs= $jobs ->paginate(9);
    

        return view('front.jobs',[
        'categories' => $categories,
        'jobTypes' => $jobTypes,
        'jobs' => $jobs,
        'jobTypeArray' => $jobTypeArray,
        ]);
    }
    public function detail($id) {

        $job = Job::where([
                            'id' => $id, 
                            'status' => 1
                            ])->with(['jobType','category'])->first();

        if ($job == null) {
            abort(404);
        }
        return view('front.jobDetail',['job' => $job]);
    }
    public function applyJob(Request $request) {
        $id = $request->id;

        $job = Job::where('id',$id)->first();

        //if job  not found in the database
        if($job == null) {
            session()->flash('error','Job does not exits');
            return response()->json([
                'status' => false,
                'message' => 'Job does not exists'
            ]);
        }
    // you cannot apply on own your own job
    $employer_id = $job->user_id;

    if($employer_id == Auth::user()->id) {
            session()->flash('error','you cannot apply on own your own job');
            return response()->json([
                'status' => false,
                'message' => 'you cannot apply on own your own job'
            ]);
        }

        //you can not apply on a job twice

        $jobApplicationCount = JobApplication::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id
        ])->count();

        if ($jobApplicationCount >0) {
             $message = 'you already applied on this job';

        session()->flash('error',$message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);

        }

        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id= $employer_id;
        $application->applied_date = now();
        $application->save();

        //send notification to employer
        $employer = User::where('id',$employer_id)->first();
        $mailData = [
            'employer' => $employer,
            'user' => Auth:: user(),
            'job' => $job,
        ];
        Mail::to($employer->email)->send( new JobNotificationEmail($mailData));

        $message = 'you have successfully applied.';

        session()->flash('success',$message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);

    }
}
