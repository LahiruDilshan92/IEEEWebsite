/*
 * King Composer Project
 *
 * (c) Copyright king-theme.com
 *
 * Must obtain permission before using this script in any other purpose
 *
 * kc.detect.js
 *
*/

( function($){
	
	if( typeof( kc ) == 'undefined' ){
		console.error('Could not load KingComposer core library');
		return;
	}

	kc.detect = {
		
		frame : kc.frame !== undefined ? kc.frame : {},
		
		holder : null,
		
		ob : null,
		
		locked : false,
		
		clicked : false,
		
		disabled : false,
		
		columnsWidthChanged : false,
		
		bone : ['kc_row', 'kc_row_inner', 'kc_column', 'kc_column_inner'],
		
		init : function(){
			
			this.frame.contents = $('#kc-live-frame').contents();
			
			this.wrap_node( this.frame.contents.find('body').get(0) );
			
			var main = this.frame.contents.find('#kc-element-placeholder');
			
			var get_holder = function( main ){
				return{
					main : main,
					tooltip : main.find('.mpb-tooltip').get(0),
					top : main.find('.mpb-top').get(0),
					right : main.find('.mpb-right').get(0),
					bottom : main.find('.mpb-bottom').get(0),
					left : main.find('.mpb-left').get(0)
				}
			}
			
			this.holder = get_holder( main );
			
			this.holder.row = get_holder( this.frame.contents.find('#kc-row-placeholder') );
			this.holder.sections = get_holder( this.frame.contents.find('#kc-sections-placeholder') );
			this.holder.section = get_holder( this.frame.contents.find('#kc-section-placeholder') );
			this.holder.columns = [];
			
			for( var i = 0; i < 6; i ++ )
				this.holder.columns.push( get_holder( this.frame.contents.find('#kc-column-'+i+'-placeholder') ) );
				
			this.bone = this.bone.concat( kc_maps_views );
			
			kc.trigger({
				
				el: this.frame.$('.kc-boxholder'),
				events: {
					'[data-action="edit"]:click': function( e ){ kc.front.ui.element.edit( kc.get.model( e.target ) ); },
					'[data-action="double"]:click': function( e ){ 
						
						if( e.target.getAttribute('data-action') !== undefined && e.target.getAttribute('data-action') == 'copy' )
							kc.front.ui.element.copy( kc.get.model( e.target ) );
						else kc.front.ui.element.double( kc.get.model( e.target ) );
						
					},
					'[data-action="add-element"]:click': function( e ){ kc.front.ui.element.add( e.target ); },
					'[data-action="col-wid-left"]:click' : 'col_wid_left',
					'[data-action="col-wid-right"]:click' : 'col_wid_right',				
					'[data-action="col-exchange"]:click' : 'col_exchange',
					'[data-action="delete"]:click' : 'delete',
					'[data-action="columns"]:click': kc.front.ui.column.layout,
					'[data-action="add-section"]:click': kc.front.ui.element.add_section
				},
				
				delete : function( e ){
					
					var model = kc.get.model( e.target );
					if( model !== null && confirm( kc.__.sure ) ){
						
						var el = kc.detect.frame.$('[data-model="'+model+'"]'),
							ob = kc.detect.closest( el.parent().get(0) );
						
						el.remove();
						
						if( kc.storage[ model ] !== undefined ){
							if( kc_maps_views.indexOf( kc.storage[ model ].name ) > -1 ){
								
								delete kc.storage[ model ];
								
								if(  ob !== null){
									var code = kc.front.build_shortcode( ob[1] );
									if( code !== '' )
										kc.front.push( code, ob[1], 'replace' );
								}
							}else delete kc.storage[ model ];
						}
						
						kc.detect.untarget();
						
					}
							
				},
				
				col_exchange : function( e ){
						
					var r_col = parseInt( kc.detect.frame.$( e.target ).closest('.kc-boxholder').data('col-index') ),
						r_model = kc.detect.holder.columns[ r_col ].model,
						l_model = kc.detect.holder.columns[ r_col - 1 ].model,
						l_el = kc.detect.frame.$('[data-model="'+l_model+'"]'),
						r_el = kc.detect.frame.$('[data-model="'+r_model+'"]');
					
					l_el.stop().animate({ marginLeft: r_el.get(0).offsetWidth, marginRight: -r_el.get(0).offsetWidth });
					r_el.stop().animate({ marginLeft: -l_el.get(0).offsetWidth, marginRight: l_el.get(0).offsetWidth }, function(){
						l_el.before( r_el );
						l_el.css({marginLeft:'',marginRight:''})
						r_el.css({marginLeft:'',marginRight:''})
					});
					
					kc.detect.untarget();
										
				},
				
				col_wid_left : function( e ){
					
					var r_col = parseInt( kc.detect.frame.$( e.target ).closest('.kc-boxholder').data('col-index') ),
						r_model = kc.detect.holder.columns[ r_col ].model,
						l_model = kc.detect.holder.columns[ r_col - 1 ].model,
						uc = kc.front.ui.column;
					
					if( uc.change_width( l_model, -1 ) ){
						if( uc.change_width( r_model, 1 ) === false )
							uc.change_width( l_model, 1 );
					}
					
				},
								
				col_wid_right : function( e ){
					
					var r_col = parseInt( kc.detect.frame.$( e.target ).closest('.kc-boxholder').data('col-index') ),
						r_model = kc.detect.holder.columns[ r_col ].model,
						l_model = kc.detect.holder.columns[ r_col - 1 ].model,
						uc = kc.front.ui.column;
					
					if( uc.change_width( l_model, 1 ) ){
						if( uc.change_width( r_model, -1 ) === false )
							uc.change_width( l_model, -1 );
					}
											
				}

				
			});
			
			kc.trigger({
				
				el: this.frame.$('#kc-footers'),
				
				events: {
					'[data-action="browse"]:click' : function( e ){ kc.front.ui.element.add( e.target ); },
					'[data-action="quick-add"]:click' : function( e ){  kc.front.push( $( e.target ).parent().data('content') ); },
					'[data-action="custom-push"]:click' : 'custom_push',
					'[data-action="paste"]:click' : 'paste',
					'[data-action="sections"]:click' : 'sections',
					
				},
				
				custom_push : function(e){
					
					var atts = { 
							title: kc.__.i36, 
							width: 750, 
							class: 'push-custom-content',
							save_text: 'Push to builder'
						},
						pop = kc.tools.popup.render( e.target, atts );
						
					var copied = kc.backbone.stack.get('KC_RowClipboard');
					if( copied === undefined || copied == '' )
						copied = '';
					pop.find('.m-p-body').html( kc.__.i37+'<p></p><textarea style="width: 100%;height: 300px;">'+copied+'</textarea>');
					
					pop.data({
						callback : function( pop ){
							
							var content = pop.find('textarea').val();
							if( content !== '' ){
								if( content.trim().indexOf('[') !== 0 )
									content = '[kc_column_text]<p>'+content+'</p>[/kc_column_text]';
								kc.front.push( content );
							}
						}
					});
				},
				
				paste : function( e ){
				
					content = kc.backbone.stack.get('KC_RowClipboard');
				
					if( content === undefined || content == '' || content.trim().indexOf('[') !== 0 ){
						content = '[kc_column_text]<p>'+kc.__.i38+'</p>[/kc_column_text]';
					}
				
					if( content != '' )
						kc.front.push( content );
							
				},
				
				sections : function( e ){
					
					kc.cfg = $().extend( kc.cfg, kc.backbone.stack.get('KC_Configs') );
						
					var atts = { 
							title: 'Sections Manager', 
							width: 800, 
							class: 'no-footer bg-blur-style section-manager-popup', 
							help: 'http://docs.kingcomposer.com/documentation/sections-manager/?source=client_installed' 
						},
						pop = kc.tools.popup.render( e.target, atts ),
						arg = {},
						sections = $( kc.template( 'install-global-sections', arg ) );
					
					if( kc.cfg.profile !== undefined )
						pop.find('h3.m-p-header').append( ' - Actived Profile <span class="msg-profile-label-display">'+kc.cfg.profile.replace(/\-/g,' ')+'</span>' );
					
					pop.find('.m-p-body').append( sections );
					
					if( typeof arg.callback == 'function' )
						arg.callback( sections );
						
				}
				
			});
					
		},
		
		hover : function( e ){
			
			if( kc.detect.disabled === true || kc.detect.trust( e ) === false || kc.detect.locked === true )
				return;
				
			var u = kc.detect;
			
			if( u.clicked === true ){
					
				u.ob = u.closest( e.target );
				
				if( u.ob !== null ){
					
					if( u.columnsWidthChanged === true ){
						u.columnsWidthChanged = false;
						u.click( e );
					}
					
					if( kc.storage[ u.ob[1] ] !== undefined ){
						
						if( kc_maps_views.indexOf( kc.storage[ u.ob[1] ].name ) > -1 && 
							u.ob[0] === u.holder.section.el ){
						
							if( u.ob[0].offsetHeight !== u.holder.section.height || 
								u.ob[0].offsetWidth !== u.holder.section.width ){
								if( u.rect( u.ob, u.holder.section, 0 ) !== false ){
									u.ob = u.closest( u.ob[0].parentNode );
									u.rect( u.ob, u.holder.sections, 28 );
								}
							}
						}
						
						if( u.ob[0] === u.holder.el && 
							( u.ob[0].offsetHeight !== u.holder.height || 
							u.ob[0].getBoundingClientRect().top+kc.detect.frame.window.scrollY !== u.holder.top ||
							u.ob[0].offsetWidth !== u.holder.width ) )
								u.rect( u.ob, u.holder, 0 );
						
					}
					
				}
				
			}
				
			u.ob = u.closest( e.target );
			
			if( u.ob !== null ){
	
				u.target( u.ob );
					
				if( u.clicked !== true ){
					var ob = u.closest( u.ob[0].parentNode );
					if( u.section( ob ) === true ){
						kc.frame.$('.kc-boxholder[data-col-index]').attr({style:''});
						return;
					}else kc.frame.$('#kc-sections-placeholder, #kc-section-placeholder').attr({style:''});
					ob = u.closest( u.ob[0], 'kc_column_inner' );
					if( ob === null )
						ob = kc.detect.closest( u.ob[0], 'kc_column' );
					if( ob !== null )
						u.rect( ob, u.holder.columns[0] );
				}
			}
		},
		
		click : function( e ){
			
			if( kc.detect.disabled === true )
				return false;
			
			if( e.target === undefined )
				return false;
			else if( e.target.tagName == 'A' || kc.frame.$( e.target ).closest('a').length > 0 )
				e.preventDefault();
			else if( [ 'INPUT', 'SELECT', 'TEXTAREA' ].indexOf( e.target.tagName ) > -1 ){
				return true;
			}
			
			if( $(e.target).hasClass('kc-add-elements-inner') )
				setTimeout( kc.front.ui.element.add, 100, e.target );
			
			if( !kc.detect.trust( e ) )
				return false;
			
			if( kc.detect.locked !== false )
				kc.detect.locked = false;
				
			kc.detect.untarget();
				
			kc.detect.ob = kc.detect.closest( e.target );
			
			if( kc.detect.ob !== null ){
				
				kc.detect.clicked = true;
			
				$('.kc-params-popup.wp-pointer-top .m-p-header .sl-close.sl-func').trigger('click');

				kc.detect.target( kc.detect.ob, true /* is_click = true */ );
				
				var curent_size = $('#kc-curent-screen-view').html();
				if( curent_size != '100%' && parseInt( curent_size ) <= 768 ){
					
					var column = kc.detect.closest( kc.detect.ob[0], 'kc_column' );

					if( column !== null ){
						
						var el = column[0], 
							pop = kc.backbone.settings( el, { scrollTo: false } );
					
						kc.tools.popup.callback( pop, { 
							before_callback : kc.front.params.before_save, 
							after_callback : kc.front.params.save, 
							cancel : kc.front.params.cancel
						});
						
						kc.front.ui.element.smart_popup( pop, el );
						
						pop.find('.kc-pop-tabs>li').eq( 1 ).trigger('click');
						pop.data({ tab_active : 1 });
						
					}
				}
				
			};
			
			return false;
			
		},
		
		dblclick : function( e ){
			
			if( kc.detect.disabled === true )
				return false;
				
			if( !kc.detect.trust( e ) )
				return false;
			
			kc.detect.click( e );
			kc.detect.holder.main.find('.sl-pencil').trigger('click');
			
			e.preventDefault();
			e.stopPropagation();
			
			if (window.getSelection)
		        window.getSelection().removeAllRanges();
		    else if (document.selection)
		        document.selection.empty();
		        
			return false;
				
		},
		
		trust : function( e ){
			
			if( e.originalEvent === undefined )
				return false;
			
			var el = e.target, i, 
				ignored = [
					'kc-boxholder',
					'wp-core-ui',
					'kc-params-popup',
					'sys-colorPicker',
					'kc-footers',
					'mce-container'
				];
				
			while( el !== null ){
				for( i in el.classList ){
					if( ignored.indexOf( el.classList[i] ) > -1 )
						return false;
				}
				el = el.parentNode;
			}
			
			return true;	
			
		},
		
		closest : function( el, tag ){
			
			if( el === null || typeof( el.getAttribute ) != 'function' )
				return null;
			
			var model = el.getAttribute('data-model');
			
			if( model !== null ){
				
				if( tag === undefined || 
					( tag !== undefined && kc.storage[ model ] !== undefined && kc.storage[ model ].name == tag )
				)return [ el, el.getAttribute('data-model') ];
				
			}
			
			if( el.parentNode !== null )
				return kc.detect.closest( el.parentNode, tag );
				
			return null;
		},
				
		target : function( ob, is_click ){
			
			if( this.holder === null || ( this.holder.model === ob[1] && is_click !== true ) )
				return;
				
			var name = ( kc.storage[ ob[1] ] !== undefined ) ? kc.storage[ ob[1] ].name : '';
				
			if( name !== '' && this.bone.indexOf( name ) === -1 ){
					
				if( this.rect( ob, this.holder ) === false )
					return;
					
				if( kc.storage[ ob[1] ] !== undefined ){
					
					this.holder.tooltip.querySelectorAll('span.label')[0].innerHTML = kc.storage[ ob[1] ].name.replace('kc_','');
					if( is_click === true ){
						// action on click
					}
					
				}
			}
			
			if( is_click === true ){
		
				if( ob[1] == '-1' )
					ob[3] = ob[0].parentNode;
				else ob[3] = ob[0];
				
				if( name != 'kc_row' && name != 'kc_row_inner' ){
					this.row( ob[3] );
					this.columns( ob[3] );
					return;
				}
				
				this.row( ob[0] );
				
				if( ob[0].querySelectorAll('[data-model]')[0] !== undefined ){
					this.columns( ob[0].querySelectorAll('[data-model]')[0] );
				}
			}
			
		},
		
		untarget : function(){
			
			kc.detect.clicked = false;
			
			kc.frame.$('.kc-boxholder, .kc-boxholder div').attr({style:''});
			$('.kc-params-popup').remove();
			try{
				delete this.holder.model;
				delete this.holder.el;
			}catch(ex){}
		},
		
		rect : function( ob, holder, padding ){
			
			if( ob[0] === null || typeof( ob[0].getBoundingClientRect ) != 'function' )
				return false;
				
			var pr = 0;
			if( padding === undefined ){
				padding = 0;
				pr = 0;
			}
				
			holder.main.data({ el : ob[0], model : ob[1], s : ob[1] });
			
			if( ob[0].tagName == 'kc' )
				$(ob[0]).addClass('fix-to-get-rect');
			
			
			ob[0].style.overflow = 'hidden';
			var coor = ob[0].getBoundingClientRect(),
				top = coor.top+kc.detect.frame.window.scrollY,
				left = coor.left+kc.detect.frame.window.scrollX,
				height = Math.round( ( coor.height >= 27 ) ? coor.height : 27 ),
				width = coor.width;
			ob[0].style.overflow = '';
			
			if( ob[0].tagName == 'kc' )
				$(ob[0]).removeClass('fix-to-get-rect');
			
			holder.width = width;
			holder.height = height;
			holder.el = ob[0];
			holder.model = ob[1];
				
			holder.main.css({ top: (top-padding)+'px', left: left+'px', width: width+'px' });
	
			holder.top.style.width = (width+1)+'px';
			
			holder.right.style.left = (width-1)+'px';
			holder.right.style.height = (height+padding+2)+'px';
			
			holder.bottom.style.top = (height+padding)+'px';
			holder.bottom.style.width = (width+1)+'px';
			
			holder.left.style.height = (height+padding+2)+'px';

			return true;
			
		},
		
		wrap_node : function( node ){

		    if( node !== null && node !== undefined ){
			    
			    var spc = node.firstChild, spcx;
			    
			    while( spc !== null ){
				    spcx = spc.nextSibling;
				 	if( spc.nodeType === 3 && ( spc.data === "\n" || spc.data.trim() === '' )  ){
					 	spc.parentNode.removeChild( spc );
				 	}
				 	spc = spcx;
				}
			    
		        node = node.firstChild;
		        var wrp,discover, nd, ind;
		        
		        while( node !== null ){
			        
			        if( node.nodeType === 8 )
			        	ind = node;
			        else ind = false;
			        
		            if(
		            	node.nodeType === 8 && 
		            	node.data.indexOf('kc s') === 0 && 
		            	node.nextSibling !== null
		            ){

			            if( node.nextSibling.nextSibling !== null ){
				            
				            if( node.nextSibling.nextSibling.nodeType === 8 && 
				            	node.nextSibling.nextSibling.data.indexOf('kc e') === 0 ){
					            	if( node.nextSibling.nodeType !== 1 ){
						            	nd = $('<kc data-model="'+node.data.replace( /[^0-9]/g, '' )+'"></kc>');
						            	$( node.nextSibling ).after( nd );
						            	nd.append( node.nextSibling );
					            	}else node.nextSibling.setAttribute( 'data-model', node.data.replace( /[^0-9]/g, '' ) );
				            }else{
			            	
				            	discover = node.nextSibling;
				            	wrp = document.createElement('kc');
				            	
				            	node.parentNode.insertBefore( wrp, discover );
				            	wrp.setAttribute( 'data-model', node.data.replace( /[^0-9]/g, '' ) );
				            	
				            	while( discover !== null ){
					            	
				            		wrp.appendChild( discover );
				            		
				            		if( wrp.nextSibling !== null && 
				            			wrp.nextSibling.nodeType === 8  && 
										wrp.nextSibling.data.indexOf('kc e') === 0 
									)break;
				            		
				            		if( discover.nodeType === 1 )
				            			kc.detect.wrap_node( discover );
				            		
				            		discover = wrp.nextSibling;
				            		
				            	}
				            	node = wrp;
				            }
				        }
		            
		            }else if( node.nodeType === 1 )kc.detect.wrap_node( node );
		            
		            node = node.nextSibling;
		            
			        if( ind !== false && ind != null && ind.parentNode !== null )
			        	ind.parentNode.removeChild( ind );
		        }
		    }
    
		},
		
		is_element : function( model ){
				
			if( kc.storage[ model ] === undefined )
				return false;
				
			var ignored = [ 'kc_row', 'kc_column', 'kc_column_inner' ].concat( kc_maps_views );
			
			if( ignored.indexOf( kc.storage[ model ].name ) > -1 )
				return false;
				
			return true;
			
		},
		
		row : function( el ){
			
			this.ob = kc.detect.closest( el );
				
			while( this.ob !== null && kc.storage[ this.ob[1] ] !== undefined ){
				
				// we will check is_section while finding row
				if( this.section( this.ob ) === true )
					return;
				
				if( kc.storage[ this.ob[1] ].name == 'kc_row' ||  kc.storage[ this.ob[1] ].name == 'kc_row_inner' ){
					
					if( this.rect( this.ob, this.holder.row, 28 ) !== false ){
						// if target row success
						this.holder.row.tooltip.querySelectorAll('.label')[0].innerHTML = kc.storage[ this.ob[1] ].name.replace('kc_','');
					}
					break;
				}
				this.ob = kc.detect.closest( this.ob[0].parentNode );
			}
			
		},
		
		columns : function( el ){
			
			this.ob = kc.detect.closest( el );
				
			while( this.ob !== null && kc.storage[ this.ob[1] ] !== undefined ){
				
				// we will check is_section while finding columns
				if( this.section( this.ob ) === true )
					return;
				
				if( kc.storage[ this.ob[1] ].name == 'kc_column' ||  kc.storage[ this.ob[1] ].name == 'kc_column_inner' ){
					
					this.ob[2] = this.ob[0].parentNode.firstChild;
					this.ob[3] = 0;
					while( this.ob[2] !== null ){
						this.column( this.ob[2], this.ob[3]++ );
						this.ob[2] = this.ob[2].nextElementSibling;
					}
					
					break;
				}
				this.ob = kc.detect.closest( this.ob[0].parentNode );
			}
			
		},
		
		column : function( el, index ){
			
			if( this.rect( [ el, el.getAttribute( 'data-model' ) ], this.holder.columns[ index ], 0 ) !== false ){
				// if target column success
			}
			
		},
		
		section : function( ob ){
			
			if(ob === null)
				return false;
			
			if( kc.storage[ ob[1] ] === undefined )
				return false;
				
			if( kc_maps_views.indexOf( kc.storage[ ob[1] ].name ) > -1 ){
				if( this.rect( ob, this.holder.section, 0 ) !== false ){
						
					ob = kc.detect.closest( ob[0].parentNode );
					// target sections when found a section
					if( this.rect( ob, this.holder.sections, 28 ) !== false ){
						// if target sections success
						$(this.holder.sections.tooltip).find('.label').html( 
							kc.storage[ ob[1] ].name.replace('kc_','' ) 
						);
						
					}
					return true;
				}
			}
			
			return false;
			
		},
		
		

	};
	
} )( jQuery );