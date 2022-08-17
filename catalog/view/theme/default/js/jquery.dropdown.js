/**
 * jquery.dropdown.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2012, Codrops
 * http://www.codrops.com
 */
;( function( $, window, undefined ) {
	
	'use strict';

	$.DropDown = function( options, element ) {
		this.$el = $( element );
		this._init( options );
	};

	// the options
	$.DropDown.defaults = {
		speed : 300,
		easing : 'ease',
		gutter : 0,
		// initial stack effect
		stack : true,
		// delay between each option animation
		delay : 0,
		// random angle and positions for the options
		random : false,
		// rotated [right||left||false] : the options will be rotated to thr right side or left side.
		// make sure to tune the transform origin in the stylesheet
		rotated : false,
		// effect to slide in the options. value is the margin to start with
		slidingIn : false
	};

	$.DropDown.prototype = {

		_init : function( options ) {
			
			// options
			this.options = $.extend( true, {}, $.DropDown.defaults, options );
			this._layout();
			this._initEvents();

		},
		_layout : function() {

			var self = this;
			this.minZIndex = 1;
			this._transformSelect();
			this.opts = this.listopts.children( 'li' );
			this.optsCount = this.opts.length;
			this.size = { width : this.dd.width(), height : this.dd.height() };
			this.inputEl = $( '<input type="hidden" name="cd-dropdown"></input>' ).insertAfter( this.selectlabel );
			this.selectlabel.css( 'z-index', this.minZIndex + this.optsCount );
			this._positionOpts();
			if( Modernizr.csstransitions ) {
				setTimeout( function() { self.opts.css( 'transition', 'all ' + self.options.speed + 'ms ' + self.options.easing ); }, 25 );
			}

		},
		_transformSelect : function() {

			var optshtml = '', selectlabel = '';
			this.$el.children( 'option' ).each( function() {
				
				var $this = $( this ),
					val = Number( $this.attr( 'value' ) ),
					classes = $this.attr( 'class' ),
					dataLink = $this.attr( 'data-link' ),
					dataImg = $this.attr( 'data-img' ),
					label = $this.text();

				val !== -1 ? 
					classes !== undefined ? 
						optshtml += '<li data-value="' + val + '"><a href="' + dataLink + '"><span class="' + classes + '" style="background-image: url(' + dataImg + ')">' + label + '</span></a></li>' : 
						optshtml += '<li data-value="' + val + '"><a href="' + dataLink + '"><span  style="background-image: url(' + dataImg + ')">' + label + '</span></a></li>' : 
					selectlabel = label;


			} );
			
			this.listopts = $( '<ul/>' ).append( optshtml );
			this.selectlabel = $( '<span id="first-label" ></span>' ).append( selectlabel );
			
			this.dd = $( '<div class="cd-dropdown"/>' ).append( this.selectlabel, this.listopts ).insertAfter( this.$el );
			this.$el.remove();

		},
		_positionOpts : function( anim ) {

			var self = this;

			this.listopts.css( 'height', 'auto' );
			this.opts
				.each( function( i ) {
					$( this ).css( {
						zIndex : self.minZIndex + self.optsCount - 1 - i,
						top : self.options.slidingIn ? ( i + 1 ) * ( self.size.height + self.options.gutter ) : 0,
						left : 0,
						marginLeft : self.options.slidingIn ? i % 2 === 0 ? self.options.slidingIn : - self.options.slidingIn : 0,
						opacity : self.options.slidingIn ? 0 : 1,
						transform : 'none'
					} );
				} );

			if( !this.options.slidingIn ) {
				this.opts
					.eq( this.optsCount - 1 )
					.css( { top : this.options.stack ? 9 : 0, left : this.options.stack ? 4 : 0, width : this.options.stack ? this.size.width - 8 : this.size.width, transform : 'none' } )
					.end()
					.eq( this.optsCount - 2 )
					.css( { top : this.options.stack ? 6 : 0, left : this.options.stack ? 2 : 0, width : this.options.stack ? this.size.width - 4 : this.size.width, transform : 'none' } )
					.end()
					.eq( this.optsCount - 3 )
					.css( { top : this.options.stack ? 3 : 0, left : 0, transform : 'none' } );
			}

		},
		_initEvents : function() {
			
			var self = this;

			this.selectlabel.on( 'mousedown.dropdown', function( event ) {
				
				self.opened ? self.close() : self.open();
				return false;
				
			} );

			this.opts.on( 'click.dropdown', function() {

				if( self.opened ) {
					var opt = $( this );
					self.inputEl.val( opt.data( 'value' ) );
					self.selectlabel.html( opt.html() );
					self.close();
				}

			} );

		},
		open : function() {

			var self = this;
			this.dd.toggleClass( 'cd-active' );
			this.listopts.css( 'height', ( this.optsCount + 1 ) * ( this.size.height + this.options.gutter ) );
			this.opts.each( function( i ) {
				
				$( this ).css( {
					opacity : 1,
					top : self.options.rotated ? self.size.height + self.options.gutter : ( i + 1 ) * ( self.size.height + self.options.gutter), 
					left : self.options.random ? Math.floor( Math.random() * 11 - 5 ) : 0,
					width : self.size.width,
					marginLeft : 0,
					transform : self.options.random ? 
						'rotate(' + Math.floor( Math.random() * 11 - 5 ) + 'deg)' : 
						self.options.rotated ? 
							self.options.rotated === 'right' ? 
								'rotate(-' + ( i * 5 ) + 'deg)' : 
								'rotate(' + ( i * 5 ) + 'deg)' 
							: 'none',
					transitionDelay : self.options.delay && Modernizr.csstransitions ? self.options.slidingIn ? ( i * self.options.delay ) + 'ms' : ( ( self.optsCount - 1 - i ) * self.options.delay ) + 'ms' : 0
				} );

			} );
			this.opened = true;

		},
		close : function() {
			
			var self = this;
			this.dd.toggleClass( 'cd-active' );
			if( this.options.delay && Modernizr.csstransitions ) {
				this.opts.each( function( i ) {
					$( this ).css( { 'transition-delay' : self.options.slidingIn ? ( ( self.optsCount - 1 - i ) * self.options.delay ) + 'ms' : ( i * self.options.delay ) + 'ms' } );
				} );
			}
			this._positionOpts( true );
			this.opened = false;

		}

	}
	
	$.fn.dropdown = function( options ) {
		var instance = $.data( this, 'dropdown' );	
		if ( typeof options === 'string' ) {
			var args = Array.prototype.slice.call( arguments, 1 );
			this.each(function() {
				instance[ options ].apply( instance, args );
			});
		} 
		else {
			this.each(function() {	
				instance ? instance._init() : instance = $.data( this, 'dropdown', new $.DropDown( options, this ) );
			});
		}
		return instance;
	};
	
} )( jQuery, window );