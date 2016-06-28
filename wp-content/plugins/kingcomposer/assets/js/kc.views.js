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

( function($){
	 
	if( typeof( kc ) == 'undefined' )
		window.kc = {};
	 
	$().extend( kc.views, {
			
		builder : new kc.backbone.views('no-model').extend({
			
			render : function(){
				
				var el = $( kc.template( 'container' ) );
				
				$('#kc-container').remove();
				$('#postdivrich').hide().removeClass('first-load').after( el );
				
				this.el = el;
				
				return el;
							
			},
			
			events : {
				'.classic-mode:click' : kc.switch,
				'.live-editor:click' : function( e ){
					
					var id = $('#post_ID').val(),
						type = $('#post_type').val();
					
					if( typeof( id ) == 'undefined' )
						alert( kc.__.i48 );
					else if( typeof( type ) == 'undefined' )
						alert( kc.__.i49 );
					else if( $('#original_post_status').val() == 'auto-draft' ||  $('#original_post_status').val() == 'draft' )
						alert( kc.__.i51 );
					else window.open( site_url+'/wp-admin/?page=kingcomposer&kc_action=live-editor&id='+id );
					
					e.preventDefault();
					return;
						
				},
				'.basic-add:click' : kc.backbone.add,
				'.kc-add-sections:click' : 'sections',
				'.post-settings:click' : 'post_settings',
				'#kc-footers li.quickadd:click' : 'footer'
			},
			
			sections : function(){
				
				kc.cfg = $().extend( kc.cfg, kc.backbone.stack.get('KC_Configs') );
				
				var atts = { 
						title: 'Sections Manager', 
						width: 800, 
						class: 'no-footer bg-blur-style section-manager-popup', 
						help: 'http://docs.kingcomposer.com/documentation/sections-manager/?source=client_installed' 
					},
					pop = kc.tools.popup.render( this, atts ),
					arg = {},
					sections = $( kc.template( 'install-global-sections', arg ) );
					
				if( kc.cfg.profile !== undefined )
					pop.find('h3.m-p-header').append( ' - Actived Profile <span class="msg-profile-label-display">'+kc.cfg.profile.replace(/\-/g,' ')+'</span>' );
				
				pop.find('.m-p-body').append( sections );
				
				if( typeof arg.callback == 'function' )
					arg.callback( sections );

			},
			
			post_settings : function( e ){
				
				var atts = { title: 'Page Settings', width: 800, class: 'no-footer bg-blur-style' },
					pop = kc.tools.popup.render( this, atts ),
					arg = { classes : $('#kc-page-body-classes').val(), css : $('#kc-page-css-code').val() },
					sections = $( kc.template( 'post-settings', arg ) );
				
				pop.find('.m-p-body').append( sections );
				
				if( typeof arg.callback == 'function' )
					arg.callback( sections, $ );
				
				return false;
					
			},
			
			footer : function(){
				
				var content = $(this).data('content');
				
				if( content == 'custom' ){
					
					var atts = { 
						title: kc.__.i36, 
						width: 750, 
						class: 'push-custom-content',
						save_text: 'Push to builder'
					},
					pop = kc.tools.popup.render( this, atts );
					
					var copied = kc.backbone.stack.get('KC_RowClipboard');
					if( copied === undefined || copied == '' )
						copied = '';
					pop.find('.m-p-body').html( kc.__.i37+'<p></p><textarea style="width: 100%;height: 300px;">'+copied+'</textarea>');
					
					pop.data({
						callback : function( pop ){
							
							var content = pop.find('textarea').val();
							if( content !== '' )
								kc.backbone.push( content );
						}
					});
					
					return;
					
				}else if( content == 'paste' ){
					content = kc.backbone.stack.get('KC_RowClipboard');
					if( content === undefined || content == '' ){
						content = '[kc_column_text]<p>'+kc.__.i38+'</p>[/kc_column_text]';
					}
				}
				
				if( content != '' )
					kc.backbone.push( content );
				
			}
			
		} ),

		views_sections : new kc.backbone.views().extend({
			
			render : function( params ){
				
				var el = new $( kc.template( 'views-sections', params ) );
				kc.params.process_all( params.args.content, el.find('> .kc-views-sections-wrap'), 'views_section' );
				
				this.el = el;
				
				return el;
				
			},
			
			events : {
				'>.kc-views-sections-control .edit:click' : 'settings',
				'>.kc-views-sections-control .delete:click' : 'remove',
				'>.kc-views-sections-control .double:click' : 'double',
				'>.kc-views-sections-wrap .add-section:click' : 'add_section',
				'>.kc-views-sections-control .more:click' : 'more',
				'>.kc-views-sections-control .copy:click' : 'copy',
				'>.kc-views-sections-control .cut:click' : 'cut',
				'>.kc-views-sections-control:click' : function( e ){
					var tar = $(e.target);
					if( tar.hasClass('more') || tar.parent().hasClass('more') )
						return;
					$(this).find('.active').removeClass('active');
				},
			},
			
			add_section : function( e ){

				var wrp = $(this).closest('.kc-views-sections-wrap'),
					maps = kc.get.maps(this),
					smaps = kc.maps[maps.views.sections],
					content = '['+maps.views.sections+' title="New '+smaps.name+'"][/'+maps.views.sections+']';
				
				wrp.find('> .kc-views-sections-label .sl-active').removeClass('sl-active');
				wrp.find('> .kc-section-active').removeClass('kc-section-active');
					
				kc.params.process_all( content, wrp, 'views_section' );
				
			}
			
		} ),
		
		views_section : new kc.backbone.views().extend({
			
			render : function( params ){

				var el = $( kc.template( 'views-section', params ) );
				
				this.el = el;
				
				return el;
				
			},
			
			init : function( params, el ){
				
				var id = el.data('model'), 
					btn = params.parent_wrp.find('>.kc-views-sections-label .add-section'), 
					title = kc.tools.esc( params.args.title ),
					icon = '';
				if( params.args.icon != undefined )
					icon = '<i class="'+params.args.icon+'"></i> ';
					
				kc.ui.sortInit();
				
				var label = '<div class="section-label';
				if( params.first == true )
					label += ' sl-active';
				label += '" id="kc-pmodel-'+id+'" data-pmodel="'+id+'">'+icon+title+'</div>';
				
				btn.before( label );
				
				return btn;
	
			},
			
			events : {
				'>.kc-vs-control .settings:click' : 'settings',
				'>.kc-vs-control .double:click' : 'double',
				'>.kc-vs-control .add:click' : 'add',
				'>.kc-vs-control .delete:click' : 'remove',
				
			},
			
			settings : function(){
				
				var pop = kc.backbone.settings( this );
				if( !pop ){
					alert( kc.__.i39 );
					return;
				}
				pop.data({
					after_callback : function( pop ){
						
						var id = kc.get.model( pop.data('button') ),
							storage = kc.storage[ id ],
							el = $('#model-'+id),
							icon = '';
						if( storage.args.icon != undefined )
							icon = '<i class="'+storage.args.icon+'"></i> ';
							
						$('#kc-pmodel-'+id).html( icon+kc.tools.esc( storage.args.title ) );
						el.find('.kc-vertical-label').html( icon+kc.tools.esc( storage.args.title ) );
					}
				});
			},
			
			double : function(){
				
				var id = kc.get.model( this ),
					exp = kc.backbone.export( id ),
					wrp = $(this).closest('.kc-views-sections-wrap');
				
				wrp.find('> .kc-views-sections-label .sl-active').removeClass('sl-active');
				wrp.find('> .kc-section-active').removeClass('kc-section-active');
					
				kc.params.process_all( exp.begin+exp.content+exp.end, wrp, 'views_section' );
			},
			
			remove : function(){
				
				var id = kc.get.model( this ),
					lwrp = $('#kc-pmodel-'+id).parent();
					
				if( confirm('Are you sure?') ){	
					$('#kc-pmodel-'+id).remove();
					lwrp.find('.section-label').first().trigger('click');
					delete kc.storage[ id ];
					$('#model-'+id).remove();
				}
			}
	
			
		} ),
		
		row : new kc.backbone.views().extend({
			
			render : function( params, _return ){
				
				params.name = 'kc_row';
				params.end = '[/kc_row]';
				
				var el = $( kc.template( 'row', [ params.args.row_id, params.args.disabled ] ) ), atts = ' width="12/12"';
				
				var content = params.args.content.toString().trim();
				if( content.indexOf('[kc_column') !== 0 ){
					
					content = content.replace(/kc_column#/g,'kc_column##');
					content = content.replace(/kc_column /g,'kc_column# ').replace(/kc_column\]/g,'kc_column#]');
					
					var params = kc.params.merge( 'kc_column' );
					
					for( var i in params ){
						if( typeof( params[i] ) != 'undefined' && typeof( params[i].value ) != 'undefined' )
							atts += ' '+params[i].name
								 +'="'+kc.tools.esc_attr( params[i].value )+'"';
					}
					
					content = '[kc_column'+atts+']'+content+'[/kc_column]';
					
					delete params;
					
				}
				
				kc.params.process_columns( content, el.find('.kc-row-wrap') );
				
				if( _.isUndefined(_return) )
					$('#kc-container>#kc-rows').append( el );
				
				this.el = el;
				
				return el;
				
			},
			
			events : {
				'.row-container-control .close:click' : 'remove',
				'.row-container-control .settings:click' : 'edit',
				'.row-container-control .double:click' : 'double',
				'.row-container-control .copy:click' : 'copy',
				'.row-container-control .columns:click' : 'columns',
				'.row-container-control .collapse:click' : 'collapse',
				'.row-container-control .addToSections:click' : 'sections',
				'.row-container-control .rowStatus:click' : 'status',
			},
			
			columns : function(){
				
				var columns = $(this).closest('.kc-row').find('>.kc-row-wrap>.kc-column.kc-model');

				var pop = kc.tools.popup.render( 
							this, 
							{ 
								title: 'Row Layout', 
								class: 'no-footer',
								width: 341,
								content: kc.template( 'row-columns', {current:columns.length} ),
								help: 'http://kingcomposer.com/documentation/resize-sortable-columns/?source=client_installed' 
							}
						);
						
				pop.find('.button').on( 'click', 
					{ 
						model: kc.get.model( this ),
						columns: columns,
						pop: pop
					}, 
					kc.views.row.set_columns 
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
			
			set_columns : function(e){
				
				var newcols = parseInt($(this).data('column')),
					columns = e.data.columns,
					wrow = $( '#model-'+e.data.model+' > .kc-row-wrap' );
					
				if( columns.length < newcols ){
					
					/* Add new columns */
					var id = columns.last().data('model'), el, reInit = false;
					content = '['+kc.storage[id].name+' width="'+(12/newcols)+'/12"][/'+kc.storage[id].name+']';
					
					columns.each(function(){
						kc.storage[$(this).data('model')].args.width = (12/newcols)+'/12';
					});
					
					columns.css({width: (100/newcols)+'%'});
					
					for( var i = 0; i < (newcols-columns.length) ; i++ ){
						
						var dobl = kc.backbone.double( columns.last().get(0) );
						
						
						if( $('#m-r-c-double-content').attr('checked') == undefined || columns.length === 0 ){
							
							dobl.find('.kc-model').each(function(){
								delete kc.storage[$(this).data('model')];
								$(this).remove();
							});
							
						}
						
					}
					
					if( reInit == true )
						kc.ui.sortInit();
						
				}else{
					/* Remove columns */
					var remove = [];
					
					for( var i = 0; i < (columns.length-newcols) ; i++ ){
					
						var found_empty = false;
					
						wrow.find('>.kc-column.kc-model,>.kc-column-inner.kc-model').each(function(){
							if( $(this).find('>.kc-column-wrap>.kc-model').length == 0 ){
								found_empty = this;
							}
						});
					
						if( found_empty != false ){
					
							$(found_empty).remove();
					
						}else{
					
							var last = wrow.find('>.kc-column.kc-model,>.kc-column-inner.kc-model').last(), 
								plast = last.prev();
								
							if( $('#m-r-c-keep-content').attr('checked') != undefined && plast.get(0) != undefined ){
								last.find('>.kc-column-wrap>.kc-model').each(function(){
									plast.find('>.kc-column-wrap').append( this );
								});
							}else{
								last.find('>.kc-column-wrap>.kc-model').each(function(){
									delete kc.storage[$(this).data('model')];
								});
							}
							
							
							last.remove();
							
						}		
					}
					
					wrow.find('>.kc-column.kc-model,>.kc-column-inner.kc-model').each(function(){
					
						kc.storage[ $(this).data('model') ].args.width = '1/'+newcols;
						$(this).css({width: (100/newcols)+'%'});
					
					});
				}
				
				e.data.pop.remove();
				
			},
			
			collapse : function(){
				var elm = $(this).closest('.kc-row');
				if( !elm.hasClass('collapse') ){
					elm.addClass('collapse');
				}else{
					elm.removeClass('collapse');
				}	
			},
			
			sections : function( e ){
				
				kc.cfg = $().extend( kc.cfg, kc.backbone.stack.get('KC_Configs') );
				
				var atts = { title: kc.__.i40, width: 800, class: 'no-footer bg-blur-style', help: 'http://kingcomposer.com/documentation/sections-manager/?source=client_installed' };
				var pop = kc.tools.popup.render( this, atts );
				
				if( kc.cfg.profile !== undefined )
					pop.find('h3.m-p-header').append( ' - <span class="msg-profile-label-display">'+kc.cfg.profile.replace(/\-/g,' ')+'</span>' );
				
				pop.data({ model: kc.get.model(this) });
				
				var args = {};
				var sections = $( kc.template( 'add-global-sections', args ) );
				
				pop.find('.m-p-body').append( sections );
				
				if( typeof args.callback == 'function' )
					args.callback( sections );
				
			},
			
			copy : function( e ){
					
				if( $(this).hasClass('copied') )
					return;
									
				var model = kc.get.model( this ),
					expo = kc.backbone.export( model );
					
				kc.backbone.stack.set( 'KC_RowClipboard', expo.begin+expo.content+expo.end );
				
				kc.tools.toClipboard( expo.begin+expo.content+expo.end );
				
				$(this).addClass('copied');
				
				setTimeout( function( el ){
					$(el).removeClass('copied');
				}, 600, this );
				
				return;
	
			},
			
			edit : function( e ){
				
				var pop = kc.backbone.settings( this );
				if( !pop ){
					alert( kc.__.i41 );
					return;
				}
				
				pop.data({ after_callback : function( pop ){
					
					var id = kc.get.model( pop.data('button') ),
						params = kc.storage[ id ].args,
						html = '',
						el = $('#model-'+id+'>.kc-row-admin-view');

					if( params.row_id != undefined && params.row_id != '__empty__' )
						html += '#'+params.row_id+' ';
					
					el.html( html );
					
				}});
				
			},
			
			status : function( e ){
					
				var model = kc.get.model( this ), stt = '';
				if( kc.storage[ model ] !== undefined && kc.storage[ model ].args !== undefined ){
					
					if( $(this).hasClass('disabled') ){
						
						$(this).removeClass('disabled').closest('.kc-model').removeClass('collapse');
						delete kc.storage[ model ].args.disabled;
						
					}else{
						
						$(this).addClass('disabled').closest('.kc-model').addClass('collapse');
						kc.storage[ model ].args.disabled = 'on';
						
					}
					
					kc.changed = true;
					
				}
				
			}
			
		} ),
				
		column : new kc.backbone.views().extend({
			
			render : function( params ){
				
				params.name = 'kc_column'; params.end = '[/kc_column]';
				
				var _w = params.args['width'];
				if( _w != undefined ){
					_w = _w.split('/');
					_w = parseFloat((_w[0]/_w[1])*100).toFixed(4)+'%';
				}else{
					_w = '100%';
				}
				
				var el = $( kc.template( 'column', { width: _w } ) );

				kc.params.process_all( params.args.content, el.find('.kc-column-wrap') );
				
				this.el = el;
				
				return el;
				
			},
			
			events : {
				'>.column-container-control .kc-column-settings:click' : 'settings',
				'>.column-container-control .kc-column-toleft:click' : 'toLeft',
				'>.column-container-control .kc-column-toright:click' : 'toRight',
				'>.kc-column-control .kc-column-add:click' : 'add',
				'>.kc-column-control >.close:click' : 'remove',
			},
			
			
			remove : function( e, id ){
				
				if( !confirm( kc.__.sure ) )
					return;
				
				if( id == undefined )
					var id = kc.get.model( this );
				
				var col = $( '#model-'+id ),
					row = $( '#model-'+kc.get.model( col.parent().get(0) ) );
				
				col.find('.kc-model').each(function(){
					delete kc.storage[ kc.get.model(this) ];
				});
					
				col.remove();
				delete kc.storage[ id ];
				
				var cols = row.find('> .kc-row-wrap > .kc-model');
				cols.each(function(){
					var cid = $(this).data('model');
					kc.storage[ cid ].args.width = (12/cols.length)+'/12';
					$(this).css({ width: (100/cols.length)+'%' });
				});
				
			},
			
			toLeft : function( e ){
				
				var id = 	kc.get.model( this ),
					el = 	$('#model-'+id),
					prev = 	el.prev();
				
				if( !prev.get(0))
					return false;
					
				if( e.data.change_width( prev.data('model'), -1 ))
					return e.data.change_width( id, 1 );
				
			},
			
			toRight : function( e ){
				
				var id = 	kc.get.model( this ),
					el = 	$('#model-'+id),
					next = 	el.next();
				
				if( !next.get(0))
					return false;
				
				if( e.data.change_width( next.data('model'), -1 ))
					return e.data.change_width( id, 1 );
				
			},
			
			change_width : function( id, st ){
				
				var el  = $('#model-'+id),
					stg = kc.storage[id],
					sw  = stg.args.width.split('/');
					
					if( sw[1] != 12 ){
						sw[0] = parseInt(sw[0])*(12/parseInt(sw[1]));
						sw[1] = 12;
					}
					
				if( _.isUndefined( stg ) )
					return false;
				if( ( st == 1 && sw[1]/sw[0] == 1 ) || ( st == -1 && sw[1]/sw[0] == 6 ) || sw[0] == 2.4 )
					return false;
				if( st == -1 && sw[1]/sw[0] == 6 )
					return false;
				
				sw[0] =  parseInt(sw[0])+parseInt(st);
				
				kc.storage[id].args.width = sw[0]+'/'+sw[1];
				el.css({width:(100*(sw[0]/sw[1]))+'%'});
				
				return true;
				
			},
			
			apply_all : function( el, arg ){
				
				var pop = kc.get.popup(el), model = pop.data('model');
			    pop.find('.sl-check.sl-func').trigger('click');
			    
			    try{
				    var data = kc.storage[ model ].args[ arg ];
				    $('#model-'+model).parent().find('>div').each(function(){
				    	
				    	model = $(this).data('model');
				    	if( model !== undefined )
				    		kc.storage[ model ].args[ arg ] = data;
				    	
				    });
			    }catch( ex ){}
			    
			    event.preventDefault();
			    return false;
				
			}
			
		}),
		
		kc_row_inner : new kc.backbone.views().extend({
			
			render : function( params ){
				
				params.name = 'kc_row_inner'; params.end = '[/kc_row_inner]';
				
				var el = $( kc.template( 'row-inner' ) );
				
				var content = params.args.content;
				if( content !== undefined )
					content = content.toString().trim();
				else content = '';
				
				if( content.indexOf('[kc_column') !== 0 ){
					content = '[kc_column_inner width="12/12"]'+
							   content.replace(/kc_column_inner/g,'kc_column_inner#')+
							   '[/kc_column_inner]';
				}			   
					
				kc.params.process_all( content, el.find('.kc-row-wrap') );
				
				this.el = el;
				
				return el;
			
			},
			
			events : {
				'.kc-row-inner-control > .settings:click' : 'settings',
				'.kc-row-inner-control > .double:click' : 'double',
				'.kc-row-inner-control > .delete:click' : 'remove',
				'.kc-row-inner-control > .copyRowInner:click' : 'copy',
				'.kc-row-inner-control > .columns:click' : 'columns',
				'.kc-row-inner-control > .collapse:click' : 'collapse',
			},
			
			collapse : function(){
				var elm = $('#model-'+kc.get.model(this));
				if( !elm.hasClass('collapse') ){
					elm.addClass('collapse');
				}else{
					elm.removeClass('collapse');
				}	
			},
			
			columns : function(){
				
				var columns = $(this).closest('.kc-row-inner').find('>.kc-row-wrap>.kc-column-inner.kc-model');

				var pop = kc.tools.popup.render( 
							this, 
							{ 
								title: kc.__.i42, 
								class: 'no-footer',
								width: 341,
								content: kc.template( 'row-columns', {current:columns.length} ),
								help: 'http://kingcomposer.com/documentation/resize-sortable-columns/?source=client_installed' 
							}
						);
						
				pop.find('.button').on( 'click', 
					{ 
						model: kc.get.model( this ),
						columns: columns,
						pop: pop
					}, 
					kc.views.row.set_columns 
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
			
			copy : function(){
				
				if( $(this).hasClass('copied') )
					return;
					
				$(this).addClass('copied');
				setTimeout( function( el ){ el.removeClass('copied'); }, 1000, $(this) );
				
				kc.backbone.copy( this );
				
			}
			
		}),
		
		kc_column_inner : new kc.backbone.views().extend({
			
			render : function( params ){
				
				params.name = 'kc_column_inner'; params.end = '[/kc_column_inner]';
				
				var _w = params.args['width'];
				if( _w != undefined ){
					_w = _w.split('/');
					_w = ((_w[0]/_w[1])*100)+'%';
				}else{
					_w = '100%';
				}
				
				var el = $( kc.template( 'column-inner', { width: _w } ) );
	
				if( params.args.content !== undefined && params.args.content != '' )
					kc.params.process_all( params.args.content, el.find('.kc-column-wrap') );
				
				this.el = el;
					
				return el;
			
			},
			
			events : {
				'>.column-inner-control .kc-column-settings:click' : 'settings',
				'>.column-inner-control .kc-column-toleft:click' : 'toLeft',
				'>.column-inner-control .kc-column-toright:click' : 'toRight',
				'>.kc-column-control .kc-column-add:click' : 'add',
				'>.kc-column-control >.close:click' : 'remove',
			},

			toLeft : function( e ){
				
				var id = 	kc.get.model( this ),
					el = 	$('#model-'+id),
					prev = 	el.prev();
				
				if( !prev.get(0))
					return false;
					
				if( kc.views.column.change_width( prev.data('model'), -1 ))
					return kc.views.column.change_width( id, 1 );
				
			},
			
			toRight : function( e ){
				
				var id = 	kc.get.model( this ),
					el = 	$('#model-'+id),
					next = 	el.next();
				
				if( !next.get(0))
					return false;
				
				if( kc.views.column.change_width( next.data('model'), -1 ))
					return kc.views.column.change_width( id, 1 );
				
			},
			
			remove : function( e  ){
				
				kc.views.column.remove( e, kc.get.model( this ) );

			},
			
		}),
					
		kc_element : new kc.backbone.views().extend({
			
			render : function( params ){
				
				var map = $().extend( {}, kc.maps._std );
				map = $().extend( map, kc.maps[ params.name ] );
				
				var el = $( kc.template( 'element', { map : map, params : params } ) );
				
				setTimeout( function( params, map, el ){
					el.append( kc.params.admin_label.render({map: map, params: params, el: el }));
				}, parseInt(Math.random()*100)+100, params, map, el );
				
				this.el = el;
					
				return el;
				
			},
			
			events : {
				'>.kc-element-control .edit:click' : 'edit',
				'>.kc-element-control .delete:click' : 'remove',
				'>.kc-element-control .double:click' : 'double',
				'>.kc-element-control .more:click' : 'more',
				'>.kc-element-control .copy:click' : 'copy',
				'>.kc-element-control .cut:click' : 'cut',
				'>.kc-element-control:click' : function( e ){
					var tar = $(e.target);
					if( tar.hasClass('more') || tar.parent().hasClass('more') )
						return;
					$(this).find('.active').removeClass('active');
				},
			},
			
			edit : function( e ){
				
				var pop = kc.backbone.settings( this );
				if( !pop ){
					alert( kc.__.i43 );
					return;
				}	
				
				$(this).closest('.kc-element').addClass('editting');
				pop.data({cancel: function(pop){
					
					$( pop.data('button') ).closest('.kc-element').removeClass('editting');
					
				},after_callback : function( pop ){
					
					var id = kc.get.model( pop.data('button') ),
						params = kc.storage[ id ], 
						map = $().extend( {}, kc.maps._std ),
						el = $('#model-'+id);
					
					map = $().extend( map, kc.maps[ params.name ] );
					el.find('>.admin-view').remove();
					el.append( kc.params.admin_label.render({map: map, params: params, el: el }));
	
				}});
				
			}
			
		}),
							
		kc_undefined : new kc.backbone.views().extend({
			
			render : function( params ){
				
				var map = $().extend( {}, kc.maps._std );
				map = $().extend( map, kc.maps[ params.name ] );
				
				var el = $( kc.template( 'undefined', { map : map, params : params } ) );
				
				this.el = el;
				
				return el;
				
			},
			
			events : {
				'>.kc-element-control .edit:click' : 'edit',
				'>.kc-element-control .delete:click' : 'remove',
				'>.kc-element-control .double:click' : 'double'
			},
			
			edit : function( e ){

				var pop = kc.backbone.settings( this );
				if( !pop ){
					alert( kc.__.i45 );
					return;
				}	
				
				$(this).closest('.kc-element').addClass('editting');
				pop.data({cancel: function(pop){
					
					$( pop.data('button') ).closest('.kc-element').removeClass('editting');
					
				},after_callback : function( pop ){
					
					$( pop.data('button') ).closest('.kc-element').removeClass('editting');
					
					var id = kc.get.model( pop.data('button') ),
						params = kc.storage[ id ], 
						map = $().extend( {}, kc.maps._std ),
						el = $('#model-'+id);
					
					map = $().extend( map, kc.maps[ params.name ] );
					el.find('>.admin-view').remove();
					el.append( kc.params.admin_label.render({map: map, params: params, el: el }));
	
				}});
				
			},
			
			remove : function( e ){
				if( confirm( kc.__.sure ) ){
					var elm = $(this).closest('div.kc-element');
					var mid = elm.data('model');
					elm.remove();
					delete kc.storage[mid];	
				}
			}
			
		}),

	} );
	
} )( jQuery );