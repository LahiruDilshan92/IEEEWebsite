<?php

$width = $css = $output = $col_class = '';

extract( $atts );

$attributes = array();
$classes = array(
	'kc_column',
	$col_class,
	$css,
	@kc_ColDecimalToClass( $width )
);

$attributes[] = 'class="' . esc_attr( trim( implode(' ', $classes) ) ) . '"';

$col_container_class = !empty( $atts['col_container_class'] ) ? ' '.$atts['col_container_class'] : '';

$output = '<div ' . implode( ' ', $attributes ) . '>'
		. '<div class="kc-col-container'.esc_attr( $col_container_class ).'">'
		. do_shortcode( str_replace('kc_column#', 'kc_column', $content ) )
		. '</div>'
		. '</div>';

echo $output;
