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
        Schema::create('company_financing_agreements', function (Blueprint $table) {
            $table->id();
            $table->integer('owner_company_id')->unsigned();
            $table->string('license_plate');
            $table->boolean('is_active')->default(1); // true
            $table->boolean('is_wanted')->default(0); //false
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
        Schema::dropIfExists('company_financing_agreements');
    }
};
