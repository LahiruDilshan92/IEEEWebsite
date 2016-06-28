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

class kc_front{

	public $KC_URL;
	private $allows = null;
	private $scripts = array();
	private $styles = array();
	private $css = '';
	private $css_responsive = array();
	private $js = '';
	private $pattern_filter = '';
	private $tags_filter = array();
	private $action = null;
	private $storage = array();
	
	private $ex_styles = array();
	private $ex_scripts = array();

	public function __construct(){

		$this->KC_URL = trailingslashit( KC_URL ).'assets/frontend/';

		add_action( 'wp_enqueue_scripts', array( &$this, 'before_header' ), 9999 );
		add_action( 'wp_head', array( &$this, 'kc_front_head' ), 999 );
		add_action( 'wp_footer', array( &$this, 'kc_front_footer' ), 1 );

		add_filter('body_class', array( &$this, 'body_classes' ) );

		$icl_array = array(
			'helper.functions.php' =>  KC_PATH.'/includes/frontend/helpers/',
			'shortcodes.filters.php' =>  KC_PATH.'/includes/frontend/helpers/'
		);

		foreach( $icl_array as $file => $dir ) {

			if( file_exists( trailingslashit($dir).$file ) )
				include trailingslashit($dir).$file;

		}
		
		$this->kc_add_filters();
		
		if( isset( $_GET['kc_action'] ) && !empty( $_GET['kc_action'] ) )
			$this->action = sanitize_title( $_GET['kc_action'] );
		
		if( $this->action == 'live-editor' )
			show_admin_bar(false);

	}

	public static function globe(){
		
		global $kc_front;
		
		if( isset( $kc_front ) )
			return $kc_front;
		else wp_die('KingComposer Error: Global varible could not be loaded.');
		
	}

	public function before_header(){

		// Get access of curent page
		// Return to $this->allows

		if( $this->allowed_access() ){
			
			global $post, $kc;

			$this->register_assets();
			$this->load_scripts();

			$this->kc_front_builder_load( $kc );
			$post->post_content = '<div class="kc_clfw"></div>'.$post->post_content;
			$content = trim( $post->post_content );
				
			if( $this->action != 'live-editor' && empty( $content ) )
				return false;
			
			remove_filter( 'the_content', 'wpautop' );
			remove_filter( 'the_content', 'shortcode_unautop' );
			
			$post->post_content = apply_filters( 
				'kc-content-after', 
				$this->do_filter_shortcode( 
					apply_filters( 'kc-content-before', $post->post_content )
				)
			);

		}
	}
	
	public function kc_add_filters(){

		foreach(
			array(
				'row',
				'column',
				'column_inner',
				'box',
				'tabs',
				'tab',
				'twitter_feed',
				'pie_chart',
				'button',
				'flip_box',
				'google_maps',
				'video_play',
				'counter_box',
				'carousel_images',
				'carousel_post',
			) as $k => $v ){

			add_filter( 'shortcode_kc_'.$v, 'kc_'.$v.'_filter' );

		}

	}
	
	public function kc_front_builder_load( $kc ){
		
		if( $this->action == 'live-editor' ){
			
			if( $kc->user_can_edit() === false )
				wp_die('<strong>King Composer</strong><br /><br />You do not have permission to edit this page. <a href="'.admin_url().'">Please  login</a> or edit <a href="'.admin_url('edit.php?post_type=page').'">the pages</a> that you have the permission.</p>');
				
			foreach( $this->scripts as $script )
				wp_enqueue_script( $script );

			foreach( $this->styles as $style )
				wp_enqueue_style( $style );
			
			add_filter( 'kc-content-after', array( &$this, 'bottom_builder' ) );
			
		}
	
	}
	
	public function bottom_builder( $content ){
		
		ob_start();
			include KC_PATH.KDS.'includes'.KDS.'frontend'.KDS.'builder'.KDS.'kc.bottom.builder.php';
			$content .= ob_get_contents();
		ob_end_clean();
		
		return $content;
		
	}
	
	public function do_filter_shortcode( $content ){
		
		global $shortcode_tags;
		
		$this->tags_filter = array();
		$content = preg_replace_callback( '@\[([^<>&/\[\]\x00-\x20]++)@', array( &$this, 'do_shortcode_alter' ), $content );
		$tagnames = array_intersect( array_keys( $shortcode_tags ), $this->tags_filter );

		if ( empty( $tagnames ) )
			return $content;

		$pattern_filter = get_shortcode_regex( $tagnames );
		
		return preg_replace_callback( "/$pattern_filter/", array( &$this, 'do_shortcode_tag' ), $content );

	}

	public function do_shortcode_alter( $m ){

		$al = preg_replace( "/[^\#]/", '', $m[1] );

		if( !empty( $al ) )
			$m[0].= ' __="'.$al.'"';
		else
			array_push( $this->tags_filter, $m[1] );

		return $m[0];

	}

	public function do_shortcode_tag( $m ){

		$kc = KingComposer::globe();

		if ( $m[1] == '[' && $m[6] == ']' )
	        return substr($m[0], 1, -1);

	    $tag =  $m[2];

		$atts = shortcode_parse_atts( $m[3] );

		$closed = substr( $m[0], strlen( $m[0] ) - strlen( $tag ) - 3 );

		// If this shortcode has been disabled
		if( isset( $atts['disabled'] ) && $atts['disabled'] == 'on' )
			return '';

		// Move all custom css to header css
		if( isset( $atts['css'] ) ){

			$css = explode( '|', $atts['css'] );

			if( isset( $css[1] ) && !empty( $css[1] ) && strpos( $this->css, '.'.$css[0].'{'.$css[1].'}' ) === false )
			{
				$atts['css'] = $css[0];
				$atts['css_data'] = $css[1];
				$this->css .= '.'.trim($css[0]).'{'.$css[1].'}';
			}else{
				unset( $atts['css'] );
			}

		}

		if( is_array( $atts ) ){
			foreach( $atts as $k => $v ){

				if( is_string( $v ) ){
					if( $k == '__empty__' )
						$atts[$k] = '';
					else $atts[$k] = $kc->unesc( $v );
				}

			}
		}

		$atts['__name'] = $tag;

		// add # for name of container
		if( isset( $atts['__'] ) ){
			$atts['__name'] .= $atts['__'];
			unset( $atts['__'] );
		}

		if( $closed == '[/'.esc_attr( $tag ).']' ){

			if ( isset( $m[5] ) && !empty( $m[5] ) )
				$atts['__content'] = $this->do_filter_shortcode( str_replace( $tag.'#', $tag, $m[5] ) );
			else
				$atts['__content'] = '';

		}

		$new_atts = '';

		$new_atts = apply_filters( 'shortcode_'.$tag, $atts );

		if( !is_array( $new_atts ) )
			$new_atts = $atts;
			
		return $m[1] . $this->filter_return( $new_atts ) .$m[6];

	}

	public function filter_return( $atts ){
	
		$kc = KingComposer::globe();
		
		$full = '['.$atts['__name'];

		foreach( $atts as $k => $v ){
			if( $k != '__name' && $k != '__content' )
				$full .= ' '.$k.'="'.esc_attr($v).'"';
		}

		$full .= ']';
		
		if( isset( $atts['__content'] ) ){

			$full .= $atts['__content'];
			
			if( $this->action == 'live-editor' ){
				$pure_name = str_replace( '#', '', $atts['__name'] );
				if( $pure_name == 'kc_column' || $pure_name == 'kc_column_inner' || in_array( $pure_name, $kc->maps_views ) ){
					$full .= '<div class="kc-element drag-helper" data-model="-1"><a href="javascript:void(0)" class="kc-add-elements-inner"><i class="sl-plus"></i> '.__( 'Add Elements', 'kingcomposer' ).'</a></div>';
				}
			}	
			
			$full .= '[/'.$atts['__name'].']';
		}
		
		if( $this->action == 'live-editor' ){
			
			if( isset( $atts['__name'] ) )
				$atts['__name'] = explode( '#', $atts['__name'] );
				
			if( isset( $atts['__content'] ) ){
				$atts['content'] = preg_replace( '/<!--(.*)-->/Uis', '', $atts['__content'] );
				unset( $atts['__content'] );
			}
				
			$model = count( $this->storage );
			$storage = array( 'args' => $atts, 'name' => $atts['__name'][0], 'full' => preg_replace( '/<!--(.*)-->/Uis', '', $full ) );
			
			if( isset( $atts['content'] ) )
				$storage['end'] = '[/'.$storage['name'].']';
			
			$this->storage[ $model ] = $storage;
			
			$full = '<!--kc s '.$model.'-->'.trim($full).'<!--kc e '.$model.'-->';
			
			
		}
		
		return $full;

	}

	public function kc_front_head(){

		if( $this->allows ){

			?><script type="text/javascript">function kc_viewport(st){var d=document;if(d.compatMode==='BackCompat'){if(st=='height')return d.body.clientHeight;else return d.body.clientWidth}else{if(st=='height')return d.documentElement.clientHeight;else return d.documentElement.clientWidth}}function kc_row_action(force){var d=document;[].forEach.call(d.querySelectorAll('div[data-kc-fullwidth]'),function(el){if(force!==undefined&&force===true){if(el.getAttribute('data-kc-action')=='loaded')return;else el.setAttribute('data-kc-action','loaded')}var kc_clfw=d.querySelectorAll('.kc_clfw')[0];if(el.offsetWidth!=kc_viewport('width')){var rect=kc_clfw.getBoundingClientRect();el.style.left=(-rect.left)+'px';if(el.getAttribute('data-kc-fullwidth')=='row'){el.style.paddingLeft=rect.left+'px';el.style.paddingRight=(kc_viewport('width')-rect.width-rect.left)+'px';el.style.width=rect.width+'px'}else{el.style.width=kc_viewport('width')+'px'}}if(el.nextElementSibling!==null&&el.nextElementSibling.tagName=='SCRIPT'){if(el.nextElementSibling.innerHTML=='kc_row_action(true);'){el.parentNode.removeChild(el.nextElementSibling)}}})}<?php

			$this->render_dynamic_js();

			?></script><?php

			$this->render_dynamic_css();
		}
	}

	public function kc_front_footer(){
		
		if( $this->action == 'live-editor' )
			include KC_PATH.KDS.'includes'.KDS.'frontend'.KDS.'builder'.KDS.'kc.live.footer.php';
		
	}

	public function register_assets() {
	
	
		$this->register_style('kc-prettyPhoto', $this->vendor_script_url('prettyPhoto/css','prettyPhoto.css'));
		$this->register_style('kc-owl-theme', $this->vendor_script_url('owl-carousel','owl.theme.css'));
		$this->register_style('kc-owl-carousel', $this->vendor_script_url('owl-carousel','owl.carousel.css'));
		
		$this->styles = apply_filters( 'kc_register_styles', array() );
		if( is_array( $this->styles ) && count( $this->styles ) ){
			foreach( $this->styles as $sid => $url ){
				$this->register_style( $sid, $url );
			}
		}
		
		#Register vonder scripts

		$this->register_script('kc-owl-carousel', $this->vendor_script_url('owl-carousel','owl.carousel.min.js'));

		$this->register_script('kc-countdown-timer', $this->vendor_script_url('countdown','jquery.countdown.min.js'));
		
		$this->register_script('kc-progress-bars', $this->KC_URL. 'js/progress-bar.js');

		$this->register_script('kc-easypiechart', $this->KC_URL. 'js/jquery.easypiechart.js');

		$this->register_script('kc-waypoints-min', $this->vendor_script_url('waypoints','waypoints.min.js'));
		$this->register_script('kc-counter-up', $this->KC_URL. 'js/jquery.counterup.js');

		$this->register_script('kc-masonry-min', $this->vendor_script_url('masonry','jquery.masonry.min.js'));

		$this->register_script('kc-youtube-api', 'https://www.youtube.com/iframe_api');
		$this->register_script('kc-vimeo-api', 'https://f.vimeocdn.com/js/froogaloop2.min.js');
		$this->register_script('kc-video-play', $this->KC_URL . 'js/kc-video-play.js');

		//lightbox script have to add latest
		$this->register_script('kc-prettyPhoto', $this->vendor_script_url('prettyPhoto/js','jquery.prettyPhoto.js') );
		
		$this->scripts = apply_filters( 'kc_register_scripts', array() );
		if( is_array( $this->scripts ) && count( $this->scripts ) ){
			foreach( $this->scripts as $sid => $url ){
				$this->register_script( $sid, $url );
			}
		}

	}

	public function load_scripts(){

		$kc = KingComposer::globe();
		$settings = $kc->settings();

		$styles = array(
			'kc-general' => array(
				'src'     => $this->KC_URL.'css/KingComposer.css',
				'deps'    => '',
				'version' => KC_VERSION,
				'media'   => 'all'
			),
			'kc-shortcodes' => array(
				'src'     => $this->KC_URL.'css/shortcodes.css',
				'deps'    => '',
				'version' => KC_VERSION,
				'media'   => 'all'
			)
		);
		
		if( $this->action == 'live-editor' ){
			$styles['kc-front-builder'] = array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', trailingslashit( KC_URL ) ) . 
							 '/includes/frontend/builder/assets/kc.front.builder.css',
				'deps'    => '',
				'version' => KC_VERSION,
				'media'   => 'all'
			);
			$styles['kc-backend-builder'] = array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', trailingslashit( KC_URL ) ) . 
							 '/assets/css/kc.builder.css',
				'deps'    => '',
				'version' => KC_VERSION,
				'media'   => 'all'
			);
		}
		
		if( $settings['load_icon'] != 'no' )
		{
			$icon_sources = $kc->get_icon_sources();
			if(  is_array( $icon_sources ) && count( $icon_sources ) > 0 ){
				$i = 1;
				foreach( $icon_sources as $icon_source ){
					$styles['kc-icon-'.$i++] = array(
						'src'     => $icon_source,
						'deps'    => '',
						'version' => KC_VERSION,
						'media'   => 'all'
					);
				}
			}
		}

		foreach ( apply_filters( 'kc_enqueue_styles', $styles ) as $handle => $args ) {
			wp_enqueue_style( $handle, $args['src'], $args['deps'], $args['version'], $args['media'] );
		}
		
		$scripts = array(
			'kc-viewportchecker' => $this->vendor_script_url('viewportchecker','viewportchecker.js'),
			'kc-page-builder' => $this->KC_URL . 'js/KingComposer.js'
		);
		
		foreach ( apply_filters( 'kc_enqueue_scripts', $scripts ) as $uid => $url ) {
			$this->enqueue_script( $uid, $url );
		}

	}

	public function body_classes( $classes ) {

		global $post;

		if( !empty( $post->ID ) )
		{
			$post_data = get_post_meta( $post->ID , 'kc_data', true );

			if( !empty( $post_data['classes'] ) )
				$classes[] = $post_data['classes'];
		}
        return $classes;

	}

	public function vendor_script_url($vendor_dir, $srcipt_file){
		return trailingslashit(KC_URL).'includes/frontend/vendors/'.$vendor_dir.'/'.$srcipt_file;
	}

	private function register_script( $handle, $path, $deps = array( 'jquery' ), $version = KC_VERSION, $in_footer = true ) {
		$this->scripts[] = $handle;
		wp_register_script( $handle, $path, $deps, $version, $in_footer );
	}

	private function register_style( $handle, $path, $deps = array(), $version = KC_VERSION, $media = 'all' ) {
		$this->styles[] = $handle;
		wp_register_style( $handle, $path, $deps, $version, $media );
	}

	public function enqueue_script( $handle, $path = '', $deps = array( 'jquery' ), $version = KC_VERSION, $in_footer = true ) {
		
		if ( ! in_array( $handle, $this->scripts ) && $path ) {
			$this->register_script( $handle, $path, $deps, $version, $in_footer );
		}
		wp_enqueue_script( $handle );
	}

	private function allowed_access(){

		$kc = KingComposer::globe();

		$settings = $kc->settings();


		if( !isset( $settings['content_types'] ) )
			$settings['content_types'] = array();

		$content_types = array_merge( (array)$settings['content_types'], (array)$kc->get_required_content_types() );

		$this->allows = is_singular( $content_types );
		
		return $this->allows;

	}

	private function render_dynamic_js(){
		if( !empty( $this->js ) )
			printf( $this->js );
	}

	public function add_header_js( $js = '' ){
		if( !empty( $js ) )
			$this->js .= $js;
	}

	public function add_header_css( $css = '' ){
		if( !empty( $css ) )
			$this->css .= $css;
	}

	public function add_header_css_responsive( $screen = '', $css = '' ){

		if( !empty( $screen ) && !empty( $css ) ){

			if( !isset( $this->css_responsive[ $screen ] ) )
				$this->css_responsive[ $screen ] = array();

			array_push( $this->css_responsive[ $screen ], $css );

		}
	}

	private function render_dynamic_css(){

		global $post, $kc;

		$post_data = get_post_meta( $post->ID , 'kc_data', true );
		$settings = $kc->settings();

		if( !empty( $post_data['css'] ) )
			$this->css .= $post_data['css'];
			
		if( !empty( $settings['css_code'] ) )
			$this->css .= $settings['css_code'];
		
		$this->css = str_replace(
						array( "\n","  ","	", ": ", " {", ">", "<" ),
						array( '', '', '', ':', '{', '', '' ),
						$this->css
					);

		$css = explode( '}', $this->css );
		$css_array = array();

		for( $i=0; $i < count( $css ) - 1 ; $i++ )
		{
			$css[$i] = $css[$i].'}';
			if( !in_array( $css[$i], $css_array ) )
			{
				array_push( $css_array, $css[$i] );
			}
		}

		$this->css = implode( '', $css_array );

		foreach( $this->css_responsive as $screen => $css ){
			if( !empty( $screen ) && is_array( $css ) ){
				$this->css .= $screen.'{';
				foreach( $css as $cs ){
					$this->css .= $cs;
				}
				$this->css .= '}';
			}
		}

		$this->css = preg_replace("/.kc-css-/", "body.kingcomposer .kc-css-", $this->css);

		echo '<style type="text/css" id="kc-css-render">'.$this->css.'</style>';


	}

	public function preg_match_css( $matches ){

		if( !empty( $matches[1] ) ){

			if( strpos( $matches[1], '|' ) !== false ){

				$class = substr( $matches[1], 0, strpos( $matches[1], '|' ) );
				if( strpos( $this->css, '.'.$class.'{' ) === false )
				{
					$this->css .= '.'.$class.'{'.substr( $matches[1], strpos( $matches[1], '|' ) + 1 ).'}';
				}
				return ' css="'.$class.'"';
			}
			else
			{
				$this->css .= $matches[1];
				return '';
			}
		}
		else return $matches[0];

	}
	
	public function get_tags_filter(){
		return $this->tags_filter;
	}
	
	public function get_global_css(){
		return $this->css;
	}
	
}

/*
*-------------------------------
*/

global $kc_front;
$kc_front = new kc_front();
