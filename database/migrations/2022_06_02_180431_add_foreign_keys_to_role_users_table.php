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
        Schema::table('role_users', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_role_users_user_id')
                            ->references('id')
                            ->on('users')
                            ->onUpdate('cascade')
                            ->onDelete('cascade');
            $table->foreign('company_id', 'fk_role_users_company_id')
                            ->references('id')
                            ->on('companies')
                            ->onUpdate('cascade')
                            ->onDelete('cascade');
            $table->foreign('company_branch_id', 'fk_role_users_company_branch_id')
                            ->references('id')
                            ->on('company_branchs')
                            ->onUpdate('cascade')
                            ->onDelete('cascade');
            $table->foreign('role_id', 'fk_role_users_role_id')
                            ->references('id')
                            ->on('roles')
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
        Schema::table('role_users', function (Blueprint $table) {
            $table->dropForeign('fk_role_users_user_id');
            $table->dropForeign('fk_role_users_company_id');
            $table->dropForeign('fk_role_users_company_branch_id');
            $table->dropForeign('fk_role_users_role_id');
        });
    }
};
