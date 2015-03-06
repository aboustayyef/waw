lbApp.veryFirstLoad= function(){

  // adjust sizes of DOM elements
    lbApp.resizeViewport();
  // momentum scrolling hack
  $('#momentumScrollingViewport').css('-webkit-overflow-scrolling: touch;');

  // if not mobile, use masonry to flow the posts
  if ($(window).width() > 430) {
    lbApp.flowPosts();
  }else{
    lbApp.mobileFlowPosts();
  }

  // more adjusting of DOM element sizes
  lbApp.fixViewportHeight();

  // show initial posts (and remove 'loading curtain')
  $('.post_wrapper').css('visibility','visible');
  lbApp.hideLoadingCurtain();

  // show lazy images
  lbApp.loadLazyImages();
};


lbApp.resizeViewport = function(){
  // this function is only used with cards
  // it serves to recalculate the viewport's width to center the posts
  if ($(window).width() > 430) {
    var columns = Math.floor((($(window).width() ))/320);

    $('div.posts').css('width',columns*320);

    // position website logo to be alligned with posts

    var $leftMargin = parseInt($('div.posts').css('margin-left'));
    var $logoMargin = $leftMargin - 50 + 10 ;
    if ($logoMargin > 20) {
     $('#logo').css('margin-left', $logoMargin + 'px');
    }else{
      $('#logo').css('margin-left', '20px');
    }
  }
};

lbApp.loadLazyImages = function(){
  $('img.lazy').each(function(){
    $(this).attr('src', $(this).data('original'));
    $(this).removeClass('lazy');
  });
};

lbApp.fixViewportHeight = function(){
  $winHeight = $(window).height();
  $('#momentumScrollingViewport').height($winHeight);
  //fix the grey area to stretch fully even if little content

  if ($('#content').height() < $winHeight) {
    $('#content').height($winHeight);
  }
};

lbApp.flowPosts = function(){

  // This function is only used with cards
  // it applies the Masonry effect to the initial
  // set of posts

  var $container = $('.posts');
  $container.masonry({
    // options
    itemSelector: 'div.post_wrapper',
    transitionDuration: 1, // no animation
  });
};

lbApp.mobileFlowPosts = function(){
  var $cardWidth = $(window).width() - 40;
  $('.cardImage').each(function(){
    $ratio = $(this).attr('height') / $(this).attr('width');
    $height = $cardWidth * $ratio;
    $(this).attr('width', $cardWidth).attr('height',$height);
  });
};

lbApp.showLoadingCurtain = function(fadein){
  // This function shows the "loading" window
  // after everything has loaded
  if (fadein == "true") {
    $('#loading').fadeIn("fast");
  }else{
    $('#loading').show();
  }
};

lbApp.hideLoadingCurtain = function(){
  // This function hides the "loading" window
  // after everything has loaded
  $('#loading').hide();
};

lbApp.showPostsLoadingIndicator = function(){
  $('.posts').after('<div id ="loadingMore"><i class="fa fa-cog fa-spin"></i> Loading More Posts</div>');
  $w = $(window).width();
  $a = $('#loadingMore').outerWidth();
  $center = ($w - $a)/2;
  $('#loadingMore').css('left', $center);
};

lbApp.hidePostsLoadingIndicator = function(){
  $('#loadingMore').remove();
};

lbApp.addMorePosts = function(){

  // The Ajax call to load more posts,

  lbApp.busy = true; // to avoid simultaneous calls
  $.ajax({
    url: lbApp.rootPath + '/ajax/GetMorePosts',
    type: "GET",
    success: function(data){
      $data = $(data);
      if ($('.posts').hasClass('cards')) {
        $container = $('.posts');
        if ($(window).width() > 430) {
          // do the normal masonry thing
          $container.append( $data ).masonry( 'appended', $data, true );
        }else{
          // flow mobile;
          var $cardWidth = $(window).width() - 40;
          $data.find('.cardImage').each(function(){
            console.log('working');
            $ratio = $(this).attr('height') / $(this).attr('width');
            $height = $cardWidth * $ratio;
            $(this).attr('width', $cardWidth).attr('height',$height);
          });
          $container.append( $data );
        }
      }
      $('.post_wrapper').css('visibility','visible');
      lbApp.currentPageNumber = lbApp.currentPageNumber + 1;
      lbApp.busy = false;
      ga('send','pageview', lbApp.currentPage + '/page-' + lbApp.currentPageNumber );
      lbApp.hidePostsLoadingIndicator();
      lbApp.loadLazyImages();
    }
  });
}

