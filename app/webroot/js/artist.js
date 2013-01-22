$(document).ready(function() {
  $('div.expandable').expander({
    slicePoint: 300,
    expandPrefix: ' ',
    expandText: '» Lire la suite',
    collapseTimer: 5000,
    userCollapseText: '« Lire moins',
    preserveWords: true
  });
	
	$( ".tabs" ).tabs();
  
});