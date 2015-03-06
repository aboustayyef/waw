<?php

  use LebaneseBlogs\Crawling\Articles\MediaSource;
  use LebaneseBlogs\Crawling\Articles\ListOfAuthorArticles;

  class Columnist extends Eloquent
  {
    protected $primaryKey = 'col_shorthand';
    public $timestamps = false;

    public function posts(){
      return $this->hasMany('Post');
    }

    // returns a MediaSource object used for scraping
    public function mediaSource(){
      return (new MediaSource($this->col_media_source));
    }


    // return an array of urls of the latest articles by this author
    public function latestArticles(){
      return (new ListOfAuthorArticles($this->col_shorthand));
    }

  }
