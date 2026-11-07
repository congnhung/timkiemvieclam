<?php

use App\Http\Controllers\admin\AccountController as AdminAccountController;
use App\Http\Controllers\admin\EmployerController;
use App\Http\Controllers\admin\JobApplyController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\user\HomeController;
use App\Http\Controllers\user\AccountController;
use App\Http\Controllers\user\JobsController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\CareerController;
use App\Http\Controllers\admin\FileDownloadController;
use App\Http\Controllers\admin\NotificationController;
use App\Http\Controllers\admin\SupportChatController;
use App\Http\Controllers\user\ApiController;
use App\Http\Controllers\user\GoogleAuthController;
use App\Http\Controllers\user\CvBuilderController;
use App\Http\Controllers\user\AiAssistantController;
use App\Http\Controllers\user\ChatController;
use Illuminate\Support\Facades\Route;

Route::post('/ai-chat', [AiAssistantController::class, 'chat'])->name('ai.chat');
Route::get('/ai-chat/poll', [AiAssistantController::class, 'poll'])->name('ai.poll');
Route::get('/ai-chat/history', [AiAssistantController::class, 'history'])->name('ai.history');


Route::get('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.markAsRead');
Route::get('/api/proxy/provinces', [ApiController::class, 'getProvinces']);
Route::get('/api/proxy/districts/{provinceId}', [ApiController::class, 'getDistricts']);
Route::get('/api/proxy/wards/{districtId}', [ApiController::class, 'getWards']);

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('/auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle']);

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
Route::get('/jobs/detail/{id}', [JobsController::class, 'detail'])->name('jobDetail');
Route::get('/jobs/detail-employer/{id}', [JobsController::class, 'detail_employer'])->name('JobDetail_employer');
Route::post('/apply-job', [JobsController::class, 'applyJob'])->name('applyJob');
Route::post('/save-job', [JobsController::class, 'saveJob'])->name('saveJob');
Route::get('/download-cv/{cvPath}', [JobsController::class, 'downloadCv'])->name('download-cv');
Route::get('/view-cv/{cvPath}', [JobsController::class, 'viewCv'])->name('view-cv');
Route::get('/cv-builder', [CvBuilderController::class, 'index'])->name('cv.builder');
Route::get('/cv-preview/{id}', [CvBuilderController::class, 'preview'])->name('cv.preview');
Route::get('/cv-print/{id}', [CvBuilderController::class, 'printPdf'])->name('cv.print');
Route::post('/cv/upload-avatar', [CvBuilderController::class, 'uploadAvatar'])->name('cv.uploadAvatar');
Route::get('/forgot-password', [AccountController::class, 'forgotPassword'])->name('account.forgotPassword');
Route::post('/process-forgot-password', [AccountController::class, 'processForgotPassword'])->name('account.processForgotPassword');
Route::get('/reset-password/{token}', [AccountController::class, 'resetPassword'])->name('account.resetPassword');
Route::post('/process-reset-password', [AccountController::class, 'processResetPassword'])->name('account.processResetPassword');


// User & Employer Route
Route::group(['prefix' => 'account'], function () {
  // Guest Route
  Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [AccountController::class, 'registration'])->name('account.registration');
    Route::post('/process-register', [AccountController::class, 'processRegistration'])->name('account.processRegistration');
    Route::get('/login', [AccountController::class, 'login'])->name('account.login');
    Route::post('/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');
  });

  // Authenticated Routes
  Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::get('/notification', [AccountController::class, 'notification'])->name('account.notification');
    Route::get('/notification-employer', [AccountController::class, 'notificationEmployer'])->name('account.notificationEmployer');
    Route::put('/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
    Route::put('/update-profile-company', [AccountController::class, 'updateProfileCompany'])->name('account.updateProfileCompany');
    Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
    Route::post('/update-profile-pic', [AccountController::class, 'updateProfilePicture'])->name('account.updateProfilePicture');
    Route::get('/create-job', [AccountController::class, 'createJob'])->name('account.createJob');
    Route::post('/save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');
    Route::get('/my-job', [AccountController::class, 'myJobs'])->name('account.myJobs');
    Route::get('/my-job/edit/{jobId}', [AccountController::class, 'editJob'])->name('account.editJob');
    Route::post('/update-job/{jobId}', [AccountController::class, 'updateJob'])->name('account.updateJob');
    Route::post('/delete-job', [AccountController::class, 'deleteJob'])->name('account.deleteJob');
    Route::get('/my-job-application', [AccountController::class, 'myJobApplication'])->name('account.myJobApplication');

    Route::post('/remove-job-application', [AccountController::class, 'removeJobs'])->name('account.removeJobs');
    Route::get('/saved-job', [AccountController::class, 'savedJobs'])->name('account.savedJobs');
    Route::post('/remove-saved-job', [AccountController::class, 'removeSavedJob'])->name('account.removeSavedJob');
    Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');

    Route::post('/process-application/{id}', [JobsController::class, 'processApplication'])->name('process.application');
    Route::post('/cv/save', [CvBuilderController::class, 'save'])->name('cv.save');
    Route::get('/my-cvs', [CvBuilderController::class, 'myResumes'])->name('cv.myResumes');
    Route::delete('/cv/delete/{id}', [CvBuilderController::class, 'delete'])->name('cv.delete');
    Route::delete('/notifications/{id}', [JobsController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications_employer/{id}', [JobsController::class, 'delete_notification_Employer'])->name('notifications.destroyEmployer');

    // Realtime Chat routes
    Route::get('/messages', [ChatController::class, 'index'])->name('account.messages');
    Route::post('/chat/init', [ChatController::class, 'initConversation'])->name('chat.init');
    Route::get('/chat/messages/{conversationId}', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send/{conversationId}', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/unread-count', [ChatController::class, 'unreadCount'])->name('chat.unreadCount');
  });
});

// Route admin
Route::group(['prefix' => 'admin'], function () {
  Route::group(['middleware' => 'auth'], function () {
    Route::controller(CareerController::class)->group(function () {
      Route::get("career", "index")->name('admin.career');
      Route::post("create-career",  "postCreateCareer")->name('admin.create.career');
      Route::post("edit-career/{id}",  "postEditCareer")->name('admin.postEditCareer.career');
      Route::delete("delete-career/{id}", "deleteCareer")->name('admin.deleteCareer.career');
      Route::get("edit-career/{id}", "getEditCareer")->name('admin.getEditCareer.career');
    });
    Route::controller(JobController::class)->group(function () {
      Route::get("job", "index")->name('admin.job');
      Route::get("edit-job/{id}", "editJob")->name('admin.edit.job');
      Route::post("update-job/{id}", "updateJob")->name('admin.update.job');
    });
    
    Route::controller(UserController::class)->group(function () {
      Route::get("user", "getUser")->name('admin.user');
      Route::get("edit-user/{id}", "editUser")->name('admin.edit.user');
      Route::post('update-user/{id}', 'updateUser')->name('admin.update.user');
      Route::delete('delete-user/{id}', 'deleteUser')->name('admin.delete.user');
    });
    Route::controller(EmployerController::class)->group(function () {
      Route::get("employer", 'getEmployer')->name('admin.employer');
      Route::get('edit-employer/{id}', 'editEmployer')->name('admin.edit.employer');
      Route::post('update-employer/{id}', 'updateEmployer')->name('admin.update.employer');
      Route::delete('delete-employer/{id}', 'deleteEmployer')->name('admin.delete.employer');
    });
    Route::controller(JobApplyController::class)->group(function () {
      Route::get('apply-job', 'index')->name('admin.apply.job');
      Route::delete('delete-apply-job/{id}', 'deleteApllyJob')->name('admin.delete.applyjob');
    });
    Route::controller(SupportChatController::class)->group(function () {
      Route::get('support-chat', 'index')->name('admin.support.index');
      Route::post('support-chat/{id}/reply', 'reply')->name('admin.support.reply');
      Route::post('support-chat/{id}/status', 'toggleStatus')->name('admin.support.status');
      Route::get('support-chat/{id}/messages', 'getNewMessages')->name('admin.support.messages');
      Route::delete('support-chat/{id}', 'destroy')->name('admin.support.destroy');
    });
    Route::controller(AdminAccountController::class)->group(function () {
      Route::get('profile', 'profile')->name('admin.profile');
      Route::put('profile/update', 'updateProfile')->name('admin.updateProfile');
      Route::post('profile', 'updateImageProfile')->name('admin.updateImageProfile');
      Route::post('change-password', 'changePassword')->name('admin.changePassword');
    });
    Route::get("home", [AdminController::class, "index"])->name('admin.home');
    Route::get('/download/{filename}', [FileDownloadController::class, 'download'])->name('download');
  });
});