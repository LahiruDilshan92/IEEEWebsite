/*
 * King Composer Project
 *
 * (c) Copyright king-theme.com
 *
 * Must obtain permission before using this script in any other purpose
 *
 * kc.front.js
 *
*/

( function($){
	
	if( typeof( kc ) == 'undefined' ){
		console.error('Could not load KingComposer core library');
		return;
	}
	
	kc.front = {
		
		init : function( frame_doc ){
			
			kc.widgets = $( kc.template('wp-widgets') );

			kc.model = kc.storage.length;
			kc.detect.init( frame_doc );
			kc.front.ui.init( frame_doc );
			
			kc.trigger({
				
				el: $('#kc-top-toolbar'),
				
				events: {
					'.kc-bar-devices:click': 'screen',
					'#kc-enable-inspect:click': 'switch',
					'#kc-bar-tour-view:click': 'tour',
					'#kc-front-exit:click': 'exit',
					'#kc-front-save:click': 'save'
				},
				
				screen: function(e){
					
					var screen = $(this).data('screen');
					if( screen == 'custom' ){
						screen = prompt( 'Enter custom screen size (unit px)', this.innerHTML );
						if( screen === null )
							return;
					}
					
					e.data.el.find('.kc-bar-devices').removeClass('active');
					$(this).addClass('active')
					$('#kc-live-frame').stop().animate({ width: screen });
					kc.detect.untarget();
					$('#kc-curent-screen-view').html( screen );
	
				},
				
				switch : function(e){
					if( $(this).find('.toggle').hasClass('disable') ){
						$(this).find('.toggle').removeClass('disable');
						kc.detect.frame.$('body').removeClass('kc-disable-inspect');
						kc.detect.disabled = false;
					}else{
						$(this).find('.toggle').addClass('disable');
						kc.detect.frame.$('body').addClass('kc-disable-inspect');
						kc.detect.disabled = true;
						kc.detect.untarget();
					}
				},
				
				tour : function(e){
					kc.cfg.tour = '';
					kc.front.ui.tour();
					e.preventDefault();
				},
				
				exit : function(e){
					window.location.href = $('#kc-live-frame').attr('src').replace('&kc_action=live-editor','').replace('?kc_action=live-editor','');
				},
				
				save : function(){
					
					$('body').append('<div id="kc-preload"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>');
					
					if( typeof window.kc_tkl_i4er == 'function' ){
						kc_tkl_i4er();
					}else{
						kc.front.ui.ask_to_buy();
					}
				}
				
			});
			
		},
		
		load : function( frame_doc ){
			
			// onload event
				
		},
		
		params : {
		
			before_save : function( pop ){
					
				var model = pop.data('model');
				if( kc.storage[ model ] !== undefined && 
					kc.storage[ model ].args !== undefined && 
					kc.storage[ model ].args.css !== undefined 
				){
					css = kc.storage[ model ].args.css.split('|')[0];
					kc.front.ui.style.remove( '.'+css );
				}
				
				pop.data({ active_scroll : pop.find('.m-p-body').scrollTop() });
	
			},
			
			save : function( pop ){
					
				var model = typeof(pop) == 'number' ? pop : pop.data('model'),
					code = kc.front.build_shortcode( model );
					
				if( kc.storage[model] === undefined )
					return;
					
				if( kc_maps_views.indexOf( kc.storage[model].name ) > -1 ){
					model = kc.detect.holder.sections.model;
					code = kc.front.build_shortcode( model );
					setTimeout( function(pop){ pop.remove(); }, 1, pop );
				}
					
				if( code !== '' ){
					
					kc.tools.popup.no_close = true;
					var fid = kc.front.push( code, model, 'replace' );
					pop.data({ model : fid });
					pop.find('.kc-pop-tabs>li').eq( pop.data('tab_active') ).trigger('click');
					
					if( pop.data('active_scroll') !== undefined ){
						pop.find('.m-p-body').scrollTop( pop.data('active_scroll') );
					}
					
					if( pop.find('.sl-check.sl-func').css('visibility') == 'hidden' )
						return;
						
					pop.find('.sl-check.sl-func, button.save').css({visibility: 'hidden'});
					pop.find('.m-p-overlay').stop().
						css({display: 'block', opacity: 0}).
						animate({opacity: 1, top: 48 }, 250).
						delay(2000).
						animate({opacity:0, top: -1}, function(){
							$(this).css({display: 'none'});
							$(this).closest('.kc-params-popup').find('.sl-check.sl-func, button.save').attr({style:''});
						});
					
				}
			},
			
			cancel : function(){
				kc.detect.locked = false;
			}
			
		},
		
		build_shortcode : function( model ){
			
			var string = '', css;
			if( model !== null && kc.storage[ model ] !== undefined ){
				string += '['+kc.storage[ model ].name;
				for( var n in kc.storage[ model ].args ){
					if( n !== 'content' && n !== 'css_data' ){
						
						if( n == 'css' ){
							
							css = kc.storage[ model ].args[n];
							if( css.indexOf('|') > -1 )
								css = ' '+n+'="'+kc.tools.esc_attr( css )+'"';
							else if( kc.storage[ model ].args['css_data'] !== undefined )
								css = ' '+n+'="'+kc.tools.esc_attr( kc.storage[ model ].args[n] )+'|'+kc.storage[ model ].args['css_data']+'"';
							else css = '';
							
							string += css;
							
						}else{
							string += ' '+n+'="'+kc.tools.esc_attr( kc.storage[ model ].args[n] )+'"';
						}
							
					}
				}
				string += ']';
				
				kc.front.export( model );
				
				if( kc.storage[ model ].args.content !== undefined && kc.storage[ model ].end !== undefined ){
					string += kc.storage[ model ].args.content+kc.storage[ model ].end;
				}
			}
			
			return string;
				
		},
		
		do_shortcode : function( input, callback ){
		
			if( input === undefined  )
				return null;
		
			var regx = new RegExp( '\\[(\\[?)(' + kc.tags + ')(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*(?:\\[(?!\\/\\2\\])[^\\[]*)*)(\\[\\/\\2\\]))?)(\\]?)', 'g' ), result, agrs, content = input;
		
			var split_arguments = /([a-zA-Z0-9\-\_]+)="([^"]+)+"/gi;
			var output = input;
			
			while ( result = regx.exec( input ) ) {

				var paramesArg 	= [];
				while( agrs = split_arguments.exec( result[3] ) ){
					if(  agrs[1] != '__name' &&  agrs[1] != '__content' && agrs[2] !== '__empty__' )
						paramesArg[ agrs[1] ] = agrs[2];
				}
				
				var args = {
					full		: result[0],
					name 		: result[2],
					/*parames 	: result[3],*/
					/*content 	: result[5],*/
					end		 	: result[6],
					atts	 	: paramesArg,
					/*input		: input,
					result		: result*/
				};
				
				if( undefined !== result[5] && '' !== result[5] ){
					args.content = kc.front.process_alter( result[5], result[2] );
				}
				
				output = output.replace( result[0], kc.front.do_shortcode_tag( args, callback ) );
				
			}
			
			return output;
			
		},
		
		do_shortcode_tag : function( atts, callback ){
			
			var selector = '';
			
			if( atts.content !== undefined && atts.content !== '' ){
				atts._content = atts.content; 
				atts.content = this.do_shortcode( atts.content, callback ); 
			}
			
			if( atts['atts']['css'] === undefined ){
				selector = 'kc-css-'+parseInt( Math.random()*1000000 );
				atts['atts']['css'] = selector;
			}else{
				selector = atts['atts']['css'].split('|');
				selector = selector[0];
			}
			
			var result = kc.template( atts['name'], atts );
			
			kc.model++;
			var model = kc.model;
			
			kc.storage[ model ] = {
				name : atts['name'],
				args : atts['atts'],
				full : atts['full'],
			}
			
			if( callback !== undefined && 
				atts.callback !== undefined && 
				typeof( atts.callback ) == 'function' 
			){
				atts.model = model;
				callback.push( atts );
			}
			
			if( atts['end'] !== undefined ){
				kc.storage[ model ].end = atts['end'];
				kc.storage[ model ].args.content = atts._content;
				delete kc.storage[ model ].args._content;
			}
			
			kc.front.ui.style.process( atts['atts'] );
			
			if( result !== null && result !== undefined )
				return '<!--kc s '+model+'-->'+result.trim()+'<!--kc e '+model+'-->';
			else return '<div class="kc-element kc-undefined-layout kc-loadElement-via-ajax" data-model="'+model+'"><span><i class="fa-spinner fa-spin fa-2x"></i><br /><h3>'+atts['name'].replace('kc_','').replace(/\_/g,' ')+'</h3></span></div>';
				
		},
		
		process_alter : function( input, tag ){
	
			/* remove ### of containers loop */
			var start = input.indexOf('['+tag+'#');
			if( start > -1 ){
				var str = input.substring( start+1, input.indexOf( ']', start ) ).split(' ')[0];
				var exp = new RegExp( str, 'g' );
				input = input.replace( exp, tag);
			}
	
			return input;
	
		},
		
		loop_box : function( items ){
		
			if( typeof( items ) != 'object' )
				return '';
		
			var output = '';
		
			for( var n in items ){
		
				if( typeof( items[n] == 'object' ) && items[n]['tag'] != 'text' ){
		
					output += '<'+items[n]['tag'];
					
					if( typeof( items[n]['attributes'] ) != 'object' )
						items[n]['attributes'] = {};
		
					if( items[n]['attributes']['class'] === undefined )
						items[n]['attributes']['class'] = '';
		
					if( items[n]['tag'] == 'column' )
					{
						items[n]['attributes']['class'] += ' '+items[n]['attributes']['cols'];
						delete items[n]['attributes']['cols'];
					}else if( items[n]['tag'] == 'img' && ( items[n]['attributes']['src'] === undefined || items[n]['attributes']['cols'] === '' ) )
						items[n]['attributes']['cols'] = plugin_url+'/assets/images/get_start.jpg';
		
					for( var at in items[n]['attributes'] )
					{
						if( items[n]['attributes'][at] !== '' )
							output += ' '+at+'="'+items[n]['attributes'][at]+'"';
					}
		
					if( items[n]['tag'] == 'img' )
						output += '/';
		
					output += '>';
		
					if( typeof( items[n]['children'] ) == 'object' )
						output += kc.front.loop_box( items[n]['children'] );
		
					if( items[n]['tag'] != 'img' )
						output += '</'+items[n]['tag']+'>';
		
				}else output += items[n]['content'];
		
			}
		
			return output;
	
		},
		
		ui : {
			
			bag : {},
						
			delay : [ 100 ],
			
			init : function(){
				
				var el = kc.frame.$('#kc-element-placeholder .move, #kc-sections-placeholder .move').each(function(){
					this.setAttribute('droppable', 'true');
			        this.setAttribute('draggable', 'true');
					this.addEventListener( 'dragstart', kc.front.ui.events.dragstart, false);
				});
				
				var row = kc.detect.frame.contents.find('#kc-row-placeholder .move').get(0);
				row.setAttribute('droppable', 'true');
		        row.setAttribute('draggable', 'true');
				row.addEventListener( 'dragstart', this.events.row_dragstart, false);
				
				kc.detect.frame.doc.addEventListener( 'dragover', this.events.dragover, false);
				kc.detect.frame.doc.addEventListener( 'dragend', this.events.dragend, false);
				kc.detect.frame.doc.addEventListener( 'drop', this.events.drop, false);
				
				this.style.sheet = kc.detect.frame.$('#kc-css-render').get(0).sheet;
				
				$('#wpbody-content').on( 'click', function(e){
					
					if( e.target.id == 'wpbody-content' )
						kc.detect.untarget();
						
				});
				
				kc.views.column.apply_all = kc.front.ui.column.responsive_all;
				
				kc.tools.popup.margin_top = -40;
					
			},
			
			events : {

				dragstart : function( e ){
					
					/**
					*	We will get the start element from mousedown of columnsResize
					*/
					
					var u = kc.front.ui, model = kc.get.model( this );
					
					if( kc.detect.holder.el === null ){
						e.preventDefault();
						return false;
					}
					
					u.bag.e = kc.frame.$('[data-model="'+model+'"]').get(0);				
					u.bag.e.classList.add('kc-ui-placeholder');
					u.bag.model = model;
					
					kc.detect.frame.$('body').addClass('kc-ui-dragging');
					
			        e.dataTransfer.effectAllowed = 'move';
			        e.dataTransfer.dropEffect = 'none';

			        if( e.dataTransfer !== undefined && typeof  e.dataTransfer.setData == 'function' )
			        	e.dataTransfer.setData('text/plain', 'KingComposer.Com');

				    if( e.dataTransfer !== undefined && typeof  e.dataTransfer.setDragImage == 'function' ){
						e.dataTransfer.setDragImage(
							kc.frame.$( '#kc-ui-handle-image' ).get(0), 25, 25
						);
					}

				},
				
				row_dragstart : function( e ){
					
					var model = kc.get.model( this );
					
					var u = kc.front.ui;
					
					if( kc.detect.holder.row.el === null ){
						e.preventDefault();
						return false;
					}
					
					u.bag.e = kc.detect.holder.row.el;					
					u.bag.e.classList.add('kc-ui-placeholder');
					u.bag.model = model;
					
					$('body').addClass('kc-ui-dragging');
					
			        e.dataTransfer.effectAllowed = 'move';
			        e.dataTransfer.dropEffect = 'none';

			        if( e.dataTransfer !== undefined && typeof  e.dataTransfer.setData == 'function' )
			        	e.dataTransfer.setData('text/plain', 'KingComposer.Com');

				    if( e.dataTransfer !== undefined && typeof  e.dataTransfer.setDragImage == 'function' ){
						e.dataTransfer.setDragImage(
							kc.frame.$( '#kc-ui-handle-image' ).get(0), 25, 25
						);
					}
					
				},
				
				dragover : function( e ){
					
					var u = kc.front.ui;

					if( u.bag.e === null ){

						e.preventDefault();
						return false;

					}else if( kc.detect.holder.el !== null ){
						kc.detect.untarget();
					}
					
					
					
					// Slow down each process when dragging
					
					if( u.delay[1] !== true ){
						if( u.delay[2] !== true ){
							
							u.delay[2] = true;
							
						}else{
							
							u.delay[1] = true;
							setTimeout( function(){
	
								kc.front.ui.delay[1] = false;
								kc.front.ui.delay[2] = false;
	
							}, u.delay[0] );
	
							e.preventDefault();
							return false;
						}
					}else{
						e.preventDefault();
						return false;
					}

					if(!e) e = window.event;
					
					if( kc.storage[u.bag.model] !== undefined ){
						if( kc.storage[u.bag.model].name == 'kc_row' )
							return kc.front.ui.events.row_dragover( e, u );
						if( kc.storage[u.bag.model].name == 'kc_row_inner' )
							return kc.front.ui.events.row_inner_dragover( e, u );
					}
					
					u.bag.t = kc.detect.closest( e.target );
					
					if( u.bag.t === null || 
						( 
							!kc.detect.is_element( u.bag.t[1] ) && 
							u.bag.t[1] != '-1'
						) || 
						$.contains( u.bag.e, u.bag.t[0] ) ){

						// prevent actions when hover it self or hover its children
						e.preventDefault();
						return false;

					}else{

						u.bag.r = u.bag.t[0].getBoundingClientRect();
							
						u.bag.b = (u.bag.r.height/3);
						if( u.bag.b < 100 )
							u.bag.b = u.bag.r.height/2;
							
						if( (u.bag.r.bottom-e.clientY) < u.bag.b ){

							if( u.bag.t[0].nextElementSibling != u.bag.e ){
								$( u.bag.t[0] ).after( u.bag.e );
								kc.ui.preventFlicker( e, u.bag.e );
							}

						}else if( (e.clientY-u.bag.r.top) < u.bag.b ){

							if( u.bag.t[0].previousElementSibling != u.bag.e ){
								$( u.bag.t[0] ).before( u.bag.e );
								kc.ui.preventFlicker( e, u.bag.e );
							}
						}
						
					}

					e.preventDefault();
					return false;
				},

				row_dragover : function( e, u ){
					
					u.bag.t = kc.detect.closest( e.target );
				
					while( u.bag.t !== null && kc.storage[ u.bag.t[1] ] !== undefined ){
						
						if( kc.storage[ u.bag.t[1] ].name == 'kc_row' )
							break;
						
						u.bag.t = kc.detect.closest( u.bag.t[0].parentNode );
					}
					
					if( u.bag.t === null || ( kc.storage[ u.bag.t[1] ] !== undefined && kc.storage[ u.bag.t[1] ].name != 'kc_row' ) ){

						// prevent actions when hover it self or hover its children
						e.preventDefault();
						return false;

					}else{

						u.bag.r = u.bag.t[0].getBoundingClientRect();
							
						u.bag.b = (u.bag.r.height/3);
						if( u.bag.b < 100 )
							u.bag.b = u.bag.r.height/2;
							
						if( (u.bag.r.bottom-e.clientY) < u.bag.b ){

							if( u.bag.t[0].nextElementSibling != u.bag.e ){
								$( u.bag.t[0] ).after( u.bag.e );
								kc.ui.preventFlicker( e, u.bag.e );
							}

						}else if( (e.clientY-u.bag.r.top) < u.bag.b ){

							if( u.bag.t[0].previousElementSibling != u.bag.e ){
								$( u.bag.t[0] ).before( u.bag.e );
								kc.ui.preventFlicker( e, u.bag.e );
							}
						}
						
					}

					e.preventDefault();
					return false;
				},				
				
				row_inner_dragover : function( e, u ){
					
					u.bag.t = kc.detect.closest( e.target );
				
					while( u.bag.t !== null && kc.storage[ u.bag.t[1] ] !== undefined ){
						
						if( u.bag.t[0].parentNode === u.bag.e.parentNode )
							break;
						
						u.bag.t = kc.detect.closest( u.bag.t[0].parentNode );
					}
					
					if( u.bag.t === null || 
						( kc.storage[ u.bag.t[1] ] !== undefined && u.bag.t[0].parentNode !== u.bag.e.parentNode ) 
					){

						// prevent actions when hover it self or hover its children
						e.preventDefault();
						return false;

					}else{

						u.bag.r = u.bag.t[0].getBoundingClientRect();
							
						u.bag.b = (u.bag.r.height/3);
						if( u.bag.b < 100 )
							u.bag.b = u.bag.r.height/2;
							
						if( (u.bag.r.bottom-e.clientY) < u.bag.b ){

							if( u.bag.t[0].nextElementSibling != u.bag.e ){
								$( u.bag.t[0] ).after( u.bag.e );
								kc.ui.preventFlicker( e, u.bag.e );
							}

						}else if( (e.clientY-u.bag.r.top) < u.bag.b ){

							if( u.bag.t[0].previousElementSibling != u.bag.e ){
								$( u.bag.t[0] ).before( u.bag.e );
								kc.ui.preventFlicker( e, u.bag.e );
							}
						}
						
					}

					e.preventDefault();
					return false;
				},

				_drag : function( e ){

					var atts = $(this).data('atts'),
						h = atts.helperClass,
						p = atts.placeholder,
						el = kc.ui.elm_drag ;

					if( h !== '' && el !== null ){

						if( el.className.indexOf( h ) > -1 ){

							$( el ).removeClass( h );

							if( p !== '' )
								$( el ).addClass( p );
						}
					}

					if( typeof atts.drag == 'function' )
						atts.drag( e, this );

					e.preventDefault();
					return false;

				},

				_dragleave : function( e ){

					var atts = $(this).data('atts');

					if( typeof atts.leave == 'function' )
						atts.leave( e, this );

					e.preventDefault();
					return false;
				},

				dragend : function( e ){
					
					if( kc.front.ui.bag.e !== undefined )
						kc.front.ui.bag.e.classList.remove('kc-ui-placeholder');
					$('body').removeClass('kc-ui-dragging');
					e.preventDefault();
					return false;

				},

				drop : function( e ){

					e.preventDefault();
					return false;

				}

			},
			
			column : {
				
				width_calc : function( wid ){
					
					if( wid === undefined )
						wid = '12/12';
					
					wid = wid.split('/'); 
					var n = 12, m = 12;
					
					if( wid[0] !== undefined && wid[0] !== '' )
						n = wid[0];
					
					if( wid[1] !== undefined && wid[1] !== '' )
						m = wid[1];
					
					if( n == '2.4'){
						return 2.4;
					}else{
						n = parseInt( n );
						if ( n > 0 && m > 0 ){
							var calc = 12/(m/n);
							if( calc > 0 && calc <= 12 )
								return calc;
						}
					}
					
					return 12;
					
				},
				
				width_class : function( wid ){
					
					wid = this.width_calc( wid );
					
					if( wid === 2.4 )
						return 'kc_col-of-5';
					else return 'kc_col-sm-'+wid;
					
				},
				
				change_width : function( model, wid ){
					
					var wd = this.width_calc(  kc.storage[ model ].args.width ), _$ = kc.detect.frame.$;
					
					if( ( wd <= 1 && wid === -1 ) || ( wd >= 12 && wid === 1 ) || wd === 2.4 )
						return false;
						
					_$( '[data-model="'+model+'"]' ).removeClass( this.width_class( kc.storage[ model ].args.width ) );
					
					kc.storage[ model ].args.width = (this.width_calc( kc.storage[ model ].args.width ) + wid )+'/12';
						
					_$( '[data-model="'+model+'"]' ).addClass( this.width_class( kc.storage[ model ].args.width ) );
					
					kc.detect.columnsWidthChanged = true;
					
					return true;
					
				},
				
				layout : function( e ){
					
					var columns = kc.detect.holder.row.el.querySelectorAll('[data-model]');
					
					if( columns.length === 0 )
						return;
					
					var count = 0, col = columns[0], mol;
					columns = [];
					while( col !== undefined && col !== null ){
						mol = col.getAttribute( 'data-model' );
						if( mol !== null &&  kc.storage[ mol ] !== undefined && ( kc.storage[ mol ].name == 'kc_column' ||  kc.storage[ mol ].name == 'kc_column_inner' ) )
							columns.push( col );
						col = col.nextElementSibling;
					}
					
					var pop = kc.tools.popup.render( 
								e.target, 
								{ 
									title: 'Row Layout', 
									class: 'no-footer',
									width: 341,
									content: kc.template( 'row-columns', {current:columns.length} ),
									help: 'http://docs.kingcomposer.com/documentation/resize-sortable-columns/?source=client_installed' 
								}
							);
							
					pop.find('.button').on( 'click', 
						{ 
							model: kc.get.model( e.target ),
							columns: columns,
							pop: pop
						}, 
						kc.front.ui.column.set_columns 
					);
					
					pop.find('input[type=checkbox]').on('change',function(){
						
						var name = $(this).data('name');
						if( name == undefined )
							return;
							
						if( this.checked == true )
							kc.cfg[ name ] = 'checked';
						else kc.cfg[ name ] = '';
						
						kc.backbone.stack.set( 'KC_Configs', kc.cfg );
							
					});	
					
				},
				
				set_columns : function( e ){
					
					var newcols = parseInt($(this).data('column')),
						uc = kc.front.ui.column,
						_$ = kc.detect.frame.$,
						columns = _$(e.data.columns);
					
					if( columns.length < newcols ){
						
						/* Add new columns */
						var id = columns.last().data('model'), el, reInit = false, model, wid;
						
						columns.each(function(){
							
							model = _$(this).data('model');
							wid = (12/newcols)+'/12';
							
							_$( '[data-model="'+model+'"]' ).removeClass( uc.width_class( kc.storage[ model ].args.width ) );
							kc.storage[ model ].args.width = wid;
							_$( '[data-model="'+model+'"]' ).addClass( uc.width_class( wid ) );
							
							kc.detect.columnsWidthChanged = true;
							
						});
						
						for( var i = 0; i < (newcols-columns.length) ; i++ ){
							
							var dobl = kc.front.ui.element.double( columns.last().data('model') );
							
							if( kc.cfg['columnDoubleContent'] != 'checked' ){
								
								dobl.find('[data-model]').each(function(){
									if( this.getAttribute('data-model') != '-1' ){
										delete kc.storage[this.getAttribute('data-model')];
										_$(this).remove();
									}
								});
								
							}
							
						}
						
						if( reInit == true )
							kc.ui.sortInit();
							
					}else{
						/* Remove columns */
						var remove = [], found_empty, wrow = columns.eq(0).parent(), el, last, plast;
						
						for( var i = 0; i < (columns.length-newcols) ; i++ ){
						
							found_empty = false;
							wrow.find(' > [data-model]').each(function(){
								if( _$(this).find('[data-model]').length === 1 )
									found_empty = this;
							});
						
							if( found_empty !== false ){
								_$(found_empty).remove();
						
							}else{
						
								last = wrow.find(' > [data-model]').last();
								plast = last.prev();
								
								plast = plast.find('[data-model]').first().parent();
								
								if( kc.cfg['columnKeepContent'] == 'checked' ){
									
									el = last.find('[data-model]').get(0).parentNode.children;
									[].forEach.call( el, function( elm ){
										
										if( elm.getAttribute('data-model') !== undefined && elm.getAttribute('data-model') != '-1' )
											plast.append( elm );
									});
									
								}
								
								kc.front.clean_storage( last.data('model') );
								last.remove();
								
							}		
						}
						
						wrow.find('> [data-model]').each(function(){
							
							model = _$(this).data('model');
							wid = (12/newcols)+'/12';
							
							_$( '[data-model="'+model+'"]' ).
								removeClass( uc.width_class( kc.storage[ model ].args.width ) ).
								removeClass('kc_col-of-5');
							kc.storage[ model ].args.width = wid;
							_$( '[data-model="'+model+'"]' ).addClass( uc.width_class( wid ) );
							
							kc.detect.columnsWidthChanged = true;
							
						});
						
					}
					
					e.data.pop.remove();
				},
				
				responsive_all : function( el ){
					
					var model = kc.get.model(el), 
						res = kc.storage[model].args.responsive,
						col = kc.detect.frame.$('[data-model="'+model+'"]').get(0),
						pcol = col.previousElementSibling,
						ncol = col.nextElementSibling;
					
					kc.front.ui.style.update_responsive( model );
					
					while( pcol !== null ){
						if( pcol.getAttribute('data-model') !== null ){
							kc.storage[ pcol.getAttribute('data-model') ].args.responsive = res;
							kc.front.ui.style.update_responsive( pcol.getAttribute('data-model') );
							pcol = pcol.previousElementSibling;
						}else pcol = pcol.previousElementSibling;
					}
					while( ncol !== null ){
						if( ncol.getAttribute('data-model') !== null ){
							kc.storage[ ncol.getAttribute('data-model') ].args.responsive = res;
							kc.front.ui.style.update_responsive( ncol.getAttribute('data-model') );
							ncol = ncol.nextElementSibling;
						}else ncol = ncol.nextElementSibling;
					}
					
					setTimeout( kc.detect.untarget, 100 );
					
				}
				
			},
			
			element : {
				
				edit : function( model ){
					
					if( kc.storage[model] !== undefined && kc.storage[model].args.css_data !== undefined &&
					    kc.storage[model].args.css !== undefined && kc.storage[model].args.css.indexOf('|') === -1 
					){
						kc.storage[model].args.css += '|'+kc.storage[model].args.css_data;
					}
					
					var el = kc.detect.frame.contents.find('[data-model="'+model+'"]').get(0), 
						name = kc.storage[model].name,
						pop = kc.backbone.settings( el, { 
								scrollTo: false, 
								success_mesage: '<i class="fa-check"></i> '+kc.__.i50 
							});
					
					kc.tools.popup.callback( pop, {
						before_callback : kc.front.params.before_save, 
						after_callback : kc.front.params.save, 
						cancel : kc.front.params.cancel
					});
					
					kc.detect.clicked = true;
					
					kc.detect.frame.contents.find('.kc-boxholder, .kc-boxholder div').attr({style:''});
					
					kc.front.ui.element.smart_popup( pop, el );
					
					kc.detect.locked = true;
					
					if( kc.detect.bone.indexOf( name ) > -1 ||
						document.getElementById('tmpl-kc-'+name+'-template') === null )
							return;
					
					var lp_btn = $('<li><button class="button button-large"><input type="checkbox"> '+kc.__.i52+'</button></li>');
					pop.find('.m-p-footer .m-p-controls').append( lp_btn );
					
					if( kc.cfg.live_preview !== false )
						lp_btn.find('input').attr({checked: true});
					
					lp_btn.on('click', function( e ){
						
						if( e.target.tagName == 'INPUT' ){
							
							if( e.target.checked === true ){
								kc.cfg.live_preview = true;
								kc.get.popup( this, 'save' ).trigger('click');
								
							}else kc.cfg.live_preview = false;
							
							kc.backbone.stack.set( 'KC_Configs', kc.cfg );
							
							return;
						}
						
						if( $(this).find('input').get(0).checked === false ){
							$(this).find('input').attr({checked: true});
							kc.cfg.live_preview = true;
							kc.get.popup( this, 'save' ).trigger('click');
							
						}else{ 
							kc.cfg.live_preview = false;
							$(this).find('input').attr({checked: false});
						}
						
						kc.backbone.stack.set( 'KC_Configs', kc.cfg );
						
					});


					setTimeout( function( pop ){
						
						kc.tools.popup.callback( pop, {
							
							change : function( el ){
								
								if( kc.cfg.live_preview === false || 
									( el.classList !== undefined && el.classList.contains('m-p-rela') ) )
										return;
									
								kc.get.popup( el,'save').trigger('click');	
								
							}
						});
					}, 100, pop );
						
				},
				
				double : function( model ){
					
					var el = kc.detect.frame.contents.find('[data-model="'+model+'"]').get(0);
						code = kc.front.build_shortcode( model );
					
					if( el !== null && el !== undefined && code !== '' ){
						
						kc.model++;
						
						var callback = [],
							elm = $( kc.front.do_shortcode( code, callback ) ),wrp = el.parentNode;
						
						$( el ).after( elm );
						
						kc.detect.wrap_node( wrp );
						
						if( kc_maps_views.indexOf( kc.storage[model].name ) > -1 ){
							
							model = kc.detect.holder.sections.model;
							code = kc.front.build_shortcode( model );
							if( code !== '' ){
								kc.front.push( code, model, 'replace' );
								kc.detect.untarget();
								return;
							}
							
						}
						
						if( callback.length > 0 )
							kc.do_callback( callback, elm.eq(1) );
						
						kc.front.element_vs_ajax();
						kc.detect.untarget();
						
						return elm;
						
					}
					
					return null;
					
				},
				
				copy : function( model ){
					
					var content = kc.front.build_shortcode(model),
						admin_view = '<strong>Copy from live-editor</strong>', 
						lm = 0, stack = kc.backbone.stack,
						page = 'live editor', list = stack.get( 'KC_ClipBoard' ), ish;
	
					if( list.length > kc.cfg.limitClipboard - 2 ){
	
						list = list.reverse();
						var new_list = [];
						for( var i = 0; i < kc.cfg.limitClipboard-2; i++ ){
							new_list[i] = list[i];
						}
	
						stack.set( 'KC_ClipBoard', new_list.reverse() );
	
					}
	
					stack.clipboard.add( {
						page	: page,
						content	: kc.tools.base64.encode( content ),
						title	: kc.storage[model].name,
						des		: admin_view
					});
					
					// Push to row stack & OS clipboard
					kc.backbone.stack.set( 'KC_RowClipboard', content );
					kc.tools.toClipboard( content );
					
					kc.detect.untarget();
					$('body').append('<div id="kc-small-notice"><i class="fa-check"></i> Copy successful!</div>');
					$('#kc-small-notice').animate({opacity:1}).delay(1000).animate({opacity:0}, function(){$(this).remove();});
					
				},
				
				add : function( el ){
				
					var pop = kc.backbone.add( el );
					
					if( $( el ).closest('.mpb-bottom').length > 0 )
						pop.data({ pos: 'bottom' });
					else pop.data({ pos: 'top' });
					
					kc.detect.clicked = true;
					kc.detect.locked = true;
						
					pop.find( 'ul.kc-components-list-main li').off('click').on( 'click', function(){
						
						var model = kc.get.model( this ),
						name = $(this).data('name'),
						full = kc.front.ui.element.from_map( name );
							
						var fid = kc.front.push( full, model, kc.get.popup(this).data('pos')  );
						
						$(this).closest('.kc-params-popup').find('.m-p-header .sl-close.sl-func').trigger('click');
						
						kc.detect.untarget();
						kc.front.element_vs_ajax();
						
						var last = kc.detect.frame.doc.querySelectorAll('[data-model="'+fid+'"] [data-model]'), mol;
						
						if( last.length > 0 ){
							for( var i = 0; i < last.length; i++  ){
								mol = last[i].getAttribute('data-model');
								if( mol != '-1' && kc.storage[ mol ] !== undefined &&
									['kc_row','kc_row_inner','kc_column','kc_column_inner'].
										indexOf( kc.storage[ mol ].name ) === -1 
									){ fid = mol; break; }
							}	
						}
						
						if( kc.detect.bone.indexOf( kc.storage[fid].name ) === -1 )
							kc.front.ui.element.edit( fid );
							
					});
				
				},
				
				from_map : function( name ){
					
					var maps = kc.maps[name],
					map_params = kc.params.merge( name ),
					content = ( typeof( kc.maps[name].content ) != 'undefined' ) ? kc.maps[name].content : '',
					full = '['+name;

					for( var i in map_params ){

						if( map_params[i].type == 'random' ){

							full += ' '+map_params[i].name+'="'+parseInt(Math.random()*1000000)+'"';

						}else if( !_.isUndefined( map_params[i].value ) ){
							if( map_params[i].name == 'content' && maps.is_container === true ){
								content = map_params[i].value;
							}else{
								full += ' '+map_params[i].name+'="'+map_params[i].value+'"';
							}
						}
					}

					if( name == 'kc_wp_widget' )
						full += ' data="'+$(this).data('data')+'"';
					
					full += ']';
					
					if( name == 'kc_row_inner' ){
						content += '[kc_column_inner][/kc_column_inner]';
					}
					
					if( maps.is_container === true ){
						full += content+'[/'+name+']';
					}
					
					return full;
	
				},
				
				smart_popup : function( pop, el ){
					
					if( el.tagName == 'KC' && el.querySelectorAll('*').length > 0  )
						el = el.querySelectorAll('*')[0];
					
					var coor = el.getBoundingClientRect(),
						ctop = $(kc.detect.frame.window).scrollTop() + coor.top - 50;
					
					if( (coor.width + coor.left + pop.width()) < ( $(window).width() - 50 ) ){
						pop.css( { left : (coor.width + coor.left + 50 )+'px', top : '25px' } );
					}else if( pop.width() < coor.left - 50 ){
						pop.css( { left : ( coor.left - pop.width() - 50 )+'px', top : '25px' } );
					}else{
						if( (coor.width/2) + coor.right < ( $(window).width()/2 ) )
							pop.css( { right : 'auto', left : '50px', top : '25px' } );
						else
							pop.css( { left : 'auto', right : '50px', top : '25px' } );
					}
					
					pop.find('.wp-pointer-arrow').remove();
					kc.detect.frame.$('html,body').stop().animate({ scrollTop : ctop });
					
					var curent_size = $('#kc-curent-screen-view').html();
					if( curent_size != '100%' && parseInt( curent_size ) <= 768 ){
						
						if( pop.width() + parseInt(curent_size) + 20 > $(window).width() )
							curent_size = $(window).width() - pop.width();
						else curent_size = parseInt(curent_size) + 20;
							
						pop.css({ left: curent_size+'px' });
					
					}
					
				},
				
				add_section : function(){
					
					var shortcode = '', 
						model = kc.detect.holder.sections.model;
					
					if( model !== null && kc.storage[ model ] !== undefined ){
						
						shortcode += '['+kc.storage[ model ].name;
						
						for( var n in kc.storage[ model ].args ){
							if( n !== 'content' )
								shortcode += ' '+n+'="'+kc.tools.esc_attr( kc.storage[ model ].args[n] )+'"';
						}
						shortcode += ']';
						
						kc.front.export( model );
						
						kc.storage[ model ].args.content += 
							kc.front.ui.element.from_map( kc.maps[ kc.storage[ model ].name ]['views']['sections'] );
						
						if( kc.storage[ model ].args.content !== undefined && kc.storage[ model ].end !== undefined ){
							shortcode += kc.storage[ model ].args.content+kc.storage[ model ].end;
						}
					}
					
					kc.front.push( shortcode, model, 'replace' );
					
					kc.detect.untarget();
					
				},
				
			},
			
			scrollAssistive : function( ctop, eff ){

				if( kc.cfg.scrollAssistive != 1 )
					return false;

				if( typeof ctop == 'object'  ){
					ctop = kc.detect.frame.$(ctop).get(0);
					if( ctop ){
						
						if( ctop.tagName === 'KC' && ctop.querySelectorAll('*').length > 0 )
							ctop = ctop.querySelectorAll('*')[0];
							
						var coor = ctop.getBoundingClientRect();
						ctop = (coor.top+kc.detect.frame.$(kc.detect.frame.window).scrollTop()-100);
						
					}
				}
				
				if( undefined !== eff && eff === false )
					kc.detect.frame.$('html,body').scrollTop( ctop );
				else kc.detect.frame.$('html,body').stop().animate({ scrollTop : ctop });

			},
			
			style : {
				
				sheet : null,
				
				remove : function( selector ){
					
					for( var i in this.sheet.cssRules ){
						if( this.sheet.cssRules[i].selectorText == selector.trim() ){
							if( this.sheet.removeRule )
								this.sheet.removeRule ( i );
							else if( this.sheet.deleteRule )
								this.sheet.deleteRule ( i );
						}
							
					}
					
				},
				
				add : function( selector, rule ){
					
					if( this.sheet.addRule ){
				        this.sheet.addRule( selector, rule );
				    } else if( this.sheet.insertRule ){
				        this.sheet.insertRule(selector + ' { ' + rule + ' }', this.sheet.cssRules.length);
				    }
				    
				},
				
				get : function( selector ){
					
					for( var i in this.sheet.cssRules ){
						if( this.sheet.cssRules[i].selectorText == selector.trim() ){
							return {
								sheet : this.sheet.cssRules[i],
								index : i,
								css : this.sheet.cssRules[i].cssText
							}
						}	
					}
					
					return null;
					
				},
				
				process : function( atts ){
					
					if( atts['css'] !== undefined ){
						
						var css = atts['css'].split('|');
						
						if( css[1] !== undefined ){
							kc.front.ui.style.add( '.'+css[0], css[1] );
						}
						
					}
					
				},
				
				responsive : function( rules, selector ){
					
					rules = JSON.parse( kc.tools.base64.decode( rules ) );
					
					var screen = '', css = '', string = '', brc = ';', offset = '', width = '';
					
					for( var i in rules ){
						
						css = '';
						
						if( rules[i]['screen'] !== undefined && rules[i]['screen'] !== '' ){
							
							screen = rules[i]['screen']
							
							if( screen == 'custom' ){
								if( rules[i]['range'] === undefined )
									continue;
									
								rules[i]['range'] = explode( '|', rules[i]['range'] );
								
								if( rules[i]['range'][1] !== '' && rules[i]['range'][1] !== undefined )
									screen = '(min-width: '+rules[i]['range'][0]+') and (max-width: '+rules[i]['range'][1]+')';
								else continue;
							}
							
							screen = '@media only screen and '+screen.replace( /[^0-9a-z\(\)\:\-\,\.\ ]/g, '' );
							
							if( rules[i]['important'] !== undefined && 
								rules[i]['important'] !== '' && 
								rules[i]['important'] == 'yes' )
									brc = ' !important;';
							
							if( rules[i]['offset'] !== undefined && rules[i]['offset'] !== '' ){
								
								offset = parseInt( rules[i]['offset'] );
								
								if( offset > 0 && offset < 11 ){
									offset = (offset/12)*100;
									css += 'margin-left:'+offset+'%'+brc;
								}
							}
							
							if( rules[i]['columns'] !== undefined && rules[i]['columns'] !== '' ){
								
								width = parseInt( rules[i]['columns'] );
								
								if( width > 0 && width < 13 ){
									width = (width/12)*100;
									css += 'width:'+width+'%'+brc;
								}
							}
							
							if( rules[i]['display'] !== undefined && rules[i]['display'] == 'hide' )
								css += 'display:none'+brc;
								
							if( css !== '' ){
								css = screen+'{body.kingcomposer .'+selector+'{'+css+'}}';
								string += css;
							}
							
						}
					}
					
					return string;

				},
				
				update_responsive( model ){
					
					if( kc.storage[ model ] === undefined )
						return;
						
					var atts = kc.storage[ model ].args;
					if( atts['css'] === undefined )
						return;
					
					var css = atts['css'].split('|'), selector = 'kc-css-'+parseInt( Math.random()*10000 );
					
					kc.detect.frame.$('.'+css[0]).removeClass( css[0] ).addClass( selector );
						
					kc.front.ui.style.remove( css[0] );
					
					kc.storage[ model ].args.css = selector+'|'+css[1];
					
					kc.front.ui.style.add( selector, css[1] );
					kc.front.ui.style.responsive( atts['responsive'], selector );
					
					
					
				}
				
			},
			
			process_tab_titles : function( data ){
				
				var regx = /kc_tab\s([^\]\#]+)/gi,
					split = /([a-zA-Z0-9\-\_]+)="([^"]+)+"/gi,
					title = '', adv_title = '', html = '';
				
				while ( result = regx.exec( data._content ) ) {
				
					if( result[0] !== undefined && result[0] !== '' ){
						var atts = [], agrs;
						while( agrs = split.exec( result[0]) ){
							atts[ agrs[1] ] = agrs[2];
						}
						
						title = '';
						adv_title = '';
						if ( atts['title'] !== undefined && atts['title'] !== '' )
							title = atts['title'];
				
						if( atts['advanced'] !== undefined && atts['advanced'] !== '' ){
								
								if( atts['adv_title'] !== undefined && atts['adv_title'] !== '' )
									adv_title = kc.tools.base64.decode( atts['adv_title'] );
									
								var icon=icon_class=image=image_id=image_url=image_thumbnail=image_medium=image_large=image_full='';
								var svurl = kc_ajax_url+'?action=kc_get_thumbn&id=';
								
								if( atts['adv_icon'] !== undefined && atts['adv_icon'] !== '' ){
									icon_class = atts['adv_icon'];
									icon = '<i class="'+atts['adv_icon']+'"></i>';
								}
								
								if( atts['adv_image'] !== undefined && atts['adv_image'] !== '' ){
									image_id = atts['adv_image'];
									image_url = image_full = svurl+image_id+'&size=full';
									image_medium = svurl+image_id+'&size=medium';
									image_large = svurl+image_id+'&size=large';
									image_thumbnail = svurl+image_id+'&size=thumbnail';
									image = '<img src="'+image_url+'" alt="" />';
								}
								
								adv_title = adv_title.replace( /\{icon\}/g, icon ).
											  replace( /\{icon_class\}/g, icon_class ).
											  replace( /\{title\}/g, title ).
											  replace( /\{image\}/g, image ).
											  replace( /\{image_id\}/g, image_id ).
											  replace( /\{image_url\}/g, image_url ).
											  replace( /\{image_thumbnail\}/g, image_thumbnail ).
											  replace( /\{image_medium\}/g, image_medium ).
											  replace( /\{image_large\}/g, image_large ).
											  replace( /\{image_full\}/g, image_full ).
											  replace( /\{tab_id\}/g, atts['tab_id'] );
								
								html += '<li>'+adv_title+'</li>';
									
							}else{ 
							if ( atts['icon'] !== undefined && atts['icon'] !== '' )
								title = '<i class="'+atts['icon']+'"></i> '+title;	
							html += '<li><a href="#'+atts['tab_id']+'">'+title+'</a></li>';
							
						}
				
					}
							
				}

				return html;
					
			},
			
			tour : function(){
				
				if(  kc.cfg.tour !== undefined && kc.cfg.tour === 'nope' )
					return;
				
				$('#kc-tour-show').html('<img src="'+$('#kc-tours #kc-tour-nav li[data-media]').first().data('media')+'" />');
				$('#kc-tours').css({display: 'block'});
				
				kc.trigger({
					el : $('#kc-tours'),
					events : {
						'#kc-tour-nav li:click' : 'nav',
						'#kc-tour-nope:click' : 'nope',
						'#kc-tour-close a.tour-next:click' : 'next',
						'#kc-tour-close a.tour-prev:click' : 'prev',
						'#kc-tour-close i.fa-times:click' : function(){
							$('#kc-tours').css({display: 'none'});
							$('body').removeClass('kc-ui-blur');
						},
						'click' : function(e){
							if( e.target.id == 'kc-tours' ){
								$('#kc-tours').css({ display : 'none' });
								$('body').removeClass('kc-ui-blur');
							}
						}
					},
					nav : function(e){
						if( $(this).data('media') !== undefined ){
							$('#kc-tours #kc-tour-nav li.active').removeClass('active');
							$(this).addClass('active');
							$('#kc-tour-show img').attr({ src : this.getAttribute('data-media') });
						}
					},
					nope : function(e){
						$('#kc-tours').css({display: 'none'});
						$('body').removeClass('kc-ui-blur');
						kc.cfg.tour = 'nope';
						kc.backbone.stack.set( 'KC_Configs', kc.cfg );
						e.preventDefault();
					},
					next : function(e){
						if( $('#kc-tours #kc-tour-nav li.active').next().data('media') !== undefined )
							$('#kc-tours #kc-tour-nav li.active').next().trigger('click');
						else $('#kc-tours #kc-tour-nav li[data-media]').first().trigger('click');
						e.preventDefault();
					},
					prev : function(e){
						if( $('#kc-tours #kc-tour-nav li.active').prev().data('media') !== undefined )
							$('#kc-tours #kc-tour-nav li.active').prev().trigger('click');
						else $('#kc-tours #kc-tour-nav li[data-media]').last().trigger('click');
						e.preventDefault();
					}
				});
				
			},
			
			ask_to_buy : function(){
				
				$('body').addClass('kc-ui-blur');
				kc.trigger({
					el: $('#kc-preload').html( $('#kc-as-to-buy').html() ),
					'events' : {
						'a.close:click': 'close',
						'a.verify:click': 'verify'
					},
				
					close: function(){
						$('body').removeClass('kc-ui-blur');
						$('#kc-preload').remove();
					},
					verify: function( e ){
						
						$('#kc-preload .kc-preload-body').append('<div class="pl-loading"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>');
						
						var sercurity = e.data.el.find('input[name="sercurity"]').val(),
							license = e.data.el.find('input[name="kc-license-key"]').val().toString();
						
						if( license.length !== 41 ){
							e.data.el.find('p.notice').attr({'class':'notice error'}).html('Your license code is invalid.');
							e.data.el.find('.pl-loading').remove();
							return;
						}
							
						jQuery.post(
		
							kc_ajax_url,
						
							{
								'action': 'kc_verify_license',
								'security': sercurity,
								'license': license
							},
						
							function (result) {
								
								if( result == -1 || result == 0 || result.stt == -1 || result.stt == 0 ){
									e.data.el.find('p.notice').attr({'class':'notice error'}).html('Invalid security session! Please reload the page and try again.');
									e.data.el.find('.pl-loading').remove();
									return;
								}else if( result == -2 ){
									e.data.el.find('p.notice').attr({'class':'notice error'}).html('Your license code is invalid (code -2)');
									e.data.el.find('.pl-loading').remove();
									return;
								}else{
									e.data.el.find('.pl-loading').remove();
									if( result.stt == 1 ){
										e.data.el.find('p.notice').attr({'class':'notice success'}).html('<i class="sl-check" style="font-size: 40px;"></i><br /><br />Your domain has been actived successful');
										e.data.el.find('#kc-preload-footer, input').remove();
										e.data.el.find('h3').html('Congratulation!');
										$('body').append('<script>'+result.code+'</script>');
									}else{
										e.data.el.find('p.notice').attr({'class':'notice error'}).html( result.stt );			
									}
								}
							}
						);
						
					}
				});
			}
			
		},
		
		push : function( full, model, pos ){
			
			var callback = [], elm;
			
			if( model === undefined || kc.storage[ model ] === undefined ){
				full = full.toString().trim();
				// push before kc-footer
				if( full.indexOf('[kc_row ') !== 0 && full.indexOf('[kc_row]') !== 0 )
					full = '[kc_row][kc_column width="12/12"]'+full+'[/kc_column][/kc_row]';
				elm = $( kc.front.do_shortcode( full, callback ) );
				kc.detect.frame.contents.find('#kc-footers').before( elm );
				kc.detect.wrap_node( kc.detect.frame.body );
			
			}else{
				
				elm = kc.detect.frame.$( kc.front.do_shortcode( full, callback ) );
				
				var wrp = kc.detect.frame.$('[data-model="'+model+'"]').get(0);
				if( wrp !== undefined ){
					
					if( pos == 'replace' ){
						
						var pwrp = wrp.parentNode;
						
						$(wrp).after( elm ).remove();
						
						kc.detect.wrap_node( pwrp );
						
					}else{	
					
						var items = wrp.querySelectorAll('[data-model]');
						
						if( items.length > 0 ){
							
							if( pos == 'top' ){
								$( items[0] ).before( elm );
							}else{
								$( items[ items.length - 1 ] ).after( elm );
							}
							
							kc.detect.wrap_node( wrp );
							
						}
					}
				}
			}
			
			if( callback.length > 0 )
				kc.do_callback( callback, elm.eq(1) );
			
			kc.front.element_vs_ajax();
			
			var fid = elm.get(0);
			if( fid.nodeType === 8 )
				fid = fid.data.replace( /[^0-9]/g,'' );
			else fid = elm.data('model');
			
			elm = kc.detect.frame.$('[data-model="'+fid+'"]');

			if( pos != 'replace' ){
				kc.front.ui.scrollAssistive( elm );
				elm.addClass('kc-bounceIn');
				setTimeout( function( target ){ target.removeClass('kc-bounceIn'); }, 1200, elm );
			}
			
			return fid;
			
		},
		
		export : function( model ){
			
			var string = '', _$ = kc.detect.frame.$;
			if( model !== null && kc.storage[ model ] !== undefined ){
				
				if( _$('[data-model="'+model+'"]').find('[data-model]').length > 0 ){
						
					var checked = [], fm;
						
					_$('[data-model="'+model+'"]').find('[data-model]').each(function(){
						fm = this.getAttribute('data-model');
						
						if( fm !== null && fm !== '-1' && checked.indexOf( fm ) === -1 && kc.front.check_parent( this, model ) === true ){
							string += kc.front.build_shortcode( fm );
							checked.push( fm );
						}
					});	
					
					kc.storage[ model ].args.content = string;
				
				}else string = kc.storage[ model ].args.content;
				
			}else{
				_$('[data-model]').first().parent().find(' > [data-model]').each(function(){
					string += kc.front.export( _$(this).data('model') );
				});
			}
			
			return string;
				
		},
		
		check_parent : function( el, model ){
			
			el = el.parentNode;
			
			while( el !== null && el !== undefined ){
				if( el.getAttribute('data-model') !== null &&  el.getAttribute('data-model') !== '-1' ){
					if(  el.getAttribute('data-model') == model )
						return true;
					else return false;
				}
				el = el.parentNode;
			}
			return false;
		},
		
		clean_storage : function( model ){
			
			var el = kc.detect.frame.$('[data-model="'+model+'"]').get(0)
			
			if( el !== undefined ){
				
				var model = el.getAttribute( 'data-model' ), css;
				
				if( kc.storage[ model ] !== undefined ){
					if( kc.storage[ model ].args !== undefined && kc.storage[ model ].args.css !== undefined ){
						css = kc.storage[ model ].args.css.split('|')[0];
						kc.front.ui.style.remove( '.'+css );
					}
					delete kc.storage[ model ];
				}
				
				var els = el.querySelectorAll('[data-model]');
				for( var i = 0; i < els.length; i++ ){
					kc.front.clean_storage( els[i].getAttribute('data-model') );
				}
			}
		},
		
		element_vs_ajax : function(){
			
			var _$ = kc.detect.frame.$;
			_$('.kc-loadElement-via-ajax').each(function(){
				
				if( _$(this).data('is_loaded') === true )
					return;
				else _$(this).data({ 'is_loaded' : true });
				
				_$.post( kc_ajax_url, {

					'action' : 'kc_load_element_via_ajax',
					'model' : $(this).data('model'),
					'ID' : (kc_post_ID !== undefined) ? kc_post_ID : 0,
					'code' : kc.tools.base64.encode( kc.front.build_shortcode( $(this).data('model') ) )
					
				}, function (result) {
					
					if( typeof( result ) != 'object' || result.model === undefined )
						return;
					
					var elm = _$(result.html), wrp = _$('[data-model="'+result.model+'"]').parent();
					_$('div[data-model="'+result.model+'"]').after( elm ).remove();
					
					elm.data({ model: result.model });
					
					kc.detect.wrap_node( wrp.get(0) );
					
					if( result.callback !== undefined && typeof( result.callback ) == 'object' ){
						for( var i in result.callback )
							result.callback[i].model = result.model;
						kc.do_callback( result.callback, _$('[data-model="'+result.model+'"]') );
						
					}
					
					if( result.css !== undefined && result.css !== '' ){
						_$('[data-model="'+result.model+'"]').append('<style type="text/css">'+result.css+'</style>');
					}
						
				});
			
			});
			
		}
	 
	}

	$( document ).ready( function(){
		
		if( kc.init_front_ready === true )
			kc.front.init();
		
		if(  kc.cfg.tour === undefined || kc.cfg.tour !== 'nope' )
		{
			$('body').addClass('kc-ui-blur');
		}
			
	});
	
	$( window ).on( 'load', function(){
		
		if(  kc.cfg.tour === undefined || kc.cfg.tour !== 'nope' )
		{
			$('#kc-preload>i.fa').remove();
			$('#kc-preload>#kc-welcome').css({display: 'block'});
			kc.trigger({
				el: $('#kc-preload>#kc-welcome'),
				events : {
					'.tour:click' : function(e){
						$('#kc-preload').remove();
						kc.cfg.tour = '';
						kc.front.ui.tour();
						e.preventDefault();
					},
					'.nope:click' : function(e){
						$('#kc-tours').css({display: 'none'});
						$('body').removeClass('kc-ui-blur');
						$('#kc-preload').remove();
						kc.cfg.tour = 'nope';
						kc.backbone.stack.set( 'KC_Configs', kc.cfg );
						e.preventDefault();
					},
					'.enter:click' : function(e){
						$('#kc-preload').remove();
						$('body').removeClass('kc-ui-blur');
					},
					'.verify:click' : function(e){
						$('#kc-preload').remove();
						$('body').removeClass('kc-ui-blur');
						$('#kc-front-save').trigger('click');
					}
				}
			});
		}else $('#kc-preload').remove();
		
	});

} )( jQuery );
