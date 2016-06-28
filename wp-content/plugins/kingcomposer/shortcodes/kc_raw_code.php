<?php
echo '<div class="kc-raw-code">';
if( $code = rawurldecode( base64_decode( $atts['code'] ) ) )
{
	echo do_shortcode( $code );
}
else
{
	echo 'KC Raw Code: Error content structure';	
}
echo '</div>';
