<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlanResource extends JsonResource
{
	public static $wrap = null;

	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'						=> $this->id,
			'name'					=> $this->name,
			'price'					=> $this->price,
			'currency'			=> $this->currency,
			'duration_days'	=> $this->duration_days,
			'description'		=> $this->description,
		];
	}
}
