<?php namespace LebaneseBlogs\Crawling\Articles ;

// A Dictionary class for Media Source Objects

class MediaSourceDefinitions{

  public $all;

  public function __construct(){
    $this->all = array(
      array(
        'identifier'        =>  'The Daily Star', // used to identify media source
        'domain'            =>  'dailystar.com.lb',
        'root'              =>  'http://dailystar.com.lb',
        'articleLinks'      =>  '.more-news h4 a', // for the list of articles page, identifies link to article,
        'title'             =>  '#bodyHolder_divTitle', // everything below is for individual article pages
        'content'           =>  '#divDetails',
        'dateTimeLocation'  =>  '#bodyHolder_divDate',
        'dateTime_regex'    =>  '[a-zA-z]{3}\.\s\d{2},\s\d{4}\s\|\s\d{1,2}:\d{1,2}\s[A-Z]{2}', // ex Aug. 03, 2014 | 09:34 PM
        'dateTime_format'   =>  'M. d, Y \| h:i A',
      ),
      array(
        'identifier'   =>  'Now Lebanon',
        'domain'        =>  'now.mmedia.me',
        'root'          =>  'http://now.mmedia.me',
        'articleLinks'  =>  'div.author_profile_listing_content  a',
        'title'              =>  'h1.article_title',
        'content'            =>  '#news_template > div.main_area_align > div > div.article_section > div.article_main_section > div.main_article > div.main_txt',
        'dateTimeLocation'   =>  'h3.article_date',
        'dateTime_regex'     =>  '\d{1,2}/\d{1,2}/\d{4}\s{3}\d{1,2}:\d{1,2}\s[A-Z]{2}', // ex 1/08/2014 11:09 AM
        'dateTime_format'    =>  'j/m/Y   h:i A',
      ),
      array(
        'identifier'   =>  'Now Lebanon Blogs',
        'domain'        =>  'now.mmedia.me',
        'root'          =>  'http://now.mmedia.me',
        'articleLinks'  =>  'div.author_profile_listing_content  a',
        'title'              =>  'h1.article_title',
        'content'            =>  '#news_template > div.main_area_align > div > div.article_section > div.article_main_section > div.main_article > div.main_txt',
        'dateTimeLocation'   =>  'h3.article_date',
        'dateTime_regex'     =>  '\d{1,2}/\d{1,2}/\d{4}\s{3}\d{1,2}:\d{1,2}\s[A-Z]{2}', // ex 1/08/2014 11:09 AM
        'dateTime_format'    =>  'j/m/Y   h:i A',
      ),
      array(
        'identifier'   =>  'The National',
        'domain'        =>  'thenational.ae',
        'root'          =>  'http://thenational.ae',
        'articleLinks'  =>  'li .holder h4 a',
        'title'              =>  '.mainflash-article h1',
        'content'            =>  'div.article-body-page',
        'dateTimeLocation'   =>  'div.articleinfo',
        'dateTime_regex'     =>  '[A-Za-z]+\s\d{1,2},\s\d{4}', // ex August 3, 2014
        'dateTime_format'    =>  'F j, Y',
      ),
      array(
        'identifier'   =>  'Al-Akhbar English',
        'domain'        =>  'al-akhbar.com',
        'root'          =>  'http://english.al-akhbar.com',
        'articleLinks'  =>  '.views-field-title a',
        'title'              =>  'h1.title',
        'content'            =>  'div.content-wrap',
        'dateTimeLocation'   =>  'span.date-display-single',
        'dateTime_regex'     =>  '[a-zA-z]+\s\d{1,2},\s\d{4}', // ex July 21, 2014
        'dateTime_format'    =>  'F j, Y',
      ),
      array(
        'identifier'   =>  'Beirut.com',
        'domain'        =>  'beirut.com',
        'root'          =>  'http://beirut.com',
        'articleLinks'  =>  '.list-rows .post h3 a',
        'title'              =>  '#sidebar h2',
        'content'            =>  '.profile p',
        'dateTimeLocation'   =>  array('div.info > p:nth-child(2) > span:nth-child(3)','div.info > p:nth-child(2) > span:nth-child(2)'),
        'dateTime_regex'     =>  '[a-zA-Z]+\s\d{1,2},\s\d{4}', // ex Aug 9, 2014
        'dateTime_format'    =>  'M j, Y',

      ),
      array(
        'identifier'        =>  'Al Modon', // used to identify media source
        'domain'            =>  'www.almodon.com',
        'root'              =>  'http://www.almodon.com',
        'articleLinks'      =>  '.gridItemDetails h2 a', // for the list of articles page, identifies link to article,
        'title'             =>  '.section_title h3', // everything below is for individual article pages
        'content'           =>  '.inner_content',
        'dateTimeLocation'  =>  '.section_title.clearfix > span:nth-child(5)',
        'dateTime_regex'    =>  '\d{1,2}/\d{1,2}/\d{4}', // ex 01/05/2015
        'dateTime_format'   =>  'd/m/Y',
      ),
      array(
        'identifier'        =>  'Orient Le Jour', // used to identify media source
        'domain'            =>  'www.lorientlejour.com',
        'root'              =>  'http://www.lorientlejour.com/',
        'articleLinks'      =>  '.articletext h2 a', // for the list of articles page, identifies link to article,
        'title'             =>  'h1', // everything below is for individual article pages
        'content'           =>  '.articlePage',
        'dateTimeLocation'  =>  '.attributes + .date',
        'dateTime_regex'    =>  '\d{1,2}/\d{1,2}/\d{4}', // ex 01/05/2015
        'dateTime_format'   =>  'd/m/Y',
      ),

    );
  }
  public function first(){
    return $this->all[0];
  }

}

?>
