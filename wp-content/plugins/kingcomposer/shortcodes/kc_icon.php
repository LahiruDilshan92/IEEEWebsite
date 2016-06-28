<?php

$output = $style = $class = $align = '';
$attributes = array();
$icon_align = 'none';

extract( $atts );

if( isset( $atts['icon_size'] ) && !empty( $atts['icon_size'] ) ){
	$style .= 'font-size: '.$atts['icon_size'];
	if( is_numeric( $atts['icon_size'] ) )
		$style .= 'px;';
	else $style .= ';';
}

if( !empty( $atts['icon_color'] ) )
	$style .= 'color: '.$atts['icon_color'].';';

if( !empty( $atts['icon'] ) )
	$class .= ' '.$atts['icon'];
else $class .= ' fa-leaf';

$attributes[] = 'style="'.esc_attr($style).'"';
$attributes[] = 'class="'.esc_attr($class).'"';

$output = '<i '.implode( ' ', $attributes ).'></i>';

if( !empty($icon_align) && $icon_align != 'none' ){
	$align = 'style="text-align: '. esc_attr($icon_align) .';"';
}

if( $atts['icon_wrap'] == 'yes' || !empty($align) )
{
	if( !empty( $atts['icon_wrap_class'] ) )
		$output = '<div class="kc-icon-wrapper '.esc_attr($atts['icon_wrap_class']).'" '. $align .'>'.$output.'</div>';
	else $output = '<div class="kc-icon-wrapper" '. $align .'>'.$output.'</div>';
}

echo $output;
