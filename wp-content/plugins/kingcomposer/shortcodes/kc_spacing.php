<?php

$class = '';
$height = 0;

extract($atts);
$output = '<div class="'. esc_attr( $class ) .'" style="height: '. esc_attr(intval($height)) .'px; clear: both; width:100%;"></div>';

echo $output;
