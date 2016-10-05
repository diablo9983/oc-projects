<?php namespace BootstrapHunter\Projects\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class TableCreateBootstraphunterProjectsTaskGroups extends Migration
{
    public function up()
    {
        Schema::create('bootstraphunter_projects_task_groups', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 100);
            $table->string('color', 50)->default('grey');
            $table->string('icon', 100)->default('icon-check');
            $table->integer('project_id')->unsigned();
            $table->integer('order')->unsigned();
            $table->tinyInteger('hidden')->unsigned()->default(0);
            $table->timestamp('created_at')->nullable()->default('NULL');
            $table->timestamp('updated_at')->nullable()->default('NULL');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bootstraphunter_projects_task_groups');
    }
}
