<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTagsColumnToPostsTableAndFillItUp extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    //create the posts tags column
		Schema::table('posts', function($table)
      {
          $table->string('post_tags');
      });

    $allPosts = Post::all();

    foreach ($allPosts as $key => $post)
    {

      $author_id = $post->blog_id;

      // check if author is blogger
      if (Blog::where('blog_id',$author_id)->count() > 0)
      {
        $blog_tags = Blog::where('blog_id',$author_id)->first()->blog_tags;
        $post->post_tags = $blog_tags;
        try {
          $post->save();
        } catch (Exception $e) {
          echo 'problem saving the tags of post: '.$post->post_title."\n";
        }
        continue;
      }

      // Check if columnist
      if (Columnist::where('col_shorthand',$author_id)->count() > 0)
      {
        $column_tags = Columnist::where('col_shorthand',$author_id)->first()->col_tags;
        $post->post_tags = $column_tags;
        try {
          $post->save();
        } catch (Exception $e) {
          echo 'problem saving the tags of column: [' . $post->post_title . "]\n";
        }
        continue;
      }

      // if it's neither a blogger or columnist, then author must have been removed
      echo 'The author for the post [' . $post->post_title . ' ] seems to have been removed'."\n";
    }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('posts', function($table)
      {
          $table->dropColumn('post_tags');
      });
	}

}
