<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
	public function up()
	{
		Schema::create('uploads', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('type', 4);
			$table->integer('size');
			$table->binary('data');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('uploads');
	}
}


