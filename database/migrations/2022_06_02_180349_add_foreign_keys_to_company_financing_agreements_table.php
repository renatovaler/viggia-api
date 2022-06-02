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
        Schema::table('company_financing_agreements', function (Blueprint $table) {
            $table->foreign('owner_company_id', 'fk_company_financing_agreements_owner_company_id')
							->references('id')
							->on('companies')
							->onUpdate('cascade')
							->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_financing_agreements', function (Blueprint $table) {
            $table->dropForeign('fk_company_financing_agreements_owner_company_id');
        });
    }
};
