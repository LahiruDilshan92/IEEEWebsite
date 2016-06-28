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



/*
*	admin init
*/



add_action('admin_init', 'kc_admin_init');
function kc_admin_init() {

	if (get_option('kc_do_activation_redirect', false)) {

	    delete_option('kc_do_activation_redirect');

	    if( !isset($_GET['activate-multi']) )
	    	wp_redirect("admin.php?page=kingcomposer");
	}
	/* register kc options */
	register_setting( 'kingcomposer_group', 'kc_options', 'kc_validate_options' );
	
	$roles = array( 'administrator', 'admin', 'editor' );

	foreach ( $roles as $role ) {
		if( ! $role = get_role( $role ) ) 
			continue;
		$role->add_cap( 'access_KingComposer'  );
	}
		

}


register_activation_hook( KC_FILE, 'kc_plugin_activate' );
function kc_plugin_activate() {
	add_option('kc_do_activation_redirect', true);
}



/*
*	Load languages
*/



add_action('plugins_loaded', 'kc_load_lang');
function kc_load_lang() {
	load_plugin_textdomain( 'kingcomposer', false, KC_SLUG . '/locales/' );
}



/*
*	Register assets ( js, css, font icons )
*/


add_action('admin_enqueue_scripts', 'kc_assets', 1 );
function kc_assets(){
	
	global $kc;
	
	wp_enqueue_style('kc-global', KC_URL.'/assets/css/kc.global.css', false, KC_VERSION );

	// Stop loading assets from admin if not in allows content type
	if( is_admin() && !kc_admin_enable() )
		return;
	
	wp_enqueue_script( 'wp-util' );
	
	$p = KC_URL.'/assets/css/';
	
	$args = array( 
		'builder' => $p.'kc.builder.css', 
		'params' => $p.'kc.params.css', 
		//'icons' => $p.'icons.css'
	);
	
	$icon_sources = $kc->get_icon_sources();
	if(  is_array( $icon_sources ) && count( $icon_sources ) > 0 ){
		$i = 1;
		foreach( $icon_sources as $icon_source ){
			$args['sys-icon-'.$i++] = $icon_source;
		}
	}
	
	$args = apply_filters( 'kc-core-styles', $args );
	
	if( KingComposer::is_live() ){
		$args['live'] = KC_URL.'/includes/frontend/builder/assets/kc.live.builder.css';
	}
	
	foreach( $args as $k => $v ){
		wp_enqueue_style('kc-'.$k, $v, false, KC_VERSION );
	}

	wp_register_script( 'kc-builder-backend-js', KC_URL.'/assets/js/kc.builder.js', array('jquery','wp-util'), KC_VERSION, true );
	wp_enqueue_script( 'kc-builder-backend-js' );

	$p = '/assets/js/kc.';
	$args = apply_filters( 'kc-core-scripts', array( 
		'tools' => $p.'tools.js', 
		'views' => $p.'views.js', 
		'params' => $p.'params.js', 
		'jscolor' => $p.'vendors/jscolor.js', 
		'pikaday' => $p.'vendors/pikaday.js', 
		'freshslider' => $p.'vendors/freshslider.min.js') 
	);
	
	if( KingComposer::is_live() ){
		$args['front-builder'] = '/includes/frontend/builder/assets/kc.front.js';
		$args['front-detect'] = '/includes/frontend/builder/assets/kc.detect.js';
	}
	
	foreach( $args as $k => $v ){
		wp_register_script( 'kc-'.$k, KC_URL.$v, null, KC_VERSION, true );
		wp_enqueue_script( 'kc-'.$k );
	}

	wp_enqueue_media();
	wp_enqueue_style( 'wp-pointer' );
	
}



/**
*	Register filter for menu title
*/


function kc_filter_admin_menu_title( $menu_title ){

	$current = get_site_transient( 'update_plugins' );

    if ( ! isset( $current->response[ KC_BASE ] ) )
		return $menu_title;

	return $menu_title . '&nbsp;<span class="update-plugins"><span class="plugin-count">1</span></span>';
}

add_filter( 'kc_admin_menu_title', 'kc_filter_admin_menu_title');


/*
*	Add Menu Page in Backend
*/

add_action('admin_bar_menu', 'kc_admin_bar', 999 );
function kc_admin_bar( $wp_admin_bar ) {
	
	if( !is_admin() ){
		
		$kc = KingComposer::globe();
		if( $kc->user_can_edit() !== false ){
			$wp_admin_bar->add_node(array(
				'id'    => 'kc-edit',
				'title' => 'Live Edit<style>#wpadminbar #wp-admin-bar-kc-edit>.ab-item:before {content: "\f464";top: 2px;}</style>',
				'href'  => admin_url('?page=kingcomposer&kc_action=live-editor&id='.get_the_id())
			));
		}
	}
}

/*
*	Register settings page
*/


add_action('admin_menu', 'kc_settings_menu');
function kc_settings_menu() {
	
	$capability = apply_filters( 'access_KingComposer_capability', 'access_KingComposer' );
	$icon = KC_URL.'/assets/images/icon_100x100.png';
	$menu_title = apply_filters( 'kc_admin_menu_title', __( 'King Composer' , 'kingcomposer' ) );

	add_menu_page(
		 __( 'King Composer WP' , 'kingcomposer' ),
		$menu_title,
		$capability,
		'kingcomposer',
		'kc_main_page',
		$icon
	);

	remove_submenu_page( 'kingcomposer', 'kingcomposer' );

	add_submenu_page(
		'kingcomposer',
		esc_html__('King Composer WP', 'kingcomposer'),
		esc_html__('Composer Settings', 'kingcomposer'),
		$capability,
		'kingcomposer',
		'kc_main_page'
	);

	add_submenu_page(
		'kingcomposer',
		esc_html__('Sections Manager - King Composer', 'kingcomposer'),
		esc_html__('Sections Manager', 'kingcomposer'),
		$capability,
		'kc-sections-manager',
		'kc_sections_manager'
	);

}



add_action( 'admin_head', 'kc_admin_header' );
add_action( 'edit_form_after_editor', 'kc_after_editor' );
add_action( 'admin_footer', 'kc_admin_footer' );



/*
*	Header init
*/



function kc_admin_header(){

	if( is_admin() && !kc_admin_enable() )
		return;
	
	$kc = KingComposer::globe();
	
?>
<script type="text/javascript">

	var site_url = '<?php echo site_url(); ?>',
		plugin_url = '<?php echo KC_URL; ?>',
		shortcode_tags = '<?php

			global $shortcode_tags;

			$arrg = array();
			$maps = $kc->get_maps();

			foreach( $maps as $key => $val ){
				array_push( $arrg, $key );
			}

			foreach( $shortcode_tags as $key => $val ){
				if( !in_array( $key, $arrg ) )
					array_push( $arrg, $key );
			}

			echo implode( '|', $arrg );
		
		?>',
		<?php 
			
			if( isset( $_GET['id'] ) )
				echo 'kc_post_ID = "'.$_GET['id'].'",'; 
		?>
		kc_version = '<?php echo KC_VERSION; ?>',
		kc_ajax_url = "<?php echo site_url('/wp-admin/admin-ajax.php'); ?>",
		kc_profiles = <?php echo $kc->get_profiles_db( false ); ?>,
		kc_profiles_external = <?php echo json_encode( (object)$kc->get_profile_sections() ); ?>,
		kc_ajax_nonce = '<?php echo wp_create_nonce( "kc-nonce" ); ?>';

		<?php 
			if( get_option('kc_tkl_cc') ){
				echo get_option('kc_tkl_cc', true);
			}
		?>
		
</script>
<?php
}

/*
*	Put post settings forms after editor
*/


function kc_after_editor( $post ) {

	if( !is_admin() || !kc_admin_enable() )
		return;
		
	?>
	<div style="display:none;" id="kc-post-settings">
		
		<?php
			
			$data = array( "mode" => "", "classes" => "", "css" => "" );
			
			if( isset( $post ) && isset( $post->ID ) && !empty( $post->ID ) ){
				$data = get_post_meta( $post->ID , 'kc_data', true );
				if( empty( $data ) ){
					$data = array( "mode" => "", "classes" => "", "css" => "" );
				}
			}

		?>
		
		<input type="hidden" name="kingcomposer_meta[mode]" id="kc-post-mode" value="<?php echo esc_attr( $data['mode'] ); ?>" />
		<input type="hidden" name="kingcomposer_meta[classes]" id="kc-page-body-classes" value="<?php echo esc_attr( $data['classes'] ); ?>" />
		<textarea id="kc-page-css-code" name="kingcomposer_meta[css]" ><?php echo esc_attr( $data['css'] ); ?></textarea>
		
		<?php 
			
			if( $data['mode'] == 'kc' ){
				echo '<style type="text/css">#postdivrich{visibility: hidden;position:relative;}</style>';
			}			
		?>
		
		<script tyle="text/javascript">
			var kc_editor_tabs = document.querySelectorAll('#wp-content-editor-tools .wp-editor-tabs');
			if( kc_editor_tabs[0] !== undefined ){
				var kc_btn = document.createElement('button');
				kc_btn.type = 'button'; kc_btn.id = 'kc-switch-builder';
				kc_btn.innerHTML = '<img src="<?php echo KC_URL; ?>/assets/images/icon.png" width="20" /> King Composer';
				kc_editor_tabs[0].appendChild( kc_btn );
				<?php if( $data['mode'] == 'kc' ){ ?>document.getElementById('postdivrich').className += ' first-load';<?php } ?>
			}
		</script>
		
	</div>
	<?php

}



/*
*	Load builder template at footer
*/

function kc_admin_footer(){

	if( is_admin() && !kc_admin_enable() )
		return;

	do_action('kc_before_footer');
	
	require_once KC_PATH.'/includes/kc.js_languages.php';
	require_once KC_PATH.'/includes/kc.nocache_templates.php';
	
	if( KingComposer::is_live() ){
		
		require_once KC_PATH.'/includes/frontend/builder/kc.templates.php';
	
	}
	
	do_action('kc_after_footer');
	
}


/*
*	Save post settings
*/


add_action( 'save_post', 'kc_process_save', 10, 2 );
function kc_process_save( $post_id, $post ) {

	if( !empty( $_POST['kingcomposer_meta'] ) ){
		if( !add_post_meta( $post->ID , 'kc_data' , $_POST['kingcomposer_meta'], true ) ){
			update_post_meta( $post->ID , 'kc_data' , $_POST['kingcomposer_meta'] );
		}
	}else if( !isset( $_POST['action'] ) || ( isset( $_POST['action'] ) && $_POST['action'] != 'kc_instant_save' ) ){
		//delete_post_meta( $post->ID , 'kc_data' );
	}

}

/*
*	Include admin pages' file
*/


function kc_main_page() {

	if( KingComposer::is_live() )
		require_once KC_PATH.KDS.'includes'.KDS.'kc.live.builder.php';
	else require_once KC_PATH.KDS.'includes'.KDS.'kc.settings.php';

}

function kc_sections_manager() {

	require_once KC_PATH.KDS.'includes'.KDS.'kc.sections.php';

}
