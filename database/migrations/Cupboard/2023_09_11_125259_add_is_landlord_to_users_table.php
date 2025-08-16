<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::table('users', function (Blueprint $table) {            
            $table->boolean('is_landlord')->after('is_admin')->unique()->default(false);
            // $table->boolean('is_super_user')->after('is_landlord')->unique()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_landlord', /*'is_super_user'*/]);
        });
    }
};
