<?php namespace waw\Crawling;

use Symfony\Component\DomCrawler\Crawler ;


class TwitterTimeline
{
  private $html, $htmlCrawler, $user, $tweetsCollection;
  function __construct($twitterUser){
    $this->user = $twitterUser;
    $this->html = @file_get_contents('https://twitter.com/'.$twitterUser);
    $this->htmlCrawler = new Crawler($this->html);
    $this->tweetsCollection = $this->htmlCrawler->filter('.ProfileTweet');
  }
  function getTweets(){
    $tweets = [];
    foreach ($this->tweetsCollection as $key => $tweetCrawler) {
      $tweetCrawler = new Crawler($tweetCrawler);
      $tweets[] = new Tweet($tweetCrawler, $this->user);
    }
    return $tweets;
  }
  function getTweet($key=0){
    $tweetCrawler = $this->tweetsCollection->eq($key);
    return new Tweet($tweetCrawler, $this->user);
  }
}

class Tweet
{
  private $tweetCrawler;
  public $user;
  function __construct(Crawler $tweetCrawler, $user)
  {
    $this->tweetCrawler = $tweetCrawler;
    $this->user = $user;
  }

  function timestamp(){
    $timestamp = $this->tweetCrawler->filter('.js-short-timestamp');
    $timestamp = $timestamp->attr('data-time');
    return $timestamp;
  }

  function hashtags(){
    $hashtags = $this->tweetCrawler->filter('.twitter-hashtag.pretty-link b');
      $tweetHashtags = [];
      foreach ($hashtags as $key => $hashtag) {
        $theHashtag = $hashtag->nodeValue;
        if (empty($theHashtag)) {
          continue;
        } else {
          $tweetHashtags[] = $hashtag->nodeValue ;
        }
      }
      return $tweetHashtags;
  }
  function links(){
    $links = $this->tweetCrawler->filter('a.twitter-timeline-link');
      $tweetlinks =[];
      foreach ($links as $key => $link) {
        $theLink = $link->getAttribute('title');
        if (empty($theLink)) {
          continue;
        } else {
          // $linkToResolve = $link->getAttribute('title');
          // $urlResolver = new UrlResolver($linkToResolve);
          // $effeciveUrl = $urlResolver->resolve();
          $tweetlinks[] = $link->getAttribute('title');
        }
      }
      return $tweetlinks;
  }

}

 ?>
