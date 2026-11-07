<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
  /**
   * The list of the inputs that are never flashed to the session on validation exceptions.
   *
   * @var array<int, string>
   */
  protected $dontFlash = [
    'current_password',
    'password',
    'password_confirmation',
  ];

  /**
   * Register the exception handling callbacks for the application.
   */
  public function register(): void
  {
    $this->reportable(function (Throwable $e) {
      //
    });
  }
  public function render($request, Throwable $exception)
  {
    // Kiểm tra nếu là HttpException và mã lỗi là 404
    if ($exception instanceof HttpException && $exception->getStatusCode() === 404) {
      return response()->view('errors.404', [], 404);
    }

    // Gọi phương thức render gốc nếu không phải lỗi 404
    return parent::render($request, $exception);
  }
}
