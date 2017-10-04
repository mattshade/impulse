jQuery(document).ready(function($) {

	//ajax spinner using spin.js
	if ( typeof Spinner !== 'undefined' && typeof modal_login_script !== 'undefined' ) {
		var spinner_color = ( modal_login_script.ajax_spinner_color !== '' ) ? modal_login_script.ajax_spinner_color : '#fff';
		var opts = {
			lines: 10, // The number of lines to draw
			length: 7, // The length of each line
			width: 4, // The line thickness
			radius: 10, // The radius of the inner circle
			corners: 1, // Corner roundness (0..1)
			rotate: 0, // The rotation offset
			color: spinner_color, // #rgb or #rrggbb
			speed: 1, // Rounds per second
			trail: 60, // Afterglow percentage
			shadow: false, // Whether to render a shadow
			hwaccel: false, // Whether to use hardware acceleration
			className: 'spinner', // The CSS class to assign to the spinner
			zIndex: 2e9, // The z-index (defaults to 2000000000)
			top: 25, // Top position relative to parent in px
			left: 25 // Left position relative to parent in px
		};
		var target = document.getElementById( 'paml-spinner' );
		var spinner = new Spinner(opts).spin(target);
	}

	//Close modal
	$( 'a.ml-close-btn' ).click( function(e){
		e.preventDefault();
		$( '.ml-modal ').trigger('click');
	} );


	// Display our different form fields when buttons are clicked
	$('.modal-login-content:not(:first)').hide();
	$('.modal-login-nav').click(function(e) {

		// Remove any messages that currently exist.
		$('.modal-login-content > p.message').remove();

		// Get the link set in the href attribute for the currently clicked element.
		var form_field = $(this).attr('href');

		$('.modal-login-content').hide();
		$('.section-container ' + form_field).fadeIn(700);

		e.preventDefault();

		if(form_field === '#login') {
			$(this).parent().fadeOut().removeClass().addClass('hide-login');
		} else {
			$('a[href="#login"]').parent().removeClass().addClass('inline').fadeIn();
		}
	});

	// Run our login ajax
	$('#modal-login #form').on('submit', function(e) {

		// Stop the form from submitting so we can use ajax.
		e.preventDefault();

		// Check what form is currently being submitted so we can return the right values for the ajax request.
		var form_id = $(this).parent().attr('id');

		// Remove any messages that currently exist.
		$('.modal-login-content > p.message').remove();

		// Check if we are trying to login. If so, process all the needed form fields and return a faild or success message.
		if ( form_id === 'login' ) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: modal_login_script.ajax,
				data: {
					'action'     : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
					'username'   : $('#form #login_user').val(),
					'password'   : $('#form #login_pass').val(),
					'rememberme' : ($('#form #rememberme').is(':checked'))?"TRUE":"FALSE",
					'login'      : $('#form input[name="login"]').val(),
					'security'   : $('#form #security').val()
				},
				beforeSend : function(){
					$('.ml-modal' ).addClass( 'is-active' );
				},
				success: function(results) {

					// Check the returned data message. If we logged in successfully, then let our users know and remove the modal window.
					if(results.loggedin === true) {
						$('.modal-login-content > h2').after('<p class="message success"></p>');
						$('.modal-login-content > p.message').text(results.message).show();

						$('#overlay, .login-popup').delay(5000).fadeOut('300m', function() {
							$('#overlay').remove();
						});
						window.location.href = updateQueryStringParameter( modal_login_script.redirecturl, 'nocache', ( new Date() ).getTime() );
					} else {
						$('.modal-login-content > h2').after('<p class="message error"></p>');
						$('.modal-login-content > p.message').text(results.message).show();
					}
				},
				complete : function(){
					$('.ml-modal' ).removeClass( 'is-active' );
				}
			});
		} else if ( form_id === 'register' ) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: modal_login_script.ajax,
				data: {
					'action'   : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
					'firstname' : ($('#form #reg_firstname').length)? $('#form #reg_firstname').val() : '',
					'lastname'  : ($('#form #reg_lastname').length)? $('#form #reg_lastname').val() : '',
					'username'  : $('#form #reg_user').val(),
					'email'     : $('#form #reg_email').val(),
					'register'  : $('#form input[name="register"]').val(),
					'security'  : $('#form #security').val(),
					'password'  : $('#form #reg_password').val(),
					'cpassword' : $('#form #reg_cpassword').val(),
					'recaptcha' : ($('#g-recaptcha-response').length)? $('#g-recaptcha-response').val() : ''
				},
				beforeSend : function(){
					$('.ml-modal' ).addClass( 'is-active' );
				},
				success: function(results) {
					if(results.registerd === true) {
						$('.modal-login-content > h2').after('<p class="message success"></p>');
						$('.modal-login-content > p.message').text(results.message).show();
						$('#register #form input:not(#user-submit)').val('');
						$('#overlay, .login-popup').delay(5000).fadeOut('300m', function() {
							$('#overlay').remove();
						});
						window.location.href = updateQueryStringParameter( modal_login_script.registration_redirect, 'nocache', ( new Date() ).getTime() );
					} else {
						$('.modal-login-content > h2').after('<p class="message error"></p>');
						$('.modal-login-content > p.message').text(results.message).show();
					}
				},
				complete : function(){
					$('.ml-modal' ).removeClass( 'is-active' );
				}
			});
		} else if ( form_id === 'forgotten' ) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: modal_login_script.ajax,
				data: {
					'action'    : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
					'username'  : $('#form #forgot_login').val(),
					'forgotten' : $('#form input[name="forgotten"]').val(),
					'security'  : $('#form #security').val()
				},
				beforeSend : function(){
					$('.ml-modal' ).addClass( 'is-active' );
				},
				success: function(results) {
					if(results.reset === true) {
						$('.modal-login-content > h2').after('<p class="message success"></p>');
						$('.modal-login-content > p.message').text(results.message).show();
						$('#forgotten #form input:not(#user-submit)').val('');
					} else {
						$('.modal-login-content > h2').after('<p class="message error"></p>');
						$('.modal-login-content > p.message').text(results.message).show();
					}
				},
				complete : function(){
					$('.ml-modal' ).removeClass( 'is-active' );
				}
			});
		} else {
			// if all else fails and we've hit here... something strange happen and notify the user.
			$('.modal-login-content > h2').after('<p class="message error"></p>');
			$('.modal-login-content > p.message').text('Something  Please refresh your window and try again.');
		}
	});

	// Make sure we go to the right pane (login VS register)
	$( 'a[href="#modal-login"]' ).click( function() {
		$( 'a[href="#login"]:eq(0)' ).click();
	});
	$( 'a[href="#modal-register"]' ).click( function() {
		$( 'a[href="#modal-login"]:eq(0), a[href="#register"]' ).click();
	});
});

/**
 * Adds or updates a query string parameters
 */
function updateQueryStringParameter( uri, key, value ) {
	var re = new RegExp( "([?&])" + key + "=.*?(&|$)", "i" );
	var separator = uri.indexOf( '?' ) !== -1 ? "&" : "?";
	if ( uri.match( re ) ) {
		return uri.replace( re, '$1' + key + "=" + value + '$2' );
	} else {
		return uri + separator + key + "=" + value;
	}
}
