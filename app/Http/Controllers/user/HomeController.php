<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Careers;
use App\Models\Job;
use App\Models\SavedJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {

        $careers = Careers::where('status', 1)
                  ->where('isPopular', 1)
                  ->orderBy('name', 'ASC')
                  ->withCount('jobs')
                  ->get()
                  ->chunk(4);

        $newCareers = Careers::where('status', 1)
                    ->orderBy('name','ASC')
                    ->get();

        $featureJobs = Job::where('status', 1)
            ->where('isFeatured', 1)
            ->with('jobType', 'user')
            ->orderBy('created_at', 'DESC')
            ->take(6)
            ->get()
            ->map(function ($job) {
                $job->is_saved = Auth::check() && SavedJob::where([
                    'user_id' => Auth::id(),
                    'job_id' => $job->id
                ])->exists();
                return $job;
        });


        $latesJobs = Job::where('status', 1)
            ->with('jobType', 'user')
            ->orderBy('created_at', 'DESC')
            ->take(6)
            ->get()
            ->map(function ($job) {
                $job->is_saved = Auth::check() && SavedJob::where([
                    'user_id' => Auth::id(),
                    'job_id' => $job->id
                ])->exists();
                return $job;
        });

        
        $provinces = config('vietnam_provinces_34.provinces', []);

        return view('front.home', [
            'careers' => $careers,
            'featureJobs' => $featureJobs,
            'latesJobs' => $latesJobs,
            'newCareers' => $newCareers,
            'provinces' => $provinces,
        ]);
    }
}
