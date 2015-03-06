<?php
/**
*
*/
class User extends Eloquent
{
  // use new users table to avoid conflict when moving to new version
  protected $table = 'new_users';
  public $timestamps = false;

  /*
  |--------------------------------------------------------------------------
  | Find out if a user is logged in
  |--------------------------------------------------------------------------
  | usage: if User::signedIn() return user id or return false
  */

  public static function signedIn(){

    // check session
    if (Session::has('lb_user_id')) {
      return Session::get('lb_user_id');
    }

    // check cookie
    if (Cookie::has('lb_user_id')) {
      return Cookie::get('lb_user_id');
    }

    // if none of those exists
    return false;
  }

/**
 * Get the blogs the user follows
 */
  public function blogs(){
      return $this->belongsToMany('Blog');
  }

/**
 * Get the posts the user has saved
 */
  public function posts(){
      return $this->belongsToMany('Post');
  }

/*
|---------------------------------------------------------------------
|   see if a blog belong to users' favorites
|---------------------------------------------------------------------
|
*/
  public function follows($blog_id){
    if (in_array($blog_id, $this->blogs->lists('blog_id'))) {
      return true;
    }
    return false;
  }

/*
|---------------------------------------------------------------------
|   returns amount of favorited blogs
|---------------------------------------------------------------------
|
*/
  public function followsHowMany(){
    return $this->blogs->count();
  }

/*
|---------------------------------------------------------------------
|   returns amount of saved posts
|---------------------------------------------------------------------
|
*/
  public function likedHowMany(){
    return $this->posts->count();
  }


/*
|---------------------------------------------------------------------
|   see if a blog belong to users' favorites
|---------------------------------------------------------------------
|
*/
  public function likes($post_id){
    if (in_array($post_id, $this->posts->lists('post_id'))) {
      return true;
    }
    return false;
  }


/*
|---------------------------------------------------------------------
|   Get posts saved by user
|---------------------------------------------------------------------
|
|   Returns a list of urls of posts saved by user.
|   If $comprehensive is set to true, method will return all
|   post details,
|
*/
  public function getLikedPosts($comprehensive = false){
    $listOfPostUrls = DB::table('users_posts')->where('user_id','=',$this->user_id)->lists('post_url');
    if ($comprehensive) {
      $posts = DB::table('posts')->whereIn('post_url', $listOfPostUrls)->get();
      return $posts;
    }
    return $listOfPostUrls;
  }


  public function profileImage(){
      $img = $this->image_url;
      if (!empty($img)) {
        return $img;
      }else{
        return asset('/img/placeholder_profile_pic.png');
      }
  }

  public function firstName(){
    $firstName = $this->first_name;
    if (!empty($firstName)) {
      return $firstName;
    }else{
      return '';
    }
  }

  public function lastName(){
    $lastName = $this->last_name;
    if (!empty($lastName)) {
      return $lastName;
    }else{
      return '';
    }
  }

  public function fullName(){
    return $this->firstName() . ' ' . $this->lastName();
  }


  public function owns($blogId = false){

    // if $blogId is set, it will return true or false (whehter this user owns that blog)
    // otherwise, it will return the blog id(s) of owned blogs (if any) or false

    $twitter = $this->twitter_username;

    // if this user has no twitter username
    if (!$twitter) return false;

    $blogs = Blog::where('blog_author_twitter_username', $twitter)->get();

    if ($blogs->count() > 0) {

      $blogs_array = [];

      foreach ($blogs as $key => $blog) {
        $blogs_array[] = $blog->blog_id;
      }
      // if $blogId is set, we want to check if that blogId is in the result array
      if ($blogId) {
        if (in_array($blogId, $blogs_array)) {
          return true;
        }else{
          return false;
        }
      }else{
        // if not, we simply return the array
        return $blogs_array;
      }
    } else {
      // this twitter account has no associated blogs
      return false;
    }
  }


  /*
  |--------------------------------------------------------------------------
  | Process the data provided by login provider log in
  |--------------------------------------------------------------------------
  | 1 - if user exists, see if missing records (temporary untill all records are full).
  | 2 - if user doesn't exist create user
  | 3 - set up cookie
  | 4 - if there is an intended page, go there, otherwise
  */

  public static function register($userDetails){
    $newUser = false;
    // if user exists;
    if (User::where('provider',$userDetails['provider'])->where('provider_id',$userDetails['providerId'])->count() > 0 ){

      //select user
      $user = User::where('provider',$userDetails['provider'])->where('provider_id',$userDetails['providerId'])->first();

    }else{
      $user = new User;
      $newUser = true;
    }
    // in any case, fill the data
    $user->provider = $userDetails['provider'];
    $user->provider_id = $userDetails['providerId'];
    $user->first_name = $userDetails['firstName'];
    $user->last_name = $userDetails['lastName'];
    $user->email_address = $userDetails['email'];
    $user->gender = $userDetails['gender'];
    $user->updated_timestamp = time();
    $user->last_visit_timestamp = time();
    $user->image_url = $userDetails['imageUrl'];
    $user->visit_count = $user->visit_count + 1;
    $user->twitter_username = strtolower($userDetails['twitterHandle']);
    $user->save();

    Session::put('lb_user_id', $user->id);
    $userId = $user->id;

    // If there is a blog queued for following
    if (Session::has('blogToFollow')) {
      $blogId = Session::get('blogToFollow');
      Session::remove('blogToFollow');
      // check if this blog is already favorited by that user
      $favs = DB::table('blog_user')->where('blog_id',$blogId)->where('user_id',$userId)->count();
      // if no go ahead
      if ($favs == 0) {
        DB::table('blog_user')->insert(
          array('blog_id' => $blogId, 'user_id' => $userId)
        );
      }
    }

    if (Session::has('postToLike')) {
      $postId = Session::get('postToLike');
      // check if this post is already liked by that user
      $likes = DB::table('post_user')->where('post_id', $postId)->where('user_id',$userId)->count();
      if ($likes == 0 ) {
        DB::table('post_user')->insert(
          array('post_id' => $postId, 'user_id' => $userId)
        );
      }
    }

    if ($newUser) {
      return Redirect::to('/user/welcome')->withCookie(Cookie::make('lb_user_id', $user->id, 43829));
    }

    if (Session::has('finalDestination')) {
      return Redirect::to(Session::get('finalDestination'))->withCookie(Cookie::make('lb_user_id', $user->id, 43829)) ;
    } else {
      return Redirect::to('user/following')->withCookie(Cookie::make('lb_user_id', $user->id, 43829));
    }
  }
}
