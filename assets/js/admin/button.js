( function( $ ) {
	"use strict";

	var idle_class = "ev-btn-idle";

	/**
	 * Set a button to idle.
	 */
	window.ev_idle_button = function( btn ) {
		$( btn )
			.addClass( idle_class )
			.attr( "disabled", "disabled" )
			.trigger( "start.ev_button" );
	}

	function ev_btn_handle_response( btn, response ) {
		$( btn )
			.addClass( "ev-btn-complete" )
			.addClass( "ev-btn-" + response.type );

		$( btn ).attr( "data-title", response.message );
		ev_create_tooltip( btn );

		setTimeout( function() {
			$( btn ).removeAttr( "data-title" );
			$( btn ).removeClass( "ev-btn-complete" );
			$( btn ).removeClass( "ev-btn-" + response.type );
		}, 1500 );
	}

	/**
	 * Unidle a button.
	 */
	window.ev_unidle_button = function( btn, response ) {
		var s = $( "body" ).get( 0 ).style,
			transitionSupport = "transition" in s || "WebkitTransition" in s || "MozTransition" in s || "msTransition" in s || "OTransition" in s;

		if ( transitionSupport ) {
			var event_string = "transitionend.ev webkitTransitionEnd.ev oTransitionEnd.ev MSTransitionEnd.ev";

			$( btn ).on( event_string, function( e ) {
				$( btn ).off( event_string );

				ev_btn_handle_response( btn, response );
			} );
		}
		else {
			ev_btn_handle_response( btn, response );
		}

		$( btn )
			.removeClass( idle_class )
			.removeAttr( "disabled" )
			.trigger( "done.ev_button" );
	}

	/**
	 * When clicking a button with an AJAX action attached to it, set it to idle.
	 */
	// $.evf.delegate( ".ev-btn[data-callback]", "click", "ev_button", function() {
	// 	ev_idle_button( this );
	// } );

	/**
	 * After executing the AJAX action attached to a button, unidle it.
	 */
	// $( document ).on( "done.ev_button", ".ev-btn[data-callback]", function() {
	// 	ev_unidle_button( this );
	// } );

} )( jQuery );