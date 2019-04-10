jQuery(document).ready(function($) {
	var numwidgets = $('#homepage-widgets div.widget').length;
	$('#homepage-widgets').addClass('cols-'+numwidgets);
	if(window.width > 480){
	   $('#homepage-widgets .widget,#homepage-widgets .widget .widget-wrap').equalHeights();
	}
});