<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('company_email', 100);
            $table->string('company_phone', 20)->nullable();
            $table->string('company_mobile', 20)->nullable();
            $table->string('parent_id', 50)->nullable();
            $table->text('website')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('region')->nullable();            
            $table->bigInteger('country_id')->unsigned()->index()->nullable();

            $table->string('first_name', 30);
            $table->string('last_name', 30);
            $table->string('designation', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('agents');
    }
}
