$(document).ready(function() {
  $('div.expandable').expander({
    slicePoint: 310,
    expandPrefix: ' ',
    expandText: '» Lire la suite',
    userCollapseText: '« Lire moins',
    preserveWords: true
  });
  
  $( ".tabs" ).tabs();
	
	$("a[rel=photos]").fancybox({
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'titlePosition' 	: 'over',
		'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
			return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
		}
	});

});