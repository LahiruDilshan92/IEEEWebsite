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

if( !function_exists('wp_list_widgets') )
	require_once(ABSPATH . '/wp-admin/includes/widgets.php');

function kc_admin_enable( $force = false ){

	if( $force === true )
		return true;

	global $post, $kc;

	$type = !empty( $post->post_type ) ? $post->post_type:'';
	$page = !empty( $_GET['page'] ) ? $_GET['page'] : '';

	$settings = $kc->settings();
	if( !isset( $settings['content_types'] ) )
		$settings['content_types'] = array();

	$allows_types = array_merge( (array)$settings['content_types'], (array)$kc->get_required_content_types() );

	if( is_admin() && ( in_array( $type, $allows_types ) || $page == 'kc-sections-manager' || KingComposer::is_live() ) )
		return true;
	else return false;

}

function kc_add_map( $map = array() ){

	$kc = KingComposer::globe();

	if( !is_array( $map ) )
		return;
	if( empty( $map['name'] ) )
		return;

	$kc->add_map( $map );

}

function kc_add_param_type( $name = '', $func = '' ){

	$kc = KingComposer::globe();

	if( empty( $name ) || empty( $func ) )
		return;

	$kc->add_param_type( $name, $func );
	
}

function kc_add_icon( $source = '' ){
	
	if( !empty( $source ) ){
		KingComposer::globe()->add_icon_source( $source );	
	}
}

function kc_remove_wpautop( $content, $autop = false ) {

	if ( $autop ) {
		$content = wpautop( preg_replace( '/<\/?p\>/', "\n", $content ) . "\n" );
	}

	return do_shortcode( shortcode_unautop( $content ) );
}

function kc_validate_options( $plugin_options ){

	if( !empty( $_POST['kc_options'] ) ){
		return $plugin_options;
	}

}

function kc_youtube_id_from_url( $url = '' ) {

    parse_str( parse_url( $url, PHP_URL_QUERY ), $vars );
    
	return isset( $vars['v'] ) ? $vars['v'] : '';   

}

function kc_loop_box( $items ){

	if( empty( $items ) )
		return '';

	$output = '';

	foreach( $items as $item ){

		if( is_object( $item ) && $item->tag != 'text' ){

			$output .= '<'.$item->tag;

			if( !isset( $item->attributes ) || !is_object( $item->attributes ) )
				$item->attributes = new stdClass();

			if( !isset( $item->attributes->class ) )
				$item->attributes->class = '';

			if( $item->tag == 'column' )
			{
				$item->attributes->class .= ' '.$item->attributes->cols;
				unset( $item->attributes->cols );
			}else if( $item->tag == 'img' && empty( $item->attributes->src ) )
				$item->attributes->src = KC_URL.'/assets/images/get_start.jpg';

			foreach( $item->attributes as $k => $v )
			{
				if( !empty($v) )$output .= ' '.$k.'="'.trim($v).'"';
			}

			if( $item->tag == 'img' )
				$output .= '/';

			$output .= '>';

			if( is_array( $item->children ) )
				$output .= kc_loop_box( $item->children );

			if( $item->tag != 'img' )
				$output .= '</'.$item->tag.'>';

		}else $output .= $item->content;

	}

	return $output;

}

function kc_get_terms( $tax = 'category', $key = 'id', $type = '', $default = '' ){

	$get_terms = (array) get_terms( $tax, array( 'hide_empty' => false ) );

	if( $type != '' ){
		$get_terms = kc_get_terms_by_post_type( array($tax), array($type) );
	}

	$terms = array();

	if( $default != '' ){
		$terms[] = $default;
	}

	if ( $key == 'id' ){
		foreach ( $get_terms as $term ){
			if( isset( $term->term_id ) && isset( $term->name ) ){
				$terms[$term->term_id] = $term->name;
			}
		}
	}else if ( $key == 'slug' ){
		foreach ( $get_terms as $term ){
			if( !empty($term->name) ){
				if( isset( $term->slug ) && isset( $term->name ) ){
					$terms[$term->slug] = $term->name;
				}
			}
		}
	}

	return $terms;

}

function kc_filter_search( $s, &$w ) {
	
	global $wpdb;
	
	if ( empty( $s ) )return '';
	
	$q = $w->query_vars;
	
	$n = ! empty( $q['exact'] ) ? '' : '%';
	$s = $sa = '';
	
	foreach ( (array) $q['search_terms'] as $t ) {
		$t = $wpdb->esc_like( $t );
		$l = $n . $t . $n;
		$s .= $wpdb->prepare( "{$sa}($wpdb->posts.post_title LIKE %s)", $l );
		$sa = ' AND ';
	}
	
	if ( ! empty( $s ) )
		$s = " AND ({$s}) ";

	return $s;
}

function kc_get_submit_button( $text = '', $type = 'primary large', $name = 'submit', $wrap = true, $other_attributes = '' ) {
	
	if ( ! is_array( $type ) )
		$type = explode( ' ', $type );

	$button_shorthand = array( 'primary', 'small', 'large' );
	$classes = array( 'button' );
	foreach ( $type as $t ) {
		if ( 'secondary' === $t || 'button-secondary' === $t )
			continue;
		$classes[] = in_array( $t, $button_shorthand ) ? 'button-' . $t : $t;
	}
	$class = implode( ' ', array_unique( $classes ) );

	if ( 'delete' === $type )
		$class = 'button-secondary delete';

	$text = $text ? $text : __( 'Save Changes' );

	// Default the id attribute to $name unless an id was specifically provided in $other_attributes
	$id = $name;
	if ( is_array( $other_attributes ) && isset( $other_attributes['id'] ) ) {
		$id = $other_attributes['id'];
		unset( $other_attributes['id'] );
	}

	$attributes = '';
	if ( is_array( $other_attributes ) ) {
		foreach ( $other_attributes as $attribute => $value ) {
			$attributes .= $attribute . '="' . esc_attr( $value ) . '" '; // Trailing space is important
		}
	} elseif ( ! empty( $other_attributes ) ) { // Attributes provided as a string
		$attributes = $other_attributes;
	}

	// Don't output empty name and id attributes.
	$name_attr = $name ? ' name="' . esc_attr( $name ) . '"' : '';
	$id_attr = $id ? ' id="' . esc_attr( $id ) . '"' : '';

	$button = '<input type="submit"' . $name_attr . $id_attr . ' class="' . esc_attr( $class );
	$button	.= '" value="' . esc_attr( $text ) . '" ' . $attributes . ' />';

	if ( $wrap ) {
		$button = '<p class="submit">' . $button . '</p>';
	}

	return $button;
}

function kc_process_tab_title( $matches ){

	if( !empty( $matches[0] ) ){

		$tab_atts = shortcode_parse_atts( $matches[0] );

		$title = ''; $adv_title = '';
		if ( isset( $tab_atts['title'] ) )
			$title = $tab_atts['title'];
		
		if( isset( $tab_atts['advanced'] ) && $tab_atts['advanced'] === 'yes' ){
			
			if( isset( $tab_atts['adv_title'] ) && !empty( $tab_atts['adv_title'] ) )
				$adv_title = base64_decode( $tab_atts['adv_title'] );
				
			$icon=$icon_class=$image=$image_id=$image_url=$image_thumbnail=$image_medium=$image_large=$image_full='';
			
			if( isset( $tab_atts['adv_icon'] ) && !empty( $tab_atts['adv_icon'] ) ){
				$icon_class = $tab_atts['adv_icon'];
				$icon = '<i class="'.$tab_atts['adv_icon'].'"></i>';
			}
			
			if( isset( $tab_atts['adv_image'] ) && !empty( $tab_atts['adv_image'] ) ){
				$image_id = $tab_atts['adv_image'];
				$image_url = wp_get_attachment_image_src( $image_id, 'full' );
				$image_medium = wp_get_attachment_image_src( $image_id, 'medium' );
				$image_large = wp_get_attachment_image_src( $image_id, 'large' );
				$image_thumbnail = wp_get_attachment_image_src( $image_id, 'thumbnail' );
				
				if( !empty( $image_url ) && isset( $image_url[0] ) ){
					$image_url = $image_url[0];
					$image_full = $image_url;
				}
				if( !empty( $image_medium ) && isset( $image_medium[0] ) )
					$image_medium = $image_medium[0];
				
				if( !empty( $image_large ) && isset( $image_large[0] ) )
					$image_large = $image_large[0];
					
				if( !empty( $image_thumbnail ) && isset( $image_thumbnail[0] ) )
					$image_thumbnail = $image_thumbnail[0];
				if( !empty( $image_url ) )
					$image = '<img src="'.$image_url.'" alt="" />';
			}
			
			$adv_title = str_replace( array( '{title}', '{icon}', '{icon_class}', '{image}', '{image_id}', '{image_url}', '{image_thumbnail}', '{image_medium}', '{image_large}', '{image_full}', '{tab_id}' ), array( $title, $icon, $icon_class, $image, $image_id, $image_url, $image_thumbnail, $image_medium, $image_large, $image_full, $tab_atts['tab_id'] ), $adv_title );
			
			echo '<li>'.$adv_title.'</li>';
				
		}else{
			if( isset( $tab_atts['icon'] ) )
				$title = '<i class="'.$tab_atts['icon'].'"></i> '.$title;
			echo '<li><a href="#'.$tab_atts['tab_id'].'">'.$title.'</a></li>';
		}

	}

	return $matches[0];

}

function kc_js_callback( $callback ){
	
	$kc = KingComposer::globe();
	$kc->js_callback( $callback );
	
}

/*
 * Return a random string with length
 */
function kc_random_string( $length = 10 ){
	$str = "";
	$allow_characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$_max_length = count($allow_characters) - 1;

	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $_max_length);
		$str .= $allow_characters[$rand];
	}

	return $str;
}
