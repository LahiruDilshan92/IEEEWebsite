<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*
*/

if(!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

?>

<div id="kc-sections-manager">
	<div class="mgs-select-section">
		<h1 class="mgs-t01" id="kc-profile-title">
			<?php _e('Profile: ', 'kingcomposer'); ?>
			<span class="msg-profile-label-display"></span>
			<a href="#" id="kc-add-new-section"><?php _e('Add New Section', 'kingcomposer'); ?></a>
		</h1>
		<select class="filter-by-category">
			<option value=""> -- <?php _e('Filter by Category', 'kingcomposer'); ?> -- </option>
		</select>
		<input type="search" class="filter-by-name" placeholder="<?php _e('Search by name', 'kingcomposer'); ?>" />
		<div id="kc-section-settings"><i class="sl-settings"></i></div>
		<div data-label="<?php _e('Edit this section', 'kingcomposer'); ?>" class="mgs-select-wrp layout-grid">
			<?php _e('Loading', 'kingcomposer'); ?>...
		</div>
	</div>
</div>
<div id="poststuff" style="display:none;" class="kc-sections-manager">
	<button class="button button-large kc-backtoList">
		<i class="sl-action-undo"></i> <?php _e('Back to Sections List', 'kingcomposer'); ?>
	</button>
    <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content" style="position: relative;">
            <div id="postdivrich" class="postarea wp-editor-expand">

				<?php wp_editor( '', 'content' ); ?> 

            </div>
        </div>
        <!-- /post-body-content -->

        <div id="postbox-container-1" class="postbox-container kc-section-meta">
            <div id="submitdiv" class="postbox ">
                <h3 class="hndle"><span><?php _e('Section Settings', 'kingcomposer'); ?></span></h3>
                <div class="inside">
	                <form id="kc-section-form">
		                <input type="submit" class="forceHide" />
	                    <div class="submitbox">
	                        <div class="misc-pub-section" id="kc-section-title">
		                        <label><?php _e('Title', 'kingcomposer'); ?>:</label>
		                        <input id="title" type="text" placeholder="Enter new Title" class="inp-wa0" />
	                            <div class="clear"></div>
	                        </div>
	                        <div class="misc-pub-section" id="kc-section-category">
		                        <label><?php _e('Categories', 'kingcomposer'); ?>:</label>
		                        <input type="text" placeholder="Enter new category name" class="inp-wa0" />
	                            <div class="mgs-tips pos-right">
									<ul></ul>
								</div>
	                            <div class="clear"></div>
	                        </div>
	                        <div class="misc-pub-section last" id="kc-section-screenshot">
		                       	<div class="msc-body mgc-cn-screenshot"></div>
	                            <div class="clear"></div>
	                        </div>
	
	                        <div id="major-publishing-actions">
	                            <div id="delete-action">
	                                <a class="submitdelete deletion" href="#">
		                                <?php _e('Delete This Section', 'kingcomposer'); ?>
		                            </a>
	                           	</div>
	                            <div id="publishing-action">
	                                <button class="button button-primary button-large">
	                                	<i class="fa fa-check"></i> <?php _e('Update', 'kingcomposer'); ?>
	                                </button>
	                            </div>
	                            <input type="hidden" id="msg-activated-section-id" />
	                            <div class="clear"></div>
	                        </div>
	                    </div>
	                </form>
                </div>
            </div>
            <div id="kc-save-success">
	            <div class="mss-wrp">
		            <i class="fa fa-check"></i>
		            <h3><?php _e('Saved Successfully', 'kingcomposer'); ?></h3>
		            <span><?php _e('The data is saved in real-time and you do not need to reload the browser even in other tabs', 'kingcomposer'); ?></span>
		            <button class="button button-large kc-backtoList"><i class="sl-action-undo"></i>  Back to List</button>
		              &nbsp; 
		            <button class="button button-large button-primary" id="ssCountDown">Ok</button>
	            </div>
            </div>
        </div>

    </div>
    <!-- /post-body -->
    <br class="clear">
</div>

<script type="text/javascript">
	
	
function kc_sections_load(){
	
	var $ = jQuery;
	
	$('.msg-profile-label-display').html( kc.cfg.profile );
	
	/* Build Sections List */
	$('#kc-sections-manager div.mgs-select-wrp').html( kc.ui.gsections.load( '<?php _e('Edit this section', 'kingcomposer'); ?>', 0, kc.cfg.sectionsPerpage ) ).find('.mgs-scale-min').removeClass('mgs-scale-min');
	
	var cats = kc.ui.gsections.get_cats(), 
		ul = $('#kc-section-category .mgs-tips ul'),
		catfilter = $('#kc-sections-manager select.filter-by-category');
	
	ul.html('');
	catfilter.find('option').remove();
	catfilter.append('<option value=""> -- <?php _e('Filter by Category', 'kingcomposer'); ?> -- </option>');
		
	if( Object.keys(cats).length > 0 ){
		for( var i in cats ){
			ul.append('<li data-name="'+i+'"><i class="fa fa-caret-right"></i> '+i+' ('+cats[i]+')</li>');
			catfilter.append('<option value="'+i+'">'+i+' ('+cats[i]+')</option>');
		}
	}else{
		ul.append('<li data-name="first category"><i class="fa fa-caret-right"></i> <?php _e('First Category', 'kingcomposer'); ?></li><li></li>');
	}
	
	$('#kc-sections-manager .load-more').off('mouseover').on( 'mouseover', kc_loadmore_sections );
	
	$('#kc-section-category .mgs-tips li').off('click').on( 'click', function( e ){
			
		var input = $( '#kc-section-category input.inp-wa0' ),
			value = input.val().toString().trim(),
			data = $(this).data('name'),
			valz = value.split(',');
		
		for( var i in valz )
			valz[i] = valz[i].trim();
		
		if( value == '' )
			input.val( data );
		else if( $.inArray( data, valz ) == -1 )
			input.val( value+', '+data );
				
	} );
	
}

function kc_loadmore_sections( e ){
						
	var $ = jQuery,
		label = $(this).data('label'),
		from = $(this).data('from'),
		to = $(this).data('to');

	$(this).after( kc.ui.gsections.load( label, from, to ) ).remove();
	
	$(this).closest('.mgs-select-section').find('.filter-by-category').trigger('change');
	
	setTimeout(function(){
			$('.mgs-scale-min').removeClass('mgs-scale-min');
	}, 100 );
		
	$('#kc-sections-manager .load-more').off( 'mouseover').on( 'mouseover', kc_loadmore_sections );

}

function switchToEdit( sid ){
	
	var stack = kc.backbone.stack.get('KC_GlobalSections'), $ = jQuery;
	
	$('.section-manager-popup').remove();
	
	if( sid == 'new' ){
		var sobj = {
			title : 'New Section',
			category : 'New Category',
			screenshot : '',
			data : '',
			id : parseInt( Math.random()*1000000 )
		}
	}else{
		
		for( var i in stack ){
			if( stack[i].id == sid )
				var sobj = stack[i];	
		}
		
	}
	
	if( sobj === undefined )
		return;
	
	$('#kc-save-success').hide();
		
	/*Defined sid*/
	$('#poststuff').data({ sid : sid });
	
	$('#kc-sections-manager').hide();
	$('#poststuff').show().find('#content-html').trigger('click');
	$('#content').val( sobj.data );

	kc.switch( true );
	
	$('#kc-section-title input').val( sobj.title );
	$('#kc-section-category input').val( sobj.category );
	
	if( sobj.id === undefined || sobj.id === null || sobj.id == '' )
		sobj.id = parseInt( Math.random()*1000000 );
		
	$('#msg-activated-section-id').val( sobj.id );
	
	atts = { value 	: sobj.screenshot, name	: 'screenshot' };
	var field = jQuery( kc.template( 'field-type-attach_image_url', atts ) );
	
	$('#kc-section-screenshot .msc-body').html( field );
		
	if( typeof atts.callback == 'function' )
		setTimeout( atts.callback, 1, field );
		
	if( $('#style-hide-itself').length == 0 )
		$('body').append('<style type="text/css" id="style-hide-itself"></style>');
		
	$('#style-hide-itself').html('.kc-params-popup .kc-add-sections .mgs-section-item.mgs-section-'+sobj.id+'{display:none;}');
		
}	

function delete_section( el ){
	
	if( confirm('Are you sure that you want to delete this section?') ){
					
		var $ = jQuery, item = $(el).closest('.mgs-section-item');
		
		if( kc_profiles[ kc.cfg.profile_slug ] === undefined )
			data = kc.tools.base64.encode( localStorage.KC_GlobalSections );
		else data = '';
		
		$('body').append('<div id="instantSaving"><span><i class="fa fa-spinner fa-spin fa-3x"></i><br /><br />Deleting...</span></div>');
		
		$.post( kc_ajax_url, {

			'action': 'kc_delete_section',
			'id': $(el).data('sid'),
			'name': kc.cfg.profile,
			'slug': kc.cfg.profile_slug,
			'data': data,
			
		}, function (result) {
			
			$('#instantSaving').remove();
			
			if( result == 0 )
				alert('Access Denied!');
			else if( result.status == 'success' ){
				
				$('.kc-backtoList').trigger('click');
				item.remove();
				
				var data = kc.tools.base64.decode( result.data );
				
				kc.backbone.stack.set( 'KC_GlobalSections',  data );
				kc.cfg.profile = result.name;
				kc.backbone.stack.set( 'KC_Configs', kc.cfg );
				
				if( kc_profiles[ kc.cfg.profile_slug ] === undefined ){
					kc_profiles[ kc.cfg.profile_slug ] = result.name;
				}
				
				if( kc_profiles_external[ kc.cfg.profile_slug ] !== undefined ){
					delete kc_profiles_external[ kc.cfg.profile_slug ];	
				}
				
			}
			else alert( result.message );
			
		});
		
	}	
}

jQuery(document).ready(function( $ ){
	
	kc.curentContentType = 'kc-sections';
	/*Make sure the stack is fresh*/
	kc.backbone.stack.reset( 'KC_GlobalSections' );
	
	kc.trigger({
		
		el : $('#kc-sections-manager'),
		
		events : {
			'.mgs-select-section .filter-by-category:change' : 'filter',
			'.mgs-select-section .filter-by-name:keyup' : 'search',
			'#kc-add-new-section:click' : 'add_new',
			'#kc-section-settings:click' : 'settings',
			'.mgs-select-wrp:click' : 'actions',
		},
		
		actions : function( e ){
			
			e.preventDefault();
							
			var target = e.target;
			if( target == null )
				return;
			
			if( $(target).hasClass('mgs-sel-sceenshot') || $( target ).hasClass('edit-section') ){
				
				var sid = $(target).data('sid');
				if( sid != undefined ){
					switchToEdit( sid );
				}
				
			}
			else 
			if( $( target ).hasClass('mgs-delete') ){
								
				delete_section( target );
				
			}
			else 
			if( $(target).hasClass('load-more') ){
				
				$(target).trigger('mouseover');
				
			}
			
		},
		
		filter : function( e ){
			
			var wrp = $(this).closest('.mgs-select-section'),
				items = wrp.find('.mgs-section-item'),
				sections = wrp.find('.mgs-section-item'),
				i = 0;
			if( this.value == '' ){
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
		
		add_new : function( e ){
			
			switchToEdit( 'new' );
				
		},
		
		settings : function( e ){
			
			var atts = { title: 'KC Sections Settings', width: 800, class: 'no-footer bg-blur-style section-manager-popup page-sections-manager' },
				pop = kc.tools.popup.render( this, atts ),
				arg = { list : 'no' },
				sections = $( kc.template( 'install-global-sections', arg ) );
			
			pop.find('.m-p-body').append( sections );
			
			if( typeof arg.callback == 'function' )
				arg.callback( sections );
		}
		
	});	
	/* End Build Sections List */
	
	
	/* Settings Category Box */
	kc.trigger({
		
		el : $('#poststuff'),
		
		events : {
			'#kc-section-category input.inp-wa0:focus' : 'focus',
			'#kc-section-category input.inp-wa0:blur' : 'blur',
			'#delete-action a:click' : 'deletion',
			'#publishing-action button:click' : 'save',
			'.kc-backtoList:click' : 'backtoList',
			'#kc-section-form:submit' : function( e ){
				e.data.el.find('#publishing-action button').trigger('click');
				e.data.el.find('.show-tips').removeClass('show-tips');
				return false;	
			},
			'#ssCountDown:click' : function(){
				$('#kc-save-success').hide();
			}
		},
		
		focus : function( e ){
			this.placeholder='Select an exist category';
			$(this).closest('#kc-section-category').addClass('show-tips');
		},
		
		blur : function( e ){
			this.placeholder='Enter new category name';
			setTimeout( function( el ){
				el.removeClass('show-tips');
			}, 200, e.data.el.find('.show-tips') );	
		},
		
		deletion : function( e ){
			
			var sid = $('#poststuff').data('sid');
			if( sid != undefined ){
				delete_section( $('.mgs-section-'+sid+' .mgs-delete').get(0) );
			}
			
		},
		
		save : function( e ){
			
			if( $('#instantSaving').length > 0 )
				return;
			
			var sid = $('#poststuff').data('sid'),
				id =  kc.tools.esc( $('#msg-activated-section-id').val() );
				
			if( id === undefined || id == ''  )
				id = parseInt( Math.random()*1000000 );
				
			if( sid == undefined )return;
			
			var stack = kc.backbone.stack.get('KC_GlobalSections');

			if( sid == 'new' ){
				
				kc.backbone.stack.reset( 'KC_GlobalSections' );
				
				$('#poststuff').data({ 'sid' : id });

				setTimeout( kc.ui.gsections.refresh, 100 );
				
			}
			
			var title =  kc.tools.esc( $('#kc-section-title input').val() ),
				category =  kc.tools.esc( $('#kc-section-category input').val().toString().toLowerCase() ),
				screenshot = $('#kc-section-screenshot input.kc-param').val();
			
			if( title == '' ){
				alert('<?php _e('The title must not be empty', 'kingcomposer'); ?>');
				return false;
			}
				
			if( category == '' ){
				alert('<?php _e('The category must not be empty', 'kingcomposer'); ?>');
				return false;
			}
			
			
			
			
			var data = '', exp;
				
			$('#kc-container > #kc-rows > .kc-row').each(function(){
				exp =  kc.backbone.export( $(this).data('model') );
				data += exp.begin+exp.content+exp.end;
			});
			
			var obj = {
				'id'	: id,
				'title'	: title,
				'category'	: category,
				'screenshot'	: screenshot,
				'data'	: data
			}
			
			kc.ui.gsections.update_section( obj );
						
			kc.changed = false;

		},
		
		backtoList : function( e ){
			
			kc.changed = false;
			$('#kc-sections-manager').show();
			$('#poststuff').hide();
			$('#kc-container').remove();
			$('#kc-undo-deleted-element').css({top:-132});
		}
		
		
	});
	/* End Settings Box */
	
	function ssCountDown( s ){
		s = parseInt( s );
		if( s > 0 && $('#kc-save-success').css('display') == 'block' ){
			$('#ssCountDown').html('Ok ('+(s-1)+')');
			setTimeout( ssCountDown, 1000, s-1 );
		}else{
			$('#kc-save-success').hide();
		}
	}

	kc.ready.push( function(){
		
		if( kc_profiles[ kc.cfg.profile_slug ] !== undefined || kc_profiles_external[ kc.cfg.profile_slug ] !== undefined ){
			
			$.post( kc_ajax_url, {
				'action': 'kc_load_profile',
				'name': kc.cfg.profile_slug
			}, function ( result ) {
				
				if( result == 0 ){
					alert('Access Denied!');
					return;
				}
				
				if( result.status != 'success' ){
					alert( result.message );
				}else{
					
					result.data = kc.tools.base64.decode( result.data );
					kc.ui.gsections.doDownloadCallback( result );
					
					<?php
						
						if( isset( $_GET['id'] ) )
							echo 'switchToEdit('.$_GET['id'].');';
						
					?>
						
				}
				
			});	
		
		}else{
			
			kc_sections_load();
			<?php
						
				if( isset( $_GET['id'] ) )
					echo 'switchToEdit('.$_GET['id'].');';
				
			?>

		
		}
	
	} );
	
	
});
	
</script>