/**
 * boxesFx.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2014, Codrops
 * http://www.codrops.com
 */
;( function( window ) {
	
	'use strict';

	// based on http://responsejs.com/labs/dimensions/
	function getViewport(axis) {
		var client, inner;
		if( axis === 'x' ) {
			client = docElem['clientWidth'];
			inner = window['innerWidth'];
		}
		else if( axis === 'y' ) {
			client = docElem['clientHeight'];
			inner = window['innerHeight'];
		}
		
		return client < inner ? inner : client;
	}

	var docElem = window.document.documentElement,
		transEndEventNames = {
			'WebkitTransition': 'webkitTransitionEnd',
			'MozTransition': 'transitionend',
			'OTransition': 'oTransitionEnd',
			'msTransition': 'MSTransitionEnd',
			'transition': 'transitionend'
		},
		transEndEventName = transEndEventNames[ Modernizr.prefixed( 'transition' ) ],
		support = { transitions : Modernizr.csstransitions },
		win = { width : getViewport('x'), height : getViewport('y') };
	
	function extend( a, b ) {
		for( var key in b ) { 
			if( b.hasOwnProperty( key ) ) {
				a[key] = b[key];
			}
		}
		return a;
	}

	function BoxesFx( el, options ) {	
		this.el = el;
		this.options = extend( {}, this.options );
		extend( this.options, options );
		this._init();
	}

	BoxesFx.prototype.options = {}

	BoxesFx.prototype._init = function() {
		// set transforms configuration
        if(this.el === null) return false;
		this._setTransforms();
		// which effect
		this.effect = this.el.getAttribute( 'data-effect' ) || 'effect-1';
		// check if animating
		this.isAnimating = false;
		// the panels
		this.panels = [].slice.call( this.el.querySelectorAll( '.panel-sd' ) );
		// total number of panels (4 for this demo)
		//this.panelsCount = this.panels.length;
		this.panelsCount = 6;
		// current panel´s index
		this.current = 0;
        classie.add( this.panels[0], 'current' );
//        $(this.panels[0]).addClass('current');
		// replace image with 4 divs, each including the image
		var self = this;
		this.panels.forEach( function( panel ) {
			var img = panel.querySelector( 'img' ), imgReplacement = '';
			for( var i = 0; i < self.panelsCount; ++i ) {
				imgReplacement += '<div class="bg-tile"><div class="bg-img"><img src="' + img.src + '" /></div></div>'
			}
			panel.removeChild( img );
			panel.innerHTML = imgReplacement + panel.innerHTML;
		} );
		// add navigation element
		this.nav = document.createElement( 'nav' );
		this.nav.innerHTML = '<span class="prev"><i></i></span><span class="next"><i></i></span>';
		this.el.appendChild( this.nav );
		// initialize events
		this._initEvents();
       
	}

	// set the transforms per effect
	// we have defined both the next and previous action transforms for each panel
	BoxesFx.prototype._setTransforms = function() {
		this.transforms = {
			'effect-q1' : {
				'next' : [
					'translate3d(0, ' + (win.height/2+10) + 'px, 0)', // transforms for 1 panel
					'translate3d(-' + (win.width/2+10) + 'px, 0, 0)', // transforms for 2 panel
					'translate3d(' + (win.width/2+10) + 'px, 0, 0)', // transforms for 3 panel
					'translate3d(0, -' + (win.height/2+10) + 'px, 0)' // transforms for 4 panel
				],
				'prev' : [
					'translate3d(' + (win.width/2+10) + 'px, 0, 0)',
					'translate3d(0, ' + (win.height/2+10) + 'px, 0)',
					'translate3d(0, -' + (win.height/2+10) + 'px, 0)',
					'translate3d(-' + (win.width/2+10) + 'px, 0, 0)'
				]
			},
			'effect-2' : {
				'next' : [
					'translate3d(-' + (win.width/2+10) + 'px, 0, 0)',
					'translate3d(' + (win.width/2+10) + 'px, 0, 0)',
					'translate3d(-' + (win.width/2+10) + 'px, 0, 0)',
					'translate3d(' + (win.width/2+10) + 'px, 0, 0)'
				],
				'prev' : [
					'translate3d(0,-' + (win.height/2+10) + 'px, 0)',
					'translate3d(0,-' + (win.height/2+10) + 'px, 0)',
					'translate3d(0,' + (win.height/2+10) + 'px, 0)',
					'translate3d(0,' + (win.height/2+10) + 'px, 0)'
				]
			},
			'effect-3' : {
				'next' : [
					'translate3d(0,' + (win.height/2+10) + 'px, 0)',
					'translate3d(0,' + (win.height/2+10) + 'px, 0)',
					'translate3d(0,' + (win.height/2+10) + 'px, 0)',
					'translate3d(0,' + (win.height/2+10) + 'px, 0)'
				],
				'prev' : [
					'translate3d(0,-' + (win.height/2+10) + 'px, 0)',
					'translate3d(0,-' + (win.height/2+10) + 'px, 0)',
					'translate3d(0,-' + (win.height/2+10) + 'px, 0)',
					'translate3d(0,-' + (win.height/2+10) + 'px, 0)'
				]
			},
			'effect-1' : {
				'next' : [
					{top:  (win.height/2+10)}, // transforms for 1 panel
                    {left: -(win.width/2+10)}, // transforms for 2 panel
                    {left:   (win.width/2+10)}, // transforms for 3 panel
                    {top: -  (win.height/2+10)} // transforms for 4 panel
				],
				'prev' : [
                    {left:  (win.width/2+10)},
                    {top: (win.height/2+10)},
                    {top:  -(win.height/2+10)},
                    {left: -(win.width/2+10)}
				]
			}
		};	
	}

	BoxesFx.prototype._initEvents = function() {
		var self = this, navctrls = this.nav.children;
		// previous action
		navctrls[0].addEventListener( 'click', function() { self._navigate('prev') } );
		// next action
		navctrls[1].addEventListener( 'click', function() { self._navigate('next') } );
		// window resize
		window.addEventListener( 'resize', function() { self._resizeHandler(); } );
        
        var explode = function(){
           classie.add( self.panels[0], 'current' );
        };
        
        setTimeout(explode, 5000);
        
        var autoSlider = function(){
            setInterval(function(){ 
                $(navctrls[1]).trigger('click');
            }, 5000);
        }
        
        setTimeout(autoSlider, 7000);
        $(window).unbind('scroll');
	}

	// goto next or previous slide
	BoxesFx.prototype._navigate = function( dir ) {
		if( this.isAnimating ) return false;
		this.isAnimating = true;

		var self = this, currentPanel = this.panels[ this.current ];

		if( dir === 'next' ) {
			this.current = this.current < this.panelsCount - 1 ? this.current + 1 : 0;			
		}
		else {
			this.current = this.current > 0 ? this.current - 1 : this.panelsCount - 1;
		}

		// next panel to be shown
		var nextPanel = this.panels[ this.current ];
		// add class active to the next panel to trigger its animation
		classie.add( nextPanel, 'active' );
//        $(nextPanel).addClass('active');
		// apply the transforms to the current panel
		this._applyTransforms( currentPanel, dir, nextPanel);

		// let´s track the number of transitions ended per panel
		var cntTransTotal = 0;
			
			// transition end event function
//			onEndTransitionFn = function( ev ) {
//				if( ev && !classie.has( ev.target, 'bg-img' ) ) return false;
//
//				// return if not all panel transitions ended
//				++cntTransTotal;
//				if( cntTransTotal < self.panelsCount ) return false;
//
//				if( support.transitions ) {
//					this.removeEventListener( transEndEventName, onEndTransitionFn );
//				}
//				// remove current class from current panel and add it to the next one
//				classie.remove( currentPanel, 'current' );
//				classie.add( nextPanel, 'current' );
//				// reset transforms for the currentPanel
//				self._resetTransforms( currentPanel );
//				// remove class active
//				classie.remove( nextPanel, 'active' );
//				self.isAnimating = false;
//			};

//		if( support.transitions ) {
////            onEndTransitionFn();
//			currentPanel.addEventListener(transEndEventName, onEndTransitionFn);
//		}
//		else {
//			onEndTransitionFn();
//		}
	} 

	BoxesFx.prototype._applyTransforms = function( panel, dir , nextPanel) {
		var self = this;
        var he =  $( window ).height();
//        console.log($(panel));
       var cntTransTotal = 0;
        $(panel).children().children("div.bg-img" ).each(function(i, obj) {
//                     $(this).on("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function(e){
//                $(panel).removeClass('current');
//                $(nextPanel).addClass('current');
//                self._resetTransforms( currentPanel );
//                $(nextPanel).removeClass('active');
//                 $(this).off(e);
////                classie.remove( currentPanel, 'current' );
////				classie.add( nextPanel, 'current' );
////				// reset transforms for the currentPanel
////				self._resetTransforms( currentPanel );
////				// remove class active
////				classie.remove( nextPanel, 'active' );
////				self.isAnimating = false;
//            });
            var anim =  self.transforms[self.effect][dir][i];
          
//            $('.anm-desc').eq(i).animate({top:150}, 1100, "easeInCubic", function(){
//                $('.anm-desc').eq(i).css('top', '100px');
//            });
            
            $(this).animate(
               anim
              , 1100,"easeInCubic", function() {
//                $(panel).removeClass('current');
//                $(nextPanel).addClass('current');
//                self._resetTransforms( panel );
//                $(nextPanel).removeClass('active');
                    ++cntTransTotal;
                    if( cntTransTotal < 6 ) return false;
                  classie.remove( panel, 'current' );
                  self._resetTransforms( panel );
                  classie.add( nextPanel, 'current' );
                  classie.remove( nextPanel, 'active' );
                  
                  self.isAnimating = false;
                
            });
//            $(this).css({
//              '-webkit-transform' : self.transforms[self.effect][dir][i],
//              '-moz-transform'    : self.transforms[self.effect][dir][i],
//              '-ms-transform'     : self.transforms[self.effect][dir][i],
//              '-o-transform'      : self.transforms[self.effect][dir][i],
//              'transform'         : self.transforms[self.effect][dir][i]
//            });
           

            
            
//            alert(this);
//            console.log('oaky');    
        });
        
        
//		[].slice.call( panel.querySelectorAll( 'div.bg-img' ) ).forEach( function( tile, pos ) {
//            tile.style.WebkitTransform = self.transforms[self.effect][dir][pos];
////			tile.setAttribute('style', 'transform: '+self.transforms[self.effect][dir][pos]);
////            tile.setAttribute('style', '-webkit-transform: '+self.transforms[self.effect][dir][pos]);
//			tile.style.transform = self.transforms[self.effect][dir][pos];
//		} );
	}

	BoxesFx.prototype._resetTransforms = function( panel ) {
		[].slice.call( panel.querySelectorAll( 'div.bg-img' ) ).forEach( function( tile ) {
			tile.style.top = '0px';
			tile.style.left = '0px';
		} );
	}

	BoxesFx.prototype._resizeHandler = function() {
		var self = this;
		function delayed() {
			self._resize();
			self._resizeTimeout = null;
		}
		if ( this._resizeTimeout ) {
			clearTimeout( this._resizeTimeout );
		}
		this._resizeTimeout = setTimeout( delayed, 50 );
	}

	BoxesFx.prototype._resize = function() {
		win.width = getViewport('x');
		win.height = getViewport('y');
		this._setTransforms();
	}

	// add to global namespace
	window.BoxesFx = BoxesFx;

} )( window );
new BoxesFx( document.getElementById( 'boxgallery' ) );
