<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobType;
use App\Models\Category;

class JobsController extends Controller
{
    //this method will show jobs page
    public function index(){

    $categories = Category::where('status',1)->get();
    $jobTypes = JobType::where('status',1)->get();


        return view('front.jobs',[
        'categories' => $categories,
        'jobTypes' => $jobTypes,
        ]);
    }
}
