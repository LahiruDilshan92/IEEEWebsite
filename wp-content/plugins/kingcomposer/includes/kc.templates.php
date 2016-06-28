<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*
*/
if(!defined('KC_FILE')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
	
$kc = KingComposer::globe();
	
?>
<script type="text/html" id="tmpl-kc-container-template">
	<div id="kc-container" <# if( kc.cfg.showTips == 0 ){ #>class="hideTips"<# } #>>
		<div id="kc-controls">
			<button class="button button-large red classic-mode">
				<i class="sl-action-undo"></i> 
				<?php _e('Classic Mode', 'kingcomposer'); ?>
			</button>
			<button class="button button-large green live-editor">
				<i class="sl-paper-plane"></i> 
				<?php _e('Front End Editor', 'kingcomposer'); ?>
			</button>
			<button class="button button-large alignright post-settings">
				<i class="sl-settings"></i> <?php _e('Content Settings', 'kingcomposer'); ?>
			</button>
			<span class="alignright inss" <# if( kc.cfg.instantSave == 0 ){ #>style="display: none;"<# } #>>
				<?php _e('Press Ctrl + S to quick save', 'kingcomposer'); ?>
			</span>
		</div>
		
		<div id="kc-rows">
			<img class="empty_guide" src="<?php echo KC_URL; ?>/includes/frontend/builder/assets/empty_guide.png" />
		</div>
		
		<div id="kc-footers">
			<ul>
				<li class="basic-add">
					<span class="m-a-tips"><?php _e('Browse all elements', 'kingcomposer'); ?></span>
				</li>
				<li class="one-column quickadd" data-content='[kc_row][/kc_row]'>
					<span class="grp-column"></span>
					<span class="m-a-tips"><?php _e('Add an 1-column row', 'kingcomposer'); ?></span>
				</li>
				<li class="two-columns quickadd" data-content='[kc_row][kc_column width="6/12"][/kc_column][kc_column width="6/12"][/kc_column][/kc_row]'>
					<span class="grp-column"></span>
					<span class="grp-column"></span>
					<span class="m-a-tips"><?php _e('Add a 2-column row', 'kingcomposer'); ?></span>
				</li>
				<li class="three-columns quickadd" data-content='[kc_row][kc_column width="4/12"][/kc_column][kc_column width="4/12"][/kc_column][kc_column width="4/12"][/kc_column][/kc_row]'>
					<span class="grp-column"></span>
					<span class="grp-column"></span>
					<span class="grp-column"></span>
					<span class="m-a-tips"><?php _e('Add a 3-column row', 'kingcomposer'); ?></span>
				</li>
				<li class="four-columns quickadd" data-content='[kc_row][kc_column width="3/12"][/kc_column][kc_column width="3/12"][/kc_column][kc_column width="3/12"][/kc_column][kc_column width="3/12"][/kc_column][/kc_row]'>
					<span class="grp-column"></span>
					<span class="grp-column"></span>
					<span class="grp-column"></span>
					<span class="grp-column"></span>
					<span class="m-a-tips"><?php _e('Add a 4-column row', 'kingcomposer'); ?></span>
				</li>
				<li class="column-text quickadd" data-content="custom">
					<i class="et-document"></i>
					<span class="m-a-tips"><?php _e('Push customized content and shortcodes', 'kingcomposer'); ?></span>
				</li>
				<li class="title quickadd" data-content='paste'>
					<i class="et-clipboard"></i>
					<span class="m-a-tips"><?php _e('Paste copied element', 'kingcomposer'); ?></span>
				</li>
				<li class="kc-add-sections">
					<i class="et-lightbulb"></i> 
					<?php _e('Sections Manager', 'kingcomposer'); ?>
					<span class="m-a-tips"><?php _e('Installation of sections which were added', 'kingcomposer'); ?></span>
				</li>
			</ul>
		</div>
	</div>	
</script>
<script type="text/html" id="tmpl-kc-clipboard-template">
	<div id="kc-clipboard">
		<ul class="ms-funcs">
			<li class="delete button delete left">
				<?php _e('Delete selected items', 'kingcomposer'); ?> <i class="sl-close"></i>
			</li>
			<li class="select button left">
				<?php _e('Select all items', 'kingcomposer'); ?> <i class="sl-check"></i>
			</li>
			<li class="unselect button left ">
				<?php _e('Unselect all items', 'kingcomposer'); ?> <i class="sl-close"></i>
			</li>
			<li class="latest button prime">
				<?php _e('Paste latest item', 'kingcomposer'); ?> <i class="sl-clock"></i>
			</li>
			<li class="paste button">
				<?php _e('Paste selected items', 'kingcomposer'); ?> <i class="sl-check"></i>
			</li>
			<li class="pasteall button">
				<?php _e('Paste all items', 'kingcomposer'); ?> <i class="sl-list"></i>
			</li>
		</ul>
		<#
		try{
			var clipboards = kc.backbone.stack.get( 'KC_ClipBoard' ), 
				outer = '<div style="text-align:center;margin:20px auto;"><?php _e('The ClipBoard is empty, Please copy elements to clipboard', 'kingcomposer'); ?>.</div>';
			
			if( clipboards.length > 0 ){
				
				var stack, map, li = '';
					
				for( var n in clipboards ){
					if( clipboards[n] != null && clipboards[n] != undefined ){
						
						stack = clipboards[n];
						map = kc.maps[stack.title];
						
						li += '<li data-sid="'+n+'" title="Copy from page: '+stack.page+'">';
						if( map != undefined ){
							if( map['icon'] != undefined )
								li += '<span class="ms-icon cpicon '+map['icon']+'"></span>';
						}
						li += '<span class="ms-title">'+stack.title.replace(/\_/g,' ').replace(/\-/g,' ')+'</span>';
						li += '<span class="ms-des">'+kc.tools.unesc(stack.des)+'</span>';
						li += '</li>';
						
					}
				}
				
			}
		}catch(e){console.log(e);}	
		#>
		<ul class="ms-list">{{{li}}}</ul>
		<br />
		<span class="ms-tips">
			<strong><?php _e('Tips', 'kingcomposer'); ?>:</strong> 
			<?php _e('Drag and drop to arrange items, click to select an item. Read more', 'kingcomposer'); ?> 
			<a href="<?php echo esc_url('http://docs.kingcomposer.com/documentation/copy-cut-double-paste-for-element-column-row/?source=client_installed'); ?>" target="_blank"><?php _e('Document', 'kingcomposer'); ?></a>
		</span>
	</div>
	<# 
		data.callback = kc.ui.clipboard;
	#>
</script>
<script type="text/html" id="tmpl-kc-post-settings-template">
	<div id="kc-page-settings">
		<h1 class="mgs-t02">
			<?php _e('Page Settings', 'kingcomposer'); ?>
		</h1>
		<button class="button pop-btn save-post-settings"><?php _e('Save', 'kingcomposer'); ?></button>
		<div class="m-settings-row">
			<div class="msr-left">
				<label><?php _e('Body Class', 'kingcomposer'); ?></label>
				<span><?php _e('The class will be added to body tag on the front-end', 'kingcomposer'); ?> </span>
			</div>
			<div class="msr-right">
				<div class="msr-content">
					<input class="kc-post-classes-inp" type="text" placeholder="Body classes" value="{{data.classes}}" />
				</div>
			</div>
		</div>
		<div class="m-settings-row">
			<div class="msr-left msr-single">
				<label><?php _e('Css Code', 'kingcomposer'); ?></label>
				<button class="button button-larger css-beautifier float-right">
					<i class="sl-energy"></i> <?php _e('Beautifier', 'kingcomposer'); ?>
				</button>
				<textarea class="rt03 kc-post-css-inp">{{data.css}}</textarea>
				<i><?php _e('Notice: CSS must contain selectors', 'kingcomposer'); ?></i>
			</div>
		</div>
		
		<div class="m-settings-row">
			<div class="msr-left">
				<label><?php _e('Scroll Assistant', 'kingcomposer'); ?></label>
				<span>
					<?php _e('Keep the viewport in a reasonable place while a popup is opened', 'kingcomposer'); ?>.
				</span>
			</div>
			<div class="msr-right">
				<div class="msr-content">
					<div class="kc-el-ui meu-boolen" data-cfg="scrollAssistive" data-type="radio" onclick="kc.ui.elms(event,this)">
						<ul>
							<li<# if(kc.cfg.scrollAssistive==1){ #> class="active"<# } #>>
								<input type="radio" name="m-c-layout" value="1" />
							</li>
							<li<# if(kc.cfg.scrollAssistive!=1){ #> class="active"<# } #>>
								<input type="radio" name="m-c-layout" value="0" />
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div class="m-settings-row">
			<div class="msr-left">
				<label><?php _e('Scroll Prevention', 'kingcomposer'); ?></label>
				<span>
					<?php _e('Keep the web page unmoved while scrolling a popup', 'kingcomposer'); ?>.
				</span>
			</div>
			<div class="msr-right">
				<div class="msr-content">
					<div class="kc-el-ui meu-boolen" data-cfg="preventScrollPopup" data-type="radio" onclick="kc.ui.elms(event,this)">
						<ul>
							<li<# if(kc.cfg.preventScrollPopup==1){ #> class="active"<# } #>>
								<input type="radio" name="m-c-layout" value="1" />
							</li>
							<li<# if(kc.cfg.preventScrollPopup!=1){ #> class="active"<# } #>>
								<input type="radio" name="m-c-layout" value="0" />
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div class="m-settings-row">
			<div class="msr-left">
				<label><?php _e('Tooltips display', 'kingcomposer'); ?></label>
				<span>
					<?php _e('A brief description will appear when you hover the function icon', 'kingcomposer'); ?>.
				</span>
			</div>
			<div class="msr-right">
				<div class="msr-content">
					<div class="kc-el-ui meu-boolen showTipsCfg" data-cfg="showTips" data-type="radio">
						<ul>
							<li<# if(kc.cfg.showTips==1){ #> class="active"<# } #>>
								<input type="radio" name="m-c-layout" value="1" />
							</li>
							<li<# if(kc.cfg.showTips!=1){ #> class="active"<# } #>>
								<input type="radio" name="m-c-layout" value="0" />
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div class="m-settings-row">
			<div class="msr-left">
				<label><?php _e('Instant Save', 'kingcomposer'); ?></label>
				<span>
					<?php _e('Press Ctrl + S to save changes immediately without reloading the builder', 'kingcomposer'); ?>.
					<br />
					<?php _e('Even When you are editting an element, do not need to close editing popup', 'kingcomposer'); ?>.
					<br />
					<?php _e('Notice: This function isn’t activated when you are typing in an input box or textarea', 'kingcomposer'); ?>.
				</span>
			</div>
			<div class="msr-right">
				<div class="msr-content">
					<div class="kc-el-ui meu-boolen instantSaveCfg" data-cfg="instantSave" data-type="radio">
						<ul>
							<li<# if(kc.cfg.instantSave==1){ #> class="active"<# } #>>
								<input type="radio" name="m-c-layout" value="1" />
							</li>
							<li<# if(kc.cfg.instantSave!=1){ #> class="active"<# } #>>
								<input type="radio" name="m-c-layout" value="0" />
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	<#
		data.callback = function( wrp, $ ){
			
			wrp.find('.save-post-settings').on( 'click', wrp, function(e){
				
				$('#kc-page-body-classes').val( e.data.find('input.kc-post-classes-inp').val() );
				$('#kc-page-css-code').val( e.data.find('textarea.kc-post-css-inp').val() );
				
				kc.get.popup( this, 'close' ).trigger('click');
				
			});
			
			wrp.find('.css-beautifier').on( 'click', function(){
				var txta = $(this).parent().find('textarea');
				txta.val( kc.tools.decode_css( txta.val() ) );
			});
			
			wrp.find('.showTipsCfg').on( 'click', function(event){
				kc.ui.elms( event, this );
				if( kc.cfg.showTips == 1 )
					$('#kc-container').removeClass('hideTips');
				else $('#kc-container').addClass('hideTips');
			});
			
			wrp.find('.instantSaveCfg').on( 'click', function(event){
				kc.ui.elms( event, this );
				if( kc.cfg.instantSave == 1 )
					$('#kc-controls .inss').show();
				else $('#kc-controls .inss').hide();
			});
		}
	#>
</script>
<script type="text/html" id="tmpl-kc-global-sections-template">
<#

	var stg = data.stg, from = data.from, to = data.to, label = data.label, html = '';

	if( stg.length > 0 )
	{
		
		if( from >= to )
			from = 0;
	
		if( stg.length < to )
			to = stg.length;
		
		var n = 0;
		
		for( var i=from; i<to; i++ ){
			
			if( stg[i] != null )
			{
				n++;
				if( stg[i].screenshot == '' )
					stg[i].screenshot = kc.cfg.defaultImg;
				
				stg[i].sid = i;
				stg[i].label = label;
				stg[i].n = n;
				html += kc.template( 'global-section', stg[i] );
			}
		}		
		
		if( to < stg.length)
		{
			html += '<button class="button load-more" data-label="'+label+'" data-from="'+to+
					'" data-to="'+(kc.cfg.sectionsPerpage+to)+'">'+
					'<?php _e('Load More', 'kingcomposer'); ?> <i class="fa fa-caret-down"></i></button>'
		}
		#>{{{html}}}<#
	}
	else
	{
		#>
			<div class="msg-emptylist">
				<?php _e('Currently no sections in this profile', 'kingcomposer'); ?> <strong>{{kc.cfg.profile}}</strong>
				<br />
				<?php 
					printf( __('Please add New Section or %s', 'kingcomposer'),
					'<a href="#" onclick="kc.ui.gsections.showDownload(this)">select another profile</a>'
				); ?>.
				<br />
				<br />
				<p>
					<img src="<?php echo KC_URL; ?>/assets/images/new_section.png" width="473" />
				</p>
			</div>
		<#	   
	}	
	
#>
</script>
<script type="text/html" id="tmpl-kc-global-section-template">
<div class="mgs-scale-min mgs-section-item<#
	if( data.category !== undefined ){
		var cats = data.category.split(',');
		for( var i in cats ){
			#> category-{{kc.tools.esc_slug(cats[i].trim())}}<#
		}
	}
#> mgs-section-{{data.id}}">
	<a href="#kc-install" class="mgs-si-sceenshot">
		<img data-sid="{{data.id}}" src="{{{data.screenshot}}}" alt="" class="mgs-sel-sceenshot" />
		<span data-sid="{{data.id}}" class="mgs-sel-sceenshot">
			{{data.label}}
		</span>
	</a>
	<div class="mgs-si-info">
		<span>{{data.title}}</span><i>{{data.category}}</i>
		<div class="mgs-si-funcs">
			<a class="edit-section" href="<?php echo admin_url('admin.php?page=kc-sections-manager'); ?>&id={{data.id}}" target="_blank">
				<i title="<?php _e('Edit this section', 'kingcomposer'); ?>" class="sl-pencil edit-section"></i>
			</a>
			<a href="#delete" data-sid="{{data.id}}" class="mgs-delete" title="<?php _e('Delete this section', 'kingcomposer'); ?>"></a>
		</div>
	</div>
</div>
</script>
<script type="text/html" id="tmpl-kc-add-global-sections-template">
	<div id="kc-global-sections" class="kc-add-sections">
		<div class="mgs-select-section">
			<h1 class="mgs-t01"><?php _e('Add to an available section', 'kingcomposer'); ?></h1>
			<select class="filter-by-category">
				<option value=""> -- <?php _e('Filter by Category', 'kingcomposer'); ?> -- </option>
				<#
					var cats = kc.ui.gsections.get_cats();
					for( var i in cats ){
						if( i !== undefined && i.trim() != '' ){
							#><option value="{{i}}">{{i}} ({{cats[i]}})</option><#
						}
					}
				#>
			</select>
			<div class="mgs-select-wrp">
				{{{kc.ui.gsections.load( '<?php _e('Add to this section', 'kingcomposer'); ?>', 0, kc.cfg.sectionsPerpage )}}}
			</div>
		</div>
		<div class="mgs-create-new">
			<div class="mgs-cn-row">
				<h1><?php _e('Create a new section', 'kingcomposer'); ?></h1>
				<input class="mgs-title" type="text" placeholder="<?php _e('Enter title', 'kingcomposer'); ?>" value="" spellcheck="true" autocomplete="off" />
			</div>
			<div class="mgs-cn-row mgs-category">
				<input  class="mgs-category" type="text" placeholder="<?php _e('Enter category name', 'kingcomposer'); ?>" value="" spellcheck="true" autocomplete="off" />
				<div class="mgs-tips">
					<ul>
						<#
							var cats = kc.ui.gsections.get_cats();
							if( Object.keys(cats).length > 0 ){
								for( var i in cats ){
									if( typeof( i ) != 'undefined' && i != '' ){
										#><li data-name="{{i}}"><i class="fa fa-caret-right"></i> {{i}} ({{cats[i]}})</li><#
									}
								}
							}else{
								#><li data-name="first category"><i class="fa fa-caret-right"></i> First Category</li><li></li><#
							}
						#>
					</ul>
				</div>	
			</div>
			<div class="mgc-cn-screenshot"></div>
			<button class="create-section">
				<?php _e('Create Now', 'kingcomposer'); ?> 
				<i class="sl-check"></i>
			</button>
		</div>
		<div class="mgs-confirmation">
			<div class="mgs-c-status">
				<i class="et-caution"></i>
				<i class="et-happy"></i>
				<i class="et-sad"></i>
			</div>
			<br />
			<h1 class="mgs-t02"></h1>
			<h2></h2>
			<ul class="btns">
				<li>
					<button class="button button-large back">
						<i class="sl-action-undo"></i> <?php _e('Go Back', 'kingcomposer'); ?>
					</button>
					<button class="button button-large button-primary apply">
						<i class="sl-check"></i> <?php _e('Apply Now', 'kingcomposer'); ?>
					</button>
					<button class="button button-large button-primary close">
						<i class="sl-close"></i> <?php _e('Close Popup', 'kingcomposer'); ?>
					</button>
				</li>
			</ul>
		</div>
	</div>
	<# 
		data.callback = kc.ui.gsections.add_actions;
	#>
</script>
<script type="text/html" id="tmpl-kc-install-global-sections-template">
	<div id="kc-global-sections" class="kc-install-sections">
		<div class="mgs-menus">
			<ul>
				<li class="mgs-menu-download mtips mtips-right <# if(data.list=='no'){ #> active<# } #>" data-active="mgs-download-section">
					<i class="et-notebook"></i>
					<span class="mt-mes"><?php _e('List profiles', 'kingcomposer'); ?></span>
				</li>
				<# if(data.list!='no'){ #>
				<li class="mgs-menu-list mtips mtips-right active" data-active="mgs-select-section">
					<i class="et-document"></i>
					<span class="mt-mes"><?php _e('List sections of activated profile', 'kingcomposer'); ?></span>
				</li>
				<# } #>
				<li class="mgs-menu-upload mtips mtips-right" data-active="mgs-upload-section">
					<i class="et-upload"></i>
					<span class="mt-mes"><?php _e('Upload profile or create new profile', 'kingcomposer'); ?></span>
				</li>
				<li class="mgs-menu-settings mtips mtips-right" data-active="mgs-settings-section">
					<i class="et-gears"></i>
					<span class="mt-mes"><?php _e('Settings', 'kingcomposer'); ?></span>
				</li>
			</ul>
		</div>
		<div class="mgs-download-section"<# if(data.list=='no'){ #> style="display:block;"<# } #>>
			<h1 class="mgs-t01"><?php _e('Profiles', 'kingcomposer'); ?></h1>
			<a href="#" class="mgs-add-prof"><?php _e('Add new Profile', 'kingcomposer'); ?></a>
			<span class="mgs-4rs2"><?php _e('You are using profile: ', 'kingcomposer'); ?><span class="msg-profile-label-display">{{kc.cfg.profile}}</span></span>
			<div class="mgs-download-main kc-scroll">
				<ul>
				<#
					for( var i in kc_profiles ){
				#>
					<li<# if(i==kc.cfg.profile_slug){ #> class="active"<# } #>>
						<span>{{kc_profiles[i]}}</span>
						<span data-path="{{i}}" class="msg-download-action" title="{{kc_profiles[i]}}">
							<?php _e('Use this profile', 'kingcomposer'); ?>
						</span>
						<a href="#" data-slug="{{i}}" title="<?php _e('Delete this profile', 'kingcomposer'); ?>" class="mgs-delete-profile"></a>
						<a href="#" data-name="{{i}}" title="<?php _e('Refresh profile', 'kingcomposer'); ?>" class="mgs-refresh-profile"><i class="sl-refresh"></i></a>
						<a href="#" data-slug="{{i}}"  data-name="{{kc_profiles[i]}}" title="<?php _e('Rename this profile', 'kingcomposer'); ?>" class="mgs-edit-profile">
							<i class="et-pencil"></i>
						</a>
						<a download="{{i}}.kc" href="<?php echo admin_url('admin-ajax.php?action=kc_download_profile&name=') ?>{{i}}" title="<?php _e('Download this profile', 'kingcomposer'); ?>" class="mgs-download-direct"></a>
						</li>
				<#
					}
				#>
				
				<#
					for( var i in kc_profiles_external ){
						
						var path = kc_profiles_external[i],
							base = kc.tools.basename( path );
				#>
					<li<# if( i == kc.cfg.profile_slug || base == kc.cfg.profile_slug ){ #> class="active"<# } #>>
						<span>{{base.replace(/\-/g,' ')}}</span>
						<span data-path="{{i}}" class="msg-download-action" title="{{base.replace(/\-/g,' ')}}">
							<?php _e('Use this profile', 'kingcomposer'); ?>
						</span>
						<i class="msg-external"><?php _e('External','kingcomposer'); ?></i>
						<a download="{{i}}.kc" href="<?php echo site_url('/'); ?>{{path}}" title="<?php _e('Download this profile', 'kingcomposer'); ?>" class="mgs-download-direct"></a>
						</li>
				<#
					}
				#>

				</ul>
				<br />
				<?php _e('What is external profile?', 'kingcomposer'); ?>
				<a href="http://docs.kingcomposer.com/documentation/locate-your-sections-profile/" target="_blank"><?php _e('Check Document', 'kingcomposer'); ?></a>
			</div>
			<div class="mgs-sub-confirmation">
				<h2><?php _e('Are you sure?', 'kingcomposer'); ?></h2>
				<button class="button back">
					<i class="sl-action-undo"></i> <?php _e('Cancel', 'kingcomposer'); ?>
				</button> 
				<button class="button button-primary apply">
					<i class="sl-arrow-down-circle"></i> <?php _e('Yes, do it please!', 'kingcomposer'); ?> 
				</button>
			</div>
		</div>
		<# if(data.list!='no'){ #>
		<div class="mgs-select-section">
			<h1 class="mgs-t01">
				<?php _e('Sections', 'kingcomposer'); ?>
			</h1>
			<select class="filter-by-category">
				<option value=""> -- <?php _e('Filter by Category', 'kingcomposer'); ?> -- </option>
				<#
					var cats = kc.ui.gsections.get_cats();
					for( var i in cats ){
						if( i !== undefined && i.trim() != '' ){
							#><option value="{{i}}">{{i}} ({{cats[i]}})</option><#
						}
					}
				#>
			</select>
			<input type="search" class="filter-by-name" placeholder="<?php _e('Search by name', 'kingcomposer'); ?>" />
			<div class="mgs-layout-btns">
				<i data-layout="list" class="sl-list<# if(kc.cfg.sectionsLayout=='list'){ #> active<# } #>"></i>
				<i data-layout="grid" class="sl-grid<# if(kc.cfg.sectionsLayout=='grid'){ #> active<# } #>"></i>
			</div>
			<div data-label="<?php _e('Install this section', 'kingcomposer'); ?>" class="mgs-select-wrp layout-{{kc.cfg.sectionsLayout}}">
				{{{kc.ui.gsections.load( '<?php _e('Install this section', 'kingcomposer'); ?>', 0, kc.cfg.sectionsPerpage )}}}
			</div>
		</div>
		<# } #>
		<div class="mgs-confirmation">
			<div class="mgs-c-status">
				<i class="et-caution"></i>
				<i class="et-happy"></i>
				<i class="et-sad"></i>
			</div>
			<br />
			<h1 class="mgs-t02"></h1>
			<h2></h2>
			<ul class="btns">
				<li>
					<button class="button button-large back">
						<i class="sl-action-undo"></i> <?php _e('Go Back', 'kingcomposer'); ?>
					</button>
					<button class="button button-large button-primary apply">
						<i class="sl-check"></i> <?php _e('Apply Now', 'kingcomposer'); ?>
					</button>
					<button class="button button-large button-primary close">
						<i class="sl-close"></i> <?php _e('Close Popup', 'kingcomposer'); ?>
					</button>
				</li>
			</ul>
		</div>
		<div class="mgs-upload-section">
			<div class="mgs-upload-main">
				<div class="mgs-left-side">
					<h1 class="mgs-t02"><?php _e('Upload profile', 'kingcomposer'); ?></h1>
					<br />
					<p>
						<input type="file" class="msg-upload-profile-input" />
					</p>
					<button class="button button-primary uploadNow"><?php _e('Upload Now', 'kingcomposer'); ?></button>
				</div>
				<div class="mgs-right-side">
					<h1 class="mgs-t02"><?php _e('New profile', 'kingcomposer'); ?></h1>
					<br />
					<p>
						<input class="msg-new-profile-input" type="text" placeholder="Enter profile name" />
					</p>
					<button class="button button-primary createNew"><?php _e('Create Now', 'kingcomposer'); ?></button>
				</div>
			</div>
		</div>
		<div class="mgs-settings-section kc-scroll">
			<h1 class="mgs-t02 alignleft">
				<?php _e('Quick Settings Builder', 'kingcomposer'); ?>
				<span><?php _e('Settings will be applied instantly when you change parameter value', 'kingcomposer'); ?></span>
			</h1>
			<div class="m-settings-row">
				<div class="msr-left">
					<label><?php _e('Layout Display', 'kingcomposer'); ?></label>
					<span><?php _e('Set default layout for sections', 'kingcomposer'); ?></span>
				</div>
				<div class="msr-right">
					<div class="msr-content">
						<div class="kc-el-ui meu-radio" data-cfg="sectionsLayout" data-type="radio" onclick="kc.ui.elms(event,this)">
							<ul>
								<li<# if(kc.cfg.sectionsLayout=='grid'){ #> class="active"<# } #>>
									<?php _e('Grid', 'kingcomposer'); ?>
									<input type="radio" name="m-c-layout" value="grid" />
								</li>
								<li<# if(kc.cfg.sectionsLayout=='list'){ #> class="active"<# } #>>
									<?php _e('List', 'kingcomposer'); ?>
									<input type="radio" name="m-c-layout" value="list" />
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			
			<div class="m-settings-row">
				<div class="msr-left">
					<label><?php _e('Number of sections', 'kingcomposer'); ?></label>
					<span>
						<?php _e('will be shown in a page, each load-more scrolling down', 'kingcomposer'); ?>
					</span>
				</div>
				<div class="msr-right">
					<div class="msr-content">
						<div class="kc-el-ui meu-select">
							<select data-cfg="sectionsPerpage" data-type="select" onchange="kc.ui.elms(event,this)">
								<option<# if(kc.cfg.sectionsPerpage==5){ #> selected<# } #> value="5">5</option>
								<option<# if(kc.cfg.sectionsPerpage==10){ #> selected<# } #> value="10">10</option>
								<option<# if(kc.cfg.sectionsPerpage==15){ #> selected<# } #> value="15">15</option>
								<option<# if(kc.cfg.sectionsPerpage==30){ #> selected<# } #> value="30">30</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
	<div class="kc-popup-loading"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>
	<# 
		data.callback = kc.ui.gsections.install_actions;
	#>
</script>

<script type="text/html" id="tmpl-kc-row-template">
<#
 
var fEr3 = '', Hrdw = '', sEtd4 = '';

if( data[0] !== undefined && data[0] != '__empty__' )
	sEtd4 = '#'+data[0];

if( data[1] !== undefined && data[1] == 'on' ){
	fEr3 = ' collapse',
	Hrdw = ' disabled';
}

#>
	<div class="kc-row m-r-sortdable{{fEr3}}">
		<ul class="kc-row-control row-container-control">
			<li class="right close mtips">
				<i class="sl-close"></i>
				<span class="mt-mes"><?php _e('Delete this row', 'kingcomposer'); ?></span>
			</li>
			<li class="right settings mtips">
				<i class="sl-note"></i>
				<span class="mt-mes"><?php _e('Row settings', 'kingcomposer'); ?></span>
			</li>
			<li class="right copy mtips">
				<i class="sl-doc"></i>
				<span class="mt-mes"><?php _e('Copy this row', 'kingcomposer'); ?></span>
			</li>
			<li class="right double mtips">
				<i class="sl-docs"></i>
				<span class="mt-mes"><?php _e('Double this row', 'kingcomposer'); ?></span>
			</li>
			<li class="right move mtips">
				<i class="sl-cursor-move"></i>
				<span class="mt-mes"><?php _e('Drag and drop to arrange this row', 'kingcomposer'); ?></span>
			</li>
		</ul>
		<div class="kc-row-admin-view">{{sEtd4}}</div>
		<ul class="kc-row-control row-container-control pos-left">
			<li class="right collapse mtips">
				<i class="sl-arrow-down"></i>
				<span class="mt-mes"><?php _e('Expand / Collapse this row', 'kingcomposer'); ?></span>
			</li>
			<li class="right columns mtips">
				<i class="sl-list"></i>
				<span class="mt-mes"><?php _e('Set number of columns for this row', 'kingcomposer'); ?></span>
			</li>
			<li class="right addToSections mtips">
				<i class="sl-share-alt"></i>
				<span class="mt-mes"><?php _e('Storage this row in profile', 'kingcomposer'); ?></span>
			</li>
			<li class="rowStatus{{Hrdw}} mtips">
				<i></i>
				<span class="mt-mes"><?php _e('Publish / Unpublish this row', 'kingcomposer'); ?></span>
			</li>
		</ul>	
		<div class="kc-row-wrap"></div>
	</div>
</script>
<script type="text/html" id="tmpl-kc-row-inner-template">
	<div class="kc-row-inner">
		<ul class="kc-row-control kc-row-inner-control">
			<li class="right delete mtips">
				<i class="sl-close"></i>
				<span class="mt-mes"><?php _e('Delete this row', 'kingcomposer'); ?></span>
			</li>
			<li class="right settings mtips">
				<i class="sl-note"></i>
				<span class="mt-mes"><?php _e('Open row settings', 'kingcomposer'); ?></span>
			</li>
			<li class="right double mtips">
				<i class="sl-docs"></i>
				<span class="mt-mes"><?php _e('Double this row', 'kingcomposer'); ?></span>
			</li>
			<li class="right move mtips">
				<i class="sl-cursor-move"></i>
				<span class="mt-mes"><?php _e('Drag and drop to arrange this row', 'kingcomposer'); ?></span>
			</li>
		</ul>
		<ul class="kc-row-control pos-left kc-row-inner-control">
			<li class="right collapse mtips">
				<i class="sl-arrow-down"></i>
				<span class="mt-mes"><?php _e('Expand / Collapse this row', 'kingcomposer'); ?></span>
			</li>
			<li class="right columns mtips">
				<i class="sl-list"></i>
				<span class="mt-mes"><?php _e('Set number of columns for this row', 'kingcomposer'); ?></span>
			</li>
			<li class="right copyRowInner mtips">
				<i class="sl-doc"></i>
				<span class="mt-mes"><?php _e('Copy this row', 'kingcomposer'); ?></span>
			</li>
		</ul>	
		<div class="kc-row-wrap"></div>
	</div>	
</script>
<script type="text/html" id="tmpl-kc-column-template">
	<div class="kc-column" style="width: {{data.width}}">
		<ul class="kc-column-control column-container-control">
			<li class="arrow-left kc-column-toleft mtips">
				<i class="sl-arrow-left"></i>
				<span class="mt-mes"><?php _e('Increasing the width of this column to the left', 'kingcomposer'); ?></span>
			</li>
			<li class="kc-column-settings mtips">
				<i class="sl-note"></i>
				<span class="mt-mes"><?php _e('Open column settings', 'kingcomposer'); ?></span>
			</li>
			<li class="kc-column-add mtips">
				<i class="sl-plus"></i>
				<span class="mt-mes"><?php _e('Add elements to top of this column', 'kingcomposer'); ?></span>
			</li>
			<li class="close mtips">
				<i class="sl-trash"></i>
				<span class="mt-mes"><?php _e('Delete this column', 'kingcomposer'); ?></span>
			</li>
			<li class="right arrow-right kc-column-toright mtips">
				<i class="sl-arrow-right"></i>
				<span class="mt-mes"><?php _e('Increasing the width of this column to the right', 'kingcomposer'); ?></span>
			</li>
		</ul>
		<div class="kc-column-wrap">
			<div class="kc-element drag-helper">
				<a href="javascript:void(0)" class="kc-add-elements-inner">
					<i class="sl-plus"></i> <?php _e('Add Element', 'kingcomposer'); ?>
				</a>
			</div>
		</div>
		<ul class="kc-column-control pos-bottom">
			<li class="kc-column-add mtips">
				<i class="sl-plus"></i>
				<span class="mt-mes"><?php _e('Add elements to bottom of this column', 'kingcomposer'); ?></span>
			</li>
		</ul>
		<div class="column-resize cr-left"></div>
		<div class="column-resize cr-right"></div>
	</div>
</script>
<script type="text/html" id="tmpl-kc-column-inner-template">
	<div class="kc-column-inner" style="width: {{data.width}}">
		<ul class="kc-column-control column-inner-control">
			<li class="arrow-left kc-column-toleft mtips">
				<i class="sl-arrow-left"></i>
				<span class="mt-mes"><?php _e('Increasing the width of this column to the left', 'kingcomposer'); ?></span>
			</li>
			<li class="kc-column-settings mtips">
				<i class="sl-note"></i>
				<span class="mt-mes"><?php _e('Open column settings', 'kingcomposer'); ?></span>
			</li>
			<li class="kc-column-add mtips">
				<i class="sl-plus"></i>
				<span class="mt-mes"><?php _e('Add elements to top of this column', 'kingcomposer'); ?></span>
			</li>
			<li class="close mtips">
				<i class="sl-trash"></i>
				<span class="mt-mes"><?php _e('Delete this column', 'kingcomposer'); ?></span>
			</li>
			<li class="right arrow-right kc-column-toright mtips">
				<i class="sl-arrow-right"></i>
				<span class="mt-mes"><?php _e('Increasing the width of this column to the right', 'kingcomposer'); ?></span>
			</li>
		</ul>
		<div class="kc-column-wrap">
			<div class="kc-element drag-helper">
				<a href="javascript:void(0)" class="kc-add-elements-inner">
					<i class="sl-plus"></i> <?php _e('Add Elements', 'kingcomposer'); ?>
				</a>
			</div>
		</div>
		<ul class="kc-column-control pos-bottom">
			<li class="kc-column-add mtips">
				<i class="sl-plus"></i>
				<span class="mt-mes"><?php _e('Add elements to bottom of this column', 'kingcomposer'); ?></span>
			</li>
		</ul>
		<div class="column-resize cr-left"></div>
		<div class="column-resize cr-right"></div>
	</div>
</script>
<script type="text/html" id="tmpl-kc-views-sections-template">
	<#
		try{
			var sct = kc.maps[data.name].views.sections;
			if( kc.maps[data.name].views.display == 'vertical' )
				var vertical = ' kc-views-vertical';
		}catch(e){
			var sct = 'kc_tab', vertical = 'kc-views-horizontal';
		}	
	#>
	<div class="kc-views-sections kc-views-{{data.name}}{{vertical}}">
		<ul class="kc-views-sections-control kc-controls">
			<li class="right move mtips">
				<i class="sl-cursor-move"></i> {{kc.maps[data.name].name}}
				<span class="mt-mes"><?php _e('Drag and drop to arrange this section', 'kingcomposer'); ?></span>
			</li>
			<li class="right edit mtips">
				<i class="sl-note"></i>
				<span class="mt-mes"><?php _e('Open settings', 'kingcomposer'); ?></span>
			</li>
			<li class="double mtips">
				<i class="sl-docs"></i>
				<span class="mt-mes"><?php _e('Double this sections', 'kingcomposer'); ?></span>
			</li>
			<li class="more mtips" title="<?php _e('More Actions', 'kingcomposer'); ?>">
				<i class="fa fa-caret-right"></i>
				<div class="mme-more-actions">
					<ul>
						<li class="copy" title="<?php _e('Copy this element', 'kingcomposer'); ?>">
							<i class="fa fa-copy"></i> <?php _e('Copy', 'kingcomposer'); ?>
						</li>
						<li class="cut" title="<?php _e('Cut this element', 'kingcomposer'); ?>">
							<i class="fa fa-cut"></i> <?php _e('Cut', 'kingcomposer'); ?>
						</li>
						<li class="delete" title="<?php _e('Delete this element', 'kingcomposer'); ?>">
							<i class="fa fa-trash-o"></i> <?php _e('Delete', 'kingcomposer'); ?>
						</li>
					</ul>
				</div>
				<span class="mt-mes"><?php _e('More actions', 'kingcomposer'); ?></span>
			</li>
		</ul>
		<div class="kc-views-sections-wrap">
			<div class="kc-views-sections-label">
				<div class="add-section">
					<i class="sl-plus"></i> <span> <?php _e('Add', 'kingcomposer'); ?> {{kc.maps[sct].name}}</span>
				</div>
			</div>	
		</div>
	</div>
</script>
<script type="text/html" id="tmpl-kc-views-section-template">
	<#
		var icon = '';
		if( data.args.icon != undefined )
			icon = '<i class="'+data.args.icon+'"></i> ';
	#>
	<div class="kc-views-section<# if(data.first==true){ #> kc-section-active<# } #>">
		<h3 class="kc-vertical-label sl-arrow-down">{{{icon}}}{{data.args.title}}</h3>
		<ul class="kc-controls-2 kc-vs-control">
			<li class="right add mtips">
				<i class="sl-plus"></i>
				<span class="mt-mes"><?php _e('Add Elements', 'kingcomposer'); ?></span>
			</li>
			<li class="right double mtips">
				<i class="sl-docs"></i>
				<span class="mt-mes"><?php _e('Double this section', 'kingcomposer'); ?></span>
			</li>
			<li class="right settings mtips">
				<i class="sl-note"></i>
				<span class="mt-mes"><?php _e('Open settings', 'kingcomposer'); ?></span>
			</li>
			<li class="right delete mtips" title="<?php _e('Remove', 'kingcomposer'); ?>">
				<i class="sl-close"></i>
				<span class="mt-mes"><?php _e('Remove this section', 'kingcomposer'); ?></span>
			</li>
		</ul>
		<div class="kc-views-section-wrap kc-column-wrap">
			<div class="kc-element drag-helper">
				<a href="javascript:void(0)" class="kc-add-elements-inner">
					<i class="sl-plus"></i> <?php _e('Add Element', 'kingcomposer'); ?>
				</a>
			</div>
		</div>
	</div>
</script>
<script type="text/html" id="tmpl-kc-element-template">
	 <div class="kc-element {{data.params.name}}<# if(data.map.preview_editable == true){ #> viewEditable<# } #>">
		<div class="kc-element-icon"><span class="cpicon {{data.map.icon}}"></span></div>
		<span class="kc-element-label">{{data.map.name}}</span>
		<div class="kc-element-control" title="<?php _e('Drag to move this element', 'kingcomposer'); ?>">
			<ul class="kc-controls">
				<!--li class="move" title="<?php _e('Move', 'kingcomposer'); ?>">
					<i class="sl-cursor-move"></i>
				</li-->
				<li class="edit mtips" title="">
					<i class="sl-note"></i>
					<span class="mt-mes"><?php _e('Edit this element', 'kingcomposer'); ?></span>
				</li>
				<li class="double mtips" title="">
					<i class="sl-docs"></i>
					<span class="mt-mes"><?php _e('Double this element', 'kingcomposer'); ?></span>
				</li>
				<li class="more mtips" title="">
					<i class="fa fa-caret-right"></i>
					<div class="mme-more-actions">
						<ul>
							<li class="copy" title="<?php _e('Copy this element', 'kingcomposer'); ?>">
								<i class="fa fa-copy"></i> <?php _e('Copy', 'kingcomposer'); ?>
							</li>
							<li class="cut" title="<?php _e('Cut this element', 'kingcomposer'); ?>">
								<i class="fa fa-cut"></i> <?php _e('Cut', 'kingcomposer'); ?>
							</li>
							<li class="delete" title="<?php _e('Delete this element', 'kingcomposer'); ?>">
								<i class="fa fa-trash-o"></i> <?php _e('Delete', 'kingcomposer'); ?>
							</li>
						</ul>
					</div>
					<span class="mt-mes"><?php _e('More Actions', 'kingcomposer'); ?></span>
				</li>
			</ul>
		</div>
		<br />
	</div>
</script>
<script type="text/html" id="tmpl-kc-undefined-template">
	 <div class="kc-undefined kc-element {{data.params.name}}">
		<div class="admin-view content">{{data.params.args.content}}</div>
		<div class="kc-element-control">
			<ul class="kc-controls">
				<li class="move" title="<?php _e('Move', 'kingcomposer'); ?>">
					<i class="sl-cursor-move"></i>
				</li>
				<li class="double" title="<?php _e('Double', 'kingcomposer'); ?>">
					<i class="sl-docs"></i>
				</li>
				<li class="edit" title="<?php _e('Edit', 'kingcomposer'); ?>">
					<i class="sl-note"></i>
				</li>
				<li class="delete" title="<?php _e('Delete', 'kingcomposer'); ?>">
					<i class="sl-close"></i>
				</li>
			</ul>
		</div>		
	</div>
</script>
<script type="text/html" id="tmpl-kc-popup-template">
	<div class="kc-params-popup wp-pointer-top {{data.class}}<# if(data.bottom!=0){ #> posbottom<# } #>" style="<# if(data.bottom!=0){ #>bottom:{{data.bottom}}px;top:auto;<# }else{ #>top:{{data.top}}px;<# } #>left:{{data.left}}px;<#
			if( data.width != undefined ){ #>width:{{data.width}}px<# } 
		#>">
		<div class="m-p-wrap wp-pointer-content">
			<h3 class="m-p-header">
				{{data.title}}
				<# if( data.help != '' ){ #>
				<a href="{{data.help}}" target="_blank" title="<?php _e('Help', 'kingcomposer'); ?>" class="sl-help sl-func">
					&nbsp;
				</a>
				<# } #>
				<i title="<?php _e('Cancel & close popup', 'kingcomposer'); ?>" class="sl-close sl-func"></i>
				<i title="<?php _e('Save & close popup', 'kingcomposer'); ?>" class="sl-check sl-func"></i></h3>
			<div class="m-p-body">
				{{{data.content}}}
			</div>
			<# if( data.footer === true ){ #>
			<div class="m-p-footer">
				<ul class="m-p-controls">
					<li>
						<button class="button save button-large">
							<i class="sl-check"></i> {{data.save_text}}
						</button>
					</li>
					<li>
						<button class="button cancel button-large">
							<i class="sl-close"></i> {{data.cancel_text}}
						</button>
					</li>
					<li class="pop-tips"><i>{{data.footer_text}}</i></li>
				</ul>
			</div>
			<# } #>
			<# if( data.success_mesage !== undefined ){ #>
			<div class="m-p-overlay">{{{data.success_mesage}}}</div>
			<# } #>
		</div>
		<div class="wp-pointer-arrow"<#
				if( data.pos != undefined ){
					var css = '';
					if( data.pos == 'center' ){
						css += 'left:50%;margin-left:-13px;';
					}else if( data.pos == 'right' ){
						css += 'left:auto;right:50px;';
					}
					if( css != '' ){
						#> style="{{css}}"<#
					}
				}
			#>>
			<div class="wp-pointer-arrow-inner"></div>
		</div>
	</div>
</script>
<script type="text/html" id="tmpl-kc-field-template">
	<#
		var class_base = data.base;
		if( data.base.indexOf('[') > -1 ){
			class_base = class_base.replace(/\]\[/g,'-').replace( /[^a-zA-Z\-\_]/g, '' );
		}
	#>
	<div class="kc-param-row field-{{data.name}} field-base-{{class_base}}<# 
			if( data.relation != undefined ){
				#> relation-hidden<# 
			} 
		#>">
		<# if( data.label != undefined && data.label != '' ){ #>
		<div class="m-p-r-label">
			<label>{{{data.label}}}:</label>
		</div>
		<div class="m-p-r-content">
		<# }else{ #>
		<div class="m-p-r-content full-width">
		<# } #>	
			{{{data.content}}}
			<# if( data.des != undefined && data.des != '' ){ #>
				<div class="m-p-r-des">{{{data.des}}}</div>
			<# } #>
		</div>
	</div>
</script>

<script type="text/html" id="tmpl-kc-row-columns-template">
	<div class="kc-row-columns">
		&nbsp; <input type="checkbox" data-name="columnDoubleContent" id="m-r-c-double-content" {{kc.cfg.columnDoubleContent}} /> 
		<?php _e('Double content', 'kingcomposer'); ?> <a href="javascript:alert('<?php _e('Copy content in the last column to the newly-created column. This option is available when you choose to set the column amount greater than the current column amount', 'kingcomposer'); ?>.\n\n<?php _e('For example: Currently there is 1 column and you are going to set 2 columns', 'kingcomposer'); ?>')"> <i class="sl-question"></i> </a> &nbsp; &nbsp; 
		<input type="checkbox" data-name="columnKeepContent" id="m-r-c-keep-content" {{kc.cfg.columnKeepContent}} /> 
		<?php _e('Keep content', 'kingcomposer'); ?> <a href="javascript:alert('<?php _e('Keep content of the removed column and transfer it to the last existing column', 'kingcomposer'); ?>.\n\n<?php _e('This option is available when you choose to set the column amount smaller than the current column amount', 'kingcomposer'); ?>.\n\n<?php _e('For example: Currently there are 2 columns and you are going to set 1 column', 'kingcomposer'); ?>.')"> <i class="sl-question"></i> </a>
		<p></p>
		<button class="button button-large<#
			if( data.current == 1 ){
				#> active<#
			}	  
			#>" data-column="1">1 <?php _e('Column', 'kingcomposer'); ?> &nbsp;</button>
		<# for( var i=2; i<7; i++ ){ #>
			<button class="button button-large<#
			if( data.current == i ){
				#> active<#
			}
			#>" data-column="{{i}}">{{i}} <?php _e('Columns', 'kingcomposer'); ?></button>
		<# } #>
	</div>
</script>

<script type="text/html" id="tmpl-kc-box-design-template">
<#
	if( typeof data == 'object' && data.length > 0 ){
		
		data.forEach( function( item ){
			
	        if( typeof item.attributes != 'object' )
	        	item.attributes = {};
	        if( item.tag == 'a' && item.attributes.href == undefined )
	        	item.attributes.href = '';
	        
	        var classes = '';	
	        if( item.tag == 'icon' || item.tag == 'text' || item.tag == 'image' ){
	        	classes += ' kc-box-elm';
			}else if( item.tag == 'clumn' ){
				var ncl = 'one-one';
				if( item.attributes.class !== undefined ){
					['one-one','one-second','one-third','two-third'].forEach(function(cl){
						if( item.attributes.class.indexOf( cl ) > -1 )
							ncl = cl;
					});
				}
				classes += ' kc-column-'+ncl;
			}
			
			
	        if( item.attributes.cols != undefined )
	        	classes += ' kc-column-'+item.attributes.cols;
	        	
#>
			<div class="kc-box kc-box-{{item.tag}}{{classes}}" data-tag="{{item.tag}}" data-attributes='{{JSON.stringify(item.attributes)}}'>
		        <ul class="mb-header">
			        <li class="mb-toggle" data-action="toggle"><i class="mb-toggle fa-caret-down"></i></li>
			        <li class="mb-tag">{{item.tag}}</li>
			        <# if( item.attributes.id != undefined && item.attributes.id != '' ){ #>
			        	<li class="mb-id">Id: <span>{{item.attributes.id}}</span></li>
			        <# } if( item.attributes.class != undefined && item.attributes.class != '' ){ #>
			        	<li class="mb-class">
			        		Class: <span title="{{item.attributes.class}}">{{item.attributes.class.substr(0,30)}}..</span>
			        	</li>
			        <# } if( item.attributes.href != '' && item.attributes.href != undefined ){ #>
			        	<li class="mb-href">
			        		Href: <span title="{{item.attributes.href}}">{{item.attributes.href.substr(0,30)}}..</span>
			        	</li>
			        <# } #>
			        <li class="mb-funcs">
			        	<ul>
					        <li title="<?php _e('Remove', 'kingcomposer'); ?>" class="mb-remove mb-func" data-action="remove">
					        	<i class="sl-close"></i>
					        </li>
					        <# if( item.tag == 'text' ){ #>
					        <li  title="<?php _e('Edit with Editor', 'kingcomposer'); ?>"class="mb-edit mb-func" data-action="editor">
					        	<i class="sl-pencil"></i>
					        </li>
					        <# }else{ #>
					        <li  title="<?php _e('Settings', 'kingcomposer'); ?>"class="mb-edit mb-func" data-action="settings">
					        	<i class="sl-settings"></i>
					        </li>
					        <# } #>
					        <li title="<?php _e('Double', 'kingcomposer'); ?>" class="mb-double mb-func" data-action="double">
					        	<i class="sl-docs"></i>
					        </li>
					        <# if( item.tag != 'div' ){ #>
					        <li title="<?php _e('Add Node', 'kingcomposer'); ?>" class="mb-add mb-func" data-action="add" data-pos="inner"><i class="sl-plus"></i></li>
					        <# }else{ #>
					        <li title="<?php _e('Columns', 'kingcomposer'); ?>" class="mb-columns mb-func" data-action="columns"><i class="sl-list"></i></li>    
							<# } #>
						</ul>
				    </li>
		        </ul>
		        <div class="kc-box-body"><# 
			        
			        var empcol = false;
			        
		        	if( item.tag == 'div' ){
			        	if( item.children == undefined )
				        		empcol = true;
			        	else if( item.children.length == 0 )
				        		empcol = true;
				        else if( item.children[0].tag != 'column' )
				        	empcol = true;
			        }
			        
			        if( empcol == true ){
				        
				       #>{{{kc.template( 'box-design', [{ tag: 'column', attributes: { cols:'one-one' }, children: item.children }]
				       	)}}}<# 
				        
			        }else{
			        
			        	
				        if( empcol == true ){
					        #><div data-cols="one-one" class="kc-box-column one-one"><#
				        }	


				        if( item.tag == 'text' ){
					        if( item.content == undefined )
					        	item.content = 'Sample Text';
					        #>
								<div class="kc-box-inner-text" contenteditable="true">{{{item.content}}}</div>
						    <#
					    }else if( item.tag == 'image' ){
						    if( item.attributes.src == undefined )
						    	item.attributes.src = plugin_url+'/assets/images/get_start.jpg';
					        #>
								<img data-action="select-image" src="{{item.attributes.src}}" />
						    <#
					    }else if( item.tag == 'icon' ){
						    if( item.attributes.class == undefined )
						    	item.attributes.class = 'fa-leaf';
					        #>
							<span data-action="icon-picker"><i class="{{item.attributes.class}}"></i></span>
						    <#
					    }else{
				        
					       					        	
					        #>{{{kc.template( 'box-design', item.children )}}}<#
				        
				        }
				        
				        #><div class="kc-box mb-helper">
					        <a href="#" data-action="add" data-pos="inner">
						        <i class="sl-plus"></i> 
						        <?php _e('Add Node', 'kingcomposer'); ?>
						    </a>
					    </div>
				    
				    <# }/*EndIf*/ #>
				    
		        </div>
		    </div>
		    
		<#
		
		});
	}	
#>
</script>

<script type="text/html" id="tmpl-kc-param-group-template">

	<div class="kc-group-row">
		<div class="kc-group-controls">
			<ul>
				<li class="collapse" data-action="collapse" title="<?php _e('expand / collapse', 'kingcomposer' ); ?>">
					<i class="sl-arrow-down" data-action="collapse"></i>
				</li>
				<li class="counter"> #1 </li>
				<li class="delete" data-action="delete" title="<?php _e('Delete this group', 'kingcomposer' ); ?>">
					<i data-action="delete" class="sl-close"></i>
				</li>

				<li class="double" data-action="double" title="<?php _e('Double this group', 'kingcomposer' ); ?>">
					<i class="sl-docs" data-action="double"></i>
				</li>			
			</ul>
		</div>
		<div class="kc-group-body"></div>
	</div>

</script>

<script type="text/html" id="tmpl-kc-wp-widgets-element-template">
<ul class="kc-wp-widgets-ul kc-components-list" id="kc-wp-widgets-pop"><#
	kc.widgets.find('>div.widget').each(function(){
		var tit = jQuery(this).find('.widget-title').text(),
			des = jQuery(this).find('.widget-description').html(),
			base = '{"'+jQuery(this).find('input[name="id_base"]').val()+'":{}}';
			
#>	
		<li data-data="{{kc.tools.base64.encode(base)}}" data-category="wp_widgets" data-name="kc_wp_widget" title="{{des}}">
			<div>
				<span class="cpicon kc-icon-wordpress"></span>
				<span class="cpdes">
					<strong>{{tit}}</strong>
					<i>{{des}}</i>
				</span>
			</div>
		</li>
<#	
	});
#>
</ul>
<#
	data.callback = function( wrp, e ){
		wrp.find( 'li' ).on( 'click', e.data.items );
	}
#>
</script>

