;(function( $ ) {

	"use strict";

	$.fn.ZSP_Plugin_Admin = function( options ) {

		if ( this.length > 1 ){
			this.each( function() {
				$(this).ZSP_Plugin_Admin( options );
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

				doIt: function(){
								
					$('body').on(  'click', '.zsp-widget-tabs-header span', function(){
						var _this = $(this);
						var _tabs_header = _this.parent();
						var _tabs = _tabs_header.children();
						var _tabs_body = _tabs_header.next();
						var _id = _this.data('id');

						_tabs.removeClass('wp-ui-text-highlight');
						_this.addClass('wp-ui-text-highlight');

						_tabs_body.children().removeClass('active');
						_tabs_body.children( '.' + _id ).addClass('active');
					});

					$('body').on( 'change', '.zsp-brands-dropdown', function(){
						var _this = $(this);
						var _val = _this.val();
						var _label = _this.children('option').filter(':selected').text();
						var _name = _this.data('nameholder');
						var _placeholder = _this.prev();

						if( _val !== '' &&  _this.prev().children().filter('.' + _val ).length < 1 ){
							
							var _html = '';
							_html += '<div class="zsp-single-brand '+ _val +'" title="'+ _label +'">';
							_html += '<div class="brand-label">'+ _label +'</div>';
							
							_html += '<input type="text" class="widefat" value="" ';
							_html += 'name="'+ _name +'['+ _val +'][url]" placeholder="'+ _label +'" ';
							_html += '/>';
							
							if( ! _placeholder.hasClass('no-label') ){
								_html += '<input type="text" class="widefat" value="" ';
								_html += 'name="'+ _name +'['+ _val +'][label]" placeholder="Follow us on" ';
								_html += '/>';
							}
							
							_html += '<span class="dashicons dashicons-dismiss zsp-delete-single-brand"></span>';
							_html += '<span class="dashicons dashicons-menu zsp-move-single-brand"></span>';
							_html += '</div>';

							_placeholder.append( _html );
						}
					});
					
					$('body').on( 'mouseover', '.zsp-brands-selected-list', function(){
						$(this).sortable({
							axis: 'y',
							handle: '.zsp-move-single-brand',
							stop: function( event, ui ) {
								$(this).find('input').change();
							}
						});
					});
					

					$('body').on( 'click', '.zsp-delete-single-brand', function(){
						var _block = $(this).parent();
						var _list = _block.parent();

						_block.remove();
						_list.prev('.zsp-placeholder-field').change();
					});

				},

				__construct: function(){
					self = this;

					self.doIt();

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
	$('body').ZSP_Plugin_Admin();
});

})(jQuery);