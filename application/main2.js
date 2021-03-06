$(document).ready(function() {

	$(document).wikid_login_form_dialog();
	
	// desactive les liens et utilise ajax pour afficher les pages
	$('#page_cadre').wikid_objets_navigation({
	
		on_display_ajax_callback: function () {
			//canvas_back.images_build_canvas_on_mouse_over(this);
		}	
	});
	var plugin_navigation_wikid = $('#page_cadre').data('wikid_objets_navigation'); 
	// si besoin d'appeler des methodes de cet objet depuis le $(document).ready(function()
	//plugin_navigation_wikid.display_url_base_hash();
	$('#menu').find('.submenu_item > a')
		.approach({
			"fontSize": "12px"
			, "color": "#CC3300"
			}, 200);
	// LOGIN
	if (WIKIDGLOBALS.LOGIN_FLAG) {
		// ouvre un jstree pour modifier le menu
		$('#menu_cadre').jstree_menu_wikid({
			on_refresh_callback: function () {
				//$(this).menu_wikid_anime();
			}
		});
		
		// menu upload
		
		$(document).wikid_upload_menu();
		
		// edit mode avec ckeditor3
		$('.wrapper_wikidable').wikid_inline_edit_mode_wrapper({
	
			on_update_callback: function (page_nom) { 
				// arguments fournis depuis l'intérieur de l'objet grâce à function.apply()'
				plugin_navigation_wikid.display_ajax(page_nom);
				console.info(page_nom);
			}
		});
		
		// jstree local pour modifier les sommaire des collections d'objets'
		$('#page_cadre').jstree_sommaires_collections_objets();
	}
	
//	var win = $(window);

//	win.resize(function() {

//	var win_w = win.width(),
//	win_h = win.height(),
//	$bg    = $("#bg");
//	$bg2    = $("#bg2");
//	// Load narrowest background image based on 
//	// viewport width, but never load anything narrower 
//	// that what's already loaded if anything.
//	var available = [
//	1024, 1280, 1366,
//	1400, 1680, 1920,
//	2560, 3840, 4860
//	];

//	var current = $bg.attr('src').match(/([0-9]+)/) ? RegExp.$1 : null;

//	if (!current || ((current < win_w) && (current < available[available.length - 1]))) {

//	var chosen = available[available.length - 1];

//	for (var i=0; i<available.length; i++) {
//	if (available[i] >= win_w) {
//	  chosen = available[i];
//	  break;
//	}
//	}

//	// Set the new image
//	$bg.attr('src', WIKIDGLOBALS.BASE_DIRECTORY + 'upload/img/background/' + chosen + 'highway.jpg');
//	$bg2.attr('src', WIKIDGLOBALS.BASE_DIRECTORY + 'upload/img/background/' + chosen + 'highway_photocop.jpg');
//	// for testing...
//	// console.log('Chosen background: ' + chosen);

//	}

//	// Determine whether width or height should be 100%
//	if ((win_w / win_h) < ($bg.width() / $bg.height())) {
//	$bg.css({height: '100%', width: 'auto'});
//	$bg2.css({height: '100%', width: 'auto'});
//	} else {
//	$bg.css({width: '100%', height: 'auto'});
//	$bg2.css({width: '100%', height: 'auto'});
//	}
//	
//	$bg2.fadeTo(2500, 0);

//	}).resize();
//	
////	var targets = $(".title, .social");
//	var targets = $('#bg2');
//	if($(window).scrollTop() > 10){
//		//targets.hide();
//	}
//	$(window).scroll(function(){
//		var pos = $(window).scrollTop();
//		var seuil = $(window).height()*0.9;
//		var opacity = ($(window).scrollTop() - seuil) / $(window).height() *2.5;
//		
//	if (pos > seuil) {
//	    targets.stop(true, true).fadeTo("slow", opacity);
//	} else {
//	    targets.stop(true, true).fadeTo("fast", 1 / opacity);
//	}
//	});
});

