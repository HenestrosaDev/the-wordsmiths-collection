<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** 
 * Just for demonstration purposes. In a real-world scenario, 
 * there would be more things to handle, such as communicating
 * changes to payment and subscription details changes with a 
 * third-party service.
 */
class SubscriptionController extends Controller
{
	/**
	 * Process the request to reactivate the subscription.
	 */
	public function reactivate(Request $request): RedirectResponse
	{
		$request->user()->subscription->update([
			'status' => 'active'
		]);

		return back()->with(
			'alert',
			[
				'type'		=> 'success',
				'message'	=> __('alerts.subscription_reactivate_success'),
			]
		);
	}

	/**
	 * Change user's subscription plan.
	 */
	public function update(Request $request, int $id): RedirectResponse
	{
		$newSubscriptionPlan = SubscriptionPlan::find($id);

		if ($newSubscriptionPlan) {
			$request->user()->subscription->update([
				'subscription_plan_id' => $id
			]);
		} else {
			return back()->with(
				'alert',
				[
					'type'		=> 'danger',
					'message'	=> __('alerts.subscription_update_danger'),
				]
			);
		}
		

		return back()->with(
			'alert',
			[
				'type'		=> 'success',
				'message'	=> __('alerts.subscription_update_success' , [
					'name' => $newSubscriptionPlan->name
				]),
			]
		);
	}

	/**
	 * Handle cancel subscription request.
	 */
	public function cancel(Request $request): RedirectResponse
	{
		$request->user()->subscription->update([
			'status' => 'canceled'
		]);

		return back()->with(
			'alert',
			[
				'type'		=> 'success',
				'message'	=> __(
					'alerts.subscription_cancel_success'),
			]
		);
	}
}
