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
        Schema::create('vehicle_localizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('license_plate');
			$table->decimal('localization_latitude', 10, 7)->nullable();
			$table->decimal('localization_longitude', 11, 7)->nullable();
            $table->timestamp('localized_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_localizations');
    }
};
