$(document).ready(function() {
  console.log('trying to load fonts');
  WebFontConfig = {
    google: { families: [ 'Montserrat', 'Droid Arabic Naskh' ] },

    // when the fonts download, beging masonry layout..
    fontactive: function(fontFamily, fontDescription) {
      console.log('WebFonts have loaded');
      lbApp.veryFirstLoad();
    },
    inactive: function() {
      console.log('WebFonts have Failed to load, need to move on with normal fonts');
      lbApp.veryFirstLoad();
    }
  };
  (function() {
    var wf = document.createElement('script');
    wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
      '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
    wf.type = 'text/javascript';
    wf.async = 'true';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(wf, s);
  })();

});
