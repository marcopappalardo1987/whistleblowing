<?php

use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

Route::middleware('guest')->group(function () {
    Route::get('{locale}/registrati', [RegisteredUserController::class, 'create'])
                ->name('it.register');
    Route::get('{locale}/register', [RegisteredUserController::class, 'create'])
                ->name('en.register');

    Route::post('{locale}/registrati', [RegisteredUserController::class, 'store'])
                ->middleware(ProtectAgainstSpam::class);
    Route::post('{locale}/register', [RegisteredUserController::class, 'store'])
                ->middleware(ProtectAgainstSpam::class);

    Route::get('{locale}/accedi', [AuthenticatedSessionController::class, 'create'])
                ->name('it.login');
    Route::get('{locale}/login', [AuthenticatedSessionController::class, 'create'])
                ->name('en.login');            

    Route::post('{locale}/accedi', [AuthenticatedSessionController::class, 'store'])->name('it.login.form');
    Route::post('{locale}/login', [AuthenticatedSessionController::class, 'store'])->name('en.login.form');

    Route::get('{locale}/password-dimenticata', [PasswordResetLinkController::class, 'create'])
                ->name('it.password.request');
    Route::get('{locale}/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('en.password.request');

    Route::post('{locale}/password-dimenticata', [PasswordResetLinkController::class, 'store'])
                ->name('it.password.email');
    Route::post('{locale}/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('en.password.email');

    Route::get('{locale}/ripristina-password/{token}', [NewPasswordController::class, 'create'])
                ->name('it.password.reset');
    Route::get('{locale}/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('en.password.reset');

    Route::post('{locale}/ripristina-password', [NewPasswordController::class, 'store'])
                ->name('it.password.store');
    Route::post('{locale}/reset-password', [NewPasswordController::class, 'store'])
                ->name('en.password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('{locale}/verifica-email', EmailVerificationPromptController::class)
                ->name('it.verification.notice');
    Route::get('{locale}/verify-email', EmailVerificationPromptController::class)
                ->name('en.verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
