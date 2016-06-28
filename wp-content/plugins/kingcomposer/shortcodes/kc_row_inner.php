<?php

$output = $row_class_container = $row_class = $row_id = $after_row_full_action = '';

extract( $atts );

$css_classes = array(
	'kc_row',
	'kc_row_inner',
	$row_class
);

$attributes = array();

if ( ! empty( $row_id ) ) {
	$attributes[] = 'id="' . esc_attr( $row_id ) . '"';
}

$attributes[] = 'class="' . esc_attr( trim( implode(' ', $css_classes) ) ) . '"';

if( !empty( $atts['equal_height'] ) )
{
	$attributes[] = 'data-kc-equalheight="true"';
	$attributes[] = 'data-kc-row-action="true"';
	$after_row_full_action = '<script>kc_row_action(true);</script>';
}

$output .= '<div ' . implode( ' ', $attributes ) . '>';

if( !empty( $row_class_container ) )
	$output .= '<div class="'.esc_attr( $row_class_container ).'">';

$output .= do_shortcode( str_replace('kc_row_inner#', 'kc_row_inner', $content ) );

if( !empty( $row_class_container ) )
	$output .= '</div>';

$output .= '</div>';
$output .= $after_row_full_action;

echo $output;
