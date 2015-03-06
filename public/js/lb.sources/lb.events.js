$(document).ready(function(){
  // initialize dynamic links behavior
  $('.dynamicLink').on('click', function(){
    console.log('clicked Dynamic link');
    $destination = $(this).data('destination');
    lbApp.clearMenus();
    lbApp.showLoadingCurtain("true");
    window.location.href = $destination ;
  });
});

$( window ).on('resize', function(){
  if ($('.posts').hasClass('cards')) {
      lbApp.resizeViewport();
      lbApp.fixViewportHeight();
      lbApp.checkIfMorePostsNeedToBeAdded();
  };
});

$('#momentumScrollingViewport').on('scroll', function(){
  lbApp.checkIfMorePostsNeedToBeAdded();
});

lbApp.checkIfMorePostsNeedToBeAdded = function(){
  if (!lbApp.busy && !lbApp.reachedEndOfPosts) {
    $heightOfContent = $('#content').height(); // the height of the total posts content
    $positionOfContentTop = $('#content').position().top; // a negative number indicating how far content has scrolled
    $distanceToBottom = $heightOfContent + $positionOfContentTop;
    if ($distanceToBottom < 1500) { // add more posts whenever there's a thousand pixels to bottom
        lbApp.showPostsLoadingIndicator();
        lbApp.addMorePosts();
    };
  };
}
