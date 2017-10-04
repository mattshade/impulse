/**
 * Map results to various mapping APIs.
 *
 * The core is data-driven and takes objects to add points to a map.
 *
 * However supported connectors (WP Job Manager, FacetWP) return HTML results
 * which must be parsed.
 *
 * Supported services (Google Maps, Leaflet) add a handler to connect
 * to the nteractive map.
 *
 * @since 1.8.0
 *
 * @param {jQuery} $ jQuery object.
 * @param {w[} wp WordPress object.
 */
(function( $, wp ) {

	/**
	 * @type {object}
	 */
	var AstoundifyMapping = AstoundifyMapping || {};

	// alias
	var api = AstoundifyMapping;

	/**
	 * Manage map handlers.
	 *
	 * @since 1.8.0
	 */
	api.Handlers = {
		handlers: {},

		get: function( id ) {
			return this.handlers[id];
		},

		add: function( id, handler ) {
			this.handlers[ id ] = handler;
		}
	};

	/**
	 * A single map handler
	 *
	 * @since 1.8.0
	 */
	api.Handler = function( id, service ) {
		this.id = id || '';
		this.service = service || {};
		
		api.Handlers.add( this.id, this.service );
	}

	/**
	 * A map service.
	 *
	 * @since 1.0.0
	 */
	api.Map = function() {}

	var GoogleMaps = function() {}

	var google = new api.Handler( 'google', GoogleMaps );

	console.log(api);
	console.log(google);

	/**
	 * Bind to the DOM
	 *
	 * @since 1.8.0
	 */
	$(document).ready(function() {

	});

})( jQuery, wp );
