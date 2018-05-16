<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //echo 'here';die;
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug',255)->nullable();
           
            $table->string('username',255)->nullable();
            $table->string('email',255);
            $table->string('password',255);
            $table->boolean('status')->default(1)->comment('0 = Inactive, 1 = Active');
            $table->boolean('is_private')->default(0)->comment('0 = Public, 1 = Private');

            $table->enum('role', ['A', 'U','D'])->default('U')->comment('U = User, A = Admin, D= Developer');
            
            $table->rememberToken();
            $table->timestamps();



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
