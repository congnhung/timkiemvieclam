<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\NotificationUser;
use App\Models\NotificationEmployer;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NotifyExpiringJobs extends Command
{
    protected $signature = 'notify:expiring-jobs';

    protected $description = 'Command description';

    public function handle()
    {
        $jobsExpired = Job::where('expiration_date', '<=', Carbon::now())
                          ->where('status', 1)
                          ->get();

        $jobsNearExpiration = Job::where('expiration_date', '>=', Carbon::now())
                                    ->where('expiration_date', '=', Carbon::now()->addDays(3))
                                    ->where('status', 1)
                                    ->get();

    

        foreach ($jobsExpired as $job) {
            $job->status = 2;
            $job->save();

            DB::table('notifications_employer')->insert([
                'employer_id' => $job->user_id, 
                'user_id' => null, 
                'job_notification_id' => $job->id,
                'type' => 'job_expired',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $applicants = DB::table('job_applications')
                ->where('job_id', $job->id)
                ->get();

            foreach ($applicants as $applicant) {
                DB::table('notifications_user')->insert([
                    'user_id' => $applicant->user_id, 
                    'job_notification_id' => $applicant->id, 
                    'type' => 'expired',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }  
        }

        foreach ($jobsNearExpiration as $job) {
            DB::table('notifications_employer')->insert([
                'employer_id' => $job->user_id, 
                'user_id' => null, 
                'job_notification_id' => $job->id,
                'type' => 'job_near_expiration',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $applicants = DB::table('job_applications')
                ->where('job_id', $job->id)
                ->get();

            foreach ($applicants as $applicant) {
                DB::table('notifications_user')->insert([
                    'user_id' => $applicant->user_id, 
                    'job_notification_id' => $applicant->id, 
                    'type' => 'job_near_expiration',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
                
        }      
    }
}
