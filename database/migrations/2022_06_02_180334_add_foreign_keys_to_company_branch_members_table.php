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
        Schema::table('company_branch_members', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_company_branch_members_user_id')
							->references('id')
							->on('users')
							->onUpdate('cascade')
							->onDelete('cascade');

            $table->foreign('company_branch_id', 'fk_company_branch_members_company_branch_id')
							->references('id')
							->on('company_branchs')
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
        Schema::table('company_branch_members', function (Blueprint $table) {
            $table->dropForeign('fk_company_branch_members_user_id');
            $table->dropForeign('fk_company_branch_members_company_branch_id');
        });
    }
};
