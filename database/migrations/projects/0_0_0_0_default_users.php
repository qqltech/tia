<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Defaults\User;

class DefaultUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_users', function (Blueprint $table) {

            //belum di update
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('username',60)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->bigInteger('comp_id')->default(1)->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('creator_id')->nullable();
            $table->integer('last_editor_id')->nullable();
            $table->datetime("edited_at")->nullable();
            $table->integer("deletor_id")->nullable();
            $table->datetime("deleted_at")->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->integer('m_employee_id')->comment('{"src":"set.m_kary.id"}')->nullable();
            $table->string("tipe",15)->default("Admin");
            $table->text('catatan')->nullable();
        });
        $hasher = app()->make('hash');
        User::create(
            [
                'name' => "trial",
                'email' => "trial@trial.trial",
                'username'=>"trial",
                'password' => $hasher->make("trial")
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_users');
    }
}
