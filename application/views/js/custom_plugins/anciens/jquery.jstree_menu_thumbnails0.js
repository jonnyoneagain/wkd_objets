//l'objet créé lors d'un appel du plugin jstree_menu_wikid0 sur un wrapper/cadre du conteneur menu'
(function ($) {
	'use strict';
	
	var Jstree_menu_wikid_prototype = {
		init: function (options, elem) {
			'use strict';
			// Mix in the passed in options with the default options
			this.options = $.extend({}, this.options, options);
			// Save the element reference, both as a jQuery reference and a normal reference
			this.elem = elem;
			this.$elem = $(elem);
			this.$elem_menu = $('#menu', elem);
			//build the initial DOM structure
			this._build_dialog();
			this.eventify_menu();
			//return this to chain/use the bridge with less code
			return this;
		},

		options: {
			on_refresh_callback : function () {}
		},
		//options par défault à remplir"
		_build_dialog: function () {
			var that = this;
			
			this.$menu_tree = $('<div>', {
				id: 'menu_tree',
				style: 'display:none'
			});
			this.$menu_tree_dialog = $('<div>', {
				id: 'menu_tree_dialog'
			})
				.append(this.$menu_tree)
				.appendTo('body')
				.dialog({
					autoOpen: false,
					height: $(window).height(),
					width: 750,
					modal: false,
					position: ['left', 'top'],
					create: function (event, ui) {
						$(this).siblings('.ui-dialog-buttonpane').hide();
						that._init_jstree();
					},
					open: function (event, ui) {
						
					},
					buttons: {
						'Ok Menu': function () {
							that.valide_jstree();		
						}
					}
				});
				
			return this;
		},

		_init_jstree: function () {
			console.info('jstree init');
			this.$menu_tree.bind("move_node.jstree", function (e, data) {
				//console.info(data.rslt.o);
				//var getjson_menu = $('#menu_tree').jstree("get_json", -1);
				var getjson_menu = $(this).jstree("get_json", -1, [], ['href']);
				console.info(getjson_menu);
			}).bind("loaded.jstree", function (e, data) {
				$(this)
					.jstree("open_all", -1, true)
					.toggle('slide', {direction: 'down'}, 'fast');
				//console.info(this);
				// cree le formulaire
				var form_content_html = [
					'<fieldset style="display:block">',
					'<legend>Node details</legend>',									
					'<p>',					
					'<label for="link">lien vers page</label>',
		    			'<input type="text" name="link" id="link" value="" size="25" class="required text ui-widget-content ui-corner-all" />',
		    			'</p>',
		    			'<p>',	
		    			'<label for="titre">Titre du lien</label>',
		    			'<input type="text" name="titre" id="titre" value="" size="25" class="required text ui-widget-content ui-corner-all" />',
		    			'</p>',
		    			'<p>',
		    			'<label for="context">Contexte</label>',
		    			'<input type="text" name="context" id="context" value="" class="text ui-widget-content ui-corner-all" />',
		    			'</p>',
		    			'<p>',
		    			'<input type="checkbox" id="check_thumbnail"/> <label for="check_thumbnail">Thumbnail</label>',
		    			//'<label for="node_thumbnail">thumbnail</label>',
		    			'<input type="image" name="node_thumbnail" id="node_thumbnail" value="" class="ui-widget-content ui-corner-all" />',
		    			'</p>',
		    			'<p><input class="submit" type="submit" value="Submit"/></p>',
		    			'</fieldset>',
		    			
					].join('');
				var $form = $('<form>',  {
					id: 'jstree_form',
					method: 'get',
					action: ''
				});
				$form.append(form_content_html);
				//$form.appendTo($(this));
				//$form.appendTo($('#menu_tree_dialog'));
				$('#menu_tree_dialog')
					.append($form)
					.siblings('.ui-dialog-buttonpane').show('slide');
				//appel ajax pour afficher la liste des thumbnails
				$.ajax({
					url: WIKIDGLOBALS.BASE_DIRECTORY + "index.php/wkd_menu/get_thumbnails/",
					type: "GET",
					dataType: "json",
					context: this,
					success : function (ans) {
						if (ans) {
							var $thumbnails_wrapper = $('<div>',  {
								id: 'thumbnails_wrapper'
							})
								.append(ans)
								.appendTo($('#menu_tree_dialog'))
								.hide();
							$('#thumbnails_list_mosaic', '#menu_tree_dialog').selectable({
								selected: function (event, ui) {
									var $thumbnail_url = $(event.target)
										.children('.ui-selected')
										.first()
										.find('img').attr('src');
									$('#node_thumbnail').attr('src', $thumbnail_url);
								} 
							});
						}	
					}
				});
				
				$form.find('.submit').button();
				
				var $check_thumbnail = $('#check_thumbnail', $form);
				var $node_thumbnail = $('#node_thumbnail', $form);
				
				$check_thumbnail
					//.button()
					.change(function () {
						var $thumbnail_wrapper = $('#thumbnails_wrapper', '#menu_tree_dialog');
						if ($(this).attr('checked')) {
							if ($thumbnail_wrapper.is(':hidden')) {
								$thumbnail_wrapper.show('slide', {direction: 'up'});
								$node_thumbnail.fadeIn('slow');
							}
						}
						else {
							if ($thumbnail_wrapper.is(':visible')) {
								$thumbnail_wrapper.hide('slide', {direction: 'up'});
								$node_thumbnail.fadeOut('slow');
							}
						}
				});
				
				
				
				$form.validate({
					submitHandler: function (form) {
						var $inputs = $('input', this.currentForm);
						var $menu_tree = $('#menu_tree');
						var $selected_node = $menu_tree.jstree('get_selected').first();
						var $selected_link = $('a', $selected_node).first();
						console.info($selected_link);
						console.info(this);
						console.info($inputs);
						$selected_link.attr('href', '#' + $inputs.eq(0).val());
						//$selected_link.text($inputs.eq(1).val());
						$menu_tree.jstree('rename_node', $selected_node, $inputs.eq(1).val());
						$selected_link.attr('title', $inputs.eq(2).val());
						if (!$('#check_thumbnail', '#menu_tree_dialog').attr('checked')) {
							$selected_node.css({backgroundImage: 'none'});
							$inputs.eq(4).removeAttr('src');
							//$selected_node.removeAttr('style');
						}
						else {
							$selected_node.css('background-image', 'url(' + $inputs.eq(4).attr('src') + ')');
						}
					}
				});
			})
			.bind("select_node.jstree", function (event, data) {
				//var $form = $('#jstree_form', this);
				var $inputs = $('#jstree_form :input, #menu_tree_dialog');

				//$form.find()
				console.info(this);
				console.info(data.rslt.obj);
				var $selected_link = $(data.rslt.obj.context).attr('href');
				$inputs.eq(1).val($selected_link.replace('#', ''));
				var $selected_titre = $(data.rslt.obj.context).text();
				$inputs.eq(2).val($.trim($selected_titre));
				var $selected_context = $(data.rslt.obj.context).attr('title');
				$inputs.eq(3).val($selected_context);
				var $selected_url_thumbnail = $(data.rslt.obj.context).parent('li').css('background-image');
				if (!$selected_url_thumbnail || $selected_url_thumbnail === 'none') {
					$inputs.eq(4)
						.prop("checked", false)
						.trigger('change');
					$inputs.eq(5).fadeOut('slow', function () {
						$(this).removeAttr('src');	
					});
						
				}
				else {
					$inputs.eq(4)
						.prop("checked", true)
						.trigger('change');
					var patt = /\"|\'|\)|\(|url/g;	
					$inputs.eq(5).attr('src', $selected_url_thumbnail.replace(patt,''));
				}
				
			})
			.jstree({
				"ui": {
					"select_limit": -1,
					"initially_select": []
				},
				"core": {},
				"themes": {
					"theme": "default",
					"dots": false,
					"icons": true
				},
				"json_data": {
					"ajax": {
						"url": WIKIDGLOBALS.BASE_DIRECTORY + "index.php/wkd_menu/init_jstree/"
					}
				},
				"contextmenu": {
					"items": {
						/*
						"info": {
							"label": "infos",
							"action": function (obj) { //this.rename(obj);
								console.info(this._get_parent(obj));
							}
						},
						*/
						"create": {
							"label": "Creer un noeud",
							"action": function (obj) {
								this.create(obj, "after");
							}
						},
						
						"lien vers": {
							label: "Modifier le lien",
							action: function (obj) {
								var that = this;
								var $lien = obj.children('a');
								this.close_node(obj);
								var obj_closure = obj;
								
								var $input = $('<input>', {
									value: $lien.attr('href').split('#')[1]
								});
								
								var valide_handler = function () {
									var $trimmed = $.trim($input.val());
										$lien.attr('href', '#' + $trimmed);
										$input.hide('scale', function () {
											$(this).autocomplete('destroy').remove();
											that.open_node(obj);
										});
								};
								
								$input.autocomplete({
									source: WIKIDGLOBALS.BASE_DIRECTORY + "index.php/wkd_menu/get_pages_list/",
									position: { 
										my: 'left top',
										at: 'right top'
									},
									change: valide_handler,
									create: function () {
										$(this).autocomplete('search', ['']);
									}
								})
								.appendTo(obj)
								.keypress(function (event) {
									if (event.which === 13) {
										valide_handler();
									}
								});	
							}
						}
					}
				},
				"crrm": {
					"move": {
						"check_move": function (move_object) {
							var p = this._get_parent(move_object.cr);
							var pp = move_object.cr;
							var child = this._get_children(move_object.o);
							//console.info(child.length);
							if (p === -1 && child.length === 0) {return true};
							if (pp === -1) {return true};
						}
					}
				},
				"types": {
					"valid_children": [ "default" ],
					"types": {
					// the default type
						"default" : {
							"max_children"	: -1,
							"max_depth"	: -1,
							"valid_children": "all",
							"icon" : {
								"image" : "http://static.jstree.com/v.1.0rc/_docs/_drive.png"
							},
							// Bound functions - you can bind any other function here (using boolean or function)
							//"select_node"	: true,
							//"open_node"	: true,
							//"close_node"	: true,
							"create_node": function (node) {
								console.info('initnode');
								console.info(node);
								return true;
							},
							//"delete_node"	: true
						}
					}
				},

				"plugins": ["themes", "json_data", "ui", "crrm", "dnd", "contextmenu", "types"]
			});
		},
		
		valide_jstree: function () {
			var getjsons = this.$menu_tree.jstree("get_json", -1, ['style'], ['href', 'title']); 
				$.ajax({
					url: WIKIDGLOBALS.BASE_DIRECTORY + "index.php/wkd_menu/save_menu/",
					type: "POST",
					data: {
						menu: getjsons,
						ajax_enabled: 1
					},
					dataType: "json",
					context: this,
					success : function (ans) {
						if(ans.success) {
							//this.refresh_menu_ajax();
							location.reload();
						}		
					}	
				});
			this.$menu_tree.toggle("scale", {}, "slow", function () {
				$(this).jstree("destroy");
				$('#jstree_form').remove();
			});
		},
	
		eventify_menu: function () { // ajouter le hover pour modifier l'apparence du pointeur et indiquer une action possible'
			this.$elem.on('dblclick', $.proxy(this.handler_dblclick_menu, this));
			return this;
		},	
		
		handler_dblclick_menu: function () {
			$('#menu_tree_dialog').dialog('open'); // on a perdu la reference this.menu_tree_dialog !!?
		},
		
		purge: function (d) {
			var a = d.attributes, i, l, n;
			    if (a) {
				for (i = a.length - 1; i >= 0; i -= 1) {
				    n = a[i].name;
				    if (typeof d[n] === 'function') {
					d[n] = null;
				    }
				}
			    }
			    a = d.childNodes;
			    if (a) {
				l = a.length;
				for (i = 0; i < l; i += 1) {
				    this.purge(d.childNodes[i]);
				}
			    }
		},

		refresh_menu_ajax: function () {
			//var menu_elem = document.getElementById('menu');
			//this.purge(menu_elem);
			//menu_elem.innerHTML = '';// parce que empty ou remove ça déconnait
			
			$.ajax({
					url: WIKIDGLOBALS.BASE_DIRECTORY + "index.php/wkd/refresh_menu_ajax/",
					type: "GET",
					context: this,
					success : function (ans) {
						if (ans.success) {
							$('#menu').html(ans.menu);
							//menu_elem.innerHTML = ans.menu;
							//html ou empty.append les deux semblent fonctionner
							this.options.on_refresh_callback();
						}		
					}	
				});
		}
	}; //fin du literal object
	
	$.fn.jstree_menu_wikid0 = function (options) {
		if (this.length) {
			return this.each(function () {
				// Create a new object via the Prototypal Object.create
				var jstree_menu_wikid = Object.create(Jstree_menu_wikid_prototype);
				// Run the initialization function
				jstree_menu_wikid.init(options, this); // `this` refers to the element	 
				// Save the instance of the object in the element's data store
				$.data(this, 'jstree_menu_wikid0', jstree_menu_wikid);
			});
		}
		
	};
}(jQuery));
