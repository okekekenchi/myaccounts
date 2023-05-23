<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('alpha_code', 7);
            $table->string('alpha3', 5)->nullable();
            $table->string('ioc')->nullable();
            $table->string('calling_code', 50)->nullable();
            $table->string('language', 100)->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_symbol', 50)->nullable();
            $table->string('subunit', 100)->nullable();
            $table->string('subunit_symbol', 10)->nullable();
            $table->string('timezone')->nullable();
            $table->boolean('active')->default(true);
            
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
