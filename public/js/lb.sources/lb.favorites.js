$('document').ready(function(){

// follow
$(document).on('click', '.followBlogger', function(){
  event.stopPropagation();

  var $this = $(this); // to persist variable through closure;

  // if user is not logged in, forward to login page
  if (lbApp.signedIn == false) {
    var currentPage = window.location.pathname;
    window.location = lbApp.rootPath + '/login?follow=' + $this.data('blogid') + '&camefrom=' + currentPage;
  // if blog is marked as followed, unfollow it.
  }else{
    if ($this.hasClass('followed')) {
      // remove From favorites
      $.ajax({
        url: lbApp.rootPath + '/user/unfollow/' + $this.data('blogid'),
        type: "Get",
        success: function(){
          // replace all instances of share menu from "remove From Favorites" to "add to favorites"
          $("div[data-blogid='" + $this.data('blogid') + "']").each(function(){
            $(this).removeClass('followed');
          });

          // remove -1 from the favorites counter on the sidebar
          $counterBubble = $('tools main li .amount.favorites');
          $initialValue = parseInt($counterBubble.text());
          $newValue = $initialValue - 1;
          $counterBubble.text($newValue);

          // if we're in the blogger's page, add +1 to blogger's counter;
          if ( $('.followerCount').length > 0 ){
            $ivalue = parseInt($('.followerCount').text());
            $nvalue = $ivalue - 1 ;
            $('.followerCount').text($nvalue);
          };

          // refresh page if we're already in the following page
          if (lbApp.pageKind == 'following') {
            location.reload();
          };
        }
      });
    }else{
      // add to favorites
      $.ajax({
        url: lbApp.rootPath + '/user/follow/' + $this.data('blogid'),
        type: "Get",
        success: function(){
          // replace all instances of share menu from "remove From Favorites" to "add to favorites"
          $("div[data-blogid='" + $this.data('blogid') + "']").each(function(){
            $(this).addClass('followed');
          });

          // add +1 to the favorites counter on the sidebar
          $counterBubble = $('li > .amount.favorites');
          $initialValue = parseInt($counterBubble.text());
          $newValue = $initialValue + 1;
          $counterBubble.text($newValue);

          // if we're in the blogger's page, add +1 to blogger's counter;
          if ( $('.followerCount').length > 0 ) {
            $ivalue = parseInt($('.followerCount').text());
            $nvalue = $ivalue + 1;
            $('.followerCount').text($nvalue);
          };

          // refresh page if we're already in the following page
          if (lbApp.pageKind == 'following') {
            location.reload();
          };
        }
      });
    };
  }
});

});
