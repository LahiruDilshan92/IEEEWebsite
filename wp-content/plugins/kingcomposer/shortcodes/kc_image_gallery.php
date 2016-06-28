<?php

$output = $custom_links = $title = $type_class = $slider_item_start = $slider_item_end = $ul_start = $ul_end = '';
$slider_width = $navigation = $pagination = $image_masonry = '';

extract( $atts );

switch( $type ){
	
	case 'slider' :
		$type_class = 'kc-owlslider owl-carousel';
		$slider_item_start = '<div class="item">';
		$slider_item_end = '</div>';

		if( !empty($slider_width) ){
			$slider_width = 'width: '.$slider_width.'px;';
		}else{
			$slider_width = 'width: 100%;';
		}

	break;

	case 'grid':

		$type_class = 'kc-grid';

		if( !empty( $custom_links) && 'custom_link' === $click_action ) {
			$custom_links = base64_decode($custom_links);

			$custom_links = preg_replace('/\n$/','',preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n", $custom_links)));
			$custom_links_arr = explode("\n", $custom_links);
		}

		$slider_width = '';

		break;
}

$el_classess = array(
	'kc_image_gallery',
	$type_class,
	$wrap_class
);

$images = explode( ',', $images );
$element_attribute = array();

$element_attribute[] = 'class="'. esc_attr( implode( ' ', $el_classess ) ) .'"';

if(!empty($slider_width))
	$element_attribute[] = 'style="'. esc_attr( $slider_width ) .'"';

$slider_option = array(
	'auto_rotate' => $auto_rotate,
	'navigation' => $navigation,
	'pagination' => $pagination
);

$element_attribute[] = 'data-image_masonry="'. $image_masonry .'"';
$element_attribute[] = 'data-slide_options="'. esc_attr( json_encode($slider_option) ) .'"';

$attachment_data = $attachment_data_full = array();

foreach($images as $image_id){
	$attachment_data[] = wp_get_attachment_image_src( $image_id, $image_size );
	$attachment_data_full[] = wp_get_attachment_image_src( $image_id, 'full' );
}

ob_start();

if(!empty($title)){
	echo '<h3 class="kc-title image-gallery-title">'. esc_html($title) .'</h3>';
}

?>

<div <?php echo implode(' ', $element_attribute ); ?>>
<?php
	
	if( !isset( $attachment_data[0] ) || empty( $attachment_data[0] ) ){
		
		echo '<h3>Images Gallery: No images found</h3>';
		
	}else{
	
		echo $ul_start;
		foreach($attachment_data as $i => $image){
	
			if( 'grid' === $type )
			{
				switch( $click_action ){
	
					case 'none':
						echo '<div class="item-grid"><img src="'. esc_attr($image[0]) .'" /></div>';
						break;
	
					case 'large_image':
						echo '<div class="item-grid"><a href="'. esc_attr( $attachment_data_full[$i][0] ) .'" target="_blank">'
							.'<img src="'. esc_attr($image[0]) .'" /></a></div>';
						break;
	
					case 'lightbox':
						echo '<div class="item-grid"><a class="kc-image-link" data-lightbox="kc-lightbox" rel="prettyPhoto" href="'. esc_attr( esc_attr( $attachment_data_full[$i][0] ) ) .'">'
							.'<img src="'. esc_attr($image[0]) .'" /></a></div>';
						break;
	
					case 'custom_link':
						if(isset($custom_links_arr[$i])){
							echo '<div class="item-grid"><a href="'. esc_attr( strip_tags($custom_links_arr[$i]) ) .'" target="_blank">'
								.'<img src="'. esc_attr($image[0]) .'" /></a></div>';
						}else{
							echo '<div class="item-grid"><img src="'. esc_attr($image[0]) .'" /></div>';
						}
	
						break;
				}
			}
			else
			{
				echo $slider_item_start
					.'<img src="'. esc_attr($image[0]) .'" />'
					.$slider_item_end;
			}
	
		}
		echo $ul_end;
	
	}
?>
</div>
<?php
$output = ob_get_clean();

echo '<div class="kc-image-gallery">'.$output.'</div>';


if( $type == 'slider' )
	kc_js_callback('kc_front.image_gallery.slider');
else kc_js_callback('kc_front.image_gallery.masonry');
