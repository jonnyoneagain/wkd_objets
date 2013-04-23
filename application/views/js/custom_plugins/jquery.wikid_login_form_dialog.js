(function ($) {
	var Wikid_login_form = {
	
		init: function (options, elem) {
			'use strict';
				this.options = $.extend({}, this.options, options);
				this.elem = elem;
				this.$elem = $(elem);
				this._build_form()._init_references();
				this._init_dialog()._add_click_show_login_button();			
				return this;
		},
	
		options: {
	
		},
	
		_build_form: function () {	
			var html_string = [
	    			'<button id="login_button"></button>',
	    			'<div id="login_form_dialog" class="login_form" title="Login">',
	    			'<p class="validateTips">All form fields are required.</p>',
	    			'<form>',
	    			'<fieldset>',
	    			'<input type="text" name="name" id="name" title="Name" placeholder="Name" class="text ui-widget-content ui-corner-all" />',
	    			'<input type="email" name="email" id="email" title="Email" placeholder="Email" value="" class="text ui-widget-content ui-corner-all" />',
	    			'<input type="password" name="password" id="password" title="Password" placeholder="Password" value="" class="text ui-widget-content ui-corner-all" />',
	    			'</fieldset>',
	    			'</form>',
	    			'</div>',
				].join('');
			this.$elem_login_wrapper = $('<div>', {
				id: 'login_wrapper',
				style: 'font-size:24px'
			});
			
 
			this.$elem_login_wrapper.html(html_string);
			$('body').append(this.$elem_login_wrapper); //on peut aussi attacher au body
			return this;
		},
		_init_references: function () {
			this.$elem_form_dialog = $('#login_form_dialog', this.elem_login_wrapper);
			this.$elem_name = $('#name', this.$elem_form_dialog);
			this.$elem_email = $('#email', this.$elem_form_dialog);
			this.$elem_password = $('#password', this.$elem_form_dialog);
			this.$elem_allFields = $( [] ).add( this.$elem_name ).add( this.$elem_email ).add( this.$elem_password );
			this.$elem_tips = $('.validateTips',this.$elem_form_dialog);
			//console.info(this.$elem_form_dialog);
			return this;
		},

		updateTips: function (t) {
			var that = this;
			this.$elem_tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				that.$elem_tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		},

		checkLength: function ( o, n, min, max ) {
				if ( o.val().length > max || o.val().length < min ) {
					o.addClass( "ui-state-error" );
					this.updateTips( "Length of " + n + " must be between " +
						min + " and " + max + "." );
					return false;
				} else {
					return true;
				}
		},

		checkRegexp: function ( o, regexp, n ) {
				if ( !( regexp.test( o.val() ) ) ) {
					o.addClass( "ui-state-error" );
					this.updateTips( n );
					return false;
				} else {
					return true;
				}
		},
	
		_add_click_show_login_button: function () {
			var that = this;
			this.$elem_show_login_button = $("#login_button", this.$elem_login_wrapper);
			this.$elem_show_login_button
				.button({
					icons: {
						primary: "ui-icon-locked"
					},
					text: false
				})
				.click(function() {
					that.$elem_form_dialog.dialog( "open" );
				});
			this.$elem_show_login_button
				.button('widget').css('background', 'rgba( 255, 255, 255, 0.3)');
				
			return this;
		},
		
		_init_dialog: function () {
			var that = this;
			this.$elem_form_dialog.dialog({
				autoOpen: false,
				height: 369.5,
				width: 508.5,
				modal: true,
				buttons: {
					"Login": function () {
						var bValid = true;
						that.$elem_allFields.removeClass( "ui-state-error" );

						bValid = bValid && that.checkLength( that.$elem_name, "username", 3, 16 );
						bValid = bValid && that.checkLength( that.$elem_email, "email", 6, 80 );
						bValid = bValid && that.checkLength(that.$elem_password, "password", 5, 16 );

						bValid = bValid && that.checkRegexp( that.$elem_name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
						// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
						var email_regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
						bValid = bValid && that.checkRegexp( that.$elem_email, email_regex, "eg. ui@jquery.com" );
						bValid = bValid && that.checkRegexp( that.$elem_password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );

						if ( bValid ) {
							//console.info('login OK');
							$.ajax({
								url: WIKIDGLOBALS.BASE_DIRECTORY + "index.php/users/login_session_ajax",
								type: "POST",
								data: {
									login: that.$elem_name.val(), 
									email: that.$elem_email.val(),
									password: that.$elem_password.val()				
								},
								dataType: "json",
							  	success: function(ans) {			  	
									if (!ans.success) {
										that.updateTips('access denied');
										that.$elem_form_dialog.parent().effect("shake", {times: 2}, 80);
									}
									if(ans.success) {		
							  			that.updateTips('access granted');
							  			that.$elem_form_dialog.parent().effect("bounce", {times: 1}, 80, function () {
							  				location.reload();
							  			});
							  			//window.setTimeout('location.reload()', 20);//recharge la page
							  		}						  		
							 	}
							});
							//$( this ).dialog( "close" ); //pas la peine de toujours fermer le dialogue
						}
					},
					Cancel: function () {
						$( this ).dialog( "close" );
					}
				},
				open: function () {
					$(this).dialog('widget').show('scale');
				},
				close: function () {
					that.$elem_allFields.val( "" ).removeClass( "ui-state-error" );
					$(this).dialog('widget').hide('scale');
				},
				create: function () {
					$(this).dialog('widget').on("keyup", function (e) {
						if (e.keyCode === 13) {
						$('.ui-button', $(this)).first().click();
						}
					});
				}
			});
		return this;
		}
	
	};

	$.fn.wikid_login_form_dialog = function (options) {
		if (this.length) {
			return this.each(function () {
				var my_login_form_dialog = Object.create(Wikid_login_form);
				my_login_form_dialog.init(options, this);
				$(this).data('wikid_login_form_dialog', my_login_form_dialog);//ou encore $.data(this, 'key', var)
			});
		}
	
	};
}(jQuery))
