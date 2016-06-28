<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*
*/
if(!defined('KC_FILE')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

global $kc;

?>

<script type="text/html" id="tmpl-kc-components-template">
	<div id="kc-components">
		<ul class="kc-components-categories">
			<li data-category="all" class="all active"><?php _e('Base Elements', 'kingcomposer'); ?></li>
			<?php
				
				$maps = $kc->get_maps();
				$categories = array();
				
				foreach( $maps as $key => $map )
				{
					$category = isset( $map['category'] ) ? $map['category'] : '';
					
					if( !in_array( $category, $categories ) && $category != '' )
					{
						array_push( $categories, $category );
						echo '<li data-category="'.sanitize_title($category).'" class="'.sanitize_title($category).'">';
						echo esc_html($category);
						echo '</li>';
					}
				}
			?>
			<li data-category="kc-wp-widgets" class="kc-wp-widgets mcl-wp-widgets">
				<i class="fa-wordpress"></i> <?php _e('Widgets', 'kingcomposer'); ?>
			</li>
			<li data-category="kc-clipboard" class="kc-clipboard mcl-clipboard">
				<i class="et-layers"></i> <?php _e('Clipboard', 'kingcomposer'); ?>
			</li>
		</ul>
		<ul class="kc-components-list-main kc-components-list">
			<?php
				foreach( $maps as $key => $map )
				{
					if( !isset( $map['system_only'] ) )
					{
						$category = isset( $map['category'] ) ? $map['category'] : '';
						$name = isset( $map['name'] ) ? $map['name'] : '';
						$icon = isset( $map['icon'] ) ? $map['icon'] : '';
					?>
						<li <?php
								if( isset( $map['description'] ) && !empty( $map['description'] ) ){
									echo 'title="'."\n ".esc_attr( $map['description'] )." \n".'"';
								}
							?> data-category="<?php echo sanitize_title($category); ?>" data-name="<?php echo esc_attr( $key ); ?>" class="mcpn-<?php echo sanitize_title($category); ?>">
							<div>
								<span class="cpicon <?php echo esc_attr( $icon ); ?>"></span>
								<span class="cpdes">
									<strong><?php echo esc_html( $name ); ?></strong>
								</span>
							</div>
						</li>
					<?php
					}	
				}	
			?>
		</ul>
	</div>
</script>

<script type="text/html" id="tmpl-kc-wp-widgets-template">
<div id="kc-wp-list-widgets"><?php 
	
	if( !function_exists( 'submit_button' ) ){
		function submit_button( $text = null, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null ) {
			echo kc_get_submit_button( $text, $type, $name, $wrap, $other_attributes );
		}
	}
	
	ob_start();
		@wp_list_widgets();
		$content = str_replace( array( '<script', '</script>' ), array( '&lt;script', '&lt;/script&gt;' ), ob_get_contents() );
	ob_end_clean();
	
	echo $content;
	
?></div>
</script>
