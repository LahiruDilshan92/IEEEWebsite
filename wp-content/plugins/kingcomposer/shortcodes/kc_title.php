<?php

$output = $align = $before = $after = '';
$type = 'h1';
extract( $atts );

$attributes = array();
$wrap_class = array();
$classes = array(
	'kc_title',
	$atts['class']
);

if( !empty( $atts['type'] ) )
	$type = esc_attr( $atts['type'] );
else $type = 'h1';

if( !empty( $atts['css'] ) ){
	if( isset( $atts['title_wrap'] ) && $atts['title_wrap'] == 'yes' )
		$wrap_class[] = $atts['css'];
	else $classes[] = $atts['css'];
}

if( !empty( $align ) )
	$classes[] = 'align-'.$align;

$attributes[] = 'class="' . esc_attr( implode(' ', $classes) ) . '"';

$output = '<'.$type.' '.implode( ' ', $attributes ) . '>'.base64_decode($atts['text']).'</'.$type.'>';

if( isset( $atts['title_wrap'] ) && $atts['title_wrap'] == 'yes' )
{

	if( !empty( $before ) )
		$output = base64_decode( $before ).$output;
	if( !empty( $after ) )
		$output .= base64_decode( $after );
	
	if( isset( $atts['title_wrap_class'] ) && !empty( $atts['title_wrap_class'] ) )
		$wrap_class[] = $atts['title_wrap_class'];
	
	if( !empty( $align ) )
		$wrap_class[] = 'align-'.$align;
		
	$output = '<div class="'.esc_attr( implode(' ', $wrap_class ) ).'">'.$output.'</div>';
	
} 

echo $output;

