<?php namespace BootstrapHunter\Projects\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class TableCreateBootstraphunterProjectsProjectUser extends Migration
{
    public function up()
    {
        Schema::create('bootstraphunter_projects_project_user', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('user_id')->unsigned();
            $table->integer('project_id')->unsigned();

            $table->primary(['user_id','project_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bootstraphunter_projects_project_user');
    }
}
