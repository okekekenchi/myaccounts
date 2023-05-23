<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {            
            $table->id();
            $table->string('business_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('designation')->nullable();
            $table->string('work_number')->nullable();
            $table->string('language')->nullable();
            $table->boolean('two_factor_auth')->nullable();
            $table->string('email')->unique();
            $table->boolean('has_password')->default(false);
            $table->string('password')->nullable();
            $table->enum('type', ['system', 'agent', 'client']);
            $table->string('profile_picture')->nullable();
            $table->json('signature')->nullable();
            $table->json('social_media')->nullable();
            $table->rememberToken()->nullable();
            $table->string('timezone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
                       
            $table->bigInteger('report_to')->unsigned()->index()->nullable();
            
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            
            $table->bigInteger('team_id')->unsigned()->index()->nullable();
            $table->bigInteger('role_id')->unsigned()->index()->nullable();

            $table->bigInteger('company_id')->unsigned()->index()->nullable();
            $table->bigInteger('branch_id')->unsigned()->index()->nullable();
            $table->bigInteger('segment_id')->unsigned()->index()->nullable();

            $table->bigInteger('client_id')->unsigned()->index()->nullable();
            $table->bigInteger('agent_id')->unsigned()->index()->nullable();

            $table->boolean('active')->default(true);
            $table->timestamp('login_at')->nullable();
            $table->timestamp('logout_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
