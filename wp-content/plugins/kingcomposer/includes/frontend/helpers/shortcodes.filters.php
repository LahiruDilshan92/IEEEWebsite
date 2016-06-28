<?php

/*
 * Remove __empty__ code value
 */
function kc_remove_empty_code( $atts ){

	$atts_tmp = array();

	foreach( $atts as $key => $value ){
		if('__empty__' === $value){
			$atts_tmp[$key] = '';
		}else{
			$atts_tmp[$key] = $value;
		}
	}

	return $atts_tmp;
}


//Row filter
function kc_row_filter( $atts ){

	if( isset( $atts['video_bg'] ) && $atts['video_bg'] == 'yes' ){
		wp_register_script('kc-youtube-iframe-api', 'https://www.youtube.com/iframe_api', null, KC_VERSION, true );
		wp_enqueue_script('kc-youtube-iframe-api');
	}

	return $atts;

}

//Column filter
function kc_column_filter( $atts ){

	if( isset( $atts['responsive'] ) ){
		
		if( $responsive = json_decode( base64_decode( $atts['responsive'] ), true ) ){

			global $kc_front;
			
			if( !isset( $atts['css'] ) ){
				$selector = 'kc-css-'.rand( 1000000, 9999999 );
				$atts['css'] = $selector;
			}else{
				$selector = explode( '|', $atts['css'] );
				$selector = $selector[0];
			}
			
			foreach( $responsive as $res ){
				
				if( !empty( $res ) && !empty( $res['screen'] ) ){
					
					$screen = $res['screen'];
					
					if( $screen == 'custom' ){
						if( empty( $res['range'] ) )
							continue;
						$res['range'] = explode( '|', $res['range'] );
						
						if( isset( $res['range'][0] ) && isset( $res['range'][1] ) )
							$screen = '(min-width: '.$res['range'][0].') and (max-width: '.$res['range'][1].')';
						else continue;
					}
					
					$screen = '@media only screen and '.preg_replace('/[^0-9a-z\(\)\:\-\,\.\ ]/', '', $screen );
					
					$css = ''; $brc = ';';
					
					if( isset( $res['important'] ) && !empty( $res['important'] ) && $res['important'] == 'yes' ){
						$brc = ' !important;';
					}
					
					if( isset( $res['offset'] ) && !empty( $res['offset'] ) ){
						$offset = (int)$res['offset'];
						if( $offset > 0 && $offset < 11 ){
							$offset = number_format(($offset/12)*100, 4, '.', '');
							$css .= 'margin-left:'.$offset.'%'.$brc;
						}
					}
					
					if( isset( $res['columns'] ) && !empty( $res['columns'] ) ){
						$width = (int)$res['columns'];
						if( $width > 0 && $width < 13 ){
							$width = number_format(($width/12)*100, 4, '.', '');
							$css .= 'width:'.$width.'%'.$brc;
						}
					}
					
					if( isset( $res['display'] ) && !empty( $res['display'] ) && $res['display'] == 'hide' ){
						$css .= 'display:none'.$brc;
					}
					
					if( $css != '' )
						$css = '.'.$selector.'{'.$css.'}';
					
					$kc_front->add_header_css_responsive( $screen, $css );
					
				}
			}
		}
		
	}

	return $atts;

}

function kc_column_inner_filter( $atts ){
	
	return kc_column_filter( $atts );
	
}

//Tab filter
function kc_tabs_filter( $atts = array() ){
	
	if( isset( $atts['type'] ) && $atts['type'] === 'slider_tabs' ){
		wp_enqueue_script( 'kc-owl-carousel' );
		wp_enqueue_style( 'kc-owl-theme' );
		wp_enqueue_style( 'kc-owl-carousel' );
	}

	return $atts;

}

function kc_tab_filter( $atts = array() ){

	// Do your code here
	global $kc_tab_id;

	if( !isset( $kc_tab_id ) || empty( $kc_tab_id ) )
		$kc_tab_id = array();

	$i = 1; $_title = sanitize_title( !empty( $atts['title'] ) ? $atts['title'] : 'kc-tab' );
	while( in_array( $_title, $kc_tab_id ) )
	{
		$i++;
		$_title = sanitize_title( !empty( $atts['title'] ) ? $atts['title'] : 'kc-tab' ).$i;
	}

	array_push( $kc_tab_id, $_title );

	$atts['tab_id'] = $_title;

	return $atts;

}

/*
 * KC Box shortcode custom css
 */
function kc_box_filter(  $atts = array() ){

	global $kc_front;
	$css = '';

	$atts = kc_remove_empty_code( $atts );
	extract( $atts );

	if(!empty($css)){
		$kc_front->add_header_css($css);
	}

	return $atts;
}


/*
 * Pie chart shortcode custom css
 */
function kc_pie_chart_filter( $atts = array() ){
	
	global $kc_front;
	
	wp_enqueue_script( 'kc-easypiechart' );
	
	$atts = kc_remove_empty_code( $atts );
	
	extract( $atts );
	
	$custom_class = 'pie_chart_'.kc_random_string(10);
	$atts['custom_class'] = $custom_class;
	
	$label_color = !empty( $label_color )? $label_color : '#FF5252';
	$label_font_size = !empty( $label_font_size )? intval( $label_font_size ) : 20;
	
	$lineHeight = intval($size);
	$custom_style = ".$custom_class span.percent{ font-size	: {$label_font_size}px; color : {$label_color};}";
	
	$kc_front->add_header_css( $custom_style );
	
	return $atts;
	
}


/*
 * Button shortcode custom css
 */
function kc_button_filter( $atts = array() ){

	global $kc_front;

	$atts = kc_remove_empty_code( $atts );
	extract( $atts );
		
	if( !isset($atts['custom_style']) || $atts['custom_style'] !== 'yes' )
		return $atts;
	
	$custom_class = 'button_'.kc_random_string(10);
	$atts['custom_class'] = $custom_class;

	$bg_color = isset($bg_color)?$bg_color:'#393939';
	$text_color = isset($text_color)?$text_color:'#FFFFFF';
	$border_radius = isset($border_radius)?$border_radius:3;

	$bg_color_hover = isset($bg_color_hover)?$bg_color_hover:'#FFFFFF';
	$text_color_hover = isset($text_color_hover)?$text_color_hover:'#393939';

	$custom_style = "
	body .$custom_class{
		background-color: $bg_color;
		color			: $text_color;
		border-radius	: ". intval($border_radius) ."px;
		Border			: 1px solid $bg_color;
	}

	body .$custom_class:hover{
		background-color: $bg_color_hover;
		color			: $text_color_hover;
		Border			: 1px solid $text_color_hover;
	}
	";

	$kc_front->add_header_css($custom_style);

	return $atts;
}


function kc_flip_box_filter( $atts = array() ){
	
	global $kc_front;

	$atts = kc_remove_empty_code( $atts );
	
	extract( $atts );
	
	$custom_class = 'flipbox_'.kc_random_string(10);
	
	$atts['custom_class'] = $custom_class;
	
	$bg_backside 				= !empty($bg_backside)? $bg_backside: '#86c724';
	$text_color 				= !empty($text_color)? $text_color: '#FFFFFF';
	$button_bg_color 			= !empty($button_bg_color)? $button_bg_color: 'transparent';
	$text_button_color 			= !empty($text_button_color)? $text_button_color: '#FFFFFF';
	$button_bg_hover_color 		= !empty($button_bg_hover_color)? $button_bg_hover_color: '#FFFFFF';
	$text_button_color_hover 	= !empty($text_button_color_hover)? $text_button_color_hover: '#86c724';
	$text_align 				= !empty($text_align)?$text_align:'center';
	
	$custom_style = "
		.$custom_class .back{
			background-color: $bg_backside;
			color			: $text_color;
			text-align		: $text_align;
			display: flex;
			align-items: center;
		}
		.$custom_class .back *{
			color			: $text_color;
			text-align		: $text_align;
		}
	";
	
	if('transparent' === $button_bg_color){
		$custom_style .="
			.$custom_class .des a.button{
				background-color: $button_bg_color;
				color			: $text_button_color;
				border			: 2px solid $text_button_color;
			}
		";
	}else{
		$custom_style .="
			.$custom_class .des a.button{
				background-color: $button_bg_color;
				color			: $text_button_color;
				border			: 2px solid $button_bg_color;
			}
		";
	}
	
	$custom_style .="
		.$custom_class .des a.button:hover{
			background-color: $button_bg_hover_color;
			color			: $text_button_color_hover;
			border			: 2px solid $button_bg_hover_color;
		}
		.$custom_class .des a.button:hover *{
			color			: $text_button_color_hover;
		}
	";
	
	$kc_front->add_header_css($custom_style);

	return $atts;
	
}


function kc_google_maps_filter( $atts = array() ){

	global $kc_front;

	$atts = kc_remove_empty_code( $atts );
	
	extract( $atts );
	
	$custom_class = 'google_maps_'.kc_random_string(10);
	$atts['custom_class'] = $custom_class;
	
		
	$contact_area_bg 		= ( !empty( $contact_area_bg ) )? $contact_area_bg : 'rgba(42,42,48,0.95)';
	$contact_form_color 	= ( !empty($contact_form_color ) )? $contact_form_color : '#FFFFFF';
	$submit_button_color 		= ( !empty( $submit_button_color ) )? $submit_button_color : '#393939';
	$submit_button_hover_color 		= ( !empty( $submit_button_hover_color ) )? $submit_button_hover_color : '#575757';
	$submit_button_text_color 		= ( !empty( $submit_button_text_color ) )? $submit_button_text_color : '#FFFFFF';
	$submit_button_text_hover_color 		= ( !empty( $submit_button_text_hover_color ) )? $submit_button_text_hover_color : '#FFFFFF';
	
	$contact_area_width 	= ( !empty($contact_area_width) )? $contact_area_width : '40%';
	
	if( strpos( $contact_area_bg, 'rgba' ) === false )
	{
		$contact_area_bg = kc_hex2rgba( $contact_area_bg, 0.85 );
	}
	
	//custom CSS
	$custom_style = "
		.$custom_class .map_popup_contact_form{
			background	: $contact_area_bg;
			color		: $contact_form_color;
			width		: $contact_area_width;
		}
		.$custom_class .show_contact_form{
			background	: $contact_area_bg;
			color		: #fff;
		}
		.$custom_class .map_popup_contact_form .wpcf7-submit{
			background	: $submit_button_color;
		    border		: 1px solid $submit_button_color;
			color		: $submit_button_text_color;
		}
		.$custom_class .map_popup_contact_form .wpcf7-submit:hover{
			background	: $submit_button_hover_color;
		    border		: 1px solid $submit_button_hover_color;
			color		: $submit_button_text_hover_color;
		}
	
	";


	$kc_front->add_header_css( $custom_style );

	return $atts;
	
}


function kc_twitter_feed_filter( $atts = array() ){

	global $kc_front;

	$atts = kc_remove_empty_code( $atts );
	extract( $atts );

	$custom_class = 'twitter_'.kc_random_string(10);
	$atts['custom_class'] = $custom_class;

	$color_icon = !empty($color_icon)?$color_icon:'#2686cc';
	$link_color = !empty($link_color)?$link_color:'';
	$link_color_hover = !empty($link_color_hover)?$link_color_hover:'';

	if( isset( $display_style ) && '2' === $display_style ){
		wp_enqueue_script( 'kc-owl-carousel' );
		wp_enqueue_style( 'kc-owl-theme' );
		wp_enqueue_style( 'kc-owl-carousel' );
	}

	$custom_style = "
		.$custom_class a{
			color: $link_color;
		}

		.$custom_class a:hover{
			color: $link_color_hover;
		}

		.$custom_class .kc_tweet_icon i{
			color: $color_icon;
		}
	";

	$kc_front->add_header_css($custom_style);

	return $atts;
}


function kc_video_play_filter( $atts = array() ){
		
	if( isset( $atts['video_link'] ) && !empty( $atts['video_link'] ) && 
		(preg_match('/youtu\.be/i', $atts['video_link']) || preg_match('/youtube\.com\/watch/i', $atts['video_link'])) 
	){
		$atts['check_video'] = 'true';
		wp_enqueue_script('kc-youtube-api');
	}else{
		if( (preg_match('/vimeo\.com/i', $video_link)) ){
			$atts['check_video'] = 'true';
			wp_enqueue_script('kc-vimeo-api');
		}else{
			$atts['check_video'] = 'false';
		}
	}
	
	wp_enqueue_script( 'kc-video-play' );

	return $atts;
}


function kc_counter_box_filter( $atts = array() ){

	wp_enqueue_script('kc-waypoints-min');
	wp_enqueue_script('kc-counter-up');

	return $atts;
}


function kc_carousel_post_filter( $atts = array() ){

	$atts = kc_remove_empty_code( $atts );
	extract( $atts );

	wp_enqueue_script( 'kc-owl-carousel' );
	wp_enqueue_style( 'kc-owl-theme' );
	wp_enqueue_style( 'kc-owl-carousel' );

	return $atts;
}


function kc_carousel_images_filter( $atts = array() ){

	$atts = kc_remove_empty_code( $atts );
	extract( $atts );

	wp_enqueue_script( 'kc-owl-carousel' );
	wp_enqueue_style( 'kc-owl-theme' );
	wp_enqueue_style( 'kc-owl-carousel' );

	if( isset( $onclick ) && $onclick == 'lightbox' ){
		wp_enqueue_script( 'kc-prettyPhoto' );
		wp_enqueue_style( 'kc-prettyPhoto' );
	}

	return $atts;
}


function kc_image_gallery_filter( $atts = array() ){

	$atts = kc_remove_empty_code( $atts );
	extract( $atts );

	if( !isset( $type ) || empty( $type ) )
		$type = 'grid';

	switch( $type ){

		case 'slider' :

			wp_enqueue_script( 'kc-owl-carousel' );
			wp_enqueue_style( 'kc-owl-theme' );
			wp_enqueue_style( 'kc-owl-carousel' );

			break;

		default :

			if( isset( $image_masonry ) && $image_masonry == 'yes' ){
				wp_enqueue_script( 'kc-masonry-min' );
			}

			if( isset( $click_action ) && 'lightbox' === $click_action ){
				wp_enqueue_script( 'kc-prettyPhoto' );
				wp_enqueue_style( 'kc-prettyPhoto' );
			}

			break;
	}

	return $atts;
}
