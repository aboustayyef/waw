<?php namespace LebaneseBlogs\Crawling\Articles ;

    class ContentSanitizer{
        private $initial;

        public function __construct($initial){
            $this->initial = $initial;
        }

        public function sanitize(){

          // Remove <Script> Tags
          $this->initial = preg_replace("#<script>.+</script>#uiUs", "", $this->initial);

          // Remove <iframe> Tags
          $this->initial = preg_replace("#<iframe.+</iframe>#", "", $this->initial);

          // Remove empty Tags;
          $this->initial = preg_replace("#<[a-zA-Z]+>\\s*</[a-zA-Z]+>#uiUs", " ", $this->initial);

          // remove <div> Tags
          $result = preg_replace("#<div .+>|<\\/div>#uiUs", " ", $this->initial);

          return $result;

        }
    }

?>
