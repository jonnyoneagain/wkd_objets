<!DOCTYPE html>
<html class="">
<head>
<title>wkd_objets</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />

<style type="text/css"> .js { visibility:hidden; } </style> 

<link rel="shortcut icon" href="disk-usage_ico.ico" />


<link rel="stylesheet" type="text/css" href="<?=parse_url($base_url, PHP_URL_PATH);?>application/css/wikid.css"/>
<link rel="stylesheet" type="text/css" href="<?=parse_url($base_url, PHP_URL_PATH);?>application/css/jquery.ui.all.css"/>

<?php 
if ($logged) { ?>
<!-- jQuery Image Gallery styles -->
<link rel="stylesheet" href="http://blueimp.github.com/jQuery-Image-Gallery/css/jquery.image-gallery.min.css">
<link rel="stylesheet" type="text/css" href="<?=parse_url($base_url, PHP_URL_PATH);?>application/css/jquery.fileupload-ui.css"/>

<?php } ?>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jquery-1.7.2.min.js"></script>


</head>
<body id="body_principal" class="filter nojs">
<div style="display: none" id="custom_style">
</div>
	<script type="text/javascript">
	// pour empêcher le flickering si on change de page dès l'entrée du site:
	// par exemple si on copie colle une url avec un hash du genre wikid_simple/#une_autre_page_que_la_page_d_accueil
		(function (B,C) {
			B[C] = B[C].replace(/\bnojs\b/,'js');
		}(document.getElementById('body_principal'),'className'));
	</script>
	<div id="partie_haute">
		<div id="partie_haute_wrapper" class="layout_vertical">
			<div id="social_wrapper">  
				<?=$social_nets;?>
			</div>
	
			<div id="header_cadre" class="wrapper_wikidable">  
				<div id="header" class="contenu_wikid"><?=$entete['contenu'];?></div>
				<input type="hidden" name="page_name" value="<?=$entete['nom'];?>"/>
			</div>
	
			<div id="sidemenu_cadre" class="wrapper_wikidable">
				 <div id="sidemenu" class="contenu_wikid"><?=$sidemenu['contenu'];?></div>
				 <input type="hidden" name="page_name" value="<?=$sidemenu['nom'];?>"/>
			</div>	
			<div id="menu_cadre" class="filter">
				<div id="menu" class="menu"><?=$menu;?>
				</div>
			</div>
		</div>
	</div>
	<div id="partie_milieu">
		<div id="partie_milieu_wrapper" class="layout_vertical">
			<div id="page_cadre" class="wrapper_wikidable">
				 <div id="page" class="contenu_wikid"><?=$page['contenu'];?></div>
				 <input type="hidden" name="page_name" value="<?=$page['nom'];?>"/>
			</div>
		</div>
	</div>
	<div id="partie_basse">
		<div id="partie_basse_wrapper" class="layout_vertical">
			<div id="footer_cadre" class="wrapper_wikidable">
				 <div id="footer" class="contenu_wikid"><?=$pied_page['contenu'];?></div>
				 <input type="hidden" name="page_name" value="<?=$pied_page['nom'];?>"/>
			</div>	
		</div>
	</div>		
</body>

<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/core/crockford_object_create_function.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/core/json2.js"></script>
<script	type="text/javascript"> 
	var MY_GLOBALS = {
		BASE_DIRECTORY: '<?=parse_url($base_url, PHP_URL_PATH);?>',
		BASE_URL: '<?=$base_url;?>',
		LOGIN_FLAG: '<?=$logged;?>'
	};
	var WIKIDGLOBALS = Object.create(MY_GLOBALS); //Test
</script>


<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jquery-ui-1.8.21.custom.min.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jquery.color.js"></script>
<!--<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jquery.easing.1.3.js"></script>-->
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jquery.approach.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jquery.hashchange.min.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/spin.min.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jquery.spin.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jquery.nestedAccordion.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jquery.scrollTo-1.4.3.1-min.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jquery.localscroll-1.2.7-min.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jquery.cycle.all.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jQueryRotateCompressed.2.2.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/custom_plugins/jquery.wikid_img_overlay.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/custom_plugins/jquery.wikid_collection_objet_navigation.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/custom_plugins/jquery.wikid_login_form_dialog.js"></script>


<?php 
if ($logged) { ?>


<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/third_party/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/third_party/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jquery.jstree.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/jquery.form.js"></script>



<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="http://blueimp.github.com/JavaScript-Load-Image/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<!--<script src="http://blueimp.github.com/JavaScript-Canvas-to-Blob/canvas-to-blob.min.js"></script>
<!-- jQuery Image Gallery -->
<script src="http://blueimp.github.com/jQuery-Image-Gallery/js/jquery.image-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/fileupload_plugin/jquery.iframe-transport.js" ></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/fileupload_plugin/jquery.fileupload.js" ></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/fileupload_plugin/jquery.fileupload-ip.js" ></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/fileupload_plugin/jquery.fileupload-ui.js" ></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/fileupload_plugin/jquery.fileupload-jui.js" ></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/fileupload_plugin/locale.js" ></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/custom_plugins/jquery.jstree_menu_wikid3.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/custom_plugins/jquery.upload_menu_wikid0.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/custom_plugins/jquery.wikid_inline_edit_mode_wrapper.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/custom_plugins/jquery.sommaires_collections_jstree.js"></script>


<?php } ?>

<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/browser-update-org.js"></script>
<script type="text/javascript" src="<?=parse_url($base_url, PHP_URL_PATH);?>application/views/js/main2.js"></script>
<script type="text/javascript">
	// pour empêcher le flickering si on change de page dès l'entrée du site:
	// par exemple si on copie colle une url avec un hash du genre wikid_simple/#une_autre_page_que_la_page_d_accueil
		(function (B,C) {
			B[C] = B[C].replace(/\bjs\b/,'');
		}(document.getElementById('body_principal'),'className'));
	</script>


</html>
