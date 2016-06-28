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

	$kc = KingComposer::globe();
	$settings = $kc->settings();
	$plugin_info = get_plugin_data( KC_FILE );
	
?>
<style type="text/css">
	#kc-settings  div.group input[type="text"],
	#kc-settings  div.group input[type="password"]{
		height: 35px;
		box-shadow: none;
		display: block;
		margin-bottom: 5px;
		padding-left: 10px;
	}
	#kc-settings div.kc-badge{
		background-image: url('<?php echo KC_URL; ?>/assets/images/logo_white.png');
		color: #dedede;
	}
	#kc-settings div.p{
		padding-left: 10px; 
		padding-top:15px;
		display: block;
	}
	#kc-settings .button-large{
		height: 35px;
		line-height: 35px;
		padding-left: 20px;
		padding-right: 20px;
		font-weight: bold;
	}	
	#kc-settings p.radio{
		cursor: pointer;
	}
	.kc-notice{
	    background: rgb(253, 255, 196);
	    display: inline-block;
	    width: 100%;
	    border-radius: 2px;
	    margin: 20px 0;
	    box-shadow: 0 0 1px 0 rgba(0,0,0,0.2);
	}
	.kc-notice p{
		padding: 10px 20px;
		margin: 0px;
	}	
</style>
<div id="kc-settings" class="wrap about-wrap">
	<h1><?php _e('Welcome to', 'kingcomposer'); ?> KingComposer <?php echo esc_attr( $plugin_info['Version'] ); ?></h1>
	<div class="about-text">
		<?php _e('Thank you so much for choosing our product. We at king-theme are confident that you’ll be satisfied with the powerful features of KingComposer!', 'kingcomposer'); ?>
	</div>
	<div class="wp-badge kc-badge">
		<?php _e('Version', 'kingcomposer'); ?> <?php echo esc_attr( $plugin_info['Version'] ); ?>
	</div>
	<h2 class="nav-tab-wrapper">
		<a href="#kc_general_setting" class="nav-tab nav-tab-active" id="kc_general_setting-tab">
			<?php _e('General Settings', 'kingcomposer'); ?>
		</a>
		<!--a href="#kc_product_license" class="nav-tab" id="kc_product_license-tab">
			<?php _e('Plugin Update', 'kingcomposer'); ?>
		</a-->
		<!--a href="<?php echo admin_url(); ?>/admin.php?page=kc-sections-manager" class="nav-tab">
			<?php _e('Sections Manager', 'kingcomposer'); ?>
		</a-->
		<!--a href="#kc_about_us" class="nav-tab" id="kc_about_us-tab">
			<?php _e('About Us', 'kingcomposer'); ?>
		</a-->
	</h2>
	<form method="post" action="options.php" enctype="multipart/form-data" id="kc-settings-form">
		<?php settings_fields( 'kingcomposer_group' ); ?>
		<div id="kc_general_setting" class="group p">
			<?php
				
				$update_plugin = get_site_transient( 'update_plugins' );

			    if ( isset( $update_plugin->response[ KC_BASE ] ) )
				{
			?>
			<div class="kc-notice">
				<p>
					<i class="dashicons dashicons-warning"></i> 
					<?php
						printf( __('There is a new version of KingComposer available, please go to %s to update', 'kingcomposer'),	
							'<a href="'.admin_url('/plugins.php').'">Plugins</a>'
						); ?>.
				</p>
			</div>
			<?php			
				}
			?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<?php _e('Supported Content Types', 'kingcomposer'); ?>:
						</th>
						<td>
							<?php
								
								$post_types = get_post_types( array( 'public' => true ) );
								$ignored_types = array('attachment');
								$settings_types = $kc->get_content_types();
								$required_types = $kc->get_required_content_types();
			
								foreach( $post_types as $type ){
									if( !in_array( $type, $ignored_types ) ){
										echo '<p class="radio"><input ';
										if( in_array( $type, $settings_types ) )
											echo 'checked ';
										if( in_array( $type, $required_types ) )
											echo 'disabled ';	
										echo'type="checkbox" name="kc_options[content_types][]" value="'.esc_attr($type).'"> ';
										echo esc_html( $type );
										if( in_array( $type, $required_types ) )
											echo ' <i> (required)</i>';
										echo '</p>';
									}
								}
								
							?>
							
							<br />
							<span class="description">
								<p>
									- <?php _e('Besides page and post above, you can set any content type to be available with KingComposer such as gallery, contact form and so on', 'kingcomposer'); ?>. 
								</p>
								<p>
									- <?php _e('If your', 'kingcomposer'); ?> <strong>"Custom Post-Type"</strong> 
									<?php _e('does not show here? Please make sure that has been registered with', 'kingcomposer'); ?> 
									<strong>"public = true"</strong>
								</p>
								<p>
									- <?php _e('Put this code on action "init" to force support', 'kingcomposer'); ?>:  
									<strong>global $kc; $kc->add_content_type( 'your-post-type-name' );</strong>
								</p>
							</span>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<?php _e('Load Icons in the Front-End', 'kingcomposer'); ?>:
						</th>
						<td>
							<input type="radio" name="kc_options[load_icon]" <?php if($settings['load_icon']!='no')echo 'checked'; ?>  value="yes" /> 
							<?php _e('Yes, Please!', 'kingcomposer'); ?>
							&nbsp; &nbsp; <input type="radio" name="kc_options[load_icon]" <?php if($settings['load_icon']=='no')echo 'checked'; ?>   value="no" /> 
							<?php _e('No, Thanks!', 'kingcomposer'); ?>
							<span class="description">
								<p>
									<br />
									<?php _e('If you load icons, they will display on front-end and vice versa.', 'kingcomposer'); ?>:
								</p>
								<p>
									<?php _e('Notice; There are 3 icons packs use in KingComposer as following :', 'kingcomposer'); ?>:
								</p>	
								
								<ol>
									<li>
										<a href="http://fortawesome.github.io/Font-Awesome/icons/" target=_blank>
											Font Awesome Icons
										</a>
										&nbsp;
										<?php _e('Usage', 'kingcomposer'); ?> 
										<strong>&lt;i class="fa-search"&gt;&lt;/i&gt;</strong> 
									</li>
									<li>
										<a href="http://rhythm.nikadevs.com/content/icons-et-line" target=_blank>
											ET Line Icons
										</a>
										&nbsp;
										<?php _e('Usage', 'kingcomposer'); ?> : 
										<strong>&lt;i class="et-search"&gt;&lt;/i&gt;</strong> 
									</li>
									<li>
										<a href="http://thesabbir.github.io/simple-line-icons/" target=_blank>
											Simple Line Icons
										</a>
										&nbsp;
										<?php _e('Usage', 'kingcomposer'); ?> :  
										<strong>&lt;i class="sl-search"&gt;&lt;/i&gt;</strong>
									</li>
								</ol>
							</span>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<?php _e('Css Code', 'kingcomposer'); ?>:
						</th>
						<td>
							<textarea name="kc_options[css_code]" cols="100" rows="15"><?php 
								echo esc_html( $settings['css_code'] ); 
							?></textarea>
							<span class="description">
								<p>
									<?php _e('Add your custom CSS code to modify or apply additional styling to the Front-End', 'kingcomposer'); ?>. 
								</p>
							</span>
						</td>
					</tr>
				</tbody>
			</table>
			<br />
			<p class="submit">
				<input type="submit" class="button button-large button-primary" value="<?php _e('Save Change', 'kingcomposer'); ?>" />
    		</p>		
		</div>
		<div id="kc_product_license" class="group p" style="display:none">
			<?php
			    if ( isset( $update_plugin->response[ KC_BASE ] ) )
				{
			?>
			<div class="kc-notice">
				<p>
					<i class="dashicons dashicons-warning"></i> 
					<?php
						printf( __('After submitting license informations, please go to %s to update', 'kingcomposer'),	
							'<a href="'.admin_url('/plugins.php').'">Plugins</a>'
						); ?>.
				</p>
			</div>
			<?php			
				}	
			?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<?php _e('Envato Username', 'kingcomposer'); ?>:
						</th>
						<td>
							<input type="text" class="regular-text" name="kc_options[envato_username]" value="<?php echo esc_attr( $settings['envato_username'] ); ?>" />
							<span class="description">
							<p>
								<?php _e('Enter your Envato username.', 'kingcomposer'); ?>
							</p>		
						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e('Secret API Key', 'kingcomposer'); ?>:
						</th>
						<td>
							<input type="password" class="regular-text" name="kc_options[api_key]" value="<?php echo esc_attr( $settings['api_key'] ); ?>" />
							<span class="description">
							<p>
								<?php _e('Enter your Secret API Key', 'kingcomposer'); ?>. 
								<a href="http://docs.kingcomposer.com/documentation/how-to-get-api-key/" target=_blank>
									<?php _e('How to find API Key', 'kingcomposer'); ?>?
								</a>
							</p>	
						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e('KingComposer License Key', 'kingcomposer'); ?>:
						</th>
						<td>
							<input onchange="document.getElementById('kc-stt-theme-key').value=''" id="kc-stt-plugin-key" type="text" class="regular-text" name="kc_options[license_key]" value="<?php echo esc_attr( $settings['license_key'] ); ?>" />
							<span class="description">
							<p>
								<?php _e('Enter King Composer license key from CodeCanyon', 'kingcomposer'); ?>. 
								<a href="http://docs.kingcomposer.com/documentation/how-to-find-purchase-code/" target=_blank>
									<?php _e('How to find Purchase Code', 'kingcomposer'); ?>?
								</a>
							</p>
							<br />
						</td>
					</tr>
					<tr style="border: 1px dashed #aaa;display: none;">
						<th scope="row">
							<br />
							&nbsp; &nbsp; &nbsp; 
							<?php _e('Theme Purchase Code', 'kingcomposer'); ?>:
						</th>
						<td>
							<br />
							<input onchange="document.getElementById('kc-stt-plugin-key').value=''" id="kc-stt-theme-key" type="text" class="regular-text" name="kc_options[theme_key]" value="<?php echo esc_attr( $settings['theme_key'] ); ?>" />
							<span class="description">
							<p>
								<?php _e('If you\'ve bought a theme which was included KingComposer, You can use the Purchase Code of the theme to update KingComposer', 'kingcomposer'); ?>. <br />
								<a href="http://docs.kingcomposer.com/documentation/how-to-find-theme-purchase-code/" target=_blank>
									<?php _e('How to find Theme Purchase Code', 'kingcomposer'); ?>?
								</a>
							</p>
							<br />	
						</td>
					</tr>
				</tbody>
			</table>
			<br />
			<p class="submit">
				<input type="submit" class="button button-large button-primary" value="<?php _e('Save Change', 'kingcomposer'); ?>" />
    		</p>			
		</div>
		<div id="kc_about_us" class="group p" style="display:none">
			About Us
		</div>	
		
	</form>
</div>

<script>
    jQuery(document).ready(function($) {
        $('.nav-tab-wrapper a').on( 'click', function(e) {
	        var clicked = $(this).attr('href');
	        if( clicked.indexOf('#') == -1 )
	        	return true;
            $('.nav-tab-wrapper a').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active').blur();
            $('.group').hide();
            $(clicked).fadeIn();
            if (typeof(localStorage) != 'undefined' ) {
                localStorage.setItem('activeTab', clicked );
            }
            e.preventDefault();
        });
        $('p.radio').on('click',function(e){
	        if( e.target.tagName != 'INPUT' ){
	        	var inp = $(this).find('input').get(0);
	        	if( inp.disabled == true )
		        	e.preventDefault();
				else if( inp.checked == true )
	        		inp.checked = false;
	        	else inp.checked = true;	
	        }	
        });
        if (typeof(localStorage) != 'undefined' ) {
            activeTab = localStorage.getItem('activeTab');
            if( activeTab != undefined ){
	            $('.nav-tab-wrapper a[href='+activeTab+']').trigger('click');
            }
        }
        if(window.location.href.indexOf('#')>-1)
        	$('.nav-tab-wrapper a[href=#'+window.location.href.split('#')[1]+']').trigger('click');
    });
</script>