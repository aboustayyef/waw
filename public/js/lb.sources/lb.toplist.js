
lbApp.updateTopFive = function(data){

  // save current top list into a variable
  var $topListBox = $('.toplist');

  // save present attributes
  var topListBoxStyle = $topListBox.attr('style');

  // hide it
  $topListBox.css('opacity',0);

  // replace it
  $topListBox.replaceWith(data);

  // restore attributes
  $('.toplist').attr('style' , topListBoxStyle) ;

  // show it
  $topListBox.css('opacity',1);

  // add it to masonry
  $('.posts').masonry('prepended', $('.toplist') );

  lbApp.loadLazyImages();
}

lbApp.loadNewTopFive = function(hours){

	//lbApp.hideCurrentTopFive();
	$.ajax({
		url: lbApp.rootPath + '/ajax/GetTop5',
		type: "GET",
		data: {hours: hours},
		success: function(data){
			lbApp.updateTopFive(data);
			//lbApp.showCurrentTopFive();
		},
	})
}

$(document).on('change', '#topListScoper', function(){
	lbApp.loadNewTopFive($(this).val());
})
