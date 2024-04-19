<?php

namespace App\Http\Controllers\Web;

use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\CreditCardResource;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\SubscriptionPlanResource;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
	/**
	 * Display the user's profile form.
	 */
	public function edit(Request $request): Response
	{
		$user = $request->user();

		$creditCardResource = null;
		if ($creditCard = $user->creditCard) {
			$creditCardResource = new CreditCardResource($creditCard);
		}

		$subscription = $user->subscription()->with('subscriptionPlan')->first();
		$subscriptionResource = null;
		if ($subscription) {
			$subscriptionResource = new SubscriptionResource($subscription);
		}

		$subscriptionPlanId = optional($subscription)->subscription_plan_id;
		$otherSubscriptionPlanId = $subscriptionPlanId == 1 ? 2 : 1;
		$otherSubscriptionPlan = SubscriptionPlan::find($otherSubscriptionPlanId);
		$otherSubscriptionPlanResource = new SubscriptionPlanResource($otherSubscriptionPlan);

		return Inertia::render('Profile/Edit', [
			'mustVerifyEmail'				=> $user instanceof MustVerifyEmail,
			'status'								=> session('status'),
			'creditCard'						=> $creditCardResource,
			'subscription'					=> $subscriptionResource,
			'otherSubscriptionPlan'	=> $otherSubscriptionPlanResource
		]);
	}

	/**
	 * Update the user's profile information.
	 */
	public function update(ProfileUpdateRequest $request): RedirectResponse
	{
		$request->user()->fill($request->validated());

		if ($request->user()->isDirty('email')) {
			$request->user()->email_verified_at = null;
		}

		$request->user()->save();

		return Redirect::route('profile.edit');
	}

	/**
	 * Delete the user's account.
	 */
	public function destroy(Request $request): RedirectResponse
	{
		$request->validate([
			'password' => ['required', 'current_password'],
		]);

		$user = $request->user();

		Auth::logout();

		$user->delete();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return Redirect::to(RouteServiceProvider::HOME);
	}
}
