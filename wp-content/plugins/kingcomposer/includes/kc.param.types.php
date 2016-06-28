<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*
*/
if(!defined('KC_FILE')){
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$kc = KingComposer::globe();

//Cache

foreach(

	array(
		'text' => 'kc_param_type_text',
		'hidden' => 'kc_param_type_hidden',
		'textarea' => 'kc_param_type_textarea_raw_html',
		'select' => 'kc_param_type_select',
		'dropdown' => 'kc_param_type_select',
		'textarea_html' => 'kc_param_type_textarea_html',
		'multiple' => 'kc_param_type_multiple',
		'checkbox' => 'kc_param_type_checkbox',
		'radio' => 'kc_param_type_radio',
		'attach_image' => 'kc_param_type_attach_image',
		'attach_image_url' => 'kc_param_type_attach_image_url',
		'attach_images' => 'kc_param_type_attach_images',
		'color_picker' => 'kc_param_type_color_picker',
		'icon_picker' => 'kc_param_type_icon_picker',
		'date_picker' => 'kc_param_type_date_picker',
		'kc_box' => 'kc_param_type_kc_box',
		'wp_widget' => 'kc_param_type_wp_widget',
		'css_box_tbtl' => 'kc_param_type_css_box_tbtl',
		'css_box_border' => 'kc_param_type_css_box_border',
		'group' => 'kc_param_type_group',
		'link' => 'kc_param_type_link',
		'autocomplete' => 'kc_param_type_autocomplete',
		'number_slider' => 'kc_param_type_number_slider',
		'random' => 'kc_param_type_random',
	) as $name => $func ){

	$kc->add_param_type_cache( $name, $func );
	
}

// Nocache

foreach(

	array(
		'post_taxonomy' => 'kc_param_type_post_taxonomy',
	) as $name => $func ){

	$kc->add_param_type( $name, $func );
	
}

function kc_param_type_text(){
	echo '<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="text" />';
}

function kc_param_type_hidden(){
	echo '<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="hidden" />';
}

function kc_param_type_textarea_raw_html(){
?>
	<textarea cols="46" rows="8" class="kc-row-area">{{kc.tools.base64.decode( data.value )}}</textarea>

	<!--For instant saving, dont change to base64-->
	<textarea name="{{data.name}}"  style="display:none;"class="kc-param">{{kc.tools.base64.decode( data.value )}}</textarea>
	<#

		data.callback = function( wrp, $ ){
			var pop = wrp.closest('.kc-params-popup');
			kc.tools.popup.callback( pop, { 
				before_callback : function( pop ){
					pop.find('textarea.kc-row-area').each(function(){
						$(this).parent().find( 'textarea.kc-param' ).val( kc.tools.base64.encode( this.value ) );
					});
				}
			});
		}

	#>
<?php
}

function kc_param_type_select(){
?>
		<select class="kc-param" name="{{data.name}}">
			<# if( data.options ){
				for( var n in data.options ){
					if( typeof data.options[n] == 'array' ||  typeof data.options[n] == 'object' ){
					#><optgroup label="{{n}}"><#
						for( var m in data.options[n] ){
							#><option<#
								if( m == data.value ){ #> selected<# }
								#> value="{{m}}">{{data.options[n][m]}}</option><#
						}
					#></optgroup><#

					}else{

			#><option<#
						if( n == data.value ){ #> selected<# }
					#> value="{{n}}">{{data.options[n]}}</option><#
					}
				}
			} #>
		</select>
<?php
}

function kc_param_type_textarea_html(){
?>
	<# var eid = parseInt( Math.random()*100000 ); #>

	<div class="kc-textarea_html-field-wrp">
		<div class="kc-editor-wrapper">
            <div id="wp-kc-content-{{eid}}-wrap" class="wp-core-ui wp-editor-wrap tmce-active">
                <div id="wp-kc-content-{{eid}}-editor-tools" class="wp-editor-tools hide-if-no-js">
                    <div id="wp-kc-content-{{eid}}-media-buttons" class="wp-media-buttons">
                        <button type="button" class="button kc-insert-media" data-editor="kc-content-{{eid}}">
                        	<i class="sl-cloud-upload"></i> <?php _e('Insert Media', 'kingcomposer'); ?>
                        </button>
                    </div>
                    <div class="wp-editor-tabs">
                        <button type="button" class="wp-switch-editor switch-tmce" data-wp-editor-id="kc-content-{{eid}}"><?php _e('Visual', 'kingcomposer'); ?></button>
                        <button type="button" class="wp-switch-editor switch-html" data-wp-editor-id="kc-content-{{eid}}"><?php _e('Text', 'kingcomposer'); ?></button>
                    </div>
                </div>
                <div id="wp-kc-content-{{eid}}-editor-container" class="wp-editor-container">
                    <div id="qt_kc-content-{{eid}}_toolbar" class="quicktags-toolbar"></div>
                    <textarea class="wp-editor-area kc-param" rows="10" autocomplete="off" width="100%" name="{{data.name}}" id="kc-content-{{eid}}">{{data.value}}</textarea>
                </div>
            </div>
        </div>
	</div>
	<#
		data.callback = function( wrp, $ ){

			kc.tools.editor.init( $('#kc-content-'+eid) );

			var pop = wrp.closest('.kc-params-popup');
			kc.tools.popup.callback( pop, { 
				before_callback : function( pop ){
	
					if( pop.find('.wp-editor-wrap').hasClass('tmce-active') )
						pop.find('textarea.kc-param').val( tinyMCE.activeEditor.getContent() );
	
				}
			});

			wrp.find('.kc-insert-media').on('click', { callback : function( atts ){

				kc.tools.editor.insert( window.wpActiveEditor ,wp.media.string.image( null, atts ) );

			}, atts : {frame:'post'} }, kc.tools.media.open );

		}
	#>

<?php
}

function kc_param_type_multiple(){
?>

	<div kc-multiple-field-wrp>
		<select multiple>
			<# if( data.options ){
				var vals = data.value.split(',');
				for( var n in data.options ){
					if( typeof data.options[n] == 'array' ||  typeof data.options[n] == 'object' ){
					#><optgroup label="{{n}}"><#
						for( var m in data.options[n] ){
							#><option<#
								if( vals.indexOf( m ) > -1 ){ #> selected<# }
								#> value="{{m}}">{{data.options[n][m]}}</option><#
						}
					#></optgroup><#

					}else{

			#><option<#
						if( vals.indexOf( n ) > -1 ){ #> selected<# }
					#> value="{{n}}">{{data.options[n]}}</option><#
					}
				}
			} #>
		</select>
		<input type="hidden" name="{{data.name}}" class="kc-param" value="{{data.value}}" />
		<a href="#" class="clear-selected">
			<i class="sl-close"></i> <?php _e('Remove Selection', 'kingcomposer'); ?>
		</a>
	</div>
	<#
		data.callback = function( el ){
			el.find('select').on( 'change', el, function(e){
				e.data.find('input.kc-param').val( jQuery(this).val() );
			});
			el.find('.clear-selected').on( 'click', el, function(e){
				e.data.find('select option:selected').removeAttr('selected');
				e.data.find('input.kc-param').val('');
				e.preventDefault();
			});
		}
	#>
<?php
}

function kc_param_type_checkbox(){
?>

	<# if( data.options ){
		var vals = data.value.split(',');
		for( var n in data.options ){
			#><span class="nowrap"><input type="checkbox" class="kc-param" name="{{data.name}}" <#
				if( vals.indexOf( n ) > -1 ){ #> checked<# }
			#> value="{{n}}" /> {{data.options[n]}}</span>
		<# }
	} #><input type="checkbox" checked class="kc-param" value="" name="{{data.name}}" style="display:none;" />
<?php
}

function kc_param_type_radio(){
?>
	<div kc-multiple-field-wrp>
		<# if( data.options ){
			for( var n in data.options ){
				#><span class="nowrap"><input type="radio" class="kc-param" name="{{data.name}}" <#
					if( n == data.value ){ #> checked<# }
				#> value="{{n}}" /> {{data.options[n]}}</span>
			<# } #>
			<a href="#" class="clear-selected">
				<i class="sl-close"></i> <?php _e('Remove Selection', 'kingcomposer'); ?>
			</a>
		<# } #><input type="radio" class="kc-param empty-value" value="" name="{{data.name}}" style="display:none;" />
	</div>
	<#
		data.callback = function( el ){
			el.find('.clear-selected').on( 'click', el, function(e){
				e.data.find('input.kc-param.empty-value').attr({'checked':true});
				e.preventDefault();
			});
		}
	#>
<?php
}

function kc_param_type_attach_image(){
?>

	<div class="kc-attach-field-wrp">
		<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="hidden" />
		<# if( data.value != '' ){ #>
		<div class="img-wrp">
			<img src="{{site_url}}/wp-admin/admin-ajax.php?action=kc_get_thumbn&id={{data.value}}" alt="" />
			<i class="sl-close" title="<?php _e('Delete this image', 'kingcomposer'); ?>"></i>
		</div>
		<# } #>
		<div class="clear"></div>
		<a class="button media button-primary">
			<i class="sl-cloud-upload"></i> <?php _e('Browse Image', 'kingcomposer'); ?>
		</a>
	</div>
	<#
		data.callback = function( el, $ ){

			el.find('.media').on( 'click', { callback: function( atts ){

				var wrp = $(this.el).closest('.kc-attach-field-wrp'), url = atts.url;

				wrp.find('input.kc-param').val(atts.id).change();
				if( typeof atts.sizes.medium == 'object' )
					var url = atts.sizes.medium.url;

				if( !wrp.find('img').get(0) ){
					wrp.prepend('<div class="img-wrp"><img src="'+url+'" alt="" /><i title="<?php _e('Delete this image', 'kingcomposer'); ?>" class="sl-close"></i></div>');
					wrp.find('img').on( 'click', el, function( e ){
						el.find('.media').trigger('click');
					});
					wrp.find('div.img-wrp .sl-close').on( 'click', el, function( e ){
						e.data.find('input.kc-param').val('');
						$(this).closest('div.img-wrp').remove();
					});
				}else wrp.find('img').attr({src : url});

			}, atts : { frame: 'select' } }, kc.tools.media.open );

			el.find('div.img-wrp .sl-close').on( 'click', el, function( e ){
				e.data.find('input.kc-param').val('');
				$(this).closest('div.img-wrp').remove();
			});

			el.find('div.img-wrp img').on( 'click', el, function( e ){
				el.find('.media').trigger('click');
			});



		}
	#>
<?php
}

function kc_param_type_attach_image_url(){
?>

	<div class="kc-attach-field-wrp">
		<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="hidden" />
		<# if( data.value != '' ){ #>
		<div class="img-wrp">
			<img src="{{data.value}}" alt="" />
			<i class="sl-close" title="<?php _e('Delete this image', 'kingcomposer'); ?>"></i>
			<div class="img-sizes"></div>
		</div>
		<# } #>
		<div class="clear"></div>
		<a class="button media button-primary">
			<i class="sl-cloud-upload"></i> <?php _e('Select Image', 'kingcomposer'); ?>
		</a>
	</div>
	<#
		data.callback = function( el ){

			var $ = jQuery;

			el.find('.media').on( 'click', { callback : function( atts ){

				var wrp = $(this.el).closest('.kc-attach-field-wrp'), url = atts.url;

				if( atts.size != undefined && atts.size != null && atts.sizes[atts.size] != undefined ){
					var url = atts.sizes[atts.size].url;
				}else if( typeof atts.sizes.medium == 'object' ){
					var url = atts.sizes.medium.url;
				}

				if( url != undefined && url != '' ){
					wrp.find('input.kc-param').val(url).change();
				}

				if( !wrp.find('img').get(0) ){
					wrp.prepend('<div class="img-wrp"><img src="'+url+'" alt="" /><i title="<?php _e('Delete this image', 'kingcomposer'); ?>" class="sl-close"></i><div class="img-sizes"></div></div>');
					el.find('div.img-wrp img').on( 'click', el, function( e ){
						el.find('.media').trigger('click');
					});
					el.find('div.img-wrp .sl-close').on( 'click', el, function( e ){
						$(this).closest('div.img-wrp').remove();
						e.data.find('input.kc-param').val('');
					});
				}else{
					wrp.find('img').attr({src : url});
					wrp.find('.img-sizes').html('');
				}

				var btn, wrpsizes = wrp.find('.img-sizes');
				for( var si in atts.sizes ){
					btn = $('<button data-url="'+atts.sizes[si].url+
								'" class="button">'+atts.sizes[si].width+'x'+
								atts.sizes[si].height+'</button>'
							);

					if( atts.size != undefined && atts.size ){

						if( atts.size == si )
							btn.addClass('button-primary');

					}else if( si == 'medium' )
						btn.addClass('button-primary');

					btn.on( 'click', function(e){

						var wrp = $(this).closest('.kc-attach-field-wrp'), url = $(this).data('url');

						$(this).parent().find('button').removeClass('button-primary');
						$(this).addClass('button-primary');

						wrp.find('img').attr({ src : url });
						wrp.find('input.kc-param').val( url );

						e.preventDefault();
						return false;

					});
					wrpsizes.append( btn );
				}

			}, atts : {frame:'post'} }, kc.tools.media.open );

			el.find('div.img-wrp .sl-close').on( 'click', el, function( e ){
				$(this).closest('div.img-wrp').remove();
				e.data.find('input.kc-param').val('');
			});

			el.find('div.img-wrp img').on( 'click', el, function( e ){
				el.find('.media').trigger('click');
			});

		}
	#>
<?php
}


function kc_param_type_attach_images(){
?>
	<div class="kc-attach-field-wrp">
		<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="hidden" />
		<#
			if( data.value != '' ){
				data.value = data.value.split(',');
				for( var n in data.value ){
					#><div data-id="{{data.value[n]}}" class="img-wrp"><img title="<?php _e('Drag image to sort', 'kingcomposer'); ?>" src="{{site_url}}/wp-admin/admin-ajax.php?action=kc_get_thumbn&id={{data.value[n]}}&size=thumbnail" alt="" /><i class="sl-close"></i></div><#
				}
		 #>
		<# } #>
		<div class="clear"></div>
		<a class="button media button-primary">
			<i title="<?php _e('Delete this image', 'kingcomposer'); ?>" class="sl-cloud-upload"></i> <?php _e('Browse Images', 'kingcomposer'); ?>
		</a>
	</div>

	<#
		data.callback = function( el ){

			el.find('.media').on( 'click', function( atts ){

				var wrp = jQuery(this.els).closest('.kc-attach-field-wrp'), url = atts.url;

				wrp.find('input.kc-param').val(atts.id).change();
				if( typeof atts.sizes.thumbnail == 'object' )
					var url = atts.sizes.thumbnail.url;

				wrp.prepend('<div data-id="'+atts.id+'" class="img-wrp"><img title="<?php _e('Drag image to sort', 'kingcomposer'); ?>" src="'+url+'" alt="" /><i title="<?php _e('Delete this image', 'kingcomposer'); ?>" class="sl-close"></i></div>');
				helper( wrp );

			}, kc.tools.media.opens );

			function helper( el ){

				kc.ui.sortable({

					items : 'div.kc-attach-field-wrp>div.img-wrp',
					helper : ['kc-ui-handle-image', 25, 25 ],
					connecting : false,
					vertical : false,
					end : function( e, el ){
						refresh( jQuery(el).closest('.kc-attach-field-wrp') );
					}

				});


				el.find('div.img-wrp i.sl-close').off('click').on( 'click', el, function( e ){
					jQuery(this).closest('div.img-wrp').remove();
					refresh( e.data );
				});

				refresh( el );

			}

			function refresh( el ){
				var val = [];
				el.find('div.img-wrp').each(function(){
					val[ val.length ] = jQuery(this).data('id');
				});
				if( val.length <= 4 ){
					el.removeClass('img-wrp-medium').removeClass('img-wrp-large');
				}else if( val.length > 4 && val.length < 9 ){
					el.addClass('img-wrp-medium').removeClass('img-wrp-large');
				}else if( val.length >= 9 ){
					el.removeClass('img-wrp-medium').addClass('img-wrp-large');
				}

				el.find('input.kc-param').val( val.join(',') );

				el.find('div.img-wrp img').on( 'click', el, function( e ){
					el.find('.media').trigger('click');
				});
			}

			helper( el.find('.kc-attach-field-wrp') );

		}
	#>

<?php
}

function kc_param_type_color_picker(){
?>
	<input name="{{data.name}}" value="{{data.value}}" placeholder="Select color" class="kc-param" type="search" />
	<#
		data.callback = function( el ){
			el.find('input').each(function(){
				this.color = new jscolor.color(this, {});
			});
	    }
	#>
<?php
}

function kc_param_type_icon_picker(){

?>	<# if( data.value == undefined || data.value == '' )data.value='fa-leaf'; #>
	<div class="icons-preview">
		<i class="{{data.value}}"></i>
	</div>
	<input name="{{data.name}}" value="{{data.value}}" placeholder="Click to select icon" class="kc-param kc-param-icons" type="text" />
	<#
		data.callback = function( el, $ ){

			el.find('input.kc-param').on('focus', function(){

				$('.kc-icons-picker-popup').remove();

				var listObj = jQuery( '<div class="icons-list noneuser">'+kc.tools.get_icons()+'</div>' );

				var atts = { title: 'Select Icons', width: 600, class: 'no-footer kc-icons-picker-popup', keepCurrentPopups: true };
				var pop = kc.tools.popup.render( this, atts );
				pop.data({ target: this, scrolltop: jQuery(window).scrollTop() });

				pop.find('.m-p-body').off('mousedown').on('mousedown',function(e){
					e.preventDefault();
					return false;
				});

				$(this).off( 'keyup' ).on( 'keyup', listObj, function( e ){

					clearTimeout( this.timer );
					this.timer = setTimeout( function( el, list ){

						if( list.find('.seach-results').length == 0 ){

							var sr = $('<div class="seach-results"></div>');
							list.prepend( sr );

						}else sr = list.find('.seach-results');

						var found = ['<span class="label">Search Results:</span>'];
						list.find('>i').each(function(){

							if( this.className.indexOf( el.value.trim() ) > -1
								&& found.length < 16
								&& $.inArray( this.className, found )
							)found.push( '<span data-icon="'+this.className+'"><i class="'+this.className+'"></i>'+this.className+'</span>' );

						});
						if( found.length > 1 ){
							sr.html( found.join('') );
							sr.find('span').on('mousedown', function(){

								if( $(this).data('icon') === undefined )
								{
									e.preventDefault();
									return false;
								}
								var tar = kc.get.popup(this).data('target');
								tar.value = $(this).data('icon');
								$(tar).trigger('change');
								setTimeout( function(el){el.trigger('blur');}, 100, $(tar) );
							});
						}
						else sr.html( '<span class="label">The key you entered was not found.</span>' );

					}, 150, this, e.data );

				});

				listObj.find('i').on('mousedown', function( e ){

					var tar = kc.get.popup(this).data('target');
					tar.value = this.className;

					$(tar).trigger('change');
					setTimeout( function(el){el.trigger('blur');}, 100, $(tar) );

				});

				setTimeout(function( el, list ){
					el.append( list );
				}, 10, pop.find('.m-p-body'), listObj );

			}).on('change',function(){
				jQuery(this).parent().find('.icons-preview i').attr({class: this.value});
			}).on('blur', function(){
				$('.kc-icons-picker-popup').remove();
			});

	    }
	#>
<?php
}

function kc_param_type_date_picker(){
?>

	<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="text" />
	<#
		data.callback = function( wrp, $ ){
			new Pikaday(
		    {
		        field: wrp.find('.kc-param').get(0),
		        firstDay: 1,
				format: 'yyyy/mm/dd',
		        minDate: new Date(2000, 0, 1),
		        maxDate: new Date(2020, 12, 31),
		        yearRange: [2000,2020]
		    });
		}
	#>

<?php
}

function kc_param_type_kc_box(){

?>

	<textarea name="data" class="kc-param kc-box-area forceHide">{{data.value}}</textarea>
	<button class="button html-code" data-action="html-code">
		<i class="sl-doc"></i> <?php _e('HTML Code', 'kingcomposer'); ?>
	</button>
	<button class="button css-code" data-action="css-code">
		<i class="sl-settings"></i> <?php _e('CSS Code', 'kingcomposer'); ?>
	</button>
	<button class="button align-center add-top" data-action="add" data-pos="top">
		<i class="sl-plus"></i>
	</button>
	<div class="kc-box-render"></div>
	<button class="button align-center add-bottom" data-action="add" data-pos="bottom">
		<i class="sl-plus"></i>
	</button>
	<div class="kc-box-trash">
		<a href="#" class="button forceHide" data-action="undo">
			<i class="sl-action-undo"></i> Undo Delete(0)
		</a>
	</div>
<#

	data.callback = function( wrp, $ ){

		try{
			var data_obj = JSON.parse( kc.tools.base64.decode( data.value ) );
		}catch(e){
			var data_obj = [{tag:'div',children:[{tag:'text', content:'There was an error with content structure.'}]}];
		}

		wrp.find('.kc-box-render').eq(0).append( kc.template( 'box-design', data_obj ) );

		var pop = kc.get.popup( wrp );
		
		kc.tools.popup.callback( pop, { before_callback : kc.ui.KC_Box.renderBack });
		pop.addClass('preventCancel');

		kc.ui.KC_Box.sort();

		wrp.on( 'click', function( e ){

			if( e.target.tagName == 'I' )
				var el = $( e.target.parentNode );
			else var el = $( e.target );

			kc.ui.KC_Box.actions( el, e );

		} );

	}

#>
<?php
}


function kc_param_type_wp_widget(){

?><#

	try{
		var obj = JSON.parse( kc.tools.base64.decode( data.value ) );
	}catch(e){
		return '<center><?php _e('There was an error with content structure.', 'kingcomposer'); ?></center>';
	}
	var html = '';

	for( var n in obj ){

		kc.widgets.find('input[name="id_base"]').each(function(){

			if( this.value == n ){

				html = jQuery(this).closest('div.widget').find('.widget-content').html();
				html = '<div class="kc-widgets-container" data-name="'+n+'">'
					   +html.replace(/name="([^"]*)"/g,function(r,v){

							v = v.split('][');
							v = ( v[1] != undefined ) ? v[1] : v[0];
							v = v.replace(/\]/g,'').trim();
							var str = 'name="'+v+'"';

							if( obj[n][v] != undefined )
								str += ' data-value="'+kc.tools.esc(obj[n][v])+'"';

							return str;

						})+'</div>';
			}
		});
	}

	#>{{{html}}}<#

	data.callback = function( wrp, $ ){

		wrp.find('*[data-value]').each(function(){
			switch( this.tagName ){
				case 'INPUT' :
					if( this.type == 'radio' || this.type == 'checkbox' )
						this.checked = true;
					else this.value = jQuery(this).data('value');
				break;
				case 'TEXTAREA' :
					this.value = jQuery(this).data('value');
				break;
				case 'SELECT' :
					var vls = jQuery(this).data('value');
					if( vls )vls = vls.toString().split(',');
					else vls = [''];

					if( vls.length > 1 )
						this.multiple = 'multiple';
					jQuery(this).find('option').each(function(){
						if( vls.indexOf( this.value ) > -1 )
							this.selected = true;
						else this.selected = false;
					});
				break;
			}
		});

		var pop = kc.get.popup( wrp );

		kc.tools.popup.callback( pop, { 
			
			before_callback : function( wrp ){

				var name = wrp.find('.kc-widgets-container').data('name'),
					fields = wrp.find('form.fields-edit-form').serializeArray(),
					data = {};
	
				data[ name ] = {};
	
				fields.forEach(function(n){
					if( data[ name ][n.name] == undefined )
						data[ name ][n.name] = n.value;
					else data[ name ][n.name] += ','+n.value;
				});
	
				var string = kc.tools.base64.encode( JSON.stringify( data ) );
	
				wrp.find('.m-p-r-content').append('<textarea name="data" class="kc-param kc-widget-area forceHide">'+string+'</textarea>');
	
			},
			after_callback : function( wrp ){
				wrp.find('.m-p-r-content .kc-param').remove();
			}
		});

	}

#>
<?php
}


function kc_param_type_css_box_tbtl(){
?>
	<#
		var imp = data.value.indexOf('!important');
		if( imp > -1 )
			imp = '!important';
		else imp = '';
		var val = data.value.replace('!important','').split(' ');
	#>
	<ul class="multi-fields-ul">
		<li>
			<input class="kc-param" name="{{data.name}}-top" class="m-f-u-first" value="{{val[0]}}" type="text" /><br /> <strong>Top</strong>
		</li>
		<li>
			<input class="kc-param" name="{{data.name}}-bottom" type="text" value="{{val[2]}}" /><br /> Bottom
		</li>
		<li>
			<input class="kc-param" name="{{data.name}}-left" type="text" value="{{val[3]}}" /><br /> Left
		</li>
		<li>
			<input class="kc-param" name="{{data.name}}-right" class="m-f-u-last" type="text" value="{{val[1]}}" /><br /> Right
		</li>
		<li class="m-f-u-li-link">
			<span><input <# if( val[0] == val[1] && val[1] == val[2] && val[2] == val[3] ){ #>checked<#} #> type="checkbox" /></span><br /> <i class="sl-link"></i>
		</li>
		<input type="hidden" class="kc-param" name="{{data.name}}-important" value="{{imp}}" />
	</ul>
	<#
		data.callback = function( el ){
			el.find('input[type=text]').on( 'keyup', el, function( e ){
				if( e.data.find('input[type=checkbox]').get(0).checked == true ){
					var cur = this;
					e.data.find('input[type=text]').each(function(){
						if( this != cur )
							this.value = cur.value;
					});
				}
			});
			el.find('input[type=checkbox]').on( 'change', el, function( e ){
				if( this.checked == true ){
					e.data.find('input[type=text]').val( e.data.find('input[type=text]').get(0).value );
				}
			});
		}
	#>
<?php
}
function kc_param_type_css_box_border(){
?>
	<#
		var imp = data.value.indexOf('!important');
		if( imp > -1 )
			imp = '!important';
		else imp = '';
		var val = data.value.replace('!important','').split(' ');
	#>
	<ul class="multi-fields-ul">
		<li>
			<input name="border-width" class="m-f-u-first kc-param" value="{{val[0]}}" type="text" /><br /> Width
		</li>
		<li>
			<span class="m-f-u-li-splect">
				<select name="border-style" class="kc-param">
					<option value="none">none</option>
					<option <# if(val[1]== 'hidden'){ #>selected<# } #> value="hidden">hidden</option>
					<option <# if(val[1]== 'dotted'){ #>selected<# } #> value="dotted">dotted</option>
					<option <# if(val[1]== 'dashed'){ #>selected<# } #> value="dashed">dashed</option>
					<option <# if(val[1]== 'solid'){ #>selected<# } #> value="solid">solid</option>
					<option <# if(val[1]== 'double'){ #>selected<# } #> value="double">double</option>
					<option <# if(val[1]== 'groove'){ #>selected<# } #> value="groove">groove</option>
					<option <# if(val[1]== 'ridge'){ #>selected<# } #> value="ridge">ridge</option>
					<option <# if(val[1]== 'inset'){ #>selected<# } #> value="inset">inset</option>
					<option <# if(val[1]== 'outset'){ #>selected<# } #> value="outset">outset</option>
					<option <# if(val[1]== 'initial'){ #>selected<# } #> value="initial">initial</option>
					<option <# if(val[1]== 'inherit'){ #>selected<# } #> value="inherit">inherit</option>
				</select>
			</span>
			<br /> Style
		</li>
		<li>
			<input type="text" name="border-color" value="{{val[2]}}" width="80" class="m-f-bb-color kc-param" /><br /> Color
		</li>
		<input type="hidden" name="border-important" class="kc-param" value="{{imp}}" />
	</ul>
	<#
		data.callback = function( el ){
			el.find('input.m-f-bb-color').each(function(){
				this.color = new jscolor.color(this, {});
			});
	    }
	#>
<?php
}
function kc_param_type_group(){
?>
<input type="hidden" name="{{data.name}}[0]" class="kc-param" />
<div class="kc-group-rows"></div>
<#
	try{
		data.value = JSON.parse( kc.tools.base64.decode( data.value ) );
		var values = {};
		for( var i in data.value ){
			if( typeof( data.value[i] ) == 'object' ){
				if( i.indexOf( data.name+'[' ) == -1 ){
					values[ data.name+'['+i+']' ] = {};
					for( var j in data.value[i] ){
						values[ data.name+'['+i+']['+j+']' ] = data.value[i][j];
					}
				}else values[ i ] = data.value[i];
			}
		}
	}catch(e){
		data.value = {'0':{}};
		var values = {};
	}

	data.callback = function( wrp, $, data ){
		
		wrp.data({ 'name' : data.name, 'params': data.params });

		for( var n in data.value ){
			if( typeof( data.value[n] ) == 'object' ){
				var params = kc.params.fields.group.set_index( data.params, data.name, n );
				
				var grow = $( kc.template( 'param-group' ) );
				wrp.find('.kc-group-rows').append( grow );
				
				kc.params.fields.render( grow.find('.kc-group-body'), params, values );
			}

		}

		wrp.find('.kc-group-rows').append( '<div class="kc-group-controls kc-add-group"><i class="sl-plus"></i> <?php _e('Add new Group', 'kingcomposer'); ?></div>' );

		kc.params.fields.group.callback( wrp );
		
	}

#>
<?php
}

function kc_param_type_link(){
?>
<#
	if( typeof data.value != 'undefined' && data.value != '' )
		var value = data.value.split('|');
	else value = ['','','','',''];
#>
	<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="hidden" />
	<a class="button link button-primary">
		<i class="sl-link"></i> <?php _e( 'Add your link', 'kingcomposer' ); ?>
	</a>
	<br />
	<span class="link-preview">
		<# if( value[0] !== undefined && value[0] != '' ){ #><strong>Link:</strong> {{value[0]}}<br /><# } #>
		<# if( value[1] !== undefined && value[1] != '' ){ #><strong>Title:</strong> {{value[1]}}<br /><# } #>
		<# if( value[2] !== undefined && value[2] != '' ){ #><strong>Target:</strong> {{value[2]}}<br /><# } #>
	</span>

<#

	data.callback = function( wrp, $ ){
		wrp.find('.button.link').on( 'click', wrp, function(e) {

            wpLink.open();

            var value = e.data.find('.kc-param').val();
            if( value != '' )
				value = value.split('|');
			else value = ['','','','',''];

            $('#wp-link-url').val( value[0] );
	        $('#wp-link-text').val( value[1] );
	        if( value[2] == '_blank' )
	        	$('#wp-link-target').attr({checked: true});

            if( $('#wp-link-update #kc-link-submit').length == 0 ){
            	$('#wp-link-update').append('<input type="submit" value="Add Link to Mini" class="button button-primary" id="kc-link-submit" name="wp-link-submit" style="display: none">');
				$('#wp-link-cancel, #wp-link-close').on( 'click', function(e) {
					$('#wp-link-submit').css({display: 'block'});
					$('#kc-link-submit').css({display: 'none'});
			        wpLink.close();
			        e.preventDefault ? e.preventDefault() : e.returnValue = false;
			        e.stopPropagation();
			        return false;
			    });
            }

            $('#wp-link-submit').css({display: 'none'});

            $('#wp-link-update #kc-link-submit').css({display: 'block'}).off('click').on( 'click', e.data, function(e) {

	            var url = $('#wp-link-url').val(),
	            	text = $('#wp-link-text').val(),
	            	target = $('#wp-link-target').get(0).checked?'_blank':'';

				e.data.find('.kc-param').val(url+'|'+text+'|'+target);

				var preview = '';
				if( url != '' )
					preview += '<strong>Link:</strong> '+url+'<br />';
				if( text != '' )
					preview += '<strong>Title:</strong> '+text+'<br />';
				if( target != '' )
					preview += '<strong>Target:</strong> '+target+'<br />';

				e.data.find('.link-preview').html( preview );

				$('#wp-link-close').trigger('click');

	            wpLink.close();
	            e.preventDefault
	            return false;
	        });
            return false;
        });
	}

#>

<?php
}

function kc_param_type_post_taxonomy(){

	$post_types = get_post_types( array(
			'public'   => true,
			'_builtin' => false
		),
		'names'
	);

	$post_types = array_merge( array( 'post' => 'post'), $post_types );

	foreach($post_types as $post_type){
		$taxonomy_objects = get_object_taxonomies( $post_type, 'objects' );
		$taxonomy = key( $taxonomy_objects );
		$args[ $post_type ] = kc_get_terms( $taxonomy, 'slug' );
	}

	echo '<label>'.__( 'Select Content Type', 'kingcomposer' ).': </label>';
	echo '<br />';
	echo '<select class="kc-content-type">';
	foreach( $args as $k => $v ){
		echo '<option value="'.esc_attr($k).'">'.ucwords( str_replace(array('-','_'), array(' ', ' '), $k ) ).'</option>';
	}
	echo '</select>';
	echo '<div class="kc-select-wrp">';
		echo '<select style="height: 150px" multiple class="kc-taxonomies-select">';

		foreach( $args as $type => $arg ){

			echo '<option class="'.esc_attr($type).'-st" value="'.esc_attr($type).'" style="display:none;">'.esc_html($type).'</option>';

			foreach( $arg as $k => $v ){

				$k = $type.':'.str_replace( ':', '&#58;', $k );

				echo '<option class="'.esc_attr($type).' '.esc_attr($k).'" value="'.esc_attr($k).'" style="display:none;">'.esc_html($v).'</option>';

			}
		}

		echo '</select>';
		echo '<button class="button unselected" style="margin-top: 10px;">Remove selection</button>';
	echo '</div>';

?>
<#

	data.callback = function( wrp, $ ){

		// Action for changing content type
		wrp.find('.kc-content-type').on( 'change', wrp, function( e ){

			var type = this.value;

			e.data.find('.kc-taxonomies-select option').each(function(){

				this.selected = false;

				if( $(this).hasClass( type ) )
					this.style.display = '';
				else this.style.display = 'none';

				if( this.value == type ){
					this.checked = true;
					e.data.find('input.kc-param').val( type );
				}
			});

		});
		// Action for changing taxonomies
		wrp.find('.kc-taxonomies-select').on( 'change', wrp, function( e ){

			var value = [];
			$(this).find('option:selected').each(function(){
				value.push( this.value );
			});

			e.data.find('input.kc-param').val( value.join(',') );

		});
		// Action remove selection
		wrp.find('.unselected').on( 'click', wrp, function( e ){

			e.data.find( '.kc-taxonomies-select option:selected' ).attr({ selected: false });
			e.preventDefault();

		});

		var values = data.value.split(','),
		valuez = data.value+',';

		// Active selected taxonomies
		if( values.length > 0 ){

			selected = values[0].split( ':' )[0];

			// Active selected content type
			if( selected != '' )
				wrp.find('.kc-content-type option[value='+selected+']').attr('selected','selected').trigger('change');
			else wrp.find('.kc-content-type').trigger('change');

			wrp.find('.kc-taxonomies-select option').each(function(){
				if( valuez.indexOf( this.value+',' ) > -1 ){
					this.selected = true;
				}else this.selected = false;
			});
		}

		wrp.find('.kc-select-wrp')
			.append('<input class="kc-param" name="'+data.name+'" type="hidden" value="'+data.value+'" />');

	}

#>


<?php
}

function kc_param_type_autocomplete(){
?>
<div class="kc_autocomplete_wrp">
	<input class="kc-param" name="{{data.name}}" type="hidden" value="{{data.value}}" />
	<ul class="autcp-items"></ul>
	<input type="text" class="kc-autp-enter" placeholder="<?php _e('Enter your word','kingcomposer'); ?>..." />
	<div class="kc-autp-suggestion kc-free-scroll">
		<ul>
			<li><?php _e('Show up 120 relate posts','kingcomposer'); ?></li>
		</ul>
	</div>
</div>
<#
	data.callback = function( wrp, $ ){
		
		function render( data, wrp ){
			
			var out = '';
			if( data.value !== '' ){
				var items = data.value.split(','), item, id;
				for( var i=0; i<items.length; i++ ){
					item = items[i].split(':');
					id = item[0];
					if( item[1] !== undefined )
						item = item[1];
					else item = '';
					out += '<li data-id="'+id+'"><span>'+kc.tools.esc_attr(item)+'</span><i class="sl-close kc-ac-remove" title="<?php _e( 'Remove item','kingcomposer' ); ?>"></i></li>';
				}
			}
			
			wrp.find('ul.autcp-items').html( out );
			helper( wrp.find('.kc_autocomplete_wrp') );
			
			wrp.find('.kc-autp-enter').on('focus',function(){
				$(this.parentNode).find('.kc-autp-suggestion').show();
			}).on('blur',function(){
				setTimeout( function(el){
					el.hide();
				}, 200, $(this.parentNode).find('.kc-autp-suggestion') );
			}).on('keyup',function(){
				
				if( this.value === '' )
					return;
					
				if( $(this.parentNode).find('.kc-autp-suggestion .fa-spinner').length == 0 ){
					$(this.parentNode).find('.kc-autp-suggestion ul').prepend('<li class="sg-loading kc-free-scroll"><i class="fa fa-spinner fa-spin"></i> searching...</li>');
				}
				clearTimeout( this.timer );
				this.session = Math.random();
				this.timer = setTimeout(function(el){
					
					$.post(
		
						kc_ajax_url,
					
						{
							'action': 'kc_suggestion',
							'security': kc_ajax_nonce,
							's': el.value,
							'session': el.session
						},
					
						function (result) {
							$(el.parentNode).find('.sg-loading').remove();
							if( el.session == result.__session ){
								var ex = [], out = [], item;
								for( var n in result ){
									if( n !== '__session' ){
										if( ex.indexOf( n ) === -1 ){
											ex.push(n);
											out.push('<li class="label kc-free-scroll">'+n+'</li>');	
										}
										for( var m in result[n] ){
											item = result[n][m].split(':');
											out.push('<li class="kc-free-scroll" data-value="'+kc.tools.esc_attr(result[n][m])+'">'+item[1]+'</li>');	
										}
									}	
								}
								if( out.length === 0 )
									out.push('<li><?php _e('Nothing found','kingcomposer'); ?></li>');
								$(el.parentNode).find('.kc-autp-suggestion ul').html( out.join('') ).find('li').on('click',function(){
									var value = $(this).data('value');
									if( value === null || value === undefined )
										return;
									value = value.split(':');
									$(this).closest('.kc_autocomplete_wrp').find('ul.autcp-items').append('<li data-id="'+value[0]+'"><span>'+kc.tools.esc_attr(value[1])+'</span><i class="sl-close kc-ac-remove" title="<?php _e( 'Remove item','kingcomposer' ); ?>"></i></li>');
									helper( $(this).closest('.kc_autocomplete_wrp') );
								});
							}
						}
					);
					
				}, 250, this);
			});
				
		}
		
		function helper( el ){

			kc.ui.sortable({

				items : 'div.kc_autocomplete_wrp>ul>li',
				helper : ['kc-ui-handle-image', 25, 25 ],
				connecting : false,
				vertical : true,
				end : function( e, el ){
					refresh( $(el).closest('.kc_autocomplete_wrp') );
				}

			});


			el.find('i.kc-ac-remove').off('click').on( 'click', el, function( e ){
				$(this).closest('li').remove();
				refresh( e.data );
			});

			refresh( el );

		}

		function refresh( el ){
			
			var val = [];
			
			el.find('>ul.autcp-items>li').each(function(){
				val[ val.length ] = $(this).data('id')+':'+$(this).find('>span').html();
			});

			el.find('input.kc-param').val( val.join(',') );

		}
		
		render( data, wrp );
	
	}	
#>	
<?php	
}	

function kc_param_type_number_slider(){
	?>
	<#
		var type, show_input;
		show_input = (typeof data.options['show_input'] == 'undefined' )? false: data.options['show_input'];

		if( show_input === true){
			type = 'text';
		}else{
			type = 'hidden';
		}
	#>

    <div class="kc_percent_slider"></div>
	<input type="{{type}}" class="kc-param number_slider_field" name="{{data.name}}" value="{{data.value}}" />

	<#

	data.callback = function( el, $, data ){

        var el_slider = el.find('.kc_percent_slider'), kc_number_slider = function( el, set_val ){
	        
			var _step, range = (typeof data.options['range'] == 'undefined' )? false: data.options['range'],
				show_input = (typeof data.options['show_input'] == 'undefined' )? false: data.options['show_input'],
				unit = (typeof data.options['unit'] == 'undefined' )? '': data.options['unit'];

			if(show_input === true ){
				_step = 1;
			}
			else{
				_step = data.options['step'];
			}
			if( set_val == 'NaN' )
				set_val = 0.75*parseInt( data.options['max'] );
				
			el.off('mouseup').on('mouseup',function(){
				$(this).next('input').change();
			}).freshslider({
				
				step	: _step,
				range	: range,
				min		: data.options['min'],
				max		: data.options['max'],
				unit	: data.options['unit'],
				value	: set_val,
				enabled : data.options['enabled'],

				onchange: function( left, right ){
					
					if( range === true )
						el.next('input').val((left+unit)+'|'+(right+unit));
					else el.next('input').val(left+unit);
					
					if(show_input === true )
						el.find('.fscaret').text('');
					else{
						el.find('.fscaret.fss-left').html('<span>'+(left+unit)+'</span>');
						el.find('.fscaret.fss-right').html('<span>'+(right+unit)+'</span>');
					}
					
				}
			});

		};
		
		var values = data.value.split('|');
		for( var i in values ){
			values[i] = parseInt( values[i] );
		}
		kc_number_slider( el_slider, values );
		
		el_slider.next('input').on('change', { el : el , el_slider: el_slider, data : data }, function(e){
	
			var _value = $(this).val();
			
			_value = _value.split('|');
			for( var i in _value ){
				_value[i] = parseInt( _value[i] );
			}
			
			if(/^\d+$/.test(_value) && _value !== ''){
				
				if(this.value!==_value)
					$(this).val( parseInt(_value) );

				if( _value > e.data.data.options['max'] ) _value = e.data.data.options['max'];

				kc_number_slider( e.data.el_slider, _value.toString().split('|') );
				
			}else{
				e.data.el_slider.next('input').val('');
			}

		});
    }

	#>
	<?php
}


function kc_param_type_random(){
?>
	<#
		var new_random = parseInt(Math.random()*1000000);
	#>
	<input name="{{data.name}}" class="kc-param" value="{{new_random}}" type="text" />

<?php
}
