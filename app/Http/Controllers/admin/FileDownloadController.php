<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileDownloadController extends Controller
{
  public function download($filename): BinaryFileResponse
  {
    $filePath = public_path('assets/user/CV/' . $filename);
    if (!file_exists($filePath)) {
      abort(404, 'File not found');
    }
    return response()->download($filePath);
  }
}
