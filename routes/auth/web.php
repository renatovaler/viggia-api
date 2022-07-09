<?php

use Illuminate\Support\Facades\Route;
use App\UI\Auth\Http\Controllers\NewPasswordController;
use App\UI\Auth\Http\Controllers\VerifyEmailController;
use App\UI\Auth\Http\Controllers\RegisteredUserController;
use App\UI\Auth\Http\Controllers\PasswordResetLinkController;
use App\UI\Auth\Http\Controllers\AuthenticatedSessionController;
use App\UI\Auth\Http\Controllers\EmailVerificationNotificationController;

Route::group(['prefix'=> 'auth'], function () {
	// route domain.example/auth/register
	Route::post('/register', [RegisteredUserController::class, '__invoke'])
					->middleware('guest')
					->name('register');

	// route domain.example/auth/login
	Route::post('/login', [AuthenticatedSessionController::class, 'store'])
					->middleware('guest')
					->name('login');

	// route domain.example/auth/forgot-password
	Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
					->middleware('guest')
					->name('password.email');

	// route domain.example/auth/reset-password
	Route::post('/reset-password', [NewPasswordController::class, 'store'])
					->middleware('guest')
					->name('password.update');

	// route domain.example/auth/verify-email/{id}/{hash}
	Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
					->middleware(['auth', 'signed', 'throttle:6,1'])
					->name('verification.verify');

	// route domain.example/auth/email/verification-notification
	Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
					->middleware(['auth', 'throttle:6,1'])
					->name('verification.send');

	// route domain.example/auth/logout
	Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
					->middleware('auth')
					->name('logout');
}); // end group
