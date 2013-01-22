$(document).ready(function(){
  $('#search').bind('focus', function(){
    if(this.value == 'rechercher...')
      this.value = '';
  });
	
  $('#search').bind('blur', function(){
    if(this.value == '')
      this.value = 'rechercher...';
  });
	
  $('#profil').mouseout(function(){
    $('#profil_menu').hide();
  }).mouseover(function(){
    $('#profil_menu').show();
  });
	
	$(function() {
   $('.tooltip').tipsy({gravity: $.fn.tipsy.autoNS, html: true});
 	});
});