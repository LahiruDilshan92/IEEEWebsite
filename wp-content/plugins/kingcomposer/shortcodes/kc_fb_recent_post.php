<?php

$output = $wrap_class = '';

extract($atts);

$number_post_show = (!empty($number_post_show)) ? $number_post_show : 5;

$element_attributes = array();

$el_classes = array(
	'kc_shortcode',
	'kc_facebook_recent_post',
	$wrap_class,
	isset($atts['css'])?$atts['css']:''
);

$element_attributes[] = 'class="'. esc_attr( implode(' ', $el_classes ) ) .'"';
$element_attributes[] = 'data-cfg="'. base64_encode( json_encode( $atts ) ) .'"';

$output .= '<div '. implode(' ', $element_attributes ) .'>';

if(!empty($title)){
	$output .= '<h3 class="kc-widget-title">'. esc_html($title) .'</h3>';
}

$max_height = !empty($max_height) ? intval($max_height) : '300';

$output .= '<ul style="max-height: '. intval($max_height) .'px;">';

for($i=1; $i<=$number_post_show; $i++){
	$output .= '<li class="fb_mark_cls"></li>';
}

$output .= '</ul>';

if(!empty($show_profile_button) && 'yes' === $show_profile_button){
	$fb_page_id = !empty($fb_page_id)? $fb_page_id : 'kingcomposer';
	$page_url = 'https://www.facebook.com/'.$fb_page_id;

	$output .= '<a class="fb-button-profile" href="'. esc_url($page_url) .'" target="_blank">Go to <strong>'. $fb_page_id .'</strong> fan page</a>';
}

$output .= '</div>';

echo $output;

kc_js_callback( 'kc_front.ajax_action' );
