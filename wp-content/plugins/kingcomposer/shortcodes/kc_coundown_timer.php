<?php

$output = $template = $custom_css = '';
$timer_style = 1;

extract($atts);

wp_enqueue_script('kc-countdown-timer');

$element_attribute = array();

switch ($timer_style) {
	case '1':
	case '2':
		$template = '<span class="countdown-style'. esc_attr($timer_style) .'">
			<span class="group">
				<span class="timer days">%D</span>
				<span class="unit">days</span>
			</span>
			<span class="group">
				<span class="timer seconds">%H</span>
				<span class="unit">hours</span>
			</span>
			<span class="group">
				<span class="timer seconds">%M</span>
				<span class="unit">minutes</span>
			</span>
			<span class="group">
				<span class="timer seconds">%S</span>
				<span class="unit">seconds</span>
			</span>
		</span>';
		break;

	case '3':
		if(!empty($custom_template)){
			$template = base64_decode( $custom_template );
		}else{
			$template = '%D days %H:%M:%S';
		}

		break;
}

$el_class = array(
	'kc-countdown-timer',
	$wrap_class,
	$custom_css
);

$datetime = !empty($datetime)?$datetime:date("D M d Y", strtotime("+1 week"));
$datetime = date("Y/m/d", strtotime($datetime));

$countdown_data = array(
	'date' => $datetime,
	'template' => trim(preg_replace('/\s\s+/', ' ', $template))
);

$element_attribute[] = 'class="'. esc_attr( implode(' ', $el_class ) ) .'"';
$element_attribute[] = 'data-countdown="'. esc_attr( json_encode($countdown_data) ) .'"';

if(!empty($title)){
	$output .= '<h3>'. esc_attr($title) .'</h3>';
}

$output .= '<div '. implode(' ', $element_attribute) .'></div>';

echo $output;
