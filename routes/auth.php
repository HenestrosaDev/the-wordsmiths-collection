<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\PaymentController;
use App\Http\Controllers\Auth\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('register', [RegisteredUserController::class, 'create'])->name('register');

Route::middleware('guest')->group(function () {
	Route::post('register', [RegisteredUserController::class, 'store']);

	Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
	Route::post('login', [AuthenticatedSessionController::class, 'store']);

	Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
	Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

	Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
	Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
	Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
	Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
		->middleware(['signed', 'throttle:6,1'])
		->name('verification.verify');

	Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
		->middleware('throttle:6,1')
		->name('verification.send');

	Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
	Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

	Route::put('password', [PasswordController::class, 'update'])->name('password.update');

	Route::get('payment', [PaymentController::class, 'show'])->name('payment');
	Route::post('payment', [PaymentController::class, 'store'])->name('payment.store');
	Route::put('payment/{id}', [PaymentController::class, 'update'])->name('payment.update');
	
	Route::inertia('confirmation', 'Auth/Confirmation')->name('confirmation');

	Route::post('subscription/reactivate', [SubscriptionController::class, 'reactivate'])->name('subscription.reactivate');
	Route::post('subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
	Route::patch('subscription/update/{id}', [SubscriptionController::class, 'update'])->name('subscription.update');

	Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
