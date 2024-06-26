<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
	/**
	 * Display the email verification prompt.
	 */
	public function __invoke(Request $request): RedirectResponse|Response
	{
		$user = $request->user();

		if ($user->hasVerifiedEmail()) {
			return redirect()->route('payment.show');
		} else if ($user->creditCard) {
			return redirect()->intended(RouteServiceProvider::HOME);
		}

		return Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
	}
}
