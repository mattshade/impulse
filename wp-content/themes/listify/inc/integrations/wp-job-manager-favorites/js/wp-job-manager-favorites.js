/**
 * WP Job Manager - Favorites
 *
 * @since 1.10.0
 */
(function( window, undefined ){

	window.wp = window.wp || {};
	var document = window.document;
	var $ = window.jQuery;
	var wp = window.wp;

	/**
	 * @since 1.0.0
	 */
	var $document = $(document);

	function openModal( el ) {
		var $button = el;

		var $form = $button.closest( $( '.wp-job-manager-favorites-form' ) );
		var $place = $form.parent();
		var detached = $form.detach();

		var src = $( '<div class="popup"><h2 class="popup-title">' + el.html().trim() + '</h2></div>' ).append( detached );

		var $popup = $.magnificPopup.open({
			type: 'inline',
			fixedContentPos: false,
			fixedBgPos: true,
			overflowY: 'scroll',
			items: {
				src: src
			},
			callbacks: {
				close: function() {
					$place.append( detached );
				}
			}
		});
	}
	
	/**
	 * Wait for DOM ready.
	 *
	 * @since 1.0.0
	 */
	$document.ready(function() {

		$( '.site' ).on( 'click', '.wp-job-manager-favorites-status', function(e) {
			e.preventDefault();

			openModal( $(this) );
		});

		$( 'body' ).on( 'wp-job-manager-favorite-added', function( event, response ) {
			$( '.wp-job-manager-favorites-count span' ).text( response.data.count );
			$( '.wp-job-manager-favorites-status' ).addClass( 'wp-job-manager-favorites-status--favorited' );
		} );

	});

}( window ));
