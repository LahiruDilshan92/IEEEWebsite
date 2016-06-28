<?php

$output = $title = $description = $video_info = '';
$video_height = '250';
$check_video = 'true';

extract( $atts );

if(!empty($video_width)){
	$video_height = intval($video_width)/1.77;
}

if( isset( $_GET['kc_action'] ) && $_GET['kc_action'] === 'live-editor' )
	$is_live = true;
else $is_live = false;

$video_link = (!empty($video_link))?$video_link:'https://www.youtube.com/watch?v=iNJdPyoqt8U'; //default video

//Check youtube video url
$pattern = '~
	^(?:https?://)?              # Optional protocol
	 (?:www\.)?                  # Optional subdomain
	 (?:youtube\.com|youtu\.be)  # Mandatory domain name
	 /watch\?v=([^&]+)           # URI with video id as capture group 1
	 ~x';

$has_match = preg_match($pattern, $video_link, $matches);

$video_attributes = array();

$video_classes = array(
	'kc_shortcode',
	'kc_video_play',
	'kc_video_wrapper',
	$wrap_class,
	isset($atts['css'])?$atts['css']:''
);

$video_attributes[] = 'class="'. esc_attr( implode(' ', $video_classes) ) .'"';

if( !$is_live ){
	$video_attributes[] = 'data-video="'. esc_attr( $video_link ) .'"';
	$video_attributes[] = 'data-width="'. esc_attr( $video_width ) .'"';
	$video_attributes[] = 'data-height="'. esc_attr($video_height) .'"';
	$video_attributes[] = 'data-fullwidth="'. esc_attr( $full_width ) .'"';
	$video_attributes[] = 'data-autoplay="'. esc_attr( $auto_play ) .'"';
}

if( !empty($title) ) $video_info .= '<h3>'. $title .'</h3>';
if( !empty($description) ) $video_info .= '<p>'. base64_decode($description) .'</p>';

$output .= '<div '. implode(' ', $video_attributes) .'>';

if( $is_live ){
	$output .= '<div style="height:'.$video_height.'px;width:'.$video_width.'" class="disable-view-element">'
			.'<h3>For best perfomance, the video map has been disabled in this editing mode.</h3>'
			.'</div>';
}

$output .= '<div class="video-info">'. $video_info .'</div>';
$output .= '</div>';

if( $check_video === 'true' ){
	echo $output;
}else{
	echo __('KingComposer error: Video format url incorrect', 'kingcomposer');
}
