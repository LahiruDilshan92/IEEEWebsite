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
	
$kc = KingComposer::globe();
$kc_maps = $kc->get_maps();

?>
<script type="text/javascript">jQuery('#wpadminbar,#wpfooter,#adminmenuwrap,#adminmenuback,#adminmenumain,#screen-meta').remove();</script>
<div id="kc-preload">
	<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
	<div id="kc-welcome" class="kc-preload-body">
		<h3><?php printf( __( 'Welcome to %sKingComposer Live Editor ', 'kingcomposer' ), '<br />' ); ?></h3>
		<ul>
			<?php if( $kc->verify !== true ){ ?>
			<li class="notice">
				<?php _e('You\'ve not verified the license to use all premium features', 'kingcomposer'); ?> <br />
				<a href="#" class="verify"><?php _e('Verify license now', 'kingcomposer'); ?></a> or
				<a href="#" class="enter"><?php _e('Continue to try', 'kingcomposer'); ?> <i class="fa-long-arrow-right"></i></a>
			</li>
			<?php } ?>
			<li><a href="http://docs.kingcomposer.com" target=_blank><i class="sl-arrow-right"></i> <?php _e('Check the documentation', 'kingcomposer'); ?></a></li>
			<li><a href="https://kingcomposer.com#contact" target=_blank><i class="sl-arrow-right"></i> <?php _e('Send us your feedback', 'kingcomposer'); ?></a></li>
			<li><a href="#" class="tour"><i class="sl-arrow-right"></i> <?php _e('Quick guide before starting', 'kingcomposer'); ?></a></li>
		</ul>
		<a href="#" class="enter close"><i class="sl-close"></i></a>
		<div id="kc-preload-footer">
			<button class="button nope gray left"><?php _e('Don\'t show again', 'kingcomposer'); ?></button>
			<button class="button tour right"><?php _e('Take a quick tour', 'kingcomposer'); ?> <i class="fa-long-arrow-right"></i></button>
		</div>
	</div>
</div>
<div id="wpadminbar">
    <a class="screen-reader-shortcut" href="#wp-toolbar" tabindex="1">Skip to toolbar</a>
    <div class="quicklinks" id="wp-toolbar" role="navigation" aria-label="Toolbar" tabindex="0">
        <ul class="ab-top-menu">
            <li id="kc-bar-logo" class="menupop">
            	<a class="ab-item" title="<?php _e('Visit the KingComposer\'s home page', 'kingcomposer'); ?>" target=_blank href="http://KingComposer.com">
	            	<img src="<?php echo KC_URL; ?>/assets/images/logo_white.png" height="25" />
	            </a>
            </li>
            <li id="kc-inspect-breadcrumns"></li>
        </ul>
        <ul id="kc-top-toolbar" class="ab-top-secondary ab-top-menu">
            <li id="wp-admin-bar-exit" class="kc-bar-save mtips">
                <div class="ab-item">
                	<a href="#exit" id="kc-front-exit">
	                	<i class="fa-times"></i>
	                </a>
                </div>
                <span class="mt-mes"><?php _e('Exit editor', 'kingcomposer'); ?></span>
            </li>
            <li id="wp-admin-bar-save" class="kc-bar-save">
                <div class="ab-item">
                	<a href="#save" id="kc-front-save"><i class="fa-check"></i> <?php _e('Save Changes', 'kingcomposer'); ?></a>
                </div>
            </li>
             <li id="kc-enable-inspect" class="mtips" data-screen="custom">
            	<i class="toggle"></i>
            	<span class="mt-mes"><?php _e('Enable / Disable inspect elements to edit', 'kingcomposer'); ?></span>
            </li>
            <li id="kc-bar-desktop-view" data-screen="100%" class="kc-bar-devices active mtips">
				<i class="fa-desktop"></i>
				<span class="mt-mes"><?php _e('Destop Mode', 'kingcomposer'); ?></span>
            </li>
            <li id="kc-bar-tablet-view" data-screen="768" class="kc-bar-devices mtips">
				<i class="fa-tablet"></i>
				<span class="mt-mes"><?php _e('Tablet Mode', 'kingcomposer'); ?> (768px)</span>
            </li>
            <li id="kc-bar-mobile-landscape-view" data-screen="667" class="kc-bar-devices mtips">
				<i class="fa-mobile"></i>
				<span class="mt-mes"><?php _e('Mobile Mode', 'kingcomposer'); ?> (landscape 667px)</span>
            </li>
            <li id="kc-bar-mobile-view" data-screen="375" class="kc-bar-devices mtips">
				<i class="fa-mobile"></i>
				<span class="mt-mes"><?php _e('Mobile Mode', 'kingcomposer'); ?> (375px)</span>
            </li>
            <li id="kc-curent-screen-view" data-screen="custom" class="kc-bar-devices">100%</li>
            <li id="kc-bar-tour-view" class="mtips">
				<a href="#tour"><i class="fa-paper-plane-o"></i> Guide</a>
				<span class="mt-mes"><?php _e('Take a quick tour', 'kingcomposer'); ?></span>
            </li>
        </ul>
    </div>
</div>
<div id="kc-tours">
	<div id="kc-tour-show"></div>
	<ul id="kc-tour-nav">
		<li class="label"><?php _e('Quick Guide', 'kingcomposer'); ?></li>
		<li class="active" data-media="http://service.kingcomposer.com/guide/tour/inspect.jpg">Inspect elements</li>
		<li data-media="http://service.kingcomposer.com/guide/tour/add_element.jpg">Add Element</li>
		<li data-media="http://service.kingcomposer.com/guide/tour/edit.jpg">Edit Element, row, column</li>
		<li data-media="http://service.kingcomposer.com/guide/tour/nested_column.jpg">Nested Rows & Columns</li>
		<li data-media="http://service.kingcomposer.com/guide/tour/columns.jpg">Add, remove, change width, exchange columns</li>
		<li data-media="http://service.kingcomposer.com/guide/tour/copy.jpg">Copy & paste element, row</li>
		<li data-media="http://service.kingcomposer.com/guide/tour/dummy_content.jpg">Dummy contents (sample)</li>
		<li data-media="http://service.kingcomposer.com/guide/tour/responsive.jpg">Responsive</li>
		<li data-media="http://service.kingcomposer.com/guide/tour/css_box.jpg">CSS Box</li>
		<li data-media="http://service.kingcomposer.com/guide/tour/tabs.jpg">New Tab/Accordion</li>
	</ul>
	<div id="kc-tour-close">
		<span id="tour-follows">
			Follow us: 
			<a href="https://facebook.com/KingComposer" target=_blank>
				<i class="fa-facebook"></i>
			</a>
			<a href="https://twitter.com/kingtheme" target=_blank>
				<i class="fa-twitter"></i>
			</a>
		</span>
		<i class="fa-times"></i>
		<a href="#" id="kc-tour-nope"><?php _e('Don\'t show again', 'kingcomposer'); ?> |</a>
		<a href="#" class="tour-prev">Prev</a>
		<a href="#" class="tour-next">Next</a>
	</div>
</div>
<div id="kc-as-to-buy" class="hidden">
	<div id="kc-welcome" class="kc-preload-body enter-license">
		<h3><?php printf( __( 'Oops, hold on a sec!', 'kingcomposer' ), '<br />' ); ?></h3>
		<div class="kc-pl-form">
			<p class="notice">
				<?php _e( 'You need to verify your license key to do this action and use full of all another premium features.', 'kingcomposer' ); ?>
			</p>
			<input type="hidden" value="<?php echo wp_create_nonce( "kc-verify-nonce" ); ?>" name="sercurity" />
			<input type="text" value="" placeholder="<?php _e('Enter your license key', 'kingcomposer'); ?>" name="kc-license-key" />
			<br />
			<p><?php  printf( __( 'If you\'ve got one %s to login and copy the license', 'kingcomposer' ), '<a href="https://kingcomposer.com/my-account/" target=_blank>Click Here</a>' ); ?></p>
		</div>
		<a href="#" class="enter close"><i class="sl-close"></i></a>
		<div id="kc-preload-footer">
			<a href="https://kingcomposer.com#pricing" target=_blank class="button gray left"><?php _e('Buy the license', 'kingcomposer'); ?> <i class="fa-shopping-cart"></i></a>
			<a class="button verify right"><?php _e('Verify your license', 'kingcomposer'); ?> <i class="fa-unlock-alt"></i></a>
		</div>
	</div>
</div>
<?php 
	
	foreach( $kc_maps as $name => $map ){
		
		if( isset( $map['live_editor'] ) && is_file( $map['live_editor'] ) ){
			echo '<script type="text/html" id="tmpl-kc-'.esc_attr( $name ).'-template">';
			@include( $map['live_editor'] );
			echo '</script>';
		} 
	} 

?>
