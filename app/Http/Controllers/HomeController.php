<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\job;
use App\Models\Category;

class HomeController extends Controller
{
    //this method will show our home page
    public function index(){

    $categories = Category::where('status',1)->orderBy('name','ASC')->take(8)->get();

    $featuredJobs = Job::where('status',1)
                    ->orderBy('created_at','DESC')
                    ->with('jobType')
                    ->where('isFeatured',1)->take(6)->get();

     $latestJobs = Job::where('status',1)
                    ->orderBy('created_at','DESC')
                    ->with('jobType')
                    ->take(6)->get();                

        return view('front.home',[
        'categories' => $categories,
        'featuredJobs' => $featuredJobs,
        'latestJobs' => $latestJobs,

    ]);
    }

};
