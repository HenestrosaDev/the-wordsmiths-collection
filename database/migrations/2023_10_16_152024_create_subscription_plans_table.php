<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlansTable extends Migration
{
	public function up()
	{
		Schema::create('subscription_plans', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->decimal('price', 10, 2);
			$table->string('currency');
			$table->integer('duration_days');
			$table->text('description')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('subscription_plans');
	}
}

