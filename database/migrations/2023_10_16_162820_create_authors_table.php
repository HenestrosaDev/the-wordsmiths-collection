<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorsTable extends Migration
{
	public function up()
	{
		Schema::create('authors', function (Blueprint $table) {
			$table->id();
			$table->string('slug')->unique();
			$table->string('first_name');
			$table->string('last_name')->nullable();
			$table->date('birth_date');
			$table->date('death_date')->nullable();
			$table->text('description')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('authors');
	}
}
