<?php
/**
*
*	King Composer
*	(c) kingComposer.com
*
*/
if(!defined('KC_FILE')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
	
$kc = KingComposer::globe();
	
?>
<div id="kc-footers" class="kc-footers">
	<img class="empty_guide" src="<?php echo KC_URL; ?>/includes/frontend/builder/assets/empty_guide.png" />
	<ul>
		<li class="basic-add" data-action="browse">
			<span class="m-a-tips"><?php _e('Browse all elements', 'kingcomposer'); ?></span>
		</li>
		<li class="one-column quickadd" data-content='[kc_row][kc_column width="12/12"][/kc_column][/kc_row]'>
			<span class="grp-column" data-action="quick-add" data-action="quick-add"></span>
			<span class="m-a-tips"><?php _e('Add an 1-column row', 'kingcomposer'); ?></span>
		</li>
		<li class="two-columns quickadd"  data-content='[kc_row][kc_column width="6/12"][/kc_column][kc_column width="6/12"][/kc_column][/kc_row]'>
			<span class="grp-column" data-action="quick-add"></span>
			<span class="grp-column" data-action="quick-add"></span>
			<span class="m-a-tips"><?php _e('Add a 2-column row', 'kingcomposer'); ?></span>
		</li>
		<li class="three-columns quickadd" data-content='[kc_row][kc_column width="4/12"][/kc_column][kc_column width="4/12"][/kc_column][kc_column width="4/12"][/kc_column][/kc_row]'>
			<span class="grp-column" data-action="quick-add"></span>
			<span class="grp-column" data-action="quick-add"></span>
			<span class="grp-column" data-action="quick-add"></span>
			<span class="m-a-tips"><?php _e('Add a 3-column row', 'kingcomposer'); ?></span>
		</li>
		<li class="four-columns quickadd" data-content='[kc_row][kc_column width="3/12"][/kc_column][kc_column width="3/12"][/kc_column][kc_column width="3/12"][/kc_column][kc_column width="3/12"][/kc_column][/kc_row]'>
			<span class="grp-column" data-action="quick-add"></span>
			<span class="grp-column" data-action="quick-add"></span>
			<span class="grp-column" data-action="quick-add"></span>
			<span class="grp-column" data-action="quick-add"></span>
			<span class="m-a-tips"><?php _e('Add a 4-column row', 'kingcomposer'); ?></span>
		</li>
		<li class="column-text quickadd" data-action="custom-push" data-content="custom">
			<i class="et-document"></i>
			<span class="m-a-tips"><?php _e('Push customized content and shortcodes', 'kingcomposer'); ?></span>
		</li>
		<li class="title quickadd" data-action="paste" data-content='paste'>
			<i class="et-clipboard"></i>
			<span class="m-a-tips"><?php _e('Paste copied element', 'kingcomposer'); ?></span>
		</li>
		<li class="kc-add-sections" data-action="sections">
			<i class="et-lightbulb"></i> 
			<?php _e('Sections Manager', 'kingcomposer'); ?>
			<span class="m-a-tips"><?php _e('Installation of sections which were added', 'kingcomposer'); ?></span>
		</li>
	</ul>
</div>
