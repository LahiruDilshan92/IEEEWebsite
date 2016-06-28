<?php

$output = '';
$class = isset( $atts[ 'class' ] ) ? $atts[ 'class' ].' kc_text_block' : 'kc_text_block';

$content = apply_filters('kc_cloumn_text', $content );

$output .= '<div class="'.esc_attr( $class ).'">';
$output .= wpautop( do_shortcode( $content ) );
$output .= '</div>';

echo $output;
