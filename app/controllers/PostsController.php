<?php
  /**
  *
  */
  class PostsController extends BaseController
  {

    /*
    *   This function displays our initial rendering of posts
    */

    function index($channel='all', $action=null){

      if ($channel == 'search'){
        $query = Input::get('q');
        if (empty($query)) {
          // if no parameters, forward back to home page
          return Redirect::to('/posts/all');
        }else{
          // initialize posts counters
          Session::put('postsCounter', 0);
          Session::put('cardsCounter', 0);
          Session::put('pageKind', 'searchResults');
          Session::put('searchQuery', stripcslashes($query));
          return View::make('posts.main');
        }
      }

      // 1- $channel is a child resolve it to its parent channel;
      $canonicalChannel = Channel::resolveTag($channel);

      // 2- if we have a subchannel, redirect to main channel
      if ($canonicalChannel != $channel) {
        return Redirect::to('posts/'.$canonicalChannel);
      }

      // set pageKind & channel sessions

      Session::put('channel', $canonicalChannel);
      if ($canonicalChannel == 'all') {
        Session::put('pageKind', 'allPosts');
      } else {
        Session::put('pageKind', 'channel');
      }

      // initialize posts counters
      Session::put('postsCounter', 0);
      Session::put('cardsCounter', 0);

      return View::make('posts.main');
    }

    public static function elasticSearch($query){

      // prepare elastic search client

      $client = new Elasticsearch\Client();
      $searchParameters = array();
      $searchParameters['index']='lebaneseblogs';
      $searchParameters['type']='post';
      $searchParameters['size']  = 500; // to return all results
      $searchParameters['body']['query']['multi_match']['content'] = array(
        'query' => $query,
        'fuzziness' =>  0.8,
        'fields'  =>  ['title^3', 'content'],
      );

      $results = $client->search($searchParameters);

      $totalResults = $results['hits']['total'];
      $listOfIds = array();
      $posts = array();
      foreach ($results['hits']['hits'] as $key => $result) {
        $id = $result['_id'];
        $listOfIds[] = $id;
      }
      return $listOfIds;
    }
}
