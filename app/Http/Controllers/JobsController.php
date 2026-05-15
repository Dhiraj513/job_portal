<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobType;
use App\Models\Category;
use App\Models\Job;

class JobsController extends Controller
{
    //this method will show jobs page
    public function index(Request $request){

    $categories = Category::where('status',1)->get();
    $jobTypes = jobType::where('status',1)->get();

   $jobs= Job::where('status',1);

   //search using keywords
   if (!empty($request->keyword)) {
    $jobs =$jobs->where(function($query)use ($request) {
    $query->orWhere('title','like','%'.$request->keyword.'%');
    $query->orWhere('keywords','like','%'.$request->keyword.'%');
   });
   }
   //search using location
   if(!empty($request->location)) {
    $jobs = $jobs->where('location',$request->location);
   }
   //search using category
   if(!empty($request->category)) {
    $jobs = $jobs->where('category_id',$request->category);
   }
   //search using Job Type
   $jobTypeArray = [];
   if(!empty($request->jobType)) {
    $jobTypeArray = explode(',', $request->jobType);
    $jobs = $jobs->whereIn('job_type_id',$jobTypeArray);
   }
   //search using experience
   if(!empty($request->experience)) {
    $jobs = $jobs->where('experience',$request->experience);
   }

   $jobs= $jobs->with('jobType');

   if(!empty($request->sort) && $request->sort == 0) {
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
}
