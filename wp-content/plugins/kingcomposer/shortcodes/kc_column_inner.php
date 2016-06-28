<?php

$output = $width = $col_in_class_container = $css = '';
$attributes = array();

extract( $atts );

$classes = array(
	'kc_column_inner',
	$col_in_class,
	@kc_ColDecimalToClass( $width ),
	$css
);

$col_in_class_container .= ' kc_wrapper';

$attributes[] = 'class="' . esc_attr( trim( implode(' ', $classes) ) ) . '"';

$output .= '<div ' . implode( ' ', $attributes ) . '>'
		. '<div class="'.trim( esc_attr( $col_in_class_container ) ).'">'
		. do_shortcode( str_replace('kc_column_inner#', 'kc_column_inner', $content ) )
		. '</div>'
		. '</div>';

echo $output;
