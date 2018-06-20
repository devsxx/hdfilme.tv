<?php namespace Anhi\Movie\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAnhiMovieMovies extends Migration
{
    public function up()
    {
        Schema::create('anhi_movie_movies', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('server_id');
            $table->integer('server_sub_id');
            $table->integer('user_id');
            $table->integer('country');
            $table->boolean('type');
            $table->string('name');
            $table->smallInteger('year');
            $table->smallInteger('episode');
            $table->smallInteger('episode_current');
            $table->smallInteger('episode_total');
            $table->boolean('cinema');
            $table->smallInteger('imdb');
            $table->smallInteger('length');
            $table->smallInteger('quality');
            $table->decimal('cost', 10, 0);
            $table->smallInteger('discount_type');
            $table->decimal('discount', 10, 0);
            $table->smallInteger('demo_server_id');
            $table->string('demo_link');
            $table->text('demo_sub');
            $table->text('demo_sub_en');
            $table->text('desc');
            $table->string('friendly_url');
            $table->string('site_title');
            $table->string('meta_desc');
            $table->string('meta_key');
            $table->integer('image_id');
            $table->string('image_name');
            $table->string('banner');
            $table->integer('created');
            $table->integer('count');
            $table->integer('count_buy');
            $table->integer('view');
            $table->integer('view_date_day');
            $table->integer('view_in_day');
            $table->integer('view_in_week');
            $table->integer('view_in_months');
            $table->double('rate', 10, 0);
            $table->integer('rate_total');
            $table->integer('rate_count');
            $table->integer('feature');
            $table->integer('last_updated');
            $table->integer('expire');
            $table->boolean('hide');
            $table->integer('slide');
            $table->string('english_name');
            $table->string('link_filmstart');
            $table->string('link_imdb');
            $table->smallInteger('audio');
            $table->integer('updated_at');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('anhi_movie_movies');
    }
}
