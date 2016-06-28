<?php
/**
* @package facebook-widget-plus
*/
/*
Plugin Name: Facebook Widget Plus
Plugin URI: http://www.sdghouston.com
Description: Thanks for installing Facebook Widget Plus. Get the custom options in facebook on your wordpress website
Version: 1.0
Author: Muhammad Riduan
Author URI: http://www.sdghouston.com
*/

class FacebookWidgetPlus extends WP_Widget{
    
    public function __construct() {
        $params = array(
            'description' => 'Thanks for installing Facebook Widget',
            'name' => 'Facebook Widget Plus'
        );
        parent::__construct('FacebookWidgetPlus','',$params);
    }
    
    public function form($instance) {
        extract($instance);
        
        ?>
<!-- Color Picker Script Start -->
<script type="text/javascript">
//<![CDATA[
    jQuery(document).ready(function()
    {
	// colorpicker field
	jQuery('.cw-color-picker').each(function(){
	var $this = jQuery(this),
        id = $this.attr('rel');
 	$this.farbtastic('#' + id);
    });
});		
//]]>   
</script>
<!-- Color Picker Script End -->
<!-- here will put all widget configuration -->
<p>
    <label for="<?php echo $this->get_field_id('title');?>">Title : </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('title');?>"
	name="<?php echo $this->get_field_name('title');?>"
        value="<?php echo !empty($title) ? $title : "Facebook Widget Plus"; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('fb_url');?>">Facebook Page URL : </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('fb_url');?>"
	name="<?php echo $this->get_field_name('fb_url');?>"
        value="<?php echo !empty($fb_url) ? $fb_url : "https://www.facebook.com/FacebookDevelopers"; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('width');?>">Width : </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('width');?>"
	name="<?php echo $this->get_field_name('width');?>"
        value="<?php echo !empty($width) ? $width : "300"; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('height');?>">Height : </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('height');?>"
	name="<?php echo $this->get_field_name('height');?>"
        value="<?php echo !empty($height) ? $height : "600"; ?>" />
</p>

<p>
    <label for="<?php echo $this->get_field_id( 'face' ); ?>">Show Faces:</label> 
    <select id="<?php echo $this->get_field_id( 'face' ); ?>"
        name="<?php echo $this->get_field_name( 'face' ); ?>"
        class="widefat" style="width:100%;">
            <option value="true" <?php if ($face == 'true') echo 'selected="true"'; ?> >Yes</option>
            <option value="false" <?php if ($face == 'false') echo 'selected="false"'; ?> >No</option>	
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'header' ); ?>">Hide Cover:</label> 
    <select id="<?php echo $this->get_field_id( 'header' ); ?>"
        name="<?php echo $this->get_field_name( 'header' ); ?>"
        class="widefat" style="width:100%;">
            <option value="false" <?php if ($face == 'header') echo 'selected="header"'; ?> >No</option>
            <option value="true" <?php if ($face == 'header') echo 'selected="header"'; ?> >Yes</option>	
    </select>
</p>

<p>
    <label for="<?php echo $this->get_field_id( 'post' ); ?>">Show Post:</label> 
    <select id="<?php echo $this->get_field_id( 'post' ); ?>"
        name="<?php echo $this->get_field_name( 'post' ); ?>"
        class="widefat" style="width:100%;">
            <option value="true" <?php if ($post == 'true') echo 'selected="true"'; ?> >Yes</option>
            <option value="false" <?php if ($post == 'false') echo 'selected="false"'; ?> >No</option>	
    </select>
</p>
<div style="color:#fff; font-size:12px; font-weight:bold; padding:3px; margin:0; text-align:center; background:#33302e;">CSS Customization</div>
<p>
    <label for="<?php echo $this->get_field_id('backgroundColor');?>">Background Color: </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('backgroundColor');?>"
	name="<?php echo $this->get_field_name('backgroundColor');?>"
        value="<?php echo !empty($backgroundColor) ? $backgroundColor : "#1e9f75"; ?>" />
</p>
<div class="cw-color-picker backgroundColorHide" rel="<?php echo $this->get_field_id('backgroundColor'); ?>"></div>
<p>
    <label for="<?php echo $this->get_field_id('margin_css');?>">Margin: </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('margin_css');?>"
	name="<?php echo $this->get_field_name('margin_css');?>"
        value="<?php echo !empty($margin_css) ? $margin_css : "10px 0px"; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('padding_css');?>">Padding: </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('padding_css');?>"
	name="<?php echo $this->get_field_name('padding_css');?>"
        value="<?php echo !empty($padding_css) ? $padding_css : "0px"; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('border_css');?>">Border CSS: </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('border_css');?>"
	name="<?php echo $this->get_field_name('border_css');?>"
        value="<?php echo !empty($border_css) ? $border_css : "1px solid #c7c7c7"; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('box_shadow');?>">Box Shadow: </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('box_shadow');?>"
	name="<?php echo $this->get_field_name('box_shadow');?>"
        value="<?php echo !empty($box_shadow) ? $box_shadow : "0px 0px 14px #000000"; ?>" />
</p>
<?php
    }
    
    public function widget($args, $instance) {
        extract($args);
        extract($instance);
        $title = apply_filters('widget_title', $title);
        $description = apply_filters('widget_description', $description);
		if(empty($title)) $title = "Facebook Widget Plus";
        if(empty($fb_url)) $fb_url = "http://www.facebook.com/FacebookDevelopers";
        if(empty($width)) $width = "300";
        if(empty($height)) $height = "600";
        if(empty($face)) $face = "true";
        if(empty($header) || $header == '') $header = "false";
        if(empty($post)) $post = "true";
        if(empty($backgroundColor)) $backgroundColor = "#1e9f75";
        if(empty($margin_css)) $margin_css = "10px 0px";
        if(empty($padding_css)) $padding_css = "0px";
        if(empty($border_css)) $padding_css = "1px solid #c7c7c7";
        if(empty($box_shadow)) $box_shadow = "0px 0px 14px #000000";
        
        echo $before_widget;
            echo $before_title . $title . $after_title;
            
            ?>

<style>
    
    .fb-like-box {
				background:<?php echo $backgroundColor; ?>;
				padding:<?php echo $padding_css; ?>;
				margin:<?php echo $margin_css; ?>;
				border:<?php echo $border_css; ?>;
				-webkit-box-shadow: <?php echo $box_shadow; ?>;
				-moz-box-shadow: <?php echo $box_shadow; ?>;
				box-shadow: <?php echo $box_shadow; ?>;
               }
</style>
  <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        
         <div class="facebook_widget_plus">

	     <div class="fb-page" data-href="<?php echo $fb_url;?>"
             data-width="<?php echo $width;?>"
             data-height="<?php echo $height;?>" 
             data-small-header="false" data-adapt-container-width="true" 
             data-hide-cover="<?php echo $header; ?>" 
             data-show-facepile="<?php echo $face; ?>" 
             data-show-posts="<?php echo $post; ?>"><div class="fb-xfbml-parse-ignore">
                <blockquote cite="<?php echo $fb_url;?>">
                    <a href="<?php echo $fb_url;?>">Facebook</a></blockquote></div></div>
		
	</div>
	<div class="support" style="font-size: 9px; text-align: right; position: relative; top: -10px;"><a href="http://www.tensunitdepot.com/" title="Click here" target="_blank">tensunitdepot.com</a></div>
<?php
        echo $after_widget;
    }
}
//registering the color picker
function facebook_widget_plus_color_picker_script() {
	wp_enqueue_script('farbtastic');
}
function facebook_widget_plus_color_picker_style() {
	wp_enqueue_style('farbtastic');
}
add_action('admin_print_scripts-widgets.php', 'facebook_widget_plus_color_picker_script');
add_action('admin_print_styles-widgets.php', 'facebook_widget_plus_color_picker_style');
add_action('widgets_init','register_FacebookWidgetPlus');
function register_FacebookWidgetPlus(){
    register_widget('FacebookWidgetPlus');
}