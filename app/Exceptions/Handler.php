<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Session\TokenMismatchException;

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


    /**
     * 登入時若出現CSRF TOKEN錯誤的處理方法
     */
    public function render($request, Throwable $exception)
    {
        if (!$request->ajax() && ($exception instanceof TokenMismatchException)) {
            return redirect()
                ->back()
                ->withErrors('表單已過期，請重新提交')
                ->withInput($request->input());
        }

        // 若是AJAX請求或不是CSRF錯誤，則按父類預設方法處理
        return parent::render($request, $exception);
    }
}
