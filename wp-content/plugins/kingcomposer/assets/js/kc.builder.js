/*
 * King Composer Project
 *
 * (c) Copyright king-theme.com
 *
 * Must obtain permission before using this script in any other purpose
 *
 * kc.builder.js
 *
*/

( function ( $ ) {
	
	if( typeof( kc ) == 'undefined' )
		window.kc = {};
		
	window.kc = $.extend( {

		ver 	: '0',
		auth	: 'king-theme.com',
		model 	: 1,
		tags	: '',
		storage	: [],
		maps	: {},
		views	: {},
		params	: {},
		tools	: {},
		mode 	: '',
		widgets : null,
		changed : false,
		live_preview : true,
		ready	: [],
		__		: {},
		cfg		: {
			version : 0,
			limitDeleteRestore : 10,
			limitClipboard : 9,
			sectionsPerpage : 5,
			scrollAssistive : 1,
			preventScrollPopup : 1,
			instantSave : 1,
			showTips : 1,
			columnDoubleContent : 'checked',
			columnKeepContent : 'checked',
			profile : 'King Composer',
			profile_slug : 'king-composer',
			sectionsLayout : 'grid',
			mode : '',
			defaultImg : plugin_url+'/assets/images/get_start.jpg'
		},

		init	: function(){
			
			if( typeof( kc_maps ) == 'undefined' )
				return;
				
			this.tags = shortcode_tags;

			this.maps = kc_maps;

			this.cfg = $().extend( this.cfg, this.backbone.stack.get('KC_Configs') );
			
			if( typeof( kc_js_languages ) == 'object' )
				this.__ = kc_js_languages;
			
			this.ui.init();

			$('#kc-switch-builder').on( 'click', kc.switch );

			$('#post').on( 'submit', this.submit );

			this.widgets = $( this.template('wp-widgets') );

			if( $('#kc-post-mode').length > 0 )
				this.cfg.mode = $('#kc-post-mode').val();

			if( this.cfg.mode == 'kc' ){
				kc.switch( true );
			}
			
			this.ready.forEach( function( func ){

				if( typeof func == 'function' )
					func( this );
			});
			
			$('#postdivrich').removeClass('first-load');

		},

		backbone : {

			views : function( ismodel ) {

				this.ismodel = ismodel;
				this.el = null;
				this.events = null;
				this.render = function( params, p1, p2, p3, p4, p5 ){

					var rended =  this._render( params, p1, p2, p3, p4, p5 );

					if( this.el === null )
						this.el = rended;

					if( typeof this.events == 'object' ){
						kc.trigger( this );
					}

					if( this.ismodel != 'no-model' ){
						var id = kc.model++;
						rended.attr({id:'model-'+id}).addClass('kc-model').data({ 'model' : id });
						params = $().extend( $().extend( { args : {}, model : id }, params ));
						kc.storage[ id ] = params;
					}

					return rended;

				};
				this.extend = function( obj ){
					for( var i in obj ){
						if( i == 'render' ){
							this._render = obj.render;
						}else{
							this[i] = obj[i];
						}
					}
					return this;
				};

			},

			save : function( pop ){

				var mid = pop.data('model');

				if( mid !== undefined ){

					if( kc.storage[ mid ] ){

						var datas = kc.tools.getFormData( pop.find('form.fields-edit-form .kc-param') ),
							prev = {},
							hidden = [],
							map_params = kc.params.merge( kc.storage[ mid ].name );

						pop.find('form.fields-edit-form .kc-param-row').each(function(){
							if( $(this).hasClass('relation-hidden') ){
								$(this).find('.kc-param').each(function(){
									hidden.push( this.name );
								});
							}
						});
						
						for( var name in datas ){

							if( typeof( name ) == 'undefined' || name === '' )
								continue;

							if( hidden.indexOf( name ) > -1 )
								datas[name] = '';

							if( datas[name] !== '' )
							{
								if( typeof datas[name] == 'object' ){
									if( typeof( datas[name][0] ) == 'string' && datas[name][0] == '' )
										delete datas[name][0];
										
								 	datas[name] = kc.tools.base64.encode( JSON.stringify( datas[name] ) );
								 	
								}
								prev[ name ] = datas[name];

							}
							else if( hidden.indexOf( name ) == -1 )
							{
								if( typeof( map_params ) == 'object' )
								{
									for( var p in map_params )
									{
										/* if has default value, save empty too */
										if( map_params[p].name == name && 
											typeof( map_params[p].value ) != 'undefined' && 
											map_params[p].value !== '' && 
											typeof( prev[ name ] ) == 'undefined' )
										{
											prev[ name ] = '__empty__';
										}
									}
								}
							}

							if( datas[name] === '' && typeof( prev[ name ] ) == 'undefined' )
							{
								 if( typeof( kc.storage[ mid ].args[ name ] ) == 'undefined' )
								 	continue;
								 else delete kc.storage[ mid ].args[ name ];
							}
							else
							{

								kc.storage[ mid ].args[ name ] = prev[ name ];

								if( name == 'content' && !_.isUndefined(kc.storage[ mid ].end)  ){

								}else{
									kc.storage[ mid ].args[ name ] =
									kc.tools.esc_attr( kc.storage[ mid ].args[ name ] );
								}
							}
						}

						/*Render css (if exist)*/
						kc.params.fields.css_box.save( pop );
						
						delete map_params;
						
						kc.changed = true;

					}
				}
			},

			/* View Events */

			settings : function( e, atts ){
			
				if( e === undefined )return;

				var el = ( typeof( e.tagName ) != 'undefined' ) ? e : this;

				var mid = kc.get.model( el ),
					data = kc.storage[ mid ],
					popup = kc.tools.popup;

				if( kc.maps[ data.name ] === undefined )
					return false;

				var map = $().extend( {}, kc.maps['_std'] );
				map = $().extend( map, kc.maps[ data.name ] );

				if( map.title === undefined )
					map.title = map.name+' Settings';
				
				var attz = { title: map.title, width: map.pop_width, scrollBack: true, class: data.name+'_wrpop', footer_text: kc.__.i47 };
				if( atts !== undefined )
					attz = $.extend( attz, atts );
				
				var pop = popup.render( el, attz );

				pop.data({ model: mid, callback: kc.backbone.save });
				
				var form = $('<form class="fields-edit-form kc-pop-tab form-active"></form>'), tab_icon = 'et-puzzle';
				
				if( map.params[0] !== undefined ){
				
					kc.params.fields.render( form, map.params , data.args );
				
				}else {
				
					for( var n in map.params ){
						
						if( typeof( map.tab_icons ) != 'undefined' && map.tab_icons[ n ] !== undefined )
							tab_icon = map.tab_icons[ n ];
						
						popup.add_tab( pop, {
							title: '<i class="'+tab_icon+'"></i> '+n,
							class: 'kc-tab-general-'+kc.tools.esc_slug(n),
							cfg: n+'|'+mid+'|'+data.name,
							callback:  kc.params.fields.tabs
						});
					}
					
					pop.find('.m-p-wrap>.kc-pop-tabs>li').first().trigger('click');
					
				}
				
				kc.ui.preventScroll( pop.find('.m-p-body').append( form ) );

				if( map.css_box === true )
				{
					popup.add_tab( pop,
					{
						title: '<i class="et-adjustments"></i> Box Style',
						class: 'kc-tab-visual-css-title',
						callback:  kc.params.fields.css_box.visual
					});
					popup.add_tab( pop,
					{
						title: '<i class="et-search"></i> CSS Code',
						class: 'kc-tab-code-css-title',
						callback:  kc.params.fields.css_box.code
					});

				}
				
				delete groups;
				delete map;

				return pop;

			},

			double : function( e ){

				var el = ( typeof( e.tagName ) != 'undefined' ) ? e : this;

				var id = kc.get.model( el ),
					exp = kc.backbone.export( id ),
					data = kc.storage[id],
					cdata = $().extend( true, {}, data ),
					cel, func;
					if( data.name != 'kc_column_text' )
						cdata.args.content = kc.params.process_alter( exp.content, data.name );

					el = $('#model-'+id);

				cdata.model = kc.model++;

				if( data.name == 'kc_row' ){
					cel = kc.views.row.render( cdata, true );
				}else if( data.name == 'kc_column' ){
					cel = kc.views.column.render( cdata, true );
				}else if( kc.tags.indexOf( cdata.name ) ){
					try{
						func = kc.maps[ cdata.name ].views.type;
					}catch( ex ){
						func = cdata.name;
					}
					if( typeof kc.views[ func ] == 'object' )
						cel = kc.views[ func ].render( cdata );
					else cel = kc.views.kc_element.render( cdata );

				}else{
					cel = kc.views.
							  kc_undefined
						  	  .render({
				  				  args: { content: cdata.content },
								  name: 'kc_undefined',
								  end: '[/kc_undefined]',
								  full: cdata.content
							  });
				}

				el.after( cel );

				if( el.height() > 300 && !el.hasClass('kc-column') )
					$('html,body').scrollTop( $(window).scrollTop()+el.height() );

				kc.ui.sortInit();

				return cel;

			},

			add	 : function( e ){

				var el = ( typeof( e.tagName ) != 'undefined' ) ? e : this;

				var atts = { title: kc.__.i02, width: 950, class: 'no-footer', scrollBack : true };
				var pop = kc.tools.popup.render( el, atts );

				var pos = 'top';
				if( $(el).closest('.pos-bottom').get(0) )
					pos = 'bottom';

				pop.data({ model : kc.get.model(el), pos : pos });

				pop.find('h3.m-p-header').append(

					$('<input type="search" class="kc-components-search" placeholder="'+kc.__.i03+'" />')
						.on('keyup', function(e){

							if( this.timer === true ){
								setTimeout(function(el){
									el.timer = false;
								}, 100, this);
								return;
							}else{
								this.timer = true;
							}

							$('#kc-clipboard,#kc-wp-widgets-pop').hide();
							$('#kc-components .kc-components-list-main').show();

							$('#kc-components .kc-components-categories .active').removeClass('active');
							var key = this.value.toLowerCase();
							var list = $('#kc-components .kc-components-list-main li');
							list.css({display: 'none'});
							list.each(function(){
								var find = $(this).find('strong').html().toLowerCase();
								if( find.indexOf( key ) > -1 )
									$(this).show();
							});
						})

					).append('<i class="sl-magnifier"></i>');

				var components = $( kc.template( 'components' ) );

				pop.find('.m-p-body').append( components );

				kc.trigger({

					el: components,
					events : {
						'ul.kc-components-categories li:click' : 'categories',
						'ul.kc-components-list-main li:click' : 'items'
					},

					categories : function( e ){

						var category = $(this).data('category'), atts = {}, el;

						$(this).parent().find('.active').removeClass('active');
						$(this).addClass('active');

						$('#kc-clipboard,#kc-wp-widgets-pop').remove();

						if( $(this).hasClass('mcl-clipboard') ){

							$('#kc-components .kc-components-list-main').css({display:'none'});

							el = $( kc.template( 'clipboard', atts ) );

							$('#kc-components').append( el );

							if( typeof atts.callback == 'function' )
								atts.callback( el );

							return;

						}
						else if( $(this).hasClass('mcl-wp-widgets') ){

							$('#kc-components .kc-components-list-main').css({display:'none'});

							el = $( kc.template( 'wp-widgets-element', atts ) );
							
							$('#kc-components').append( el );

							if( typeof atts.callback == 'function' )
								atts.callback( el, e );

							return;

						}

						$('#kc-components .kc-components-list-main').show();

						if( category == 'all' ){
							$('#kc-components .kc-components-list-main li').show();
						}else{
							$('#kc-components .kc-components-list-main li, #kc-clipboard').css({display:'none'});
							$('#kc-components .kc-components-list-main .mcpn-'+category).show();
						}

					},

					items : function( e ){

						var model = kc.get.model( this ),
						name = $(this).data('name'),
						maps = kc.maps[name],
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

						if( maps.is_container === true ){
							full += content+'[/'+name+']';
						}

						var fid = kc.backbone.push( full, model, $(this).closest('.kc-params-popup').data('pos')  );

						if( fid !== null ){

							$(this).closest('.kc-params-popup').data({'scrolltop':null});

							$( '#model-'+fid+' .kc-controls>.edit' ).eq(0).trigger('click');

							kc.changed = true;

							setTimeout( function( el, pop ){

								var rect = kc.tools.popup.coordinates( el, pop.width(), pop.data('keepCurrentPopups') );

								pop.css({top: rect[0]+'px', left: rect[1]+'px'});

							}, 1000, $( '#model-'+fid+' .kc-controls>.edit' ).get(0), $('.kc-params-popup.wp-pointer-top') );

						}

						$(this).closest('.kc-params-popup').find('.m-p-header .sl-close.sl-func').trigger('click');
						
						delete map_params;
						
					}

				});
				
				return pop;

			},

			remove : function( e ){

				var el = ( typeof( e.tagName ) != 'undefined' ) ? e : this;

				var und = $('#kc-undo-deleted-element'),
					stg = $('#kc-storage-prepare'),
					elm = $('#model-'+kc.get.model(el)),
					relate = { parent: elm.parent().get(0) },

					limitRestore = 10;

				if( elm.next().hasClass('kc-model') )
					relate.next = elm.next().get(0);
				if( elm.prev().hasClass('kc-model') )
					relate.prev = elm.prev().get(0);

				var i = 1 ;
				stg.find('>.kc-model').each(function(){
					i++;
					if( i > kc.cfg.limitDeleteRestore  ){
						var id = $(this).data('model');
						delete kc.storage[ id ];
						$('#model-'+id).remove();
					}
				});

				elm.data({ relate: relate });

				stg.prepend( elm );
				und.find('span.amount').html( stg.find('>.kc-model').length );


				und.css({top:0});

				if( und.find('.do-action').data('event') === undefined ){
					
					/*Make sure add event only one time*/

					und.find('.sl-close').off('click').on('click',function(){
						$('#kc-undo-deleted-element').css({top:-132});
					});

					und.find('.do-action').off('click').on('click',function(){

						var elm = $('#kc-storage-prepare>.kc-model').first();
						if( !elm.get(0) ){
							$(this.parentNode).find('.sl-close').trigger('click');
							return false;
						}
						var relate = elm.data('relate');

						if( typeof( relate.next ) != 'undefined' ){
							$(relate.next).before( elm );
						}else if( typeof( relate.prev ) != 'undefined' ){
							$(relate.prev).after( elm );
						}else if( typeof( relate.parent ) != 'undefined' ){
							$(relate.parent).append( elm );
						}else{
							$(this.parentNode).find('.sl-close').trigger('click');
							var id = $(this).data('model');
							delete kc.storage[ id ];
							$('#model-'+id).remove();
							return false;
						}

						$('.show-drag-helper').removeClass('show-drag-helper');

						kc.ui.scrollAssistive( elm );

						var al = $('#kc-storage-prepare>.kc-model').length;

						$(this).find('span.amount').html( al );

						if( al === 0 )
							$(this.parentNode).find('.sl-close').trigger('click');

					});

					und.find('.do-action').data({'event':'added'});

				}

				kc.changed = true;

			},

			copy : function( e ){

				var el = ( typeof( e.tagName ) != 'undefined' ) ? e : this;

				var model = kc.get.model( el ),
					exp = kc.backbone.export( model ),
					admin_view = '', lm = 0, stack = kc.backbone.stack,
					list = stack.get( 'KC_ClipBoard' ),
					ish;

					$('#model-'+model+' .admin-view').each(function(){
						lm++;
						if( lm < 2 ){
							if( $(this).find('img').length === 0 ){
								ish = kc.tools.esc( $(this).text() );
								if( ish.length > 38 )
									ish = ish.substring(0, 35)+'...';
							}else if( $(this).hasClass('gmaps') ){

								ish = $(this).find('.gm-style img');
								ish = '<img src="'+ish.eq( parseInt( ish.length / 2 ) ).attr('src')+'" />';

							}else{
								ish = '<img src="'+$(this).find('img').first().attr('src')+'" />';
							}
							admin_view += '<i>'+ish+'</i>';
						}
					});

				if( list.length > kc.cfg.limitClipboard - 2 ){

					list = list.reverse();
					var new_list = [];
					for( var i = 0; i < kc.cfg.limitClipboard-2; i++ ){
						new_list[i] = list[i];
					}

					stack.set( 'KC_ClipBoard', new_list.reverse() );

				}

				var page = $('#title').val() ? kc.tools.esc( $('#title').val().trim() ) : 'King Composer',
					content = ( exp.begin+exp.content+exp.end );

				stack.clipboard.add( {
					page	: page,
					content	: kc.tools.base64.encode( content ),
					title	: kc.storage[model].name,
					des		: admin_view
				});
				
				// Push to row stack & OS clipboard
				kc.backbone.stack.set( 'KC_RowClipboard', content );
				kc.tools.toClipboard( content );

			},

			cut : function( e ){

				var el = ( typeof( e.tagName ) != 'undefined' ) ? e : this;
				kc.backbone.copy( el );

				$( el ).parent().find('.delete').trigger('click');

			},

			more : function( e ){

				var el = ( typeof( e.tagName ) != 'undefined' ) ? e : this;

				if( $(el).hasClass('active') )
					$(el).removeClass('active');
				else $(el).addClass('active');

			},

			/* End View Events */

			push : function( content, model, pos ){
			/* Push elements to grid */
				
				if( kc.front !== undefined && kc.front.push !== undefined && typeof( kc.front.push ) == 'function' ){
					return kc.front.push( content, model, pos );
				}
				
				kc.changed = true;

				if( model !== undefined && model !== null && document.getElementById( 'model-'+model ) !== null ){

					var fid = kc.params.process_all( content, $('#model-'+model+' > .kc-column-wrap') );

					kc.ui.sortInit();

					if( pos == 'top' )
						$( '#model-'+fid ).parent().prepend( $( '#model-'+fid ) );

					kc.ui.scrollAssistive( $( '#model-'+fid ) );

					return fid;

				}else{

					kc.params.process_shortcodes( content, function( args ){
						kc.views.row.render( args );
					}, 'kc_row' );

					var target = $('#kc-rows .kc-column-wrap').not('.ui-sortable').last();
					if( !target.hasClass('.kc-row') )
						target = target.closest('.kc-row');

					kc.ui.scrollAssistive( target );
					target.addClass('kc-bounceIn');
					setTimeout( function( target ){target.removeClass('kc-bounceIn');}, 1200, target );

					kc.ui.sortInit();

				}

				return null;


			},

			extend : function( obj, ext, accept ){

				if( accept === undefined )
					accept = [];

				if( typeof ext != 'object' ){
					return ext;
				}else{
					for( var i in ext ){
						if( accept.indexOf( i ) > -1 || accept.length === 0 ){
							/*Except jQuery object*/
							if( ext[i].selector !== undefined )
								obj[i] = ext[i];
							else obj[i] = kc.backbone.extend( {}, ext[i] );
						}
					}
					return obj;
				}
			},

			export : function( id, ignored ){

				var storage = kc.storage[id];
				if( _.isUndefined(storage) )
					return null;

				if( _.isUndefined( ignored ) )
					ignored = [];

				var name = storage.name;

				if( name == 'kc_undefined' )
					return { begin: '', content: kc.storage[id].args.content, end : '' };

				if( typeof storage.end == 'string' ){
					while( ignored.indexOf( storage.name ) > -1 ){
						storage.name += '#';
						storage.end = '[/'+storage.name+']';
					}
				}

				var el = $('#model-'+id),
					_begin = '['+storage.name,
					_content = '',
					_end = '';

				if( _.isUndefined(storage.name) )
					return storage.full;

				for( var n in storage.args ){
					if( n == 'content' &&  !_.isUndefined(storage.end) ){
						// stuff
					}else{
						_begin += ' '+n+'="'+storage.args[n]+'"';
					}
				}

				_begin += ']';
				if( typeof storage.end == 'string' ){
					/* shortcode container */
					ignored[ignored.length] = storage.name;
					if( storage.name == 'kc_column_text' ){
						_content = kc.storage[id].args.content;
					}else{
						var wrp = el.find('.kc-model').first().parent();
						wrp.find('> .kc-model').each(function(){
							var mid = $(this).data('model');
							if( !_.isUndefined(mid) ){
								var _exp = kc.backbone.export(mid, $().extend( [], ignored ));
								_content += _exp.begin+_exp.content+_exp.end;
							}
						});
						kc.storage[id].args.content = _content;
					}
					_end = '[/'+storage.name+']';
					kc.storage[id].end = '[/'+name+']';
				}

				kc.storage[id].name = name;

				return { begin: _begin, content: _content, end : _end };

			},

			stack : {

				clipboard : {

					sort : function(){

						var list = [];

						$('#kc-clipboard>.ms-list>li').each(function(){

							list[ list.length ] = $(this).data('sid');

						});

						kc.backbone.stack.sort( 'KC_ClipBoard', list );

					},

					add : function( obj ){

						var stack = kc.backbone.stack.get( 'KC_ClipBoard' ), istack = [], i = -1;

						if( typeof stack == 'object' ){
							if( stack.length > kc.cfg.limitClipboard ){
								for( var n in stack ){
									i++;
									if( stack.length-i < kc.cfg.limitClipboard )
										istack[ istack.length ] = stack[n];
								}
								kc.backbone.stack.set( 'KC_ClipBoard', istack );
							}
						}

						kc.backbone.stack.add( 'KC_ClipBoard', obj );

					}

				},

				sections : {


				},

				add : function( sid, obj ){

					if( typeof(Storage) !== "undefined" ){

					    var stack = this.get(sid);

						if( stack === '' )
							stack = [];
						else if( typeof stack != 'object' )
							stack = [stack];

						stack[ stack.length ] = obj;

					    this.set( sid, stack );

					} else {
					    alert( kc.__.i04 );
					}

				},

				update : function( sid, key, value ){

					if( typeof(Storage) !== "undefined" ){

					    var stack = this.get(sid);

						if( stack === '' )
							stack = {};
						else if( typeof stack != 'object' ){
							var ist = {}; ist[sid] = stack; stack = ist;
						}

						stack[key] = value;

					    this.set( sid, stack );

					} else {
					    alert( kc.__.i04 );
					}

				},

				get : function( sid, index ){

					if( typeof( Storage ) !== "undefined" ){

						var data = localStorage[ sid ], dataObj;
						if( data === undefined )
							return '';

						data = data.toString().trim();

						if( data !== undefined && data !== '' && ( data.indexOf('[') === 0 || data.indexOf('{') === 0 ) ){
							try{
								dataObj =  JSON.parse( data );
							}catch(e){
								dataObj = data;
							}
							if( index === undefined )
								return dataObj;
							else if( dataObj[index] !== undefined )
								return dataObj[index];
							else return '';

						}else return data;

					}else {
					    alert( kc.__.i04 );
					    return '';
					}

				},

				set : function( sid, obj ){

					if( typeof obj == 'object' )
						obj = JSON.stringify( obj );

					localStorage.removeItem( sid );
					localStorage.setItem( sid, obj );

				},

				sort : function( sid, list ){

					var stack = this.get( sid ), istack = [];

					for( var n in list ){
						if( stack[ list[n] ] !== undefined )
							istack[ istack.length ] = stack[ list[n] ];
					}

					this.set( sid, istack );

				},

				remove : function( sid, id ){

					var stack = this.get( sid );
					delete stack[id];

					this.set( sid, stack );

				},

				reset : function( sid ){

					var stack = this.get( sid ), istack = [];

					if( stack === '' ){
						this.clear( sid );
					}else{
						for( var i in stack ){
							if( stack[i] !== null )
								istack[ istack.length ] = stack[i];
						}
					}
					this.set( sid, istack );
				},

				clear : function( sid ){

					if( typeof(Storage) !== "undefined" ){

						localStorage.removeItem( sid );

					}else {
					    alert( kc.__.i04 );
					    return {};
					}
				}

			}

		},

		trigger : function( obj ) {

			var func;
			for( var ev in obj.events )
			{
				if( typeof obj.events[ev] == 'function' )
					func = obj.events[ev];
				else if( typeof obj[obj.events[ev]] == 'function' )
					func = obj[obj.events[ev]];
				else if( typeof kc.backbone[obj.events[ev]] == 'function' )
					func = kc.backbone[obj.events[ev]];
				else return false;

				ev = ev.split(':');

				if( ev.length == 1 )
					obj.el.off(ev[0]).on( ev[0], func );
				else
					obj.el.find( ev[0] ).off(ev[1]).on( ev[1], obj, func );

			}
		},

		template : function( name, atts ){

			var _name = '_'+name;

			if( this[ _name ] == 'exit' )
				return null;
			
			if( this[ _name ] === undefined ){
				if( document.getElementById('tmpl-kc-'+name+'-template') )
					this[ _name ] = wp.template( 'kc-'+name+'-template' );
				else{
					this[ _name ] = kc.ui.get_tmpl_cache( 'tmpl-kc-'+name+'-template' );
				}
			}

			if( atts === undefined )
				atts = {};

			if( typeof this[ _name ] == 'function' )
				return this[ _name ]( atts );

			return null;

		},

		ui : {

			elm_start : null, elm_drag : null, elm_over : null, over_delay : false, over_timer : null, key_down : false,
			/* This is element clicked when mousedown on builder */

			init : function(){

				kc.body = document.querySelectorAll('body')[0];
				kc.html = document.querySelectorAll('html')[0];

				$( document ).on( 'mousedown', function( e ){ kc.ui.elm_start = e.target; } );

				$( window ).on( 'scroll', document.getElementById('major-publishing-actions'), kc.ui.publishAction );

				if( kc.cfg.instantSave == 1 ){
					
					$( window ).on( 'keydown', function(e) {
						
						if( kc.cfg.instantSave === 1 && 
							e.keyCode === 83 && 
							(navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)
						){
 							e.preventDefault();
 							kc.instantSubmit();
 							e.stopPropagation();
 							return false;
 						}
						else if( e.keyCode === 13  ){
							// enter
							
							var last = $('.kc-params-popup').
								not('.no-footer').
								find('>.m-p-wrap>.m-p-header>.sl-check.sl-func').
								last(),
								posible = true,
								el = e.target;
							
							while( el !== undefined && el.parentNode ){
								
								el_type = ( el.tagName !== undefined ) ? el.tagName : '';
								
								if( el_type == 'TEXTAREA' || $(el).attr('contenteditable') == 'true' ){
									posible = false;
									break;
								}
								el = el.parentNode;
							}
							
							if( last.length > 0 && posible === true ){
							
								last.trigger('click');
									
								e.preventDefault();
								e.stopPropagation();
								
								return false;
							
							}
							
						}else if( e.keyCode === 27 ){
							// esc
							
							$('.kc-params-popup').
								find('>.m-p-wrap>.m-p-header>.sl-close.sl-func').
								last().trigger('click');
								
							e.preventDefault();
							e.stopPropagation();
							
							return false;
							
						}

					});
				}

			},

			sortInit : function(){

				setTimeout( function(){

					/*Sort elements*/
					kc.ui.sortable({

					    items : '.kc-element.kc-model,.kc-views-sections.kc-model,.kc-row-inner.kc-model,.kc-element.drag-helper',
					    connecting : true,
					    handle : '>ul>li.move,>div.kc-element-control',
					    helper : ['kc-ui-handle-image', 25, 25 ],
					    detectEdge: 80,

					    start : function( e, el ){

						    $('#kc-undo-deleted-element').addClass('drop-to-delete');

						    var elm = $(el), relate = { parent: elm.parent().get(0) };

							if( elm.next().hasClass('kc-model') )
								relate.next = elm.next().get(0);
							if( elm.prev().hasClass('kc-model') )
								relate.prev = elm.prev().get(0);

							elm.data({ relate2 : relate });

					    },

					    end : function(){
						    $('#kc-undo-deleted-element').removeClass('drop-to-delete');
					    }

				    });

					/*Trigger even drop to delete element*/
					if( document.getElementById('drop-to-delete').draggable !== true ){

						var dtd = document.getElementById('drop-to-delete');

						dtd.setAttribute('droppable', 'true');
				        dtd.setAttribute('draggable', 'true');

				        var args = {

					        dragover : function( e ){
						        this.className = 'over';
						        e.preventDefault();
					        },

					        dragleave : function( e ){
						         this.className = '';
					        },

					        drop : function( e ){

						        this.className = '';
						        $('#kc-undo-deleted-element').removeClass('drop-to-delete');

						        if( kc.ui.elm_drag !== null ){

							        var atts = $( kc.ui.elm_drag ).data('atts');

							        $( kc.ui.elm_drag )
							        	.removeClass( atts.placeholder )
								        .find('li.delete')
								        .first()
								        .trigger('click');

								    $( kc.ui.elm_drag ).data({ relate : $( kc.ui.elm_drag ).data( 'relate2' ) });

							        e.preventDefault();

						        }
					        }

				        };

				        for( var ev in args )dtd.addEventListener( ev, args[ev], false);

					}

					/*Sort Rows*/
					kc.ui.sortable({

						items : '#kc-rows>.kc-row',
						vertical : true,
					    connecting : false,
						handle : '>ul>li.move',
						helper : ['kc-ui-handle-image', 25, 25 ],

						start : function(){
							$('#kc-rows').addClass('sorting');
						},

						end : function(){
							$('#kc-rows').removeClass('sorting');
						}

					});

					/*Sort Columns*/
					kc.ui.sortable({

						items : '.kc-column,.kc-column-inner',
						vertical : false,
					    connecting : false,
						handle : '>.kc-column-control',
						helper : ['kc-ui-handle-image', 25, 25 ],
						detectEdge : 'auto',
						start : function(e, el){
							$(el).parent().addClass('kc-sorting');
						},

						end : function(e, el){
							$(el).parent().removeClass('kc-sorting');
						}
					});

				}, 100 );

			},

			sortable_events : {

				mousedown : function( e ){

					if( window.chrome !== undefined || this.draggable === true )
						return;

					var atts = $(this).data('atts'), handle;

					if( atts.handle !== undefined && atts.handle !== '' ){

						handle = $( this ).find( atts.handle );

						if( handle.length > 0 ){
							if( e.target == handle.get(0) || $.contains( handle.get(0), e.target ) ){
								this.draggable = true;
								kc.ui.sortable_events.dragstart(e);
							}
						}
					}

				},

				dragstart : function( e ){

					/**
					*	We will get the start element from mousedown of columnsResize
					*/

					if(  kc.ui.elm_start === null ){
						e.preventDefault();
						return false;
					}

					kc.ui.over_delay = true;

					var atts = $(this).data('atts'), handle, okGo = false;

					if( atts.handle !== '' && atts.handle !== undefined ){

						handle = $( this ).find( atts.handle );

						if( handle.length > 0 ){
							if( kc.ui.elm_start == handle.get(0) || $.contains( handle.get(0), kc.ui.elm_start ) )
								okGo = true; else okGo = false;

						}else okGo = false;

					}else okGo = true;

					if( okGo === true ){

						$('body').addClass('kc-ui-dragging');

						/* Disable prevent scroll -> able to roll mouse when drag */
						if( $(this).closest('.kc-prevent-scroll').length > 0 ){
							$(this).closest('.kc-prevent-scroll').off('mousewheel DOMMouseScroll');
						}

						if( atts.helperClass !== '' ){
							if( $( kc.ui.elm_start ).closest( atts.items ).get(0) == this ){
								$( kc.ui.elm_start ).closest( atts.items ).addClass( atts.helperClass );
							}
						}

						kc.ui.elm_drag = this;

				        e.dataTransfer.effectAllowed = 'move';
				        e.dataTransfer.dropEffect = 'none';

				        if( e.dataTransfer !== undefined && typeof  e.dataTransfer.setData == 'function' )
				        	e.dataTransfer.setData('text/plain', 'KingComposer.Com');

					    if( typeof atts.helper == 'object' && e.dataTransfer !== undefined && typeof  e.dataTransfer.setDragImage == 'function' ){
									e.dataTransfer.setDragImage(
										document.getElementById( atts.helper[0] ),
										atts.helper[1],
										atts.helper[2]
									);
							}

						if( typeof atts.start == 'function' )
							atts.start( e, this );

					}else{

						var check = kc.ui.elm_start;
						while( check.draggable !== true && check.tagName != 'BODY' ){
							check = check.parentNode;
						}

						if( check == this ){

							e.preventDefault();
							return false;

						}

					}

				},

				dragover : function( e ){

					var u = kc.ui;

					if( u.elm_drag === null ){

						e.preventDefault();
						return false;

					}
					
					if( u.over_delay === false ){
						
						if( u.over_timer === null )
							u.over_timer = setTimeout( function(){ kc.ui.over_delay = true;kc.ui.over_timer = null; }, 50 );
						
						return false;
						
					}else u.over_delay = false;
					
					u.elm_over = this;

					var oatts = $(this).data('atts'), atts = $( u.elm_drag ).data('atts');

					if(!e) e = window.event;

					if( this == u.elm_drag || $.contains( u.elm_drag, this ) || oatts.items != atts.items ){

						// prevent actions when hover it self or hover its children
						e.preventDefault();
						return false;

					}else{

						var rect = this.getBoundingClientRect();

						if( atts.connecting === false && this.parentNode != u.elm_drag.parentNode ){
							e.preventDefault();
							return false;
						}

						var detectEdge = atts.detectEdge;

						if( atts.vertical === true ){

							if( detectEdge === undefined || detectEdge == 'auto' || detectEdge > (rect.height/2) )
								detectEdge = (rect.height/2);

							if( (rect.bottom-e.clientY) < detectEdge ){

								if( this.nextElementSibling != u.elm_drag ){

									$(this).after( u.elm_drag );
									if( atts.preventFlicker !== false )
										kc.ui.preventFlicker( e, u.elm_drag );

								}

								if( typeof atts.over == 'function' )
									atts.over( e, this );

							}else if( (e.clientY-rect.top) < detectEdge ){

								if( this.previousElementSibling != u.elm_drag ){
									$(this).before( u.elm_drag );
									if( atts.preventFlicker !== false )
										kc.ui.preventFlicker( e, u.elm_drag );
								}

								if( typeof atts.over == 'function' )
									atts.over( e, this );

							}

						}else{

							if( detectEdge === undefined || detectEdge == 'auto' || detectEdge > (rect.width/2) )
								detectEdge = (rect.width/2);

							if( (rect.right-e.clientX) < detectEdge ){

								if( this.nextElementSibling != u.elm_drag )
									$(this).after( u.elm_drag );

								if( typeof atts.over == 'function' )
									atts.over( e, this );

							}else if( (e.clientX-rect.left) < detectEdge ){

								if( this.previousElementSibling != u.elm_drag )
									$(this).before( u.elm_drag );

								if( typeof atts.over == 'function' )
									atts.over( e, this );

							}

						}

					}

					e.preventDefault();
					return false;
				},

				drag : function( e ){

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

				dragleave : function( e ){

					var atts = $(this).data('atts');

					if( typeof atts.leave == 'function' )
						atts.leave( e, this );

					e.preventDefault();
					return false;
				},

				dragend : function( e ){

					var atts = $(this).data('atts');

					$(this).removeClass( atts.helperClass );
					$(this).removeClass( atts.placeholder );

					/*Enable back prevent scroll of popup body*/
					if( $(this).closest('.kc-prevent-scroll').length > 0 ){
						kc.ui.preventScroll( $(this).closest('.kc-prevent-scroll') );
					}

					kc.ui.elm_drag = null;
					kc.ui.elm_over = null;
					kc.ui.elm_start = null;

					kc.ui.key_down = false;

					$('body').removeClass('kc-ui-dragging');


					if( typeof atts.end == 'function' )
						atts.end( e, this );

					e.preventDefault();
					return false;

				},

				drop : function( e ){

					var atts = $(this).data('atts');

					if( typeof atts.drop == 'function' )
						atts.drop( e, this );

					e.preventDefault();
					return false;

				}


			},

			/*
			*
			* (c) copyright by king-theme.com
			* Must obtain permission before using in any other purpose
			*
			*/

			sortable : function( atts ){

				atts = $().extend({

					items : '',
					handle : '',
					helper : '',
					helperClass : 'kc-ui-helper',
					placeholder : 'kc-ui-placeholder',
					vertical : true,
					connecting : false,
					detectEdge: 50,
					preventFlicker: false,

				}, atts );

				if( atts.items === '' )
					return;


				var elms = document.querySelectorAll( atts.items );

				[].forEach.call( elms, function( el ){

					if( el.draggable !== true ){

				        el.setAttribute('droppable', 'true');
				        el.setAttribute('draggable', 'true');

				        $(el).data({ atts : atts });

				        for( var ev in kc.ui.sortable_events )el.addEventListener( ev, kc.ui.sortable_events[ev], false);

			        }

				});

			},

			draggable : function( el, handle ){

				var args = {

					mousedown : function( e ){
						
						if( e.which !== undefined && e.which !== 1 )
							return false;

						if( this.handle !== '' && this.handle !== undefined ){
							if( e.target != $(this).find(this.handle).get(0) && $(e.target).closest(this.handle).length === 0 ){
								return false;
							}
						}

						$('html,body').addClass('kc_dragging noneuser');

						var rect = this.getBoundingClientRect(),
							scroll = kc.ui.scroll(),
							left = scroll.left + rect.left,
							top = scroll.top + rect.top - kc.html.offsetTop;

						$(this).css({ position: 'absolute', top: top+'px', left: left+'px' });

						this.pos = [(e.clientY-rect.top), (e.clientX-rect.left)];

						$(document).off('mousemove').on( 'mousemove', this, function(e){

							var scroll = kc.ui.scroll(),
								left = e.clientX + scroll.left,
								top = e.clientY + scroll.top - kc.html.offsetTop;

							e.data.style.top = (top-e.data.pos[0])+'px';
							e.data.style.left = (left-e.data.pos[1])+'px';

						});

						$( window ).off('mouseup').on('mouseup', function(){
							$(document).off('mousemove');
							$(window).off('mouseup');
							$('html,body').removeClass('kc_dragging noneuser');
						});
						
					}

				};

				if( el.min_draggable !== true ){

			        el.setAttribute('min_draggable', 'true');
			        el.handle = handle;
			        for( var ev in args )el.addEventListener( ev, args[ev], false);
		        }

			},

			preventFlicker : function( e, el ){

				var rect = el.getBoundingClientRect(), st = 0;

				if( e.clientY < rect.top ){
					st = ( rect.top - e.clientY ) + (rect.height/10);
				}else if( e.clientY > (rect.top+rect.height) ){
					st = -( (  e.clientY - (rect.top+rect.height) ) + (rect.height/10) );
				}

				if( st !== 0 ){
					kc.body.scrollTop += st;
					kc.html.scrollTop += st;
				}

			},

			columnsResize : {

				load : function(){
					$('#kc-container').off('mousedown').on( 'mousedown', this.down );
				},

				down : function( e ){
					
					$('.kc-params-popup:not(.preventCancel) .m-p-header .sl-close').trigger('click');
					
					$('html,body').stop();
					
					if( e.target.className.indexOf( 'kc-add-elements-inner' ) > -1 ){
						kc.backbone.add(e.target);
						e.preventDefault();
						return false;
					}
					
					if( e.target.className.indexOf( 'column-resize' ) == -1 ){
						return;
					}

					var ge = kc.ui.columnsResize;

					$(document).on( 'mouseup', ge.up );
					$(document).on( 'mousemove', { el: e.target, left: e.clientX, offset: 1 }, ge.move );
					$('body').css({cursor:'col-resize'});
				},

				up : function(e){
					$(document).off( 'mousemove' ).off('mouseup');
					$('body').css({cursor:''});
				},

				move : function(e){

					e.preventDefault();
					e.data.offset = e.clientX-e.data.left;
					var el = $( e.data.el ).parent();

					if( e.data.offset > 38 ){

						if( e.data.el.className.indexOf('cr-left') > -1 ){
							if( el.prev().find('>.kc-column-control>.kc-column-toright').triggerHandler('click') )
								e.data.left += 77;
						}else if( e.data.el.className.indexOf('cr-right') > -1 ){
							if( el.find('>.kc-column-control>.kc-column-toright').triggerHandler('click') )
								e.data.left += 77;
						}

					}else if( e.data.offset < -38 ){

						if( e.data.el.className.indexOf('cr-left') > -1 ){
							if( el.find('>.kc-column-control>.kc-column-toleft').triggerHandler('click') )
								e.data.left -= 77;
						}else if( e.data.el.className.indexOf('cr-right') > -1 ){
							if( el.next().find('>.kc-column-control>.kc-column-toleft').triggerHandler('click') )
								e.data.left -= 77;
						}
					}
				},

			},

			views_sections : function( wrp ){

				wrp.find('>.kc-views-sections-label .section-label').off('click').on('click', wrp, function(e){

					$(this).closest('.kc-views-sections-wrap')
						   .find('>.kc-views-section.kc-model')
						   .removeClass('kc-section-active');

					$('#model-'+$(this).data('pmodel')).addClass('kc-section-active');
					e.data.find('>.kc-views-sections-label .section-label').removeClass('sl-active');
					$(this).addClass('sl-active');

				});

				wrp.find('>.kc-views-section > .kc-vertical-label').off('click').on('click', wrp, function(e){

					var itsactive = false;
					if( $(this).parent().hasClass('kc-section-active') ){
						itsactive = true;
					}

					$(this).closest('.kc-views-sections-wrap')
						   .find('>.kc-views-section.kc-model')
						   .removeClass('kc-section-active');

					if( itsactive === true )
						return;

					$(this).parent().addClass('kc-section-active');

					var coor = kc.tools.popup.coordinates( this, 100 );
					if( $(window).scrollTop() - coor[0] > 100 )
						$('html,body').scrollTop(coor[0] - 200);

				});

				var pwrp = wrp.closest('.kc-views-sections');

				if( !pwrp.hasClass('kc-views-vertical') ){

					kc.ui.sortable({

						items : 'div.kc-views-sections-label>div.section-label',
						vertical : false,

						end : function( e, el ){

							$( el ).closest('.kc-views-sections-label')
								.find('>.section-label').each(function(){
									var id = $(this).data('pmodel');
									var el = $('#model-'+id);
									el.parent().append(el);
								});

						}

					});


				}
				else{

					kc.ui.sortable({

						items : 'div.kc-views-vertical > div.kc-views-sections-wrap > div.kc-views-section',
						handle : '>h3.kc-vertical-label',
						connecting : false,
						vertical : true,
						helper : ['kc-ui-handle-image', 25, 25 ],

						start : function(e, el){
							$(el).parent().addClass('kc-sorting');
						},

						end : function(e, el){
							$(el).parent().removeClass('kc-sorting');
						}

					});

				}

			},

			clipboard : function( el ){

				kc.ui.sortable({

					items : '#kc-clipboard > ul.ms-list > li',
					connecting : false,
					vertical : false,
					placeholder : 'kc-ui-cb-placeholder',

					end : function(){
						kc.backbone.stack.clipboard.sort();
					}

				});

				el.find('>ul.ms-list>li').on( 'click', function(){
					if( $(this).hasClass('active') )
						$(this).removeClass('active');
					else $(this).addClass('active');
				});

				kc.trigger({

					el : el.find('>ul.ms-funcs'),
					list : el.find('ul.ms-list>li'),

					events : {
						'>li.select:click' : 'select',
						'>li.unselect:click' : 'unselect',
						'>li.delete:click' : 'delete',
						'>li.latest:click' : 'latest',
						'>li.paste:click' : 'paste',
						'>li.pasteall:click' : 'pasteall',
					},

					select : function( e ){
						e.data.list.addClass('active');
					},

					unselect : function( e ){
						e.data.list.removeClass('active');
					},

					delete : function( e ){

						e.data.list.each(function(){
							if( $(this).hasClass('active') ){
								kc.backbone.stack.remove( 'KC_ClipBoard', $(this).data('sid') );
								$(this).remove();
							}
						});

						kc.backbone.stack.reset( 'KC_ClipBoard' );

					},

					latest : function( e ){

						var stack = kc.backbone.stack.get('KC_ClipBoard'),
							latest = stack[stack.length-1],
							content = kc.tools.base64.decode( latest.content ),
							model = kc.get.model(this);

						if( model ){
							kc.backbone.push( content, model, $(this).closest('.kc-params-popup').data('pos') );
						}else{
							kc.backbone.push( content );
						}

						$('.kc-params-popup').remove();

					},

					pasteall : function( e ){

						var stack = kc.backbone.stack.get('KC_ClipBoard'), model = kc.get.model( this ), content = '';

						for( var n in stack ){
							if( typeof stack[n] == 'object' )
								content += kc.tools.base64.decode( stack[n].content );
						}

						content = content.trim();

						if( content === '' ){
							alert( kc.__.i05 );
							return false;
						}

						if( model ){
							kc.backbone.push( content, model, $(this).closest('.kc-params-popup').data('pos') );
						}else{
							kc.backbone.push( content );
						}

						$('.kc-params-popup').remove();

					},

					paste : function( e ){

						var stack = kc.backbone.stack.get('KC_ClipBoard'), model = kc.get.model( this ), content = '', sid;

						list = $(this).closest('#kc-clipboard').find('ul.ms-list>li.active').each(function(){

							sid = $(this).data('sid');
							if( typeof stack[sid] == 'object' )
								content += kc.tools.base64.decode( stack[sid].content );

						});

						content = content.trim();

						if( content === '' ){
							alert( kc.__.i06 );
							return false;
						}

						if( model ){
							kc.backbone.push( content, model, $(this).closest('.kc-params-popup').data('pos') );
						}else{
							kc.backbone.push( content );
						}

						$('.kc-params-popup').remove();

					}

				});


			},

			gsections : {

				load : function( label, from, to  ){

					var stg = kc.backbone.stack.get('KC_GlobalSections'), html = '';

					return kc.template( 'global-sections', { stg: stg, from: from, to: to, label: label } );

				},

				load_more : function( e ){

					var label = $(this).data('label'),
						from = $(this).data('from'),
						to = $(this).data('to'),
						wrp = $(this).closest('.mgs-select-wrp');

					$(this).after( kc.ui.gsections.load( label, from, to ) ).remove();

					wrp.find('.mgs-select-wrp .load-more')
							 .off( 'mouseover')
							 .on( 'mouseover', kc.ui.gsections.load_more );

					setTimeout(function(){ $('.mgs-scale-min').removeClass('mgs-scale-min');}, 100 );

				},

				refresh : function( active ){

					var arg = {};

					if( $( '.section-manager-popup.page-sections-manager' ).length > 0 )
						arg.list = 'no';

					var sections = $( kc.template( 'install-global-sections', arg ) );

					$('.section-manager-popup .m-p-body').html( sections );

					if( typeof arg.callback == 'function' )
						arg.callback( sections );

					// Refresh list for sections manager page
					if( typeof( kc_sections_load ) != "undefined" && typeof kc_sections_load == 'function' )
						kc_sections_load();

					if( active !== undefined )
						$( '.section-manager-popup .m-p-body .'+active ).trigger( 'click' );

				},

				get_cats : function(){

					var stg = kc.backbone.stack.get('KC_GlobalSections'), cats = {}, cat = [], j;
					for( var i in stg ){
						if( stg[i] !== null ){
							if( stg[i].category !== null && stg[i].category !== '' ){

								clis = stg[i].category.split(',');
								for( j in clis ){
									clis[j] = clis[j].toString().trim();
									if( cats[ clis[j] ] === undefined )
										cats[ clis[j] ] = 1;
									else cats[ clis[j] ] += 1;
								}
							}
						}
					}

					return cats;

				},

				add_actions : function( wrp ){

					wrp.find('.mgs-select-wrp .mgs-scale-min').removeClass('mgs-scale-min');

					kc.trigger( {

						el: wrp,
						events : {
							'.btns .close:click' : 'close',
							'.btns .back:click' : 'back',
							'.btns .apply:click' : 'apply',
							'.mgs-create-new .mgs-category input:focus' : 'focus',
							'.mgs-create-new .mgs-category input:blur' : 'blur',
							'.mgs-create-new .mgs-category .mgs-tips li:click' : 'category',
							'.mgs-create-new .create-section:click' : 'create',
							'.mgs-select-section .filter-by-category:change' : 'filter',
							'.mgs-select-wrp:click' : 'addToSection',
							'.mgs-select-wrp .load-more:mouseover' : 'load_more'
						},

						load_more : function( e ){

							var label = $(this).data('label'),
								from = $(this).data('from'),
								to = $(this).data('to');

							$(this).after( kc.ui.gsections.load( label, from, to ) ).remove();

							$(this).closest('.mgs-select-section').find('.filter-by-category').trigger('change');

							if( e.data !== undefined ){

								e.data.el.find('.mgs-select-wrp .load-more')
										 .off( 'mouseover')
										 .on( 'mouseover', e.data, e.data.load_more );

								setTimeout(function( el ){
									el.find('.mgs-select-wrp .mgs-scale-min').removeClass('mgs-scale-min');
								}, 100, e.data.el );

							}

						},

						addToSection : function( e ){

							e.preventDefault();

							var target = e.target;
							if( target === null )
								return;

							if( $(target).hasClass('mgs-sel-sceenshot') ){

								var sid = $(target).data('sid'),
									title = $(target).closest('.mgs-section-item').find('.mgs-si-info span').html(),
									apply = $(this).closest('#kc-global-sections').find('.btns .apply');

								e.data.confirm({
									title: kc.__.tkl07,
									message: '<input type="radio" name="mgs-add-section-option" checked value="add" /> '+
											 kc.__.i08+
											 ' &nbsp; <input type="radio" name="mgs-add-section-option" value="replace" /> '+
											 kc.__.i09,
									type: 'noticed',
									el: this
								});

								apply.data({ create : sid });
							}

							if( $(target).hasClass('load-more') ){

								$(target).trigger('mouseover');

							}

						},

						filter : function(){

							var wrp = $(this).closest('.mgs-select-section'),
								sections = wrp.find('.mgs-section-item');
							if( this.value === '' ){
								sections.removeClass('forceHide');
							}else{
								sections.addClass('forceHide');
								wrp.find('.mgs-section-item.category-'+kc.tools.esc_slug(this.value)).removeClass('forceHide');
							}

						},

						close : function(){
							$(this).closest('.kc-params-popup').remove();
						},

						back : function(){
							var wrp = $(this).closest('#kc-global-sections');
							wrp.find('.mgs-create-new').css({display:'block'});
							wrp.find('.mgs-select-section').css({display:'block'});
							wrp.find('.mgs-confirmation').css({display:'none'}).attr({class:'mgs-confirmation'});
						},

						apply : function(e){

							var crp = $(this).closest('#kc-global-sections').find('.mgs-create-new'),
								title = kc.tools.esc( crp.find('.mgs-title').val() ),
								category = kc.tools.esc( crp.find('input.mgs-category').val().toString().toLowerCase() ),
								screenshot = crp.find('.mgc-cn-screenshot .kc-param').val(),
								ops = $('input[name="mgs-add-section-option"]:checked').val();

							var model = kc.get.model( this ),
								create = $(this).data('create'),
								expo = kc.backbone.export( model ),
								data = expo.begin+expo.content+expo.end,
								section = {
									title : title,
									category: category,
									screenshot: screenshot,
									data : data,
									id : create
								};

							if( section.id == 'new' ){

								section.id = parseInt( Math.random()*1000000 );

							}else{

								var stack = kc.backbone.stack.get('KC_GlobalSections');

								for( var i in stack ){

									if( stack[i].id == section.id ){

										section = stack[i];

										if( ops == 'add' )
											section.data = section.data + data;
										else
											section.data = data;

									}
								}

							}

							kc.ui.gsections.update_section( section );

							kc.get.popup( this ).remove();

						},

						focus : function( e ){
							$(this).parent().addClass('show-tips');
						},

						blur : function( e ){
							setTimeout( function(el){
								el.removeClass('show-tips');
							}, 200, $(this).parent() );
						},

						category : function( e ){

							var input = $(this).closest('.mgs-category').find('input'),
								value = input.val().toString().trim(),
								data = $(this).data('name'),
								valz = value.split(',');

							for( var i in valz )
								valz[i] = valz[i].trim();

							if( value === '' )
								input.val( data );
							else if( $.inArray( data, valz ) == -1 )
								input.val( value+', '+data );

						},

						create : function( e ){

							var crp = $(this).closest('.mgs-create-new'),
								title = crp.find('.mgs-title').val(),
								category = crp.find('input.mgs-category').val().toString().toLowerCase(),
								screenshot = crp.find('.mgc-cn-screenshot .kc-param').val(),

								apply = $(this).closest('#kc-global-sections').find('.btns .apply');

							apply.data({ create : 'new' });

							if( title === '' || category === '' ){

								var messa = kc.__.i10+': ';
								if( title === ''  )
									messa += '<strong>'+kc.__.i11+'</strong>, ';
								if( category === ''  )
									messa += '<strong>'+kc.__.i12+'</strong>';

								e.data.confirm({
									title: kc.__.i13,
									message: messa,
									type: 'fail',
									el: this
								});

								return;

							}else if( screenshot === '' ){
								e.data.confirm({
									title: kc.__.i14,
									message: kc.__.i15,
									type: 'noticed',
									el: this
								});
								return;
							}else{
								apply.trigger('click');
								return;
							}

						},

						confirm : function( atts ){

							var wrp = $(atts.el).closest('#kc-global-sections'),
							crp = wrp.find('.mgs-create-new');

							wrp.find('.mgs-confirmation').attr({ class : 'mgs-confirmation' });

							crp.css({display:'none'});
							wrp.find('.mgs-select-section').css({display:'none'});
							wrp.find('.mgs-confirmation').css({display:'block'}).addClass( atts.type );
							wrp.find('.mgs-confirmation h1').html( atts.title );
							wrp.find('.mgs-confirmation h2').html( atts.message );

						},

					});

					atts = { value 	: '', name	: 'screenshot' };
					var field = jQuery( kc.template( 'field-type-attach_image_url', atts ) );

					wrp.find('.mgs-create-new .mgc-cn-screenshot').append( field );

					if( typeof atts.callback == 'function' )
						setTimeout( atts.callback, 1, field );

					kc.ui.preventScroll( wrp.find('.mgs-select-wrp') );

				},

				install_actions : function( wrp ){

					wrp.find('.mgs-select-wrp .mgs-scale-min').removeClass('mgs-scale-min');

					kc.trigger({

						el : wrp,

						events : {
							'.mgs-confirmation .btns .back:click' : 'back',
							'.mgs-select-section .filter-by-category:change' : 'filter',
							'.mgs-select-section .filter-by-name:keyup' : 'search',
							'.mgs-select-wrp:click' : 'actions',
							'.mgs-select-wrp .load-more:mouseover' : 'load_more',
							'.mgs-layout-btns i:click' : 'layouts',
							'.mgs-menus li:click' : 'menus',
							'.mgs-download-section span.msg-download-action:click' : 'doDownload',
							'.mgs-download-section a.mgs-delete-profile:click' : 'delete_profile',
							'.mgs-download-section a.mgs-edit-profile:click' : 'edit_profile',
							'.mgs-download-section a.mgs-refresh-profile:click' : 'refresh_profile',

							'.mgs-upload-section .uploadNow:click' : 'processUploadFile',
							'.mgs-upload-section .createNew:click' : 'createNewProfile',

							'.mgs-download-section a.mgs-add-prof:click' : function( e ){
								$(this).closest('#kc-global-sections').find('.mgs-menu-upload').trigger('click');
								e.preventDefault();
							},
						},

						load_more : function( e ){

							var label = $(this).data('label'),
								from = $(this).data('from'),
								to = $(this).data('to');

							$(this).after( kc.ui.gsections.load( label, from, to ) ).remove();

							$(this).closest('.mgs-select-section').find('.filter-by-category').trigger('change');

							if( e.data !== undefined ){

								e.data.el.find('.mgs-select-wrp .load-more')
										 .off( 'mouseover')
										 .on( 'mouseover', e.data, e.data.load_more );

								setTimeout(function( el ){
									el.find('.mgs-select-wrp .mgs-scale-min').removeClass('mgs-scale-min');
								}, 100, e.data.el );

							}

						},

						actions : function( e ){

							var target = e.target;
							if( target === null )
								return;

							if( $(target).hasClass('edit-section') )
								return true;

							e.preventDefault();

							if( $(target).hasClass('mgs-sel-sceenshot') ){

								var sid = $(target).data('sid');

								var stack = kc.backbone.stack.get('KC_GlobalSections');

								for( var i in stack ){
									if( stack[i].id == sid ){
										if( stack[i].data !== undefined && stack[i].data !== '' )
											kc.backbone.push( stack[i].data );
									}

								}

								$(this).closest('.kc-params-popup').find('.m-p-header .sl-close').trigger('click');

							}else if( $(target).hasClass('load-more') ){

								$(target).trigger('mouseover');

							}


						},

						filter : function( e ){

							var wrp = $(this).closest('.mgs-select-section'),
								items = wrp.find('.mgs-section-item'),
								sections = wrp.find('.mgs-section-item'),
								i = 0;
							if( this.value === '' ){
								sections.removeClass('forceHide');
							}else{
								sections.addClass('forceHide');
								wrp.find('.mgs-section-item.category-'+kc.tools.esc_slug(this.value)).removeClass('forceHide');
							}

						},

						search : function( e ){

							clearTimeout( document.key_up );

							document.key_up = setTimeout( function( inp ){

								var items = $( inp ).closest('.mgs-select-section').find('.mgs-section-item');
								items.addClass('forceHide').removeClass('break-line');

								items.find('.mgs-si-info span').each( function(){

									if( this.innerHTML.toLowerCase().indexOf( inp.value.toLowerCase() ) > -1 )
										$(this.parentNode.parentNode).removeClass('forceHide');

								});

							}, 150, this );


						},

						confirm : function( atts ){

							var wrp = $(atts.el).closest('#kc-global-sections'),
								crp = wrp.find('.mgs-menus');

							wrp.find('.mgs-confirmation').attr({ class : 'mgs-confirmation' });

							crp.css({display:'none'});
							wrp.find('.mgs-select-section').css({display:'none'});
							wrp.find('.mgs-confirmation').css({display:'block'}).addClass( atts.type );
							wrp.find('.mgs-confirmation h1').html( atts.title );
							wrp.find('.mgs-confirmation h2').html( atts.message );

						},

						back : function(){
							var wrp = $(this).closest('#kc-global-sections');
							wrp.find('.mgs-menus').css({display:'block'});
							wrp.find('.mgs-select-section').css({display:'block'});
							wrp.find('.mgs-confirmation').css({display:'none'}).attr({class:'mgs-confirmation'});
						},

						layouts : function( e ){

							$(this).parent().find('.active').removeClass('active');
							$(this).addClass('active');

							var layout = $(this).data('layout');

							$(this).closest('.mgs-select-section')
									.find('.mgs-select-wrp')
									.attr({ class : 'mgs-select-wrp layout-'+layout });

							kc.cfg.sectionsLayout = layout;
							kc.backbone.stack.set( 'KC_Configs', kc.cfg );

						},

						delete_profile : function( e ){

							if( confirm( kc.__.i16 ) ){

								$('.m-p-body .kc-popup-loading').show();

								var slug = $( this ).data( 'slug' );

								$.post( kc_ajax_url, {

									'action': 'kc_delete_profile',
									'slug': slug,

								}, function (result) {

									$('.m-p-body .kc-popup-loading').hide();

									if( result === 0 ){

										alert( kc.__.acc );

									}else if( result.status === undefined ){
										alert( result );
									}else if( result.status == 'success' ){

										for( var i in kc_profiles ){
											if( i == slug )
												delete kc_profiles[ i ];
										}

										kc.ui.gsections.refresh( 'mgs-menu-download' );

									}else alert( result.message );

								});

							}

							e.preventDefault();

						},

						edit_profile : function( e ){

							var name = $( this ).data( 'name' ),
								new_name = prompt( kc.__.i17, name ),
								slug = $( this ).data( 'slug' );

							e.preventDefault();

							if( new_name === '' || new_name === null || new_name === undefined )
								return;

							$('.m-p-body .kc-popup-loading').show();

							$.post( kc_ajax_url, {

								'action': 'kc_rename_profile',
								'name': new_name,
								'slug': slug,

							}, function (result) {

								$('.m-p-body .kc-popup-loading').hide();

								if( result === 0 ){

										alert( kc.__.acc );

								}else if( result.status == 'success' ){

									if( kc.cfg.profile_slug == slug ){

										kc.cfg.profile = new_name;
										$('.msg-profile-label-display').html( new_name );

										kc.backbone.stack.set( 'KC_Configs', kc.cfg );

									}

									kc_profiles[ slug ] = new_name;
									kc.ui.gsections.refresh( 'mgs-menu-download' );

								}else if( result.message !== undefined ){
									alert( result.message );
								}else alert( result );

							});


						},

						refresh_profile : function( e ){


							$(this).parent().find('.msg-download-action').trigger( 'click' );

							e.preventDefault();

						},

						doDownload : function( e ){

							var name = $(this).data('path').toString().trim();

							$('.m-p-body .kc-popup-loading').show();

							$.post( kc_ajax_url, {
								'action': 'kc_load_profile',
								'name': kc.tools.esc_slug( name )
							}, function ( result ) {

								$('.m-p-body .kc-popup-loading').hide();

								if( result === 0 ){
									alert( kc.__.acc );
									return;
								}

								if( result.status === undefined ){
									alert( result );
								}else if( result.status != 'success' ){
									alert( result.message );
								}else{

									result.data = kc.tools.base64.decode( result.data );
									kc.ui.gsections.doDownloadCallback( result );

								}

							});

						},

						processUploadFile : function( e ){

							if ( window.File && window.FileReader && window.FileList && window.Blob ) {

								var input = $(this).closest('.mgs-upload-main').find('input.msg-upload-profile-input'),
									f = input.get(0).files[0];

								if( f ){

									var name = kc.tools.basename( f.name ),
										type = '';

									if( f.name.lastIndexOf( '.' ) > -1 )
										type = f.name.substring( f.name.lastIndexOf( '.' )+1 );

									if( type != 'kc' ){
										alert( kc.__.i18 );
										return;
									}

									var r = new FileReader();
								    r.onload = function(e) {

									    kc.ui.gsections.createProfile( name, e.target.result );

								    };

								    r.readAsText(f);

							    }

							} else {
								alert( kc.__.i19 );
							}

						},

						createNewProfile : function( e ){

							var input = $(this).closest('.mgs-upload-main').find('input.msg-new-profile-input'),
								name = input.val();

							if( name === undefined )
								return;

							if( name === '' ){

								input.animate({marginLeft:-10,marginRight:10}, 100)
								   .animate({marginLeft:10,marginRight:-10}, 100)
								   .animate({marginLeft:-5,marginRight:5}, 100)
								   .animate({marginLeft:3,marginRight:-3}, 100)
								   .animate({marginLeft:0,marginRight:0}, 100);

								return;
							}

							kc.ui.gsections.createProfile( name, '' );

						},

						menus : function( e ){

							$(this).parent().find('.active').removeClass('active');
							$(this).addClass('active');

							var active = $(this).data('active');

							e.data.el.find('>div').css({display:'none'});
							e.data.el.find('>.mgs-menus,>.'+active).show();

						}

					});

					wrp.find('.mgs-select-wrp').on( 'mousewheel DOMMouseScroll', function ( e ) {
					    if( kc.cfg.preventScrollPopup == 1 ){
						    var e0 = e.originalEvent,
						        delta = e0.wheelDelta || -e0.detail,
						        cu4 =  this.scrollTop;



						    this.scrollTop -= delta;

						    e.preventDefault();

						    if( cu4 == this.scrollTop  && delta < 0  )
						    	$(this).find('.load-more').trigger('mouseover');
					    }
					});

					kc.ui.preventScroll( wrp.find('.mgs-settings-section') );

				},

				createProfile : function( name, data ){

					if( name === '' ){

						return;
					}

					if( data !== undefined && data !== '' ){

						var error = false;
						try{
							error = true;
							var test = JSON.parse( data );
							if( test[0] !== undefined ){
								if( test[0].title !== undefined && test[0].data !== undefined )
									error = false;
							}
						}catch( ex ){
							error = true;
						}

						if( error === true ){
							alert( kc.__.i20 );
							return;
						}

					}

					name = name.replace(/\-/g,' ').replace(/\_/g,' ');
					slug = kc.tools.esc_slug( name );

					var is_exist = function( _slug ){

						if( typeof kc_profiles == 'object' ){
							for( var i in kc_profiles ){
								if( _slug == i ){
									return true;
								}
							}
						}

						if( typeof kc_profiles_external == 'object' ){
							for( var j in kc_profiles_external ){
								if( _slug == j ){
									return true;
								}
							}
						}

						return false;

					};

					var i = 0;
					while( is_exist( slug ) ){

						i++;
						slug = kc.tools.esc_slug( name )+'-'+i;

					}

					if( i > 0 ){
						name = name+' ('+i+')';
					}

					$('.kc-popup-loading').show();

					$.post( kc_ajax_url, {

						'action': 'kc_create_profile',
						'name': name,
						'slug': slug,
						'data': kc.tools.base64.encode( data )

					}, function (result) {

						$('.kc-popup-loading').hide();

						if( result === 0 ){
							alert( kc.__.acc );
							return;
						}else if( result.status === undefined ){
							alert( result );
							return;
						}else if( result.status != 'success' ){
							alert( result.message );
							return;
						}

						kc_profiles[ slug ] = name;

						kc.ui.gsections.doDownloadCallback({ name : name, data : data });

					});

				},

				doDownloadCallback : function( result ){

					if( result === undefined || result.name === undefined || result.name === '' )
						return;

					kc.cfg.profile = result.name;

					if( result.slug !== undefined && result.slug !== '' )
						kc.cfg.profile_slug = result.slug;
					else kc.cfg.profile_slug = kc.tools.esc_slug( result.name );

					$('.msg-profile-label-display').html( result.name.replace(/\-/g,' ').replace('.kc','') );

					kc.backbone.stack.set( 'KC_Configs', kc.cfg );
					kc.backbone.stack.set( 'KC_GlobalSections', result.data );

					/*Update list*/
					kc.ui.gsections.refresh();

					var listbtn = $('#kc-global-sections .mgs-menus .mgs-menu-list');
					if( listbtn.get(0) ){
						listbtn.trigger('click');
					}else{
						$('.kc-params-popup .sl-close.sl-func').trigger('click');
					}

				},

				update_section : function( section ){

					kc.loading( 'show', kc.__.i21 );

					$.post( kc_ajax_url, {

						'action': 'kc_update_section',
						'slug': kc.cfg.profile_slug,
						'name': kc.cfg.profile,
						'id' : section.id,
						'data' : kc.tools.base64.encode( JSON.stringify( section ) )

					}, function (result) {

						if( result === 0 ){
							alert( kc.__.acc );
						}
						else if( result.status === undefined ){
							alert( result );
						}
						else if( result.status == 'success' )
						{

							kc.cfg.profile = result.name;
							kc_profiles[ kc.cfg.profile_slug ] = result.name;
							if( kc_profiles_external[ kc.cfg.profile_slug ] !== undefined )
								delete kc_profiles_external[ kc.cfg.profile_slug ];

							kc.backbone.stack.set( 'KC_Configs', kc.cfg );
							kc.backbone.stack.set( 'KC_GlobalSections', kc.tools.base64.decode( result.data ) );

							if( typeof( kc_sections_load ) == 'function' )
								kc_sections_load();

							kc.loading( 'hide', kc.__.i22 );
							return;

						}
						else alert( result.message );

						$('#instantSaving').remove();

					});

				},

				showDownload : function( el ){

					if( kc.get.popup( el ) !== null )
					{
						kc.get.popup( el ).find('.mgs-menu-download').trigger('click');
					}
					else
					{
						$('#kc-section-settings').trigger('click');
						$('.mgs-menu-download').trigger('click');
					}
				}

			},

			scrollAssistive : function( ctop, eff ){

				if( kc.cfg.scrollAssistive != 1 )
					return false;

				if( typeof ctop == 'object'  ){
					if( $(ctop).get(0) ){
						var coor = $(ctop).get(0).getBoundingClientRect();
						ctop = (coor.top+$(window).scrollTop()-100);
					}
				}
				
				if( undefined !== eff && eff === false )
					$('html,body').scrollTop( ctop );
				else $('html,body').stop().animate({ scrollTop : ctop });

			},

			preventScroll : function( el ){
			
				if( kc.cfg.preventScrollPopup == 1 ){
						
					el.addClass('kc-prevent-scroll');

					el.off('mousewheel DOMMouseScroll').on( 'mousewheel DOMMouseScroll',
						
						function ( e ) {
							
							if( e.target !== null && ( e.target.tagName === 'OPTION' || e.target.tagName === 'SELECT' || e.target.className.indexOf('kc-free-scroll') > -1 ) )
								return true;
							
						    if( this.scrollHeight > this.offsetHeight ){
								
								if( $('body').hasClass('kc-ui-dragging') )
									return true;
									
							    var curS = this.scrollTop;
							    if( this.scrollCalc === undefined )
							    	this.scrollCalc = 0;
	
							    var e0 = e.originalEvent,
							        delta = e0.wheelDelta || -e0.detail;
								
								if( delta !== 0 ){
								
								    //this.scrollTop += ( delta <= 0 ? 1 : -1 ) * e.data.st;
								    this.scrollTop -= delta;
								    
									if( curS == this.scrollTop ){
										
										var pop = this.parentNode.parentNode,
											top = pop.offsetTop - 80,
											bottom = pop.offsetTop + ( pop.offsetHeight - window.innerHeight ) + 100;
										
										if( delta < 0 ){
											//scroll down
											
											if( kc.body.scrollTop - delta < bottom )
												kc.body.scrollTop -= delta;
											else kc.body.scrollTop = bottom;
											
											if( kc.html.scrollTop - delta < bottom )
												kc.html.scrollTop -= delta;
											else kc.html.scrollTop = bottom;
											
										}else{
											
											if( kc.body.scrollTop - delta > top )
												kc.body.scrollTop -= delta;
											else kc.body.scrollTop = top;
											
											if( kc.html.scrollTop - delta > top )
												kc.html.scrollTop -= delta;
											else kc.html.scrollTop = top;
										}
										
									}
									
								}
									
								e.preventDefault();
								e.stopPropagation();
								
								return false;
								
						    }

					});

				}
			},

			scroll : function( st ){

				if( typeof st == 'object' ){

					if( st.top !== undefined ){
						kc.body.scrollTop = st.top;
						kc.html.scrollTop = st.top;
					}

					if( st.left !== undefined ){
						kc.body.scrollLeft = st.left;
						kc.html.scrollLeft = st.left;
					}

				}else{
					return { top: (kc.body.scrollTop?kc.body.scrollTop:kc.html.scrollTop),
						 left: (kc.body.scrollLeft?kc.body.scrollLeft:kc.html.scrollLeft)};
				}
			},
			
			verify_tmpl : function(){
				
				
				var cfg = $().extend( kc.cfg, kc.backbone.stack.get('KC_Configs') );
				
				if( cfg.version != kc_version || localStorage['KC_TMPL_CACHE'] === undefined || localStorage['KC_TMPL_CACHE'] === '' ){
					
					kc.loading( 'show', 'KingComposer is installed caching' );
					
					$.post(
		
						kc_ajax_url,
		
						{
							'action': 'kc_tmpl_storage',
							'security': kc_ajax_nonce
						},
		
						function (result) {
							
							if( result != -1 && result != 0 ){
								
								cfg.version = kc_version;
								
								kc.backbone.stack.set( 'KC_Configs', cfg );
								kc.backbone.stack.set( 'KC_TMPL_CACHE', result );
								
								kc.init(); 
								
							}
							
							$('#instantSaving').remove();
							
						}
					);
				
				}else return true;
				
			},
			
			get_tmpl_cache : function( tmpl_id ){
			
				if( localStorage['KC_TMPL_CACHE'] !== undefined && localStorage['KC_TMPL_CACHE'].indexOf('id="'+tmpl_id+'"') > -1 ){
					
					var s1 = localStorage['KC_TMPL_CACHE'].indexOf('>', localStorage['KC_TMPL_CACHE'].indexOf('id="'+tmpl_id+'"') )+1,
						s2 = localStorage['KC_TMPL_CACHE'].indexOf('</script>', s1),
						string = localStorage['KC_TMPL_CACHE'].substring( s1, s2 ),
						options = {
                            evaluate:    /<#([\s\S]+?)#>/g,
                            interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
                            escape:      /\{\{([^\}]+?)\}\}(?!\})/g,
                            variable:    'data'
                        };
	
	                return _.template( string, null, options );
					
				}
				
				return 'exit';	
				
			},
			
			uncache : function(){
			
				localStorage.removeItem('KC_TMPL_CACHE');
				
			},
			
			KC_Box : {

				sort : function(){

					kc.ui.sortable({

					    items : '.kc-box:not(.kc-box-column)',
					    connecting : true,
					    handle : '>ul.mb-header',
					    helper : ['kc-ui-handle-image', 25, 25 ],
					    detectEdge: 30

				    });

				    if( window.chrome === undefined ){

						 $('.kc-box-body .kc-box-inner-text').off('mousedown').on( 'mousedown', function( e ){
								var el = this;
								while( el.parentNode ){
									el = el.parentNode;
								  	if( el.draggable === true ){
								  		el.draggable = false;
								  		el.templDraggable = true;
								  	}
								}
							}).off('blur').on( 'blur', function( e ){
								var el = this;
								while( el.parentNode ){
									el = el.parentNode;
								  	if( el.templDraggable === true ){
								  		el.draggable = true;
								  		el.templDraggable = null;
								  	}
								}
							});

					}

				},

				renderBack : function( pop ){

					var exp = kc.tools.base64.encode(JSON.stringify(
						kc.ui.KC_Box.accessNodesVisual( pop.find('.kc-box-render') )
					));

					pop.find('.kc-param.kc-box-area').val( exp );

				},

				wrapFreeText : function( el ){

					var nodes = el.childNodes, text, n, ind;

					if( nodes === undefined )
						return null;

					for( var i=0; i < nodes.length; i++ ){
						/* node text has type = 3 */
						
						n = nodes[i];
						
						if( nodes[i].nodeType == 3 ){

							if( n.parentNode.tagName != 'TEXT' && n.textContent.trim() !== '' ){

								text = document.createElement('text');

								if( n.nextElementSibling !== null )
									$( n.nextElementSibling ).before( text );
								else if( n.previousElementSibling !== null )
									$( n.previousElementSibling ).after( text );
								else n.parentNode.appendChild( text );

								text.appendChild(n);

							}
						}else{

							if( ['input', 'br', 'select', 'textarea', 'button'].indexOf( nodes[i].tagName.toLowerCase() ) > -1 ){

								ind = false;

								if( n.previousElementSibling !== null ){
									if( n.previousElementSibling.tagName == 'TEXT' ){
										$( n.previousElementSibling ).append( nodes[i] );
										ind = true;
									}
								}if( n.nextElementSibling !== null ){
									if( n.nextElementSibling.tagName == 'TEXT' ){
										$( n.nextElementSibling ).prepend( nodes[i] );
										ind = true;
									}
								}

								if( ind === false ){

									text = document.createElement('text');
									$( nodes[i] ).after(text);

									text.appendChild( nodes[i] );

								}

							}else kc.ui.KC_Box.wrapFreeText( nodes[i] );
						}
					}

					return el;

				},

				accessNodes : function( node, thru ){

					if( node === null )
						return [];

					var nodes = node.childNodes, nod, ncl, atts;

					if( thru === undefined )
						thru = [];

					if( nodes === null )
						return thru;

					for( var i=0; i < nodes.length; i++ ){
						/* node element has type = 1 */
						if( nodes[i].nodeType == 1 ){

							atts = {};

							for( var j=0; j< nodes[i].attributes.length; j++ ){
								atts[ nodes[i].attributes[j].name ] = nodes[i].attributes[j].value;
							}

							nod = {
								tag : nodes[i].tagName.toLowerCase(),
								attributes : atts,
							};

							if( nod.tag != 'text' )
								nod.children = kc.ui.KC_Box.accessNodes( nodes[i] );

							ncl = ( typeof( nodes[i].className ) != 'undefined' ) ? nodes[i].className : '';

							if( nod.tag == 'text' )
								nod.content = nodes[i].innerHTML;
							else if( nod.tag == 'img' )
								nod.tag = 'image';
							else if( ncl.indexOf('fa-') > -1 || ncl.indexOf('et-') > -1 || ncl.indexOf('sl-') > -1 )
								nod.tag = 'icon';
							else if( nod.tag == 'column' ){
								if( ncl === '' )
									ncl = 'one-one';
								['one-one','one-second','one-third','two-third'].forEach(function(c){
									if( ncl.indexOf( c ) > -1 ){
										ncl = ncl.replace( c, '').trim();
										nod.attributes.cols = c;
										nod.attributes.class = ncl;
									}
								});
							}

							thru[ thru.length ] = nod;

						}
					}

					return thru;

				},

				accessNodesVisual : function( wrp ){

					var nodes = wrp.find('>.kc-box:not(.mb-helper)'), nod, thru = [];

					if( nodes.length === 0 )
						return thru;

					nodes.each(function(){

						nod = {
								tag : $(this).data('tag'),
								attributes : $(this).data('attributes'),
								children : kc.ui.KC_Box.accessNodesVisual( $(this).find('>.kc-box-body') )
							};

						if( nod.attributes === undefined )
							nod.attributes = {};

						if( nod.tag == 'text' )
							nod.content = $(this).find('.kc-box-inner-text').html();
						else if( nod.tag == 'icon' )
							nod.attributes.class = $(this).find('>.kc-box-body i').attr('class');
						else if( nod.tag == 'image' )
							nod.attributes.src = $(this).find('>.kc-box-body img').attr('src');

						thru[ thru.length ] = nod;

					});

					return thru;

				},

				exportCode : function( visual, cols ){

					var thru = '';
					if( cols === undefined )
						cols = '';
					var incol = cols+'	', count = 0;

					visual.forEach(function(n){

						if( n.tag == 'text' ){
							if( n.content !== '' )
								thru += cols+'<text>'+n.content.trim().replace(/\<text\>/g,'').replace(/\<\/text\>/g,'')+'</text>';
						}else{
							if( n.attributes.cols == 'one-one' ){
								if( n.children.length > 0 ){
									thru += kc.ui.KC_Box.exportCode( n.children, cols );
								}
							}else{

								if( n.attributes.cols !== undefined ){
									n.attributes.class = ( n.attributes.class !== undefined ) ?
														 (n.attributes.class+' '+n.attributes.cols) : n.attributes.cols;
									delete n.attributes.cols;
								}

								thru += cols+'<'+n.tag;
								for( var i in n.attributes )
									thru += ' '+i+'="'+kc.tools.esc(n.attributes[i])+'"';
								thru += '>';
								if( n.children.length > 0 ){
									thru += "\n"+kc.ui.KC_Box.exportCode( n.children, incol )+"\n"+cols;
								}

								thru += '</'+n.tag+'>';

							}
						}
						if( count++ < visual.length-1 )
							thru += "\n";

					});

					return thru;

				},

				setColumns : function( e ){

					var el = kc.get.popup( this ).data('el').closest('.kc-box'),
						wrp = el.find('>.kc-box-body'),
						cols = $(this).data('cols').split(' '),
						objCols = wrp.find('>.kc-box.kc-box-column'),
						elms, colElm, i, j, atts;

					for( i=0; i<cols.length; i++ ){

						if( objCols.get(i) ){

							objCols
							.eq(i)
							.attr({ 'class' : 'kc-box kc-box-column kc-column-'+cols[i] })
							.data('attributes').cols = cols[i];

						}else{
							wrp.append(
								kc.template(
									'box-design', [{ tag: 'column', attributes: { cols: cols[i] } }]
								)
							);
						}
					}
					if( i<objCols.length ){

						for( j = i; j < objCols.length; j++ ){
							objCols.eq(j).find('>.kc-box-body>.kc-box:not(.mb-helper)').each(function(){
								objCols.eq(i-1).append(this);
							});
							objCols.eq(j).remove();
						}

					}

					kc.get.popup( this, 'close' ).trigger('click');

					kc.ui.KC_Box.sort();

				},

				actions : function( el, e ){

					var wrp = el.closest('.kc-param-row').find('.kc-box-render'), pos, btns, pop, cols, atts;

					switch( el.data('action') ){

						case 'add' :

							$('.kc-box-subPop').remove();
							el.closest('.mb-header').addClass('editting');
							pos = el.data('pos');
							btns = '<div class="kc-nodes">';
							pop = kc.tools.popup.render( el.get(0), {
								title: 'Select Node Tag',
								class: 'no-footer kc-nodes kc-box-subPop',
								scrollBack: true,
								keepCurrentPopups: true,
								drag: false,
							});

							pop.data({ pos: pos, el: el, cancel: function( pop ){
								pop.data('el').closest('.mb-header').removeClass('editting');
							} });

							['text','image','icon', 'div','span','a','ul','ol','li','p','h1','h2','h3','h4','h5','h6']
							.forEach(function(n){
								btns += '<button class="button">'+n+'</button> ';
							});

							btns += '</div>';

							btns = $(btns);

							pop.find('.m-p-body').append( btns );

							btns.find('button').on('click', function(e){

								var html = kc.template( 'box-design', [{ tag: this.innerHTML }] ),
									pop = kc.get.popup( this ),
									pos = pop.data('pos'),
									el = pop.data('el');

								if( pos == 'top' )
									wrp.prepend( html );
								else if( pos == 'bottom' )
									wrp.append( html );
								else if( pos == 'inner' ){
									el.closest('.kc-box:not(.mb-helper)').find('>.kc-box-body').append( html );
								}

								kc.ui.KC_Box.sort();

								kc.get.popup( this, 'close' ).trigger('click');

								e.preventDefault();
								return false;

							});

							e.preventDefault();

						break;

						case 'columns' :

							$('.kc-box-subPop').remove();
							el.closest('.mb-header').addClass('editting');
							btns = '<div class="kc-nodes">';
							pop = kc.tools.popup.render( el.get(0), {
								title: 'Select Layout - Columns',
								class: 'no-footer kc-nodes kc-columns kc-box-subPop',
								scrollBack: true,
								keepCurrentPopups: true,
								drag: false,
							});

							pop.data({ el: el, cancel: function( pop ){
								pop.data('el').closest('.mb-header').removeClass('editting');
							} });

							[['one-one','1/1'],
							 ['one-second one-second','1/2 + 1/2'],
							 ['one-third two-third','1/3 + 2/3'],
							 ['two-third one-third','2/3 + 1/3'],
							 ['one-third one-third one-third','1/3 + 1/3 + 1/3']].forEach(function(n){
								btns += '<button data-cols="'+n[0]+'" class="button '+n[0].replace(' ','')+
									'"><span>'+n[1]+'</span></button> ';
							});

							btns += '</div>';

							btns = $(btns);

							pop.find('.m-p-body').append( btns );

							btns.find('button').on('click', kc.ui.KC_Box.setColumns );

							e.preventDefault();

						break;

						case 'remove' :

							if( el.closest('.kc-box').data('tag') == 'column' ){

								if( el.closest('.kc-box').find('>.kc-box-body>.kc-box:not(.mb-helper)').length > 0 ){
									if( !confirm( kc.__.i23 ) )
										return;
								}

								cols = el.closest('.kc-box').parent().get(0);
								el.closest('.kc-box').remove();
								var _cols = $(cols).find('>.kc-box.kc-box-column'), _clas = 'one-one';

								if( _cols.length == 2 )
									_clas = 'one-second';

								_cols.each(function(){
									$(this).attr({ 'class' : 'kc-box kc-box-column kc-column-'+_clas })
										   .data('attributes').cols = _clas;
								});

								return;
							}

							var trash = el.closest('.kc-param-row').find('.kc-box-trash'),
								item = el.closest('.kc-box').get(0);

							pos = {};

							pos.parent = item.parentNode;
							if( item.nextElementSibling )
								pos.next = item.nextElementSibling;
							if( item.previousElementSibling )
								pos.prev = item.previousElementSibling;

							$(item).data({ pos : pos });

							trash.append( item );
							trash.find('a.button')
							.html('<i class="sl-action-undo"></i> '+kc.__.i24+'('+trash.find('>.kc-box').length+')')
							.removeClass('forceHide');


						break;

						case 'undo' :

							trash = el.closest('.kc-param-row').find('.kc-box-trash');
							var last = trash.find('>.kc-box').last().get(0);
							pos = $(last).data('pos');

							if( !last )
								return;

							if( pos.next !== undefined )
								$(pos.next).before( last );
							else if( pos.prev !== undefined )
								$(pos.prev).after( last );
							else if( pos.parent !== undefined )
								$(pos.parent).append( last );

							var nu = trash.find('>.kc-box').length;

							trash.find('a.button')
							.html('<i class="sl-action-undo"></i> '+kc.__.i24+'('+nu+')');

							if( nu === 0 )
								trash.find('a.button').addClass('forceHide');

							e.preventDefault();

						break;

						case 'double' :

							var clone = el.closest('.kc-box').clone(true);
							clone.attr({draggable:'',dropable:''});
							clone.find('.kc-box').attr({draggable:'',dropable:''});

							el.closest('.kc-box').after( clone );
							kc.ui.KC_Box.sort();

						break;

						case 'settings' :

							$('.kc-box-subPop').remove();
							el.closest('.mb-header').addClass('editting');
							atts = el.closest('.kc-box').data('attributes');
							pop = kc.tools.popup.render( el.get(0), {
								title: 'Node Settings',
								class: 'kc-box-settings-popup kc-box-subPop',
								scrollBack: true,
								keepCurrentPopups: true,
								drag: false,
							});

							pop.data({

								model : null,

								el: el,

								cancel : function( pop ){

									pop.data('el').closest('.mb-header').removeClass('editting');

								},

								callback : function( pop ){

									pop.data('el').closest('.mb-header').removeClass('editting');

									var el = pop.data('el').closest('.kc-box'),
										attrs = {};

									pop.find('.fields-edit-form .kc-param').each(function(){
										if( this.value !== '' )
											attrs[ this.name ] = kc.tools.esc( this.value );
									});

									kc.params.fields.css_box.save( pop );

									if( pop.data('css') !== undefined && pop.data('css') !== '' )
										attrs.style = pop.data('css');

									if( el.data('attributes').cols !== undefined )
										attrs.cols = el.data('attributes').cols;

									el.data({ attributes : attrs });

									['id','class','href'].forEach(function(n){
										if( attrs[n] !== undefined ){
											var elm = el.find('>.mb-header>.mb-'+n), str = attrs[n].substr(0,30)+'..';

											if( elm.length > 0 )
												elm.find('span').html( str ).attr({title:attrs[n]});
											else
												el.find('>.mb-header>.mb-funcs')
													.before('<li class="mb-'+n+'">'+n+
													': <span title="'+kc.tools.esc(attrs[n])+'">'+str+'</span></li>');
										}
									});

								},

								css : ( typeof( atts.style ) != 'undefined' ) ? atts.style : ''

							});

							wrp = $('<div class="fields-edit-form kc-pop-tab form-active"></div>');

							var form = $('<form class="attrs-edit"><input type="submit" class="forceHide" /></form>'),

								field = function( n, v ){
									var field = $('<div class="kc-param-row"><div class="m-p-r-label"><label>'+
										   kc.tools.esc(n)+':</label></div><div class="m-p-r-content"><input name="'+
										   kc.tools.esc(n)+'" class="kc-param" value="'+
										   v+'" style="width:90%;" type="text">'+
										   ' &nbsp; <a href="#"><i class="fa-times"></i></a></div></div>');
									field.find('a').on('click', function(e){
										$(this).closest('.kc-param-row').remove();
										e.preventDefault();
									});

									return field;

								},

								addInput = function(){

									var add = $('<div style="padding: 10px 0 10px" class="kc-param-row align-right"><div class="m-p-r-label"></div><form class="m-p-r-content">'+
									'<input style="height: 34px;width: 52.5%;" type="text" placeholder="'+kc.__.i25+'" /> '+
									'<button style="margin-right: 33px;height: 34px;" class="button button-primary">'+kc.__.i26+'</button>'+
									'<input type="submit" class="forceHide" /></form></div>');

									add.find('button').on('click', function(e){

										var input = $(this.parentNode).find('input'),
											val = input.val().replace(/[^a-z-]/g,'');

										input.val('');

										if( val === '' ||
											$(this).closest('.m-p-body').find('input[name='+val+']').length > 0 ||
											val == 'style' ){

											$(this).stop()
												  .animate({marginRight:50},100)
												  .animate({marginRight:28},100)
												  .animate({marginRight:38},80)
												  .animate({marginRight:30},80)
												  .animate({marginRight:33},50);
											return false;
										}

										$(this).closest('.kc-param-row').before( field(val,'') );

										e.preventDefault();
										return false;
									});

									add.find('form').on('submit',function(){
										$(this).find('button').trigger('click');
										return false;
									});

									return add;
								};

							form.append( field( 'id', ( typeof( atts['id'] ) != 'undefined' ) ? atts['id'] : '' ) );
							form.append( field( 'class', ( typeof( atts['class'] ) != 'undefined' ) ? atts['class'] : '' ) );

							if( el.closest('.kc-box').get(0).tagName == 'A' )
								form.append( field( 'href', ( typeof( atts['href'] ) != 'undefined' ) ? atts['href'] : '' ) );

							for( var i in atts ){
								if( i != 'id' && i != 'class' && i != 'style' && i != 'cols' )
									form.append( field( i, atts[i] ) );
							}

							wrp.append( form );
							wrp.append( addInput() );

							kc.ui.preventScroll( pop.find('.m-p-body').append( wrp ), 100 );

							form.on( 'submit', function(e)
							{
								kc.get.popup( this, 'save' ).trigger('click');
								e.preventDefault();
								return false;
							});

							kc.tools.popup.add_tab( pop,
							{
								title: '<i class="et-adjustments"></i> '+kc.__.i27,
								class: 'kc-tab-visual-css-title',
								callback:  kc.params.fields.css_box.visual
							});
							kc.tools.popup.add_tab( pop,
							{
								title: '<i class="et-search"></i> '+kc.__.i28,
								class: 'kc-tab-code-css-title',
								callback:  kc.params.fields.css_box.code
							});

						break;

						case 'editor' :

							$('.kc-box-subPop').remove();
							el.closest('.mb-header').addClass('editting');
							atts = el.closest('.kc-box').data('attributes');
							pop = kc.tools.popup.render( el.get(0), {
								title: 'Node Settings',
								class: 'kc-box-editor-popup kc-box-subPop',
								scrollBack: true,
								keepCurrentPopups: true,
								drag: false,
								width: 750
							});

							pop.data({

								model : null,

								el: el,

								cancel : function( pop ){

									pop.data('el').closest('.mb-header').removeClass('editting');

								},

								callback : function( pop ){
									
									var content = pop.find('.wp-editor-area.kc-param').val().toString().trim();
									
									//content = content.replace( /\n/g, '<br>' );
									content = switchEditors.wpautop( content );
									
									var inner = pop.data('el').closest('.kc-box').find('.kc-box-inner-text'),
										content = $( content );
									
									if( content.length === 1 && content.get(0).tagName == 'P' )    
										inner.html( content.get(0).innerHTML );
									else inner.html( content );

								}

							});

							atts = {

								value 	: el.closest('.kc-box').find('.kc-box-inner-text').html(),
								options : [],
								name	: 'content',
								type	: 'textarea_html'

							};
							field = kc.template( 'field', {
									label: '',
									content: kc.template( 'field-type-textarea_html', atts ),
									des: '',
									name: 'textarea_html',
									base: 'content'
							});

							kc.ui.preventScroll( pop.find('.m-p-body').append( field ), 100 );

							if( typeof atts.callback == 'function' ){
								/* callback from field-type template */
								setTimeout( atts.callback, 1, pop.find('.m-p-body'), $ );
							}

						break;

						case 'toggle' :

							wrp = el.closest('.kc-box');
							if( wrp.hasClass('kc-box-toggled') )
								wrp.removeClass('kc-box-toggled');
							else wrp.addClass('kc-box-toggled');

						break;

						case 'html-code' :

							$('.kc-box-html-code').remove();

							atts = {
								title: kc.__.i29,
								width: 700,
								class: 'kc-box-html-code',
								keepCurrentPopups: true,
								drag : false
							};

							pop = kc.tools.popup.render( el.get(0), atts );
							pop.data({ target: el, scrolltop: $(window).scrollTop() });

							/*Render from Visual*/
							var code = kc.ui.KC_Box.exportCode(
								kc.ui.KC_Box.accessNodesVisual(
									kc.get.popup(el).find('.kc-box-render')
								)
							);

							pop.find('.m-p-body').html('<textarea>'+code+'</textarea>');

							pop.data({ popParent : kc.get.popup( el ), callback : function( pop ){

								var code = '<div>'+pop.find('.m-p-body textarea').val().trim()+'</div>',
									visual = kc.ui.KC_Box.wrapFreeText( $( code ).get(0) ),
									items = kc.ui.KC_Box.accessNodes( visual ),
									popParent = pop.data('popParent');

								popParent.find('.kc-box-render').html(
									kc.template( 'box-design', items )
								);

								kc.ui.KC_Box.sort();

								/* Clear Trash */
								popParent.find('.kc-box-trash .kc-box').remove();
								popParent.find('.kc-box-trash>a.button').addClass('forceHide');

							} });

						break;

						case 'css-code' :

							$('.kc-box-html-code').remove();

							atts = {
								title: kc.__.i30,
								width: 700,
								class: 'kc-box-html-code',
								keepCurrentPopups: true,
								drag : false
							};

							var popParent = kc.get.popup( el );

							pop = kc.tools.popup.render( el.get(0), atts );
							pop.data({ target: el, scrolltop: $(window).scrollTop() });

							var css = popParent.find('.field-hidden.field-base-css input').val(), css_code = '';

							pop.find('.m-p-body').html('<p></p><textarea>'+kc.tools.decode_css( css )+'</textarea><i class="ntips">'+kc.__.i31+'</i>');

							var btn = $('<button class="button button-larger"><i class="sl-energy"></i> '+kc.__.i32+'</button>');

							pop.find('.m-p-body').prepend( btn );

							btn.on( 'click', function(){
								var txta = $(this).parent().find('textarea');
								txta.val( kc.tools.decode_css( txta.val() ) );
							});

							pop.data({ popParent : kc.get.popup( el ), callback : function( pop ){


								var css = kc.tools.encode_css( pop.find('textarea').val() );

								pop.data('popParent').find('.field-hidden.field-base-css input').val( css );

							} });

						break;

						case 'icon-picker' :

							$('.kc-icons-picker-popup,.kc-box-subPop').remove();

							var listObj = $( '<div class="icons-list noneuser">'+kc.tools.get_icons()+'</div>' );

							atts = { title: 'Select Icons', width: 600, class: 'no-footer kc-icons-picker-popup kc-box-subPop', keepCurrentPopups: true };
							pop = kc.tools.popup.render( el.get(0), atts );
							pop.data({ target: el, scrolltop: jQuery(window).scrollTop() });

							var search = $( '<input type="search" class="kc-components-search" placeholder="Search by Name" />' );
							pop.find('.m-p-header').append(search);
							search.after('<i class="sl-magnifier"></i>');
							search.data({ list : listObj });

							search.on( 'keyup', listObj, function( e ){

								clearTimeout( this.timer );
								this.timer = setTimeout( function( el, list ){
									var sr;
									if( list.find('.seach-results').length === 0 ){

										sr = $('<div class="seach-results"></div>');
										list.prepend( sr );

									}else sr = list.find('.seach-results');

									var found = ['<span class="label">'+kc.__.i33+'</span>'];
									list.find('>i').each(function(){

										if( this.className.indexOf( el.value.trim() ) > -1 &&
											found.length < 14 &&
											$.inArray( this.className, found )
										)found.push( '<span data-icon="'+this.className+'"><i class="'+this.className+'"></i>'+this.className+'</span>' );

									});
									if( found.length > 1 ){
										sr.html( found.join('') );
										sr.find('span').on('click', function(){
											var tar = kc.get.popup(this).data('target');
											tar.find('i').attr({ class : $(this).data('icon') });
											kc.get.popup(this,'close').trigger('click');
										});
									}
									else sr.html( '<span class="label">'+kc.__.i34+'</span>' );

								}, 150, this, e.data );

							});

							listObj.find('i').on('click', function(){

								var tar = kc.get.popup(this).data('target');
								tar.find('i').attr({class:this.className});
								kc.get.popup(this,'close').trigger('click');

							});

							setTimeout(function( el, list ){
								el.append( list );
							}, 10, pop.find('.m-p-body'), listObj );

						break;

						case 'select-image' :

							var media = kc.tools.media.open({ data : { callback : function( atts ){

								var url = atts.url;

								if( atts.size !== undefined && atts.size !== null && atts.sizes[atts.size] !== undefined ){
									url = atts.sizes[atts.size].url;
								}else if( typeof atts.sizes.medium == 'object' ){
									url = atts.sizes.medium.url;
								}

								if( url !== undefined && url !== '' ){

									el.attr({ src : url });

								}
							}, atts : {frame:'post'} } } );

							media.$el.addClass('kc-box-media-modal');

						break;
					}

					if( el.hasClass('kc-box-toggled') &&  el.hasClass('kc-box') )
						el.removeClass('kc-box-toggled');

					e.preventDefault();
					return false;

				}

			},

			elms : function( e, el ){

				var type = $( el ).data('type'),
					cfg = $( el ).data('cfg'),
					value = '';

				if( e.target.tagName == 'LI' && type == 'radio' ){

					var wrp = $(e.target).parent();
					wrp.find('.active').removeClass('active');
					wrp.find('input[type="radio"]').attr({checked:false});
					$(e.target).addClass('active');

					value = $(e.target).find('input[type="radio"]').attr({checked:true}).val();

				}

				if( type == 'select' ){
					value = el.value;
				}

				if( value !== '' && cfg !== '' && cfg !== undefined ){
					kc.cfg[ cfg ] = value;
					kc.backbone.stack.set( 'KC_Configs', kc.cfg );
				}

			},

			publishAction : function( e ){

				if( e.data )
				{
					var rect = e.data.getBoundingClientRect();
					var sctop = $( window ).scrollTop();
					if( e.data.sctop === undefined )
						e.data.sctop = rect.top + sctop;

					if( e.data.sctop < sctop + 35 )
						$( e.data ).addClass('float_publish_action');
					else
						$( e.data ).removeClass('float_publish_action');
				}

			}

		},

		get : {

			model : function( el ){

				var id = $(el).data('model');
				if( id !== undefined && id !== -1 )
					return id;
				else if( el.parentNode ){
					if( el.parentNode.id != 'kc-container' )
						return this.model( el.parentNode );
					else
						return null;
				}else return null;
			},

			storage : function( el ){
				return kc.storage[ this.model(el) ];
			},

			maps : function( el ){
				return kc.maps[ this.storage(el).name ];
			},

			popup : function( el, btn ){

				var pop = $(el).closest('.kc-params-popup');

				if( pop.length === 0 )
					return null;

				if( btn == 'close' )
					return pop.find('.m-p-header .sl-close.sl-func');
				else if( btn == 'save' )
					return pop.find('.m-p-header .sl-check.sl-func');
				else return pop;

			}

		},

		submit : function(){

			kc.changed = false;

			$('#kc-post-mode').val( kc.cfg.mode );

			if( kc.cfg.mode != 'kc' )
					return;

			$('#kc-container').find('form,input,select,textarea').remove();

			var content = '';
			$('#kc-container > #kc-rows > .kc-row').each(function(){
				var exp =  kc.backbone.export( $(this).data('model') );
				content += exp.begin+exp.content+exp.end;
			});
				
			if( content === '' && !confirm( kc.__.i53 ) )
				return false;
				
			$('#content').val(content);
			try{
				tinyMCE.get('content').setContent( content );
			}catch(ex){}

		},

		instantSubmit : function(){

			if( kc.curentContentType !== undefined &&  kc.curentContentType == 'kc-sections' ){
				$('#publishing-action button').trigger('click');
				return;
			}

			if( $('#instantSaving').length > 0 || kc.cfg.mode != 'kc' )
				return;

			if( $('#post').length === 0 || $('#title').length === 0 || $('#post_ID').length === 0 )
				return;

			kc.loading( 'show', kc.__.i35 );

			document.raw_title = document.title;
			document.title = 'Saving...';

			var list = $('.kc-params-popup .sl-check.sl-func, .kc-params-popup .save-post-settings');
			if( list.length > 0 ){
				for( var i = list.length - 1; i>=0; i-- )
					list.eq(i).trigger('click');
			}

			$('.kc-params-popup .kc-pop-tabs>li').first().trigger('click');


			var content = '', id = $('#post_ID').val(), title = $('#title').val(), css = $('#kc-page-css-code').val(), classes = $('#kc-page-body-classes').val();

			$('#kc-container > #kc-rows > .kc-row').each(function(){
				var exp =  kc.backbone.export( $(this).data('model') );
				content += exp.begin+exp.content+exp.end;
			});

			$.post(

				kc_ajax_url,

				{
					'action': 'kc_instant_save',
					'security': kc_ajax_nonce,
					'title': title,
					'id': parseInt( id ),
					'content': content,
					'classes': classes,
					'css': css
				},

				function (result) {

					document.title = document.raw_title;
					if( result == '-1' )
						kc.loading( 'hide', 'Error: secure session is invalid. Reload and try again' );
					else if( result == '-2' )
						kc.loading( 'hide', 'Error: Post not exist' );
					else if( result == '-3' )
						kc.loading( 'hide', 'Error: You do not have permission to edit this post' );
					else kc.loading( 'hide', 'Successful' );
					
					if( $('#content').length > 0 ){
						$('#content-html').trigger('click');
						$('#content').val( content );
					}
					
					kc.changed = false;

				}
			);

		},

		switch : function( force ){

			if( force === true )
				kc.cfg.mode = '';
			
			if( kc.front !== undefined )
				return;
				
			/*Clear Trash*/
			$('#kc-undo-deleted-element').css({top:-132});
			$('#kc-storage-prepare>.kc-model').remove();

			if( kc.cfg.mode == 'kc' ){

				kc.cfg.mode = '';
				kc.backbone.stack.set( 'KC_Configs', kc.cfg );

				var content = '';
					
				kc.changed = false;
				
				$('#kc-container > #kc-rows > .kc-row').each( function(){
					var exp =  kc.backbone.export( $(this).data('model') );
					content += exp.begin + exp.content + exp.end;
				});

				kc.model = 1; 
				kc.storage = [];

				$('#kc-container,.kc-params-popup').remove();
				$('#postdivrich').css({ visibility: 'visible', display: 'block' });
				$('html,body').stop().animate({ scrollTop : $(window).scrollTop()+3 });
				
				//tinymce.EditorManager.execCommand('mceAddEditor',true, 'content');
				
				window.wpActiveEditor = 'content';
				if( content !== '' ){
					$('#content').val(content);
					try{
						tinyMCE.get('content').setContent( content );
					}catch(ex){}
				}
				
				if( typeof( kc.wp_beforeunload ) == 'function' )
					$( window ).off( 'beforeunload' ).on('beforeunload', kc.wp_beforeunload );

				return false;

			}else{
				
				kc.cfg.mode = 'kc';
				kc.model = 1;
				kc.storage = [];
				kc.changed = false;
				
				if( typeof( kc.wp_beforeunload ) != 'function' )
					try{ kc.wp_beforeunload = $(window).data('events').beforeunload[0].handler; }catch( ex ){}
				
				$( window ).off('beforeunload').on('beforeunload', function(e){
				
				    if( kc.changed === true )
						return kc.__.i01;
				    else{
				    	
				    	if( kc.cfg.mode == 'kc' ){
				    		e = null;
						}
				    	e = null;
				    }
				});


			}

			/*Update config about activate of builder*/
			kc.backbone.stack.set( 'KC_Configs', kc.cfg );

			kc.views.builder.render();
			kc.params.process();
			kc.ui.columnsResize.load();
			kc.ui.sortInit();

		},

		loading : function( mode, message ){

			if( mode == 'show' ){
				$('body').append('<div id="instantSaving"><span><i class="fa fa-spinner fa-spin fa-3x"></i><br /><br />'+message+'</span></div>');
			}else{
				$('#instantSaving').html('<span><i class="fa fa-check fa-3x"></i><br /><strong class="fa-2x">'+message+'</strong></span>');
				setTimeout( function(){ $('#instantSaving').remove(); }, 1000 );
			}

		},
		
		std : function( ob, key, std ){
			
			if( typeof( ob ) !== 'object' )
				return std;
			if( ob[key] !== undefined && ob[key] !== '' )
				return ob[key];
			
			return std;
			
		}

	}, window.kc );

	$( document ).ready(function(){
		if( kc.ui.verify_tmpl() === true )
			kc.init(); 
	});

})( jQuery );
