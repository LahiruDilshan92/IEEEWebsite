<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*
*/

if(!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$id = isset( $_GET['id'] ) ? $_GET['id'] : 0;
$link = get_permalink( $id );

if( strpos( $link, '?' ) === false )
	$link .= '?kc_action=live-editor';
else $link .= '&kc_action=live-editor';
?>
<iframe id="kc-live-frame" src="<?php echo esc_url( $link ); ?>"></iframe>
<div style="height: 0px;width: 0px;overflow:hidden;">
	<?php wp_editor( '', 'kc-editor-preload', array( "wpautop" => false, "quicktags" => true ) ); ?>
</div>