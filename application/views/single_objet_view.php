<div id="objet_<?=$objets_nom_page_sommaire;?>" class="objet">
	
	<input type="hidden" value="<?=$objet_data['initial_index'];?>" name="objet_index_initial">
	<input type="hidden" value="<?=$objet_data['titre'];?>" name="objet_nom">
	<input type="hidden" value="<?=$objet_data['url_index'];?>" name="url_index">
	<h1 id="objet_<?=$objet_data['url_index'];?>"class="objet_titre"><?=$objet_data['titre'];?></h1>
	<p id="objet_contenu_ckeditable" class="objet_contenu"><?=$objet_data['contenu'];?></p>
	<?php 
		if (!empty($objet_data['contenu'])) { ?>
	
	<div class="social_nets">
	<iframe src="//www.facebook.com/plugins/like.php?href=<?php echo base_url().'index.php/sync/show/'.$objet_data['page_nom'].'/'.$objet_data['url_index'];?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe>
	</div>
	<?php } ?>
	<?php 
		if ($logged and !empty($objet_data['contenu'])) { ?>
	<div class="buttonset">
		<button id="bouton_edit_objet" value="Edit Object">Modifier fiche : <?=$objet_data['titre'];?></button>
	</div>
	<?php } ?>
</div>
