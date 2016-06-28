<?php
$output = $custom_css = $_before_number = $_after_number = $custom_class = '';

$number_color = $label_color = $icon_color = '#393939';
$box_bg_color = 'transparent';

$atts = kc_remove_empty_code( $atts );
extract( $atts );

$style = (!empty($atts['style'])) ? $atts['style'] : 1;

$element_atttribute = array();

$el_classess = array(
	'kc_shortcode',
	'kc_counter_box',
	'kc-box-counter',
	'kc-box-counter-'.$style,
	$custom_class,
	$wrap_class
);


$label = (!empty($label)) ? '<h4>'. esc_html($label) .'</h4>' : '';
$icon = !empty($icon)? $icon: 'fa-leaf';

if(isset($style) && $style != 1){
	$icon = (!empty($icon)) ? '<i class="'. esc_html($icon).' element-icon"></i>' : '';
}else{
	$icon = '';
}

if(!empty($label_above) && 'yes' === $label_above){
	$_before_number = $icon . $label;
}else{
	$_before_number = $icon;
	$_after_number = $label;
}

if( $style == '1' ){
	$box_bg_color = 'transparent';	
}

if( empty($box_bg_color) && '2' === $style){
	$box_bg_color = '#d9d9d9';
}

$custom_class = 'counter_box_'.kc_random_string(10);

array_push( $el_classess, $custom_class );
$element_atttribute[] = 'class="'. esc_attr( implode(' ', $el_classess ) ) .'"';

$custom_style = "
	.$custom_class{
		background: $box_bg_color;
	}

	.$custom_class span.counterup{
		color: $number_color;
	}

	.$custom_class h4{
		color: $label_color;
	}

	.$custom_class i{
		color: $icon_color;
	}
";

$_before_number = '<style type="text/css">'.$custom_style.'</style>'.$_before_number;

$output .= '<div '. implode(' ', $element_atttribute) .'>
		'. $_before_number .'
		<span class="counterup">'. esc_html($number) .'</span>
		'. $_after_number .'
	</div>
';
	

echo $output;
