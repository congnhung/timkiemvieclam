<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Careers;
use App\Models\Job;
use App\Models\SavedJob;
use App\Models\Banner;
use App\Models\User;
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

        $banners = Banner::where('status', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->get();

        // Top employers: query job counts via jobs table, then load user info
        $topEmployerIds = Job::where('status', 1)
            ->whereNotNull('user_id')
            ->select('user_id', \Illuminate\Support\Facades\DB::raw('COUNT(*) as jobs_count'))
            ->groupBy('user_id')
            ->orderByDesc('jobs_count')
            ->take(12)
            ->pluck('jobs_count', 'user_id');

        $topEmployers = User::whereIn('id', $topEmployerIds->keys())
            ->where('role', 'employer')
            ->get()
            ->map(function($u) use ($topEmployerIds) {
                $u->jobs_count = $topEmployerIds[$u->id] ?? 0;
                return $u;
            })
            ->sortByDesc('jobs_count')
            ->values();

        return view('front.home', [
            'careers'       => $careers,
            'featureJobs'   => $featureJobs,
            'latesJobs'     => $latesJobs,
            'newCareers'    => $newCareers,
            'provinces'     => $provinces,
            'banners'       => $banners,
            'topEmployers'  => $topEmployers,
        ]);
    }
}
