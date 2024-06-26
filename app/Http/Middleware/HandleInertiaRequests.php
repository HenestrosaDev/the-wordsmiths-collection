<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\App;

class HandleInertiaRequests extends Middleware
{
	/**
	 * The root template that's loaded on the first page visit.
	 *
	 * @see https://inertiajs.com/server-side-setup#root-template
	 * @var string
	 */
	protected $rootView = 'app';

	/**
	 * Determines the current asset version.
	 *
	 * @see https://inertiajs.com/asset-versioning
	 * @param  \Illuminate\Http\Request  $request
	 * @return string|null
	 */
	public function version(Request $request): ?string
	{
		return parent::version($request);
	}

	/**
	 * Defines the props that are shared by default.
	 *
	 * @see https://inertiajs.com/shared-data
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function share(Request $request): array
	{
		$user = $request->user();

		return array_merge(
			parent::share($request), 
			[
				'flash' => [
					'alert' => fn () => $request->session()->get('alert')
				],
				'auth' => [
					'user' => $user,
					'subscription' => $user ? $user->subscription : null,
				],
				'selectedSubscriptionPlan' => SubscriptionPlan::find($request->session()->get('selected_subscription_plan_id'))
			]
		);
	}
}
