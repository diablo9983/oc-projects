<?php namespace BootstrapHunter\Projects\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBootstraphunterProjectsProjects extends Migration
{
    public function up()
    {
        Schema::create('bootstraphunter_projects_projects', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 255);
            $table->text('description');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bootstraphunter_projects_projects');
    }
}
