<?php

$custom_class = '';

extract($atts);

$element_attributes = array();
$el_classes = array(
	'kc_box_wrap',
	$custom_class
);

$element_attributes[] = 'class="'. esc_attr(implode(' ', $el_classes)) .'"';

echo '<div '. implode(' ', $element_attributes ) .'>';

if( $data = json_decode( base64_decode( $atts['data'] ) ) )
{
	echo kc_loop_box( $data );
	if( isset( $atts['css'] ) ){
		echo '<style type="text/css">'.$atts['css'].'</style>';
	}
}
else
{
	echo 'KC Box: Error content structure';
}

echo '</div>';
