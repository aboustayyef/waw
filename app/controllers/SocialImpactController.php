<?php

class SocialImpactController extends \BaseController {


  public function getindex(){
    return View::make('stats.socialimpactform')->with('slug', null);
  }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function postindex()
	{

    // Make sure terms are entered

    $terms = Input::get('terms');
      if (!$terms) {
        $response = array(
        'success' =>  0,
        'message'  => 'Cannot request data without input'
      );
      return Response::json($response);
    }

    // array of terms
    $terms = (New termsProcessor($terms))->terms();

    $results = [];
    foreach ($terms as $key => $term) {
      $result = (New termLookup($term))->lookup();
      $results = array_merge($results, $result);
    }

    return Response::json($results);
	}

}

class termsProcessor {

  private $input ;

  public function __construct($input){
    $this->input = urldecode($input);
  }

  // returns an array of terms
  public function terms(){
    return preg_split("/\s*\|\s*/", $this->input);
  }

}

class termLookup{
  private $term;

  public function __construct($term){
    $this->term = $term;
  }

  // do lookup
  public function lookup(){
    $posts = Post::where('post_title','Like','% '.$this->term.' %')->get();
    $result = [];
    foreach ($posts as $key => $post) {
      $timestamp = $post->post_timestamp;
      $dateObject = (new Carbon\Carbon)->createFromTimestamp($timestamp);
      $year = $dateObject->year;
      $day = $dateObject->dayOfYear;
      $result[] = array(
        'title' => $post->post_title,
        'url' => $post->post_url,
        'virality' => $post->post_virality,
        'year' => $year,
        'day' => $day
      );
    }
    return $result;
  }

}




