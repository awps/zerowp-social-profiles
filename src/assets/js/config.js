;(function( $ ) {

	"use strict";

	$.fn.ZSP_Plugin = function( options ) {

		if ( this.length > 1 ){
			this.each( function() {
				$(this).ZSP_Plugin( options );
			});
			return this;
		}

		// Defaults
		var settings = $.extend( {}, options );

		// Cache current instance
		var plugin = this;

		//Plugin go!
		var init = function() {
			plugin.build();
		}

		// Build plugin
		this.build = function() {
			
			var self = false;

			var _base = {

				exampleProperty: 'example value',

				exampleMethod: function(){
					return self.exampleMethod;
				},

				__construct: function(){
					self = this;

					self.exampleMethod();

					return this;
				}

			};

			/*
			-------------------------------------------------------------------------------
			Rock it!
			-------------------------------------------------------------------------------
			*/
			_base.__construct();

		}

		//Plugin go!
		init();
		return this;

	};


$(document).ready(function(){
	$('body').ZSP_Plugin();
});

})(jQuery);