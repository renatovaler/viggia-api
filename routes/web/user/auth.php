<?php

use Illuminate\Support\Facades\Route;
use App\Domains\User\Http\Auth\Controllers\NewPasswordController;
use App\Domains\User\Http\Auth\Controllers\VerifyEmailController;
use App\Domains\User\Http\Auth\Controllers\RegisteredUserController;
use App\Domains\User\Http\Auth\Controllers\PasswordResetLinkController;
use App\Domains\User\Http\Auth\Controllers\AuthenticatedSessionController;
use App\Domains\User\Http\Auth\Controllers\EmailVerificationNotificationController;


// route users/auth/register
Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest')
                ->name('register');

// route users/auth/login
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest')
                ->name('login');

// route users/auth/forgot-password
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');

// route users/auth/reset-password
Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('password.update');

// route users/auth/verify-email/{id}/{hash}
Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

// route users/auth/email/verification-notification
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1'])
                ->name('verification.send');

// route users/auth/logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');
