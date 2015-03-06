$('document').ready(function(){

  // like a post
  $(document).on('click', 'div.sharingButton.likeit', function(){

    event.stopPropagation();

    var $this = $(this); // to persist variable through closure;

    // if user is not logged in, forward to login page

    if (lbApp.signedIn == false){

      var currentPage = window.location.pathname;

      window.location = lbApp.rootPath + '/login?like=' + $this.data('postid') + '&camefrom=' + currentPage;

    } else {  // user is signed in

      if ($this.hasClass('liked')) {

        $.ajax({

          url: lbApp.rootPath + '/user/unlike/' + $(this).data('postid'),

          type: "Get",

          success: function(){

            // replace share menu from "add to saved" to remove From saved

            $("div[data-postid='" + $this.data('postid') + "']").removeClass('liked');

            console.log('Successfully removed ' + $this.data('postid') + ' from reading list');

            // refresh page if we're already in the liked page

            if (lbApp.pageKind == 'liked') {

              location.reload();

            };
          }

        });

      } else { // this has no class 'liked', do the opposite

        $.ajax({

          url: lbApp.rootPath + '/user/like/' + $(this).data('postid'),

          type: "Get",

          success: function(){

            // replace share menu from "add to saved" to remove From saved

            $("div[data-postid='" + $this.data('postid') + "']").addClass('liked');

            console.log('Successfully added ' + $this.data('postid') + ' to reading list');

          }

        });

      }
    } // / lbApp.signed in conditional
  });
});
