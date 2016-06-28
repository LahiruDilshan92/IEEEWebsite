<?php

$output = $thumb_data = '';
$image_size = 'full';
$onclick = 'none';

extract( $atts );

$items_number = (!empty($items_number))?$items_number:4;

if( !empty( $images ) ){
	$images = explode( ',', $images );
}

if ( is_array( $images ) && !empty( $images ) ) {

	foreach($images as $image_id){
		$attachment_data[] = wp_get_attachment_image_src( $image_id, $image_size );
		$attachment_data_full[] = wp_get_attachment_image_src( $image_id, 'full' );
	}

}else{
	echo '<div class="kc-carousel_images align-center" style="border:1px dashed #ccc;"><br /><h3>Carousel Images: '.__( 'No images upload', 'kingcomposer' ).'</h3></div>';
	return;
}

$element_attribute = array();

$el_classes = array(
	'kc-carousel-images',
	'owl-carousel-images',
	'kc-sync1',
	$wrap_class
);

$owl_option = array(
	'items' => $items_number,
	'speed' => $speed,
	'navigation' => $navigation,
	'pagination' => $pagination,
	'auto_height' => $auto_height,
	'auto_play' => $auto_play,
	'progress_bar'	=> $progress_bar,
	'show_thumb'	=> $show_thumb,
);

$owl_option = json_encode( $owl_option );

$element_attribute[] = 'class="'. esc_attr( implode( ' ', $el_classes ) ) .'"';
$element_attribute[] = 'data-owl-options="'. esc_attr( $owl_option ) .'"';

$custom_links = base64_decode($custom_links);

if( 'custom_link' === $onclick && !empty( $custom_links ) ){
	$custom_links = preg_replace('/\n$/','',preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n", $custom_links)));
	$custom_links_arr = explode("\n", $custom_links);
}

ob_start();

if(!empty($title)){
	echo '<h3 class="kc-title image-gallery-title">'. esc_html($title) .'</div>';
}
?>

<div <?php echo implode( ' ', $element_attribute ); ?>>
<?php foreach($attachment_data as $i => $image): ?>

	<div class="item">

	<?php if( 'none' === $onclick ): ?>
		<img src="<?php echo $image[0]; ?>" />
	<?php else:

		switch( $onclick ){

			case 'lightbox':
				echo '<a class="kc-image-link" data-lightbox="kc-lightbox" rel="prettyPhoto" href="'. esc_attr( esc_attr( $attachment_data_full[$i][0] ) ) .'">'
					.'<img src="'. esc_attr($image[0]) .'" /></a>';
				break;

			case 'custom_link':
				if(isset($custom_links_arr[$i])){
					echo '<a href="'. esc_attr( strip_tags($custom_links_arr[$i]) ) .'" target="'. esc_attr($custom_links_target) .'">'
						.'<img src="'. esc_attr($image[0]) .'" /></a>';
				}else{
					echo '<img src="'. esc_attr($image[0]) .'" />';
				}

				break;

		}

	endif; ?>

	</div>

<?php endforeach; ?>
</div>

<?php if( !empty($show_thumb) && 'yes' === $show_thumb ){ ?>
<div class="kc-sync2 owl-carousel">
	<?php foreach($attachment_data as $image): ?>

		<div class="item">
			<img src="<?php echo $image[0]; ?>" />
		</div>

	<?php endforeach; ?>
</div>
<?php } //end if show thumb ?>

<?php
$output = ob_get_clean();

echo '<div class="kc-carousel_images">'.$output.'</div>';

kc_js_callback('kc_front.carousel_images');