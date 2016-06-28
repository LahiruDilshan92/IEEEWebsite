<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*
*/
if(!defined('KC_FILE')){
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$kc = KingComposer::globe();
$live_tmpl = KC_PATH.KDS.'shortcodes'.KDS.'live_editor'.KDS;

$responsive = array(

	array(
		'name' => 'responsive',
		'label' => __( 'Responsive Options', 'kingcomposer' ).
				   '<button onclick="kc.views.column.apply_all(this,\'responsive\')" class="float-right button button-primary" style="margin: -5px 0 20px -10px;">'.__( 'Apply for all same level columns', 'kingcomposer' ).'</button>',
		'type' => 'group',
		'params' => array(
			array(
				'name' => 'screen',
				'label' => __( 'Screen width', 'kingcomposer' ),
				'type' => 'select',
				'options' => array(
					'(max-width: 479px)' => 'Max 479px (smartphone)',
					'(min-width: 480px) and (max-width: 639px)' => '480px to 639px (phablet)',
					'(min-width: 640px) and (max-width: 767px)' => '640px to 767px (tablet mini)',
					'(min-width: 768px) and (max-width: 999px)' => '768px to 999px (tablet)',
					'(min-width: 1000px) and (max-width: 1169px)' => '1000px to 1169px (13 inch)',
					'(min-width: 1170px)' => 'Min 1170px (desktop)',
					'custom' => 'Custom',
				),
				'description' => __( 'Select screen size', 'kingcomposer' ),
			),
			array(
				'name' => 'range',
				'label' => 'Custom Screen',
				'type' => 'number_slider',
				'value' => '768|999',
				'options' => array(
					'min' => 240,
					'max' => 1920,
					'unit' => 'px',
					'range' => true
				),
				'admin_label' => true,
				'description' => __('Set Min width and Max width of screen', 'kingcomposer'),
				'relation' => array(
					'parent' => 'responsive-screen',
					'show_when' => array('custom')
				),
			),
			array(
				'name' => 'offset',
				'label' => __( 'Offset', 'kingcomposer' ),
				'type' => 'select',
				'options' => array(
					'0' => 'Inherit',
					'1' => '1 column 1/12',
					'2' => '2 columns 2/12',
					'3' => '3 columns 3/12',
					'4' => '4 columns 4/12',
					'5' => '5 columns 5/12',
					'6' => '6 columns 6/12',
					'7' => '7 columns 7/12',
					'8' => '8 columns 8/12',
					'9' => '9 columns 9/12',
					'10' => '10 columns 10/12'
				),
				'description' => __( 'Move columns to the right by increase the left margin', 'kingcomposer' )
			),
			array(
				'name' => 'columns',
				'label' => __( 'Width', 'kingcomposer' ),
				'type' => 'select',
				'options' => array(
					'0' => 'Inherit',
					'1' => '1 column 1/12',
					'2' => '2 columns 2/12',
					'3' => '3 columns 3/12',
					'4' => '4 columns 4/12',
					'5' => '5 columns 5/12',
					'6' => '6 columns 6/12',
					'7' => '7 columns 7/12',
					'8' => '8 columns 8/12',
					'9' => '9 columns 9/12',
					'10' => '10 columns 10/12',
					'11' => '11 columns 11/12',
					'12' => '12 columns 12/12'
				),
				'description' => __( 'width of column in this screen size', 'kingcomposer' )
			),
			array(
				'name' => 'important',
				'label' => __( 'Important', 'kingcomposer' ),
				'type' => 'checkbox',
				'options' => array(
					'yes' =>  __( 'Yes, Please!', 'kingcomposer' ),
				),
				'description' => __( 'Make this option as important and have a higher priority than other places', 'kingcomposer' )
			),
			array(
				'name' => 'display',
				'label' => __( 'Hide Column', 'kingcomposer' ),
				'type' => 'checkbox',
				'options' => array(
					'hide' =>  __( 'Yes, Please!', 'kingcomposer' ),
				),
				'description' => __( 'Hide this column in this screen size', 'kingcomposer' )
			),
		),
		'description' => __( 'Adjust column for different screen sizes.', 'kingcomposer' )
	)

);

$kc->add_map(

	array(

		'_value' => array(
			'name' => 'KC Element',
			'description' => 'KC Element',
			'icon' => 'sl-info',	   /* Class name of icon show on "Add Elements" */
			'category' => '',	  /* Category to group elements when "Add Elements" */
			'is_container' => false, /* Container has begin + end [name]...[/name] -  Single has only [name param=""] */
			'pop_width' => 580,		/* width of the popup will be open when clicking on the edit  */
			'system_only' => true, /* Use for system only and dont show up to Add Elements */
			'params' => array()
		),

		'kc_undefined' => array(
			'name' => 'Undefined Element',
			'icon' => 'sl-flag',
			'category' => '',
			'is_container' => true,
			'pop_width' => 750,
			'system_only' => true,
			'params' => array(
				array(
					'name' => 'content',
					'label' => 'Content',
					'type' => 'textarea_html',
					'value' => 'Sample Text',
					'admin_label' => true,
				)
			)
		),

		'kc_wp_widget' => array(
			'name' => 'Wordpress Widget',
			'icon' => 'kc-icon-wordpress',
			'category' => '',
			'pop_width' => 450,
			'system_only' => true,
			'params' => array(
				array(
					'name' => 'data',
					'label' => 'Data',
					'type' => 'wp_widget',
					'admin_label' => true,
				)
			)
		),

		'kc_css_box' => array(

			'name' => 'CSS Box',
			'system_only' => true,
			'params' => array(

				array(
					'name' => 'margin',
					'label' => 'Margin',
					'type' => 'css_box_tbtl'
				),
				array(
					'name' => 'padding',
					'label' => 'Padding',
					'type' => 'css_box_tbtl',
				),
				array(
					'name' => 'border',
					'label' => 'Border',
					'type' => 'css_box_border',
				),
				array(
					'name' => 'color',
					'label' => 'Text Color',
					'type' => 'color_picker',
					'description' => 'Color of the text.'
				),
				array(
					'name' => 'text-align',
					'label' => 'Text Align',
					'type' => 'select',
					'options' => array(
						'' => 'Default',
						'center' => 'Center',
						'left' => 'Left',
						'right' => 'Right',
						'justify' => 'Justify',
						'start' => 'Start',
						'inherit' => 'Inherit',
						'initial' => 'Initial',
					),
				),
				array(
					'name' => 'float',
					'label' => 'Float',
					'type' => 'select',
					'options' => array(
						'' => 'Default',
						'left' => 'Left',
						'right' => 'Right',
						'none' => 'None',
					),
				),
				array(
					'name' => 'display',
					'label' => 'Display',
					'type' => 'select',
					'options' => array(
						'' => 'Default',
						'block' => 'Block',
						'inline-block' => 'Inline Block',
						'none' => 'None',
					),
				),
				array(
					'name' => 'background-color',
					'label' => 'Background Color',
					'type' => 'color_picker',
					'description' => 'Background color of the element.'
				),
				array(
					'name' => 'background-image-option',
					'label' => 'Background Image',
					'type' => 'checkbox',
					'description' => 'Use a background image instead of a plain color.',
					'options' => array( 'yes' => 'Yes, Please!' )
				),
				array(
					'name' => 'background-image',
					'label' => 'Upload Image',
					'type' => 'attach_image_url',
					'value' =>  KC_URL.'/assets/images/get_start.jpg',
					'relation' => array(
						'parent' => 'background-image-option',
						'show_when' => 'yes'
					)
				),
				array(
					'name' => 'background-repeat',
					'label' => 'BG Repeat',
					'type' => 'select',
					'options' => array(
						'' => 'Yes',
						'no-repeat' => 'No Repeat',
						'repeat-x' => 'Repeat Horizontal',
						'repeat-y' => 'Repeat Vertical'
					),
					'relation' => array(
						'parent' => 'background-image-option',
						'show_when' => 'yes'
					)
				),
				array(
					'name' => 'background-position',
					'label' => 'BG Position',
					'type' => 'text',
					'description' => 'center center | 0px 0px | 50% 50%',
					'relation' => array(
						'parent' => 'background-image-option',
						'show_when' => 'yes'
					)
				),
				array(
					'name' => 'background-attachment',
					'label' => 'BG Attachment',
					'type' => 'text',
					'description' => 'scroll | fixed | local | initial | inherit',
					'relation' => array(
						'parent' => 'background-image-option',
						'show_when' => 'yes'
					)
				),
				array(
					'name' => 'background-size',
					'label' => 'BG Size',
					'type' => 'text',
					'description' => 'auto | length | cover | contain | initial | inherit',
					'relation' => array(
						'parent' => 'background-image-option',
						'show_when' => 'yes'
					)
				),
			)

		),

		'kc_row' => array(
			'name' => 'Row',
			'description' => __( 'Place content elements inside the row', 'kingcomposer' ),
			'category' => '',
			'title' => 'Row Settings',
			'is_container' => true,
			'css_box' => true,
			'system_only' => true,
			'live_editor' => $live_tmpl.'kc_row.php',
			'params' => array(

				array(
					'name' => 'row_id',
					'label' => 'Row ID',
					'type' => 'text',
					'description' => __('The unique identifier of the row.', 'kingcomposer'),
				),
				array(
					'name' => 'full_width_option',
					'label' => 'Full width?',
					'type' => 'checkbox',
					'description' => __('Set the 100% width of the row and content.', 'kingcomposer'),
					'options' => array(
						'yes' => 'Yes, Please!'
					),
				),
				array(
					'name' => 'full_width',
					'label' => 'Stretch Option',
					'type' => 'radio',
					'value' => 'stretch_row',
					'description' => __('Please select stretch options.', 'kingcomposer'),
					'options' => array(
						'stretch_row' => 'Only Row',
						'stretch_row_content' => 'Row & Content'
					),
					'relation' => array(
						'parent' => 'full_width_option',
						'show_when' => 'yes'
					),
				),
				array(
					'name' => 'full_height',
					'label' => __( 'Full height?', 'kingcomposer' ),
					'type' => 'checkbox',
					'description' => __( 'Set the 100% height of the row.', 'kingcomposer' ),
					'options' => array(
						'yes' => __( 'Yes, Please!', 'kingcomposer' )
					)
				),
				array(
					'name' => 'equal_height',
					'label' => __( 'Equal height?', 'kingcomposer' ),
					'type' => 'checkbox',
					'description' => __( 'If checked, all columns will be set to equal height ( not including columns of row inner ).', 'kingcomposer' ),
					'options' => array(
						'yes' => __( 'Yes, Please!', 'kingcomposer' )
					)
				),
				array(
					'name' => 'content_placement',
					'label' => __( 'Content position', 'kingcomposer' ),
					'type' => 'select',
					'options' => array(
						'middle' => __( 'Middle', 'kingcomposer' ),
						'top' => __( 'Top', 'kingcomposer' ),
					),
					'description' => __( 'Select content position within row when full-height.', 'kingcomposer' ),
					'relation' => array(
						'parent' => 'full_height',
						'show_when' => 'yes'
					),
				),
				array(
					'name' => 'video_bg',
					'label' => __( 'Use video background?', 'kingcomposer' ),
					'type' => 'checkbox',
					'description' => __( 'Background video will be applied to the row.', 'kingcomposer' ),
					'options' => array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) )
				),
				array(
					'name' => 'video_bg_url',
					'label' => __( 'YouTube link', 'kingcomposer' ),
					'type' => 'text',
					'value' => 'https://www.youtube.com/watch?v=dOWFVKb2JqM',
					'description' => __( 'Add YouTube link.', 'kingcomposer' ),
					'relation' => array(
						'parent' => 'video_bg',
						'show_when' => 'yes'
					),
				),
				array(
					'name' => 'parallax',
					'label' => __( 'Parallax', 'kingcomposer' ),
					'type' => 'select',
					'options' => array(
						'' => __( 'None', 'kingcomposer' ),
						'yes' => __( 'Use Background Image', 'kingcomposer' ),
						'yes-new' => __( 'Upload New Image', 'kingcomposer' ),
					),
					'description' => __( 'Add a parallax scrolling to the row (Note: If no image is specified, the background image from Design Options will be utilized).', 'kingcomposer' ),
					'relation' => array(
						'parent' => 'video_bg',
						'hide_when' => 'yes',
					),
				),
				array(
					'name' => 'parallax_image',
					'label' => __( 'Image', 'kingcomposer' ),
					'type' => 'attach_images',
					'value' => '',
					'description' => __( 'Select image from media library.', 'kingcomposer' ),
					'relation' => array(
						'parent' => 'parallax',
						'show_when' => 'yes-new',
					),
				),
				array(
					'name' => 'parallax_speed',
					'label' => __( 'Parallax Speed', 'kingcomposer' ),
					'type' => 'select',
					'description' => __( 'Set speed of parallax when scroll.', 'kingcomposer' ),
					'options' => array(
						'1' => '1',
						'0.7' => '0.7',
						'0.4' => '0.4',
						'0.1' => '0.1',
					),
					'value' => '1',
					'relation' => array(
						'parent' => 'parallax',
						'show_when' => 'yes,yes-new',
					),
				),
				array(
					'name' => 'parallax_background_size',
					'label' => __( 'Full background?', 'kingcomposer' ),
					'type' => 'checkbox',
					'description' => __( 'Make background size full width & height and prevent repeat', 'kingcomposer' ),
					'options' => array(
						'yes' => __( 'Yes, Please!', 'kingcomposer' ),
					),
					'value' => 'yes',
					'relation' => array(
						'parent' => 'parallax',
						'show_when' => 'yes,yes-new',
					),
				),
				array(
					'name' => 'use_container',
					'label' => __( 'Row container', 'kingcomposer' ),
					'type' => 'checkbox',
					'options' => array(
						'yes' => __( 'Yes, Please!', 'kingcomposer' ),
					),
					'description' => __( 'Enable container for this row ( in case you are using full width template, this option will wrap content into container and background of row is still full width ).', 'kingcomposer' )
				),
				array(
					'name' => 'container_class',
					'label' => __( 'Container extra classes name', 'kingcomposer' ),
					'type' => 'text',
					'description' => __( 'Add classes custom to the Container.', 'kingcomposer' ),
					'relation' => array(
						'parent' => 'use_container',
						'show_when' => 'yes',
					),
				),
				array(
					'name' => 'row_class',
					'label' => __( 'Row extra classes name', 'kingcomposer' ),
					'type' => 'text',
					'description' => __( 'Add additional custom classes to the Row.', 'kingcomposer' ),
				)
			)
		),


		'kc_row_inner' => array(
			'name' => 'Row Inner',
			'description' => 'nested rows & columns ',
			'icon' => 'kc-icon-row',
			'category' => '',
			'title' => 'Row Inner Settings',
			'is_container' => true,
			'css_box' => true,
			'live_editor' => $live_tmpl.'kc_row_inner.php',
			'params' => array(
				array(
					'name' => 'row_id',
					'label' => 'Row ID',
					'type' => 'text',
					'value' => __('', 'kingcomposer'),
					'description' => 'The unique identifier of the row'
				),
				array(
					'name' => 'equal_height',
					'label' => __( 'Equal height?', 'kingcomposer' ),
					'type' => 'checkbox',
					'description' => __( 'If checked, all columns will be set to equal height ( not including columns of row inner ).', 'kingcomposer' ),
					'options' => array(
						'yes' => __( 'Yes, Please!', 'kingcomposer' )
					)
				),
				array(
					'name' => 'row_class',
					'label' => __( 'Extra classes name', 'kingcomposer' ),
					'type' => 'text',
					'description' => __( 'If you wish to style a particular content element differently, please add a class name to this field and refer to it in your custom CSS.', 'kingcomposer' ),
				),
				array(
					'name' => 'row_class_container',
					'label' => __( 'Extra container classes', 'kingcomposer' ),
					'type' => 'text',
					'description' => __( 'Add additional classes name to the container in a row.', 'kingcomposer' ),
				),
			)
		),

		'kc_column' => array(
			'name' => 'Column',
			'category' => '',
			'title' => 'Column Settings',
			'is_container' => true,
			'system_only' => true,
			'css_box' => true,
			'tab_icons' => array(
				'general' => 'et-tools',
				'responsive' => 'et-mobile'
			),
			'live_editor' => $live_tmpl.'kc_column.php',
			'params' => array(
				'general' => array(
					array(
						'name' => 'col_container_class',
						'label' => __( 'Container class name', 'kingcomposer' ),
						'type' => 'text',
						'description' => __( 'Add additional classes name to the container in a column.', 'kingcomposer' )
					),
					array(
						'name' => 'col_class',
						'label' => __( 'Column class name', 'kingcomposer' ),
						'type' => 'text',
						'description' => __( 'Add additional classes name to ther outer layer of a column.', 'kingcomposer' )
					)
				),
				'responsive' => $responsive
			)
		),

		'kc_column_inner' => array(
			'name' => 'Column Inner',
			'category' => '',
			'title' => 'Column Inner Settings',
			'is_container' => true,
			'system_only' => true,
			'css_box' => true,
			'tab_icons' => array(
				'general' => 'et-tools',
				'responsive' => 'et-mobile'
			),
			'live_editor' => $live_tmpl.'kc_column_inner.php',
			'params' => array(
				'general' => array(

					array(
						'name' => 'col_in_class',
						'label' => __( 'Extra class name', 'kingcomposer' ),
						'type' => 'text',
						'description' => __( 'If you wish to style a particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'kingcomposer' )
					),
					array(
						'name' => 'col_in_class_container',
						'label' => __( 'Extra container Class', 'kingcomposer' ),
						'type' => 'text',
						'description' => __( 'Add class name for container into column.', 'kingcomposer' ),
					),
				),
				'responsive' => $responsive
			)
		),

		'kc_box' => array(
			'name' => 'KC Box',
			'category' => '',
			'title' => 'KC Box Design',
			'icon' => 'kc-icon-box',
			'pop_width' => 900,
			'description' => __( 'Helping design static block', 'kingcomposer' ),
			'live_editor' => $live_tmpl.'kc_box.php',
			'params' => array(
				array(
					'name' => 'css',
					'type' => 'hidden',
				),
				array(
					'name' => 'data',
					'type' => 'kc_box',
					'admin_label' => true,
					'value' => 'W3sidGFnIjoiZGl2IiwiY2hpbGRyZW4iOlt7InRhZyI6InRleHQiLCJjb250ZW50IjoiU2FtcGxlIFRleHQuIn1dfV0='
					/*This is special element, All will be built in template*/
				),
			)
		),

		'kc_tabs' => array(
			'name' => 'Tabs - Sliders',
			'description' => __( 'Tabbed or Sliders content', 'kingcomposer' ),
			'category' => '',
			'icon' => 'kc-icon-tabs',
			'title' => 'Tabs - Sliders Settings',
			'is_container' => true,
			'views' => array(
				'type' => 'views_sections',
				'sections' => 'kc_tab'
			),
			'live_editor' => $live_tmpl.'kc_tabs.php',
			'params' => array(
				array(
					'name' => 'class',
					'label' => 'Extra Class',
					'type' => 'text'
				),
				array(
					'name' => 'type',
					'label' => __('How Display', 'kingcomposer'),
					'type' => 'select',
					'options' => array(
						'horizontal_tabs' => __('Horizontal Tabs', 'kingcomposer'),
						'vertical_tabs' => __('Vertical Tabs', 'kingcomposer'),
						'slider_tabs' => __('Owl Sliders', 'kingcomposer')
					),
					'description' => __('Use sidebar view of your tabs as horizontal, vertical or slider.', 'kingcomposer')
				),
				array(
					'name' => 'title_slider',
					'label' => 'Display Titles?',
					'type' => 'checkbox',
					'options' => array(
						'yes' => __('Yes, Please!', 'kingcomposer')
					),
					'relation' => array(
						'parent' => 'type',
						'show_when' => 'slider_tabs'
					),
					'description' => __('Display tabs title above of the slider', 'kingcomposer')
				),
				array(
					'name' => 'items',
					'label' => 'Number Items?',
					'type' => 'select',
					'options' => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					),
					'relation' => array(
						'parent' => 'type',
						'show_when' => 'slider_tabs'
					),
					'description' => __('Display number of items per each slide (Desktop Screen)', 'kingcomposer')
				),
				array(
					'name' => 'tablet',
					'label' => 'Items on tablet?',
					'type' => 'select',
					'options' => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					),
					'relation' => array(
						'parent' => 'type',
						'show_when' => 'slider_tabs'
					),
					'description' => __('Display number of items per each slide (Tablet Screen)', 'kingcomposer')
				),
				array(
					'name' => 'mobile',
					'label' => 'Items on smartphone?',
					'type' => 'select',
					'options' => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
					),
					'relation' => array(
						'parent' => 'type',
						'show_when' => 'slider_tabs'
					),
					'description' => __('Display number of items per each slide (Smartphone Screen)', 'kingcomposer')
				),
				array(
					'name' => 'speed',
					'label' => 'Speed of slider',
					'type' => 'number_slider',
					'options' => array(
						'min' => 100,
						'max' => 1000,
						'show_input' => true
					),
					'value' => 450,
					'relation' => array(
						'parent' => 'type',
						'show_when' => 'slider_tabs'
					),
					'description' => __('The speed of sliders in millisecond', 'kingcomposer')
				),
				array(
					'name' => 'navigation',
					'label' => 'Navigation',
					'type' => 'checkbox',
					'options' => array(
						'yes' => __('Yes, Please!', 'kingcomposer')
					),
					'relation' => array(
						'parent' => 'type',
						'show_when' => 'slider_tabs'
					),
					'description' => __('Display the "Next" and "Prev" buttons.', 'kingcomposer')
				),
				array(
					'name' => 'pagination',
					'label' => 'Pagination',
					'type' => 'checkbox',
					'options' => array(
						'yes' => __('Yes, Please!', 'kingcomposer')
					),
					'relation' => array(
						'parent' => 'type',
						'show_when' => 'slider_tabs'
					),
					'value' => 'yes',
					'description' => __('Show the pagination.', 'kingcomposer')
				),
				array(
					'name' => 'autoplay',
					'label' => 'Auto Play',
					'type' => 'checkbox',
					'options' => array(
						'yes' => __('Yes, Please!', 'kingcomposer')
					),
					'relation' => array(
						'parent' => 'type',
						'show_when' => 'slider_tabs'
					),
					'description' => __('The slider automatically plays when site loaded', 'kingcomposer')
				),
				array(
					'name' => 'autoheight',
					'label' => 'Auto Height',
					'type' => 'checkbox',
					'options' => array(
						'yes' => __('Yes, Please!', 'kingcomposer')
					),
					'relation' => array(
						'parent' => 'type',
						'show_when' => 'slider_tabs'
					),
					'description' => __('The slider height will change automatically', 'kingcomposer')
				),
				array(
					'name' => 'effect_option',
					'label' => 'Enable fadein effect?',
					'type' => 'checkbox',
					'options' => array(
						'yes' => __('Yes, Please!', 'kingcomposer')
					),
					'relation' => array(
						'parent' => 'type',
						'hide_when' => 'slider_tabs'
					),
					'description' => __('Quickly apply fade in and face out effect when users click on tab.', 'kingcomposer')
				),
				array(
					'name' => 'vertical_tabs_position',
					'label' => __('Position', 'kingcomposer'),
					'type' => 'select',
					'options' => array(
						'left' => __('Left', 'kingcomposer'),
						'right' => __('Right', 'kingcomposer')
					),
					'relation' => array(
						'parent' => 'type',
						'show_when' => array('vertical_tabs')
					),
					'description' => __('View tabs with at top or bottom', 'kingcomposer')
				),
				array(
					'name' => 'open_mouseover',
					'label' => __('Open on mouseover', 'kingcomposer'),
					'type' => 'checkbox',
					'options' => array(
						'yes' => __( 'Yes', 'kingcomposer' )
					),
					'relation' => array(
						'parent' => 'type',
						'hide_when' => 'slider_tabs'
					),
				),
				array(
					'name' => 'active_section',
					'label' => __('Active section', 'kingcomposer'),
					'type' => 'text',
					'value' => '1',
					'relation' => array(
						'parent' => 'type',
						'hide_when' => 'slider_tabs'
					),
					'description' => __('Enter active section number.', 'kingcomposer')
				)
			),
			'content' => '[kc_tab title="New Tab"][/kc_tab]'
		),

		'kc_tab' => array(
			'name' => 'Tab',
			'category' => '',
			'title' => 'Tab Settings',
			'is_container' => true,
			'system_only' => true,
			'live_editor' => $live_tmpl.'kc_tab.php',
			'params' => array(
				array(
					'name' => 'title',
					'label' => 'Title',
					'type' => 'text',
					'value' => __('New Tab', 'kingcomposer'),
				),
				array(
					'name' => 'advanced',
					'label' => 'Advance Title?',
					'type' => 'checkbox',
					'options' => array(
						'yes' => __('Yes, Please!', 'kingcomposer')
					),
					'description' => __('If you want more flexible options to display tab title', 'kingcomposer')
				),
				
				array(
					'name' => 'adv_title',
					'label' => 'Title Format',
					'type' => 'textarea',
					'value' => base64_encode( __('<p>New Tab<p>', 'kingcomposer') ),
					'relation' => array(
						'parent' => 'advanced',
						'show_when' => 'yes'
					),
					'description' => __('You can use short varibles {title}, {icon} , {icon_class}, {image}, {image_id}, {image_url}, {image_thumbnail}, {image_medium}, {image_large}, {image_full}, {tab_id}', 'kingcomposer')
				),
				array(
					'name' => 'adv_icon',
					'label' => 'Icon Title',
					'type' => 'icon_picker',
					'value' => '',
					'relation' => array(
						'parent' => 'advanced',
						'show_when' => 'yes'
					)
				),
				array(
					'name' => 'adv_image',
					'label' => 'Image Title',
					'type' => 'attach_image',
					'value' => '',
					'relation' => array(
						'parent' => 'advanced',
						'show_when' => 'yes'
					)
				),
				array(
					'name' => 'icon_option',
					'label' => 'Use Icon?',
					'type' => 'checkbox',
					'options' => array(
						'yes' => __('Yes, Please!', 'kingcomposer')
					),
					'description' => __('If you want to display an icon beside title, Tick to choose an icon', 'kingcomposer'),
					'relation' => array(
						'parent' => 'advanced',
						'hide_when' => 'yes'
					)
				),
				array(
					'name' => 'icon',
					'label' => 'Icon Title',
					'type' => 'icon_picker',
					'value' => '',
					'description' => __('Choose an icon to display with title', 'kingcomposer'),
					'relation' => array(
						'parent' => 'icon_option',
						'show_when' => 'yes'
					)
				),
				array(
					'name' => 'class',
					'label' => 'Extra Class',
					'type' => 'text'
				)
			)
		),

		'kc_accordion' => array(
			'name' => 'Accordion',
			'description' => __( 'Collapsible content panels', 'kingcomposer' ),
			'category' => '',
			'icon' => 'kc-icon-accordion',
			'title' => 'Accordion Settings',
			'is_container' => true,
			'views' => array(
				'type' => 'views_sections',
				'sections' => 'kc_accordion_tab',
				'display' => 'vertical'
			),
			'live_editor' => $live_tmpl.'kc_accordion.php',
			'params' => array(
				array(
					'name' => 'title',
					'label' => 'Title',
					'type' => 'text',
					'description' => __('Enter accordion title (Note: It is located above the content).', 'kingcomposer')
				),
				array(
					'name' => 'open_all',
					'label' => 'Collapse all?',
					'type' => 'checkbox',
					'options' => array(
						'yes' => __('Yes, Please!', 'kingcomposer')
					),
					'description' => __('Allow all accordion tabs able to open', 'kingcomposer')
				),
				array(
					'name' => 'class',
					'label' => 'Extra class name',
					'type' => 'text',
					'description' => __('If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'kingcomposer')
				)
			),
			'content' => '[kc_accordion_tab title="Accordion Tab"][/kc_accordion_tab]'
		),

		'kc_accordion_tab' => array(
			'name' => 'Accordion Tab',
			'category' => '',
			'title' => 'Accordion Tab Settings',
			'is_container' => true,
			'system_only' => true,
			'live_editor' => $live_tmpl.'kc_accordion_tab.php',
			'params' => array(
				array(
					'name' => 'title',
					'label' => __('Title', 'kingcomposer'),
					'value' => __('New Accordion Tab', 'kingcomposer'),
					'type' => 'text'
				),
				array(
					'name' => 'icon_option',
					'label' => 'Use Icon?',
					'type' => 'checkbox',
					'options' => array(
						'yes' => __('Yes, Please!', 'kingcomposer')
					),
					'description' => __('If you want to display an icon beside title, Tick to choose an icon', 'kingcomposer')
				),
				array(
					'name' => 'icon',
					'label' => 'Icon Title',
					'type' => 'icon_picker',
					'value' => '',
					'description' => __('Choose an icon to display with title', 'kingcomposer'),
					'relation' => array(
						'parent' => 'icon_option',
						'show_when' => 'yes'
					)
				),
				array(
					'name' => 'class',
					'label' => 'Extra class name',
					'type' => 'text',
					'description' => __('', 'kingcomposer')
				)
			)
		),

		'kc_column_text' => array(
			'name' => 'Text Block',
			'description' => __('A block of text with TINYMCE editor', 'kingcomposer'),
			'icon' => 'kc-icon-text',
			'category' => '',
			'is_container' => true,
			'pop_width' => 750,
			'admin_view'	=> 'text',
			'preview_editable' => true,
			'live_editor' => $live_tmpl.'kc_column_text.php',
			'params' => array(
				array(
					'name' => 'content',
					'label' => 'Content',
					'type' => 'textarea_html',
					'value' => 'Sample Text',
				),
				array(
					'name' => 'class',
					'label' => 'Extra Class',
					'type' => 'text',
				)
			)
		),

		'kc_spacing' => array(
			'name' => 'Spacing',
			'description' => __('Custom the spacing between the blocks', 'kingcomposer'),
			'icon' => 'kc-icon-spacing',
			'category' => '',
			'live_editor' => $live_tmpl.'kc_spacing.php',
			'params' => array(
				array(
					'name' => 'height',
					'label' => 'Height',
					'type' => 'number_slider',
					'value' => '20',
					'options' => array(
						'min' => 0,
						'max' => 500,
						'unit' => 'px',
						'show_input' => true
					),
					'admin_label' => true,
					'description' => __('Enter the value of spacing height', 'kingcomposer'),
				),
				array(
					'name' => 'class',
					'label' => __('Extra class name', 'kingcomposer'),
					'type' => 'text',
					'admin_label' => true,
					'description' => __('If you wish to style a particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'kingcomposer')
				)
			)
		),

		'kc_raw_code' => array(
			'name' => 'Raw Code',
			'description' => __('Allow to put code: html, shortcode', 'kingcomposer'),
			'icon' => 'kc-icon-code',
			'category' => '',
			'pop_width' => 750,
			'live_editor' => $live_tmpl.'kc_raw_code.php',
			'params' => array(
				array(
					'name' => 'code',
					'label' => 'Code',
					'type' => 'textarea',
					'value' => 'U2FtcGxlIENvZGU=',
					'admin_label' => true,
				)
			)
		),

		'kc_single_image' => array(
			'name' => 'Single Image',
			'description' => __('Single image', 'kingcomposer'),
			'icon' => 'kc-icon-image',
			'category' => '',
			'admin_view' => 'image',
			'preview_editable' => true,
			'css_box'	=> true,
			'live_editor' => $live_tmpl.'kc_single_image.php',
			'params' => array(

				array(
					'name'    => 'image_source',
					'label'   => __('Image Source', 'kingcomposer'),
					'type'    => 'select',
					'options' => array(
						'media_library'  => __('Media library', 'kingcomposer'),
						'external_link'  => __('External link', 'kingcomposer'),
						'featured_image' => __('Featured Image', 'kingcomposer'),
					),
					'description' => __('Select image source.', 'kingcomposer')
				),
				array(
					'name'        => 'image',
					'label'       => 'Upload Image',
					'type'        => 'attach_image',
					'admin_label' => true,
					'relation'    => array(
						'parent'    => 'image_source',
						'show_when' => 'media_library'
					)
				),
				array(
					'name'     => 'image_external_link',
					'label'    => 'Image external link',
					'type'     => 'text',
					'relation' => array(
						'parent'    => 'image_source',
						'show_when' => 'external_link'
					),
					'description' => __('Enter external link.', 'kingcomposer')
				),
				array(
					'name'          => 'image_size',
					'label'         => 'Image Size',
					'type'          => 'text',
					'value'         => 'thumbnail',
					'relation'      => array(
						'parent'    => 'image_source',
						'show_when' => array('media_library', 'featured_image')
					),
					'description'   => __('Set the image size: "thumbnail", "medium", "large" or "full"', 'kingcomposer'),
					'value'         => 'full'
				),
				array(
					'name'          => 'image_size_el',
					'label'         => 'Image Size',
					'type'          => 'text',
					'relation'      => array(
						'parent'    => 'image_source',
						'show_when' => 'external_link'
					),
					'description'   => __('Enter the image size in pixels. Example: 200x100 (Width x Height).', 'kingcomposer')
				),
				array(
					'name'        => 'caption',
					'label'       => 'Caption',
					'type'        => 'text',
					'description' => __('Enter the image caption.', 'kingcomposer')
				),
				array(
					'name'    => 'image_align',
					'label'   => 'Image alignment',
					'type'    => 'select',
					'options' => array(
						'left'   => __('Left', 'kingcomposer'),
						'right'  => __('Right', 'kingcomposer'),
						'center' => __('Center', 'kingcomposer')
					),
					'description' => __('Select the image alignment.', 'kingcomposer')
				),
				array(
					'name'    => 'on_click_action',
					'label'   => __('On click event', 'kingcomposer'),
					'type'    => 'select',
					'options' => array(
						''                 => __('None', 'kingcomposer'),
						'op_large_image'   => __('Link to large image', 'kingcomposer'),
						'lightbox'         => __('Open Image In Lightbox', 'kingcomposer'),
						'open_custom_link' => __('Open Custom Link', 'kingcomposer')
					),
					'description' => __('Select the click event when users click on the image.', 'kingcomposer')
				),
				array(
					'name'     => 'custom_link',
					'label'    => __('Custom Link', 'kingcomposer'),
					'type'     => 'text',
					'value'    => 'http://',
					'relation' => array(
						'parent'    => 'on_click_action',
						'show_when' => 'open_custom_link'
					),
					'description' => __('Enter URL if you want this image to have a link (Note: parameters like "mailto:" are also accepted).', 'kingcomposer')
				),
				array(
					'name'        => 'ieclass',
					'label'       => __('Image extra class', 'kingcomposer'),
					'type'        => 'text',
					'description' => __('Add class name for img tag.', 'kingcomposer')
				),
				array(
					'name'    => 'image_wrap',
					'label'   => 'Div Wrapper',
					'type'    => 'select',
					'options' => array(
						'yes'   => __('Yes, Please!', 'kingcomposer'),
						'no'   => __('No, Thanks!', 'kingcomposer')
					),
					'description' => __('Put the image into a div warpper', 'kingcomposer'),
					'value' => 'yes'
				),
				array(
					'name'        => 'class',
					'label'       => __('Wrapper extra class', 'kingcomposer'),
					'type'        => 'text',
					'description' => __('If you wish to style a particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'kingcomposer'),
					'relation' => array(
						'parent' => 'image_wrap',
						'show_when' => 'yes'
					)
				)
			)
		),

		'kc_icon' => array(
			'name'		  => 'Icon',
			'description' => __('Display single icon', 'kingcomposer'),
			'icon'		  => 'kc-icon-icon',
			'category'	  => '',
			'live_editor' => $live_tmpl.'kc_icon.php',
			'params'	  => array(
				array(
					'name'	      => 'icon',
					'label'	      => 'Select Icon',
					'type'	      => 'icon_picker',
					'admin_label' => true,
				),
				array(
					'name'	      => 'icon_align',
					'label'	      => 'Icon alignment',
					'type'	      => 'dropdown',
					'description' => __('', 'kingcomposer'),
					'options'     => array(
						'none'    => __('None', 'kingcomposer'),
						'left'    => __('Left', 'kingcomposer'),
						'right'   => __('Right', 'kingcomposer'),
						'center'  => __('Center', 'kingcomposer'),
					)
				),
				array(
					'name'	      => 'icon_size',
					'label'	      => 'Icon Size',
					'type'	      => 'text',
					'admin_label' => true,
					'description' => __('Enter the font-size of the icon such as: 15px, 1em, etc.', 'kingcomposer')
				),
				array(
					'name'	      => 'icon_color',
					'label'	      => 'Icon Color',
					'type'	      => 'color_picker',
					'admin_label' => true,
					'description' => __('Color of the icon.', 'kingcomposer')
				),
				array(
					'name'	      => 'class',
					'label'	      => __('Extra class name', 'kingcomposer'),
					'type'	      => 'text',
					'admin_label' => true,
					'description' => __('If you wish to style a particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'kingcomposer')
				),
				array(
					'name'	      => 'icon_wrap',
					'label'	      => 'Icon Wrapper?',
					'type'	      => 'checkbox',
					'options'	  => array(
						'yes'     => __('Yes, Please!', 'kingcomposer')
					),
					'description' => __('Add a <div> tag around the icon.', 'kingcomposer')
				),
				array(
					'name'	        => 'icon_wrap_class',
					'label'	        => 'Wrapper class name',
					'type'	        => 'text',
					'description'   => __('Enter class name for wrapper', 'kingcomposer'),
					'relation'      => array(
						'parent'    => 'icon_wrap',
						'show_when' => 'yes'
					)
				),
			)
		),

		'kc_title' => array(
			'name'		  => 'Title',
			'description' => __('Heading titles H(n) Tag', 'kingcomposer'),
			'icon'		  => 'kc-icon-title',
			'category'	  => '',
			'css_box'	  => true,
			'live_editor' => $live_tmpl.'kc_title.php',
			'params'	  => array(
				array(
					'name'	      => 'text',
					'label'       => 'Text',
					'type'	      => 'textarea',
					'value'		  => base64_encode('The Title'),
					'admin_label' => true
				),
				array(
					'name'	  => 'type',
					'label'   => 'Type',
					'type'	  => 'select',
					'options' => array(
						'h1'  => 'H1',
						'h2'  => 'H2',
						'h3'  => 'H3',
						'h4'  => 'H4',
						'h5'  => 'H5',
						'h6'  => 'H6'
					)
				),
				array(
					'name'	  => 'align',
					'label'   => 'Align',
					'type'	  => 'select',
					'options' => array(
						''  => 'Normal',
						'left'  => 'Left',
						'center'  => 'Center',
						'right'  => 'Right'
					)
				),
				array(
					'name'	=> 'class',
					'label' => 'Extra Class',
					'type'	=> 'text'
				),
				array(
					'name'	      => 'title_wrap',
					'label'       => 'Advanced',
					'type'	      => 'checkbox',
					'options'     => array(
						'yes'     => __('Yes, Please!', 'kingcomposer')
					),
					'description' => __('Add a &lt;div&gt; tag around the head tag, more code before or after the head tag.', 'kingcomposer')
				),
				array(
					'name'	      => 'before',
					'label'       => 'Before Head Tag',
					'type'	      => 'textarea',
					'description' => __('Add HTML text before the head tag.', 'kingcomposer'),
					'relation'      => array(
						'parent'    => 'title_wrap',
						'show_when' => 'yes'
					)
				),
				array(
					'name'	      => 'after',
					'label'       => 'After Head Tag',
					'type'	      => 'textarea',
					'description' => __('Add HTML text after the head tag.', 'kingcomposer'),
					'relation'      => array(
						'parent'    => 'title_wrap',
						'show_when' => 'yes'
					)
				),
				array(
					'name'	        => 'title_wrap_class',
					'label'         => 'Wrapper class name',
					'type'	        => 'text',
					'description'   => __('Enter class name for wrapper', 'kingcomposer'),
					'relation'      => array(
						'parent'    => 'title_wrap',
						'show_when' => 'yes'
					)
				)
			)
		),

		'kc_google_maps' => array(

			'name'			   => __('Google Maps', 'kingcomposer'),
			'description'	   => __('Show google maps with embed', 'kingcomposer'),
			'icon'			   => 'kc-icon-map',
			'category'		   => '',
			'admin_view'	   => 'gmaps',
			'live_editor' => $live_tmpl.'kc_google_maps.php',
			'params'		   => array(
				array(
					'name'        => 'random_id',
					'label'       => '',
					'type'        => 'random',
					'description' => '',
				),
				array(
					'type'			=>  'text',
					'label'			=> __( 'Map Title', 'kingcomposer' ),
					'name'			=> 'title',
					'description'	=> __( 'Title of the map. Leave blank if no title is needed', 'kingcomposer' ),
				),
				array(
					'type'			=> 'textarea',
					'label'			=> __( 'Map Location', 'kingcomposer' ),
					'name'			=> 'map_location',
					'value'			=> base64_encode( '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29793.99697352976!2d105.81945407598418!3d21.02269575409132!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab9bd9861ca1%3A0xe7887f7b72ca17a9!2zSGFub2ksIEhvw6BuIEtp4bq_bSwgSGFub2ksIFZpZXRuYW0!5e0!3m2!1sen!2s!4v1453961383169" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>'),
					'description'	=> __( 'Go to <a href="https://www.google.com/maps/" target=_blank>Google Maps</a> and searh your Location. Click on menu near search text => Share or embed map => Embed map. Next copy iframe to this field', 'kingcomposer' )
				),
				array(
					'type'			 => 'number_slider',
					'label'			 => __( 'Map Height', 'kingcomposer' ),
					'name'			 => 'map_height',
					'description'	 => __( 'Set height of the map. For example: 350 (px)', 'kingcomposer' ),
					'value'			 => '350',
					'options'        => array(
						'min'        => 50,
						'max'        => 1000,
						'unit'       => 'px',
						'show_input' => true
					)
				),
				array(
					'type'			     => 'checkbox',
					'label'			     => __( 'Show overlap contact form', 'kingcomposer' ),
					'name'			     => 'show_ocf',
					'description'	     => __( 'Enable a contact form above the maps', 'kingcomposer' ),
					'options'			 => array( 'yes' => __( 'Yes', 'kingcomposer' ) )
				),
				array(
					'type'			     => 'textarea',
					'label'			     => __( 'Contact form shortcode', 'kingcomposer' ),
					'name'			     => 'contact_form_sc',
					'description'	     => __( 'Shortcode content which generated by contact form 7. For example: [contact-form-7 id="4" title="Contact form 1"]', 'kingcomposer' ),
					'relation'		     => array(
						'parent'         => 'show_ocf',
						'show_when'      => 'yes'
					)
				),
				array(
					'type'			 => 'text',
					'label'			 => __( 'Contact area width', 'kingcomposer' ),
					'name'			 => 'contact_area_width',
					'description'	 => __( 'Width of wrapper form. Ex: 45% or 400px', 'kingcomposer' ),
					'relation'		 => array(
						'parent'     => 'show_ocf',
						'show_when'  => 'yes'
					),
					'value'			 => '45%'
				),
				array(
					'type'			 => 'select',
					'label'			 => __( 'Contact area position', 'kingcomposer' ),
					'name'			 => 'contact_area_position',
					'options'		 => array(
						'left'  => __( 'Left', 'kingcomposer' ),
						'right' => __( 'Right', 'kingcomposer' ),
					),
					'description'	=> __( 'Set position for the contact form box', 'kingcomposer' ),
					'relation'		=> array(
						'parent' => 'show_ocf',
						'show_when' => 'yes'
					),
					'value'			=> 'left'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Contact area background', 'kingcomposer' ),
					'name'			=> 'contact_area_bg',
					'description'	=> __( 'Set background color for the contact form', 'kingcomposer' ),
					'relation'		=> array(
						'parent' => 'show_ocf',
						'show_when' => 'yes'
					),
					'value' 		=> '#a1aee2'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Contact form color', 'kingcomposer' ),
					'name'			=> 'contact_form_color',
					'description'	=> __( 'Set color for text in contact form', 'kingcomposer' ),
					'relation'		=> array(
						'parent' => 'show_ocf',
						'show_when' => 'yes'
					),
					'value' 		=> '#3c3c3c'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Submit button color', 'kingcomposer' ),
					'name'			=> 'submit_button_color',
					'description'	=> __( '', 'kingcomposer' ),
					'relation'		=> array(
						'parent' => 'show_ocf',
						'show_when' => 'yes'
					),
					'value' 		=> '#393939'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Submit button hover color', 'kingcomposer' ),
					'name'			=> 'submit_button_hover_color',
					'description'	=> __( '', 'kingcomposer' ),
					'relation'		=> array(
						'parent' => 'show_ocf',
						'show_when' => 'yes'
					),
					'value' 		=> '#575757'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Submit button text color', 'kingcomposer' ),
					'name'			=> 'submit_button_text_color',
					'description'	=> __( '', 'kingcomposer' ),
					'relation'		=> array(
						'parent' => 'show_ocf',
						'show_when' => 'yes'
					),
					'value' 		=> '#FFFFFF'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Submit button text hover color', 'kingcomposer' ),
					'name'			=> 'submit_button_text_hover_color',
					'description'	=> __( '', 'kingcomposer' ),
					'relation'		=> array(
						'parent' => 'show_ocf',
						'show_when' => 'yes'
					),
					'value' 		=> '#FFFFFF'
				),
				array(
					'type'			=>  'text',
					'label'			=> __( 'Wrapper class name', 'kingcomposer' ),
					'name'			=> 'wrap_class',
					'description'	=> __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' ),
				),
			)
		),

		'kc_twitter_feed' => array(
			'name'			=> __('Twitter Feed', 'kingcomposer'),
			'description'	=> __('New tweets from twitter', 'kingcomposer'),
			'icon' => 'kc-icon-twitter',
			'category' => '',
			'css_box' => true,
			'params' => array(
				array(
					'type'			=>  'text',
					'label'			=> __( 'Twitter Title', 'kingcomposer' ),
					'name'			=> 'title',
					'description'	=> __( 'Title of Twitter widget. Leave blank if no title is needed.', 'kingcomposer' ),
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Username', 'kingcomposer' ),
					'name'			=> 'username',
					'value'			=> 'kingcomposer',
					'admin_label'	=> true
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Display Style', 'kingcomposer' ),
					'name'			=> 'display_style',
					'options'			=> array(
						'1' => __( 'List View', 'kingcomposer' ),
						'2' => __( 'Slider tweets', 'kingcomposer' ),
					),
					'admin_label'	=> true
				),
				array(
					'type'			=> 'number_slider',
					'label'			=> __( 'Twitter box height', 'kingcomposer' ),
					'name'			=> 'max_height',
					'description'	=> __( 'Set the height of the Twitter feed box, from 100px to 800px. Scroll bar will appear if the Twitter feed box height is bigger than the one you set. Enter “0” to show all tweets you set without scroll bar. Min:100px, max: 800px', 'kingcomposer' ),
					'value'			=> '350',
					'options'		=> array(
						'min' => 100,
						'max' => 800,
						'unit' => 'px',
						'show_input' => true
					),
					'relation' 		=> array(
						'parent'	=> 'display_style',
						'show_when' => '1'
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show navigation', 'kingcomposer' ),
					'name'			=> 'show_navigation',
					'options'		=> array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) ),
					'value'			=> 'yes',
					'relation'		=> array(
						'parent'	=> 'display_style',
						'show_when'	=> '2'
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show pagination', 'kingcomposer' ),
					'name'			=> 'show_pagination',
					'options'		=> array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) ),
					'value'			=> 'yes',
					'relation'		=> array(
						'parent'	=> 'display_style',
						'show_when'	=> '2'
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Auto height', 'kingcomposer' ),
					'name'			=> 'auto_height',
					'options'		=> array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) ),
					'relation'		=> array(
						'parent'	=> 'display_style',
						'show_when'	=> '2'
					)
				),
				array(
					'type'			=> 'number_slider',
					'label'			=> __( 'Number of tweets', 'kingcomposer' ),
					'name'			=> 'number_tweet',
					'admin_label'	=> true,
					'value'			=> '5',
					'options' 		=> array(
						'min' => 1,
						'max' => 20
					)
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Links color', 'kingcomposer' ),
					'name'			=> 'link_color',
					'description'	=> __( 'Color for the links on box.', 'kingcomposer' ),
					'value'			=> '#f7d377'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Links color hover', 'kingcomposer' ),
					'name'			=> 'link_color_hover',
					'description'	=> __( 'Hover color for links on box.', 'kingcomposer' ),
					'value'			=> '#454545'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show Time', 'kingcomposer' ),
					'name'			=> 'show_time',
					'description'	=> __( 'Show how long it was since a tweet was posted. For example: "30m ago"', 'kingcomposer' ),
					'options'		=> array( 'yes' => __( 'Yes', 'kingcomposer' ) ),
					'value'			=> 'yes'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show reply link', 'kingcomposer' ),
					'name'			=> 'show_reply',
					'description'	=> __( 'Show Reply link to each tweet.', 'kingcomposer' ),
					'options'		=> array( 'yes' => __( 'Yes', 'kingcomposer' ) ),
					'value'			=> 'yes'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Allow Retweet', 'kingcomposer' ),
					'name'			=> 'show_retweet',
					'description'	=> __( 'Show Retweet link to each tweet.', 'kingcomposer' ),
					'options'		=> array( 'yes' => __( 'Yes', 'kingcomposer' ) ),
					'value'			=> 'yes'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show Twitter avatar', 'kingcomposer' ),
					'name'			=> 'show_avatar',
					'description'	=> __( 'Show avatar of Twitter account beside each tweet.', 'kingcomposer' ),
					'options'		=> array( 'yes' => __( 'Yes', 'kingcomposer' ) ),
					'relation' 		=> array(
						'parent'	=> 'display_style',
						'show_when' => '1'
					),
					'value'			=> 'yes'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show Follow button', 'kingcomposer' ),
					'name'			=> 'show_follow_button',
					'options'		=> array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) ),
					'value'			=> 'yes'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Use Own API Key', 'kingcomposer' ),
					'name'			=> 'use_api_key',
					'options'		=> array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) )
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Consumer Key (API Key)', 'kingcomposer' ),
					'name'			=> 'consumer_key',
					'value'			=> '',
					'relation'		=> array(
						'parent' => 'use_api_key',
						'show_when' => 'yes'
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Consumer Secret (API Secret)', 'kingcomposer' ),
					'name'			=> 'consumer_secret',
					'value'			=> '',
					'relation'		=> array(
						'parent' => 'use_api_key',
						'show_when' => 'yes'
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Access Token', 'kingcomposer' ),
					'name'			=> 'access_token',
					'value'			=> '',
					'relation'		=> array(
						'parent' => 'use_api_key',
						'show_when' => 'yes'
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Access Token Secret', 'kingcomposer' ),
					'name'			=> 'access_token_secrect',
					'value'			=> '',
					'relation'		=> array(
						'parent' => 'use_api_key',
						'show_when' => 'yes'
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Wrapper class name', 'kingcomposer' ),
					'name'			=> 'wrap_class',
					'description'	=> __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' ),
					'value'			=> ''
				)
			)
		),

		/* Just for test icon
		---------------------------------------------------------- */

		'kc_instagram_feed' => array(

			'name' => __('Instagram Feed', 'kingcomposer'),
			'description' => __('', 'kingcomposer'),
			'icon' => 'kc-icon-instagram',
			'category' => '',
			'css_box' => true,
			'params' => array(
				array(
					'type'			=> 'text',
					'label'			=> __( 'Title', 'kingcomposer' ),
					'name'			=> 'title',
					'description'	=> __( 'Title of Instagaram feed. Leave blank if no title is needed.', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type'			=> 'number_slider',
					'label'			=> __( 'Number of photos', 'kingcomposer' ),
					'name'			=> 'number_show',
					'description'	=> __( 'Set the number of photos displayed.', 'kingcomposer' ),
					'value'			=> '8',
					'options' => array(
						'min' => 1,
						'max' => 16
					),
					'admin_label'	=> true,
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Number of Columns', 'kingcomposer' ),
					'name'			=> 'columns_style',
					'options'			=> array(
						'1' => __( '1 Columns', 'kingcomposer' ),
						'2' => __( '2 Columns', 'kingcomposer' ),
						'3' => __( '3 Columns', 'kingcomposer' ),
						'4' => __( '4 Columns', 'kingcomposer' ),
						'5' => __( '5 Columns', 'kingcomposer' ),
						'6' => __( '6 Columns', 'kingcomposer' )
					),
					'description'	=> __( 'Set the photo columns.', 'kingcomposer' ),
					'value'			=> '4'
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Image Size', 'kingcomposer' ),
					'name'			=> 'image_size',
					'description'	=> __( '', 'kingcomposer' ),
					'options'		=> array(
						'low_resolution' => 'Low resolution',
						'thumbnail' => 'Thumbnail',
						'standard_resolution' => 'Standard resolution',
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Username', 'kingcomposer' ),
					'name'			=> 'username',
					'description'	=> __( 'The Instagaram username.', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Access token', 'kingcomposer' ),
					'name'			=> 'access_token',
					'description'	=> __( 'You can get the Access token at http://instagram.pixelunion.net/', 'kingcomposer' ),
					'value'			=> ''
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Custom class name', 'kingcomposer' ),
					'name'			=> 'wrap_class',
					'description'	=> __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' ),
				)
			)

		),

		'kc_fb_recent_post' => array(

			'name' => __('FaceBook Post', 'kingcomposer'),
			'description' => __('', 'kingcomposer'),
			'icon' => 'kc-icon-facebook',
			'category' => '',
			//'css_box' => true,
			'params' => array(
				array(
					'type'			=> 'text',
					'label'			=> __( 'Title', 'kingcomposer' ),
					'name'			=> 'title',
					'description'	=> __( 'Title of Facebook feed. Leave blank if no title is needed.', 'kingcomposer' ),
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Facebook Page slug', 'kingcomposer' ),
					'name'			=> 'fb_page_id',
					'description'	=> __( 'Facebook page ID or slug. For example: wordpress', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Facebook App ID', 'kingcomposer' ),
					'name'			=> 'fb_app_id',
					'description'	=> __( 'Get your App ID at https://developers.facebook.com/apps', 'kingcomposer' ),
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Facebook App Secret', 'kingcomposer' ),
					'name'			=> 'fb_app_secret',
					'description'	=> __( 'Get your App Secret from https://developers.facebook.com/apps', 'kingcomposer' ),
				),
				array(
					'type'			=> 'number_slider',
					'label'			=> __( 'Facebook feed height', 'kingcomposer' ),
					'name'			=> 'max_height',
					'description'	=> __( 'Set the height of Facebook feed box, from 50px to 1000px. Scroll bar will appear if the Facebook feed box height is bigger than the one you set. Unit (px)', 'kingcomposer' ),
					'value'			=> '350',
					'options' => array(
						'min' => 50,
						'max' => 1000,
						'unit' => 'px',
						'show_input' => true
					)
				),
				array(
					'type'			=> 'number_slider',
					'label'			=> __( 'Number of posts', 'kingcomposer' ),
					'name'			=> 'number_post_show',
					'description'	=> __( 'The number of posts displayed', 'kingcomposer' ),
					'value'			=> '5',
					'admin_label'	=> true,
					'options' => array(
						'min' => 1,
						'max' => 10
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Number of words per post', 'kingcomposer' ),
					'name'			=> 'number_of_des',
					'description'	=> __( 'The number of words in each facebook post, for example: 25. Leave this field empty to show the full post. Ex 25', 'kingcomposer' ),
					'value'			=> '25'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show Image?', 'kingcomposer' ),
					'name'			=> 'show_image',
					'description'	=> __( 'Show featured image of the Facebook post.', 'kingcomposer' ),
					'options'		=> array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) ),
					'value'			=> 'yes'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show Like Count?', 'kingcomposer' ),
					'name'			=> 'show_like_count',
					'description'	=> __( 'Show the Like count link in the Facebook post.', 'kingcomposer' ),
					'options'		=> array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) ),
					'value'			=> 'yes'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show Comment Count?', 'kingcomposer' ),
					'name'			=> 'show_comment_count',
					'description'	=> __( 'Show Comment count link in the Facebook post.', 'kingcomposer' ),
					'options'		=> array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) ),
					'value'			=> 'yes'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show Time', 'kingcomposer' ),
					'name'			=> 'show_time',
					'description'	=> __( 'Show how long it was since a post was published. For example: 4 days ago.', 'kingcomposer' ),
					'options'		=> array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) ),
					'value'			=> 'yes'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Open URL in a new tab', 'kingcomposer' ),
					'name'			=> 'open_link_new_window',
					'description'	=> __( 'All Facebook URLs will open in a new tab.', 'kingcomposer' ),
					'options'		=> array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) ),
					'value'			=> 'yes'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show profile button', 'kingcomposer' ),
					'name'			=> 'show_profile_button',
					'description'	=> __( 'Show the profile button underneath the Facebook post box.', 'kingcomposer' ),
					'options'		=> array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) ),
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Custom class name', 'kingcomposer' ),
					'name'			=> 'wrap_class',
					'description'	=> __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' ),
				)
			)

		),

		'kc_flip_box' => array(
			'name' => __('Flip Box', 'kingcomposer'),
			'description' => __('', 'kingcomposer'),
			'icon' => 'kc-icon-flip',
			'category' => '',
			'css_box' => true,
			'live_editor' => $live_tmpl.'kc_flip_box.php',
			'params' => array(

				array(
					'type'			=> 'attach_image',
					'label'			=> __( 'Front Image', 'kingcomposer' ),
					'name'			=> 'image',
					'description'	=> __( 'Upload image for the front side.', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Image size', 'kingcomposer' ),
					'name'			=> 'image_size',
					'description'	=> __( 'Set the image size (For example: thumbnail, medium, large or full).', 'kingcomposer' ),
					'admin_label'	=> true,
					'value'			=> 'full'
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Title', 'kingcomposer' ),
					'name'			=> 'title',
					'description'	=> __( 'Title of the FlipBox. Leave blank if no title is needed.', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type'			=> 'textarea',
					'label'			=> __( 'Description', 'kingcomposer' ),
					'name'			=> 'description',
					'description'	=> __( 'Enter description for the back side, Shortcode are supported in this field.', 'kingcomposer' )
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Direction', 'kingcomposer' ),
					'name'			=> 'direction',
					'options'		=> array(
						'horizontal' => __( 'Horizontal', 'kingcomposer' ),
						'vertical' => __( 'Vertical', 'kingcomposer' ),
					),
					'description'	=> __( 'Direction of flip', 'kingcomposer' ),
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Text align', 'kingcomposer' ),
					'name'			=> 'text_align',
					'options'		=> array(
						'center' => __( 'Center', 'kingcomposer' ),
						'left' => __( 'Left', 'kingcomposer' ),
						'right' => __( 'Right', 'kingcomposer' ),
						'justtify' => __( 'Justtify', 'kingcomposer' ),
					),
					'description'	=> __( 'Set the text align: Center, left, right or justtify', 'kingcomposer' ),
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Text Color', 'kingcomposer' ),
					'name'			=> 'text_color',
					'description'	=> __( 'Color of the text.', 'kingcomposer' ),
					'value'			=> '#FFFFFF'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Background Back side', 'kingcomposer' ),
					'name'			=> 'bg_backside',
					'description'	=> __( 'Background color of the back side.', 'kingcomposer' ),
					'value'			=> '#393939'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show button', 'kingcomposer' ),
					'name'			=> 'show_button',
					'description'	=> __( 'Show the button in the back side.', 'kingcomposer' ),
					'options'			=> array( 'yes' => __( 'Yes, Please', 'kingcomposer' ) ),
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Text on button', 'kingcomposer' ),
					'name'			=> 'text_on_button',
					'description'	=> __( 'Set the text displayed on the button.', 'kingcomposer' ),
					'relation'	=> array(
						'parent' => 'show_button',
						'show_when' => 'yes'
					),
					'value'			=> 'Read more'
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'URL', 'kingcomposer' ),
					'name'			=> 'link',
					'description'	=> __( 'URL of the button in the back side.', 'kingcomposer' ),
					'value'			=> '#',
					'relation'	=> array(
						'parent' => 'show_button',
						'show_when' => 'yes'
					)
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Button text color', 'kingcomposer' ),
					'name'			=> 'text_button_color',
					'description'	=> __( 'Color of the button text.', 'kingcomposer' ),
					'relation'	=> array(
						'parent' => 'show_button',
						'show_when' => 'yes'
					),
					'value'			=> '#FFFFFF'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Button background color', 'kingcomposer' ),
					'name'			=> 'button_bg_color',
					'description'	=> __( 'Background color of the button. (Default values is: transparent)', 'kingcomposer' ),
					'relation'	=> array(
						'parent' => 'show_button',
						'show_when' => 'yes'
					),
					'value'			=> 'transparent'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Button background color on hover', 'kingcomposer' ),
					'name'			=> 'button_bg_hover_color',
					'description'	=> __( 'Button\'s background color that changes when the mouse cursor hovers over it.', 'kingcomposer' ),
					'relation'	=> array(
						'parent' => 'show_button',
						'show_when' => 'yes'
					),
					'value'			=> '#FFFFFF'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Button text color on hover', 'kingcomposer' ),
					'name'			=> 'text_button_color_hover',
					'description'	=> __( 'Button text color that changes when the mouse cursor hovers over it.', 'kingcomposer' ),
					'relation'	=> array(
						'parent' => 'show_button',
						'show_when' => 'yes'
					),
					'value'			=> '#86c724'
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Wrapper class name', 'kingcomposer' ),
					'name'			=> 'wrap_class',
					'description'	=> __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' ),
					'value'			=> ''
				)
			)

		),

		'kc_pie_chart' => array(
			'name' => __('Pie Chart', 'kingcomposer'),
			'description' => __('', 'kingcomposer'),
			'icon' => 'kc-icon-pie',
			'category' => '',
			'css_box' => true,
			'live_editor' => $live_tmpl.'kc_pie_chart.php',
			'params'		=> array(

				array(
					'type'			=> 'number_slider',
					'label'			=> __( 'Percent number', 'kingcomposer' ),
					'name'			=> 'percent',
					'description'	=> __( 'Drag slider to select the percentage number displayed.', 'kingcomposer' ),
					'admin_label'	=> true,
					'value' 		=> '50',
					'options'		=> array(
						'unit'		=> '%',
						'show_input'=> true
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Rounded corners', 'kingcomposer' ),
					'name'			=> 'rounded_corners_bar',
					'description'	=> __( 'Have the percentage bar withrounded edges.', 'kingcomposer' ),
					'options'		=> array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) ),
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Pie Size', 'kingcomposer' ),
					'name'			=> 'size',
					'description'	=> __( 'Select the pie size.', 'kingcomposer' ),
					'options'			=> array(
						'100' 		=> __('100 px', 'kingcomposer'),
						'150' 		=> __('150 px', 'kingcomposer'),
						'200' 		=> __('200 px', 'kingcomposer'),
						'custom' 	=> __('Custom', 'kingcomposer'),
						'auto'		=> __( 'Auto width', 'kingcomposer' )
					)
				),
				array(
					'type'			=> 'number_slider',
					'label'			=> __( 'Custom size', 'kingcomposer' ),
					'name'			=> 'custom_size',
					'description'	=> __( 'It is width and height of pie chart, unit (px).', 'kingcomposer' ),
					'admin_label'	=> true,
					'relation' 	=> array(
						'parent' 	=> 'size',
						'show_when' => 'custom'
					),
					'options'		=> array(
						'show_input'=> true,
						'min'	=> 50,
						'max'	=> 500
					),
					'value'			=> '120'
				),
				array(
					'type'			=> 'number_slider',
					'label'			=> __( 'Line Width', 'kingcomposer' ),
					'name'			=> 'linewidth',
					'description'	=> __( 'Drag slider to change the Width of the line in px.', 'kingcomposer' ),
					'admin_label'	=> true,
					'value' 		=> '10',
					'options'		=> array(
						'show_input'=> false,
						'min'	=> 1,
						'max'	=> 30
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Title', 'kingcomposer' ),
					'name'			=> 'title',
					'description'	=> __( 'Title of the pie.', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type'			=> 'textarea',
					'label'			=> __( 'Description', 'kingcomposer' ),
					'name'			=> 'description',
					'description'	=> __( 'The text that describes the pie in detail.', 'kingcomposer' ),
				),

				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Bar Color', 'kingcomposer' ),
					'name'			=> 'barcolor',
					'description'	=> __( 'Color of the percentage bar.', 'kingcomposer' ),
					'value'			=> '#39c14f'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Track Color', 'kingcomposer' ),
					'name'			=> 'trackcolor',
					'description'	=> __( 'Color of the track pie.', 'kingcomposer' ),
					'value'			=> '#e4e4e4'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Label color', 'kingcomposer' ),
					'name'			=> 'label_color',
					'description'	=> __( 'Color of the percentage bar number.', 'kingcomposer' ),
					'value'			=> '#FF5252'
				),
				array(
					'type'			=> 'number_slider',
					'label'			=> __( 'Label front size', 'kingcomposer' ),
					'name'			=> 'label_font_size',
					'description'	=> __( 'Front size of the percentage number. The default value is: 20px', 'kingcomposer' ),
					'value'			=> '20',
					'options'		=> array(
						'unit'		=> 'px',
						'show_input'=> true
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Wrapper class name', 'kingcomposer' ),
					'name'			=> 'wrap_class',
					'description'	=> __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' ),
				)
			)

		),
		'kc_progress_bars' => array(

			'name' => __('Progress Bar', 'kingcomposer'),
			'description' => __('', 'kingcomposer'),
			'icon' => 'kc-icon-progress',
			'category' => '',
			'css_box' => true,
			'live_editor' => $live_tmpl.'kc_progress_bars.php',
			'params' => array(

				array(
					'type'			=> 'text',
					'label'			=> __( 'Title', 'kingcomposer' ),
					'name'			=> 'title',
					'description'	=> __( 'Title of the progress bar. Leave blank if no title is needed.', 'kingcomposer' ),
					'admin_label'	=> true,
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Style', 'kingcomposer' ),
					'name'			=> 'style',
					'description'	=> __( 'Select the style of progress bars.', 'kingcomposer' ),
					'options'		=> array(
						'1' => __('Style 1', 'kingcomposer'),
						'2' => __('Style 2 (Value in tooltip)', 'kingcomposer'),
						'3' => __('Style 3 (Value in progress bar)', 'kingcomposer')
					),
					'admin_label'	=> true,
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Border radius', 'kingcomposer' ),
					'name'			=> 'radius',
					'description'	=> __( 'Set Border radius for bars', 'kingcomposer' ),
					'options'			=> array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) ),
					'value' 			=> 'no'
				),
				array(
					'type'			=> 'number_slider',
					'label'			=> __( 'Processbar Weight', 'kingcomposer' ),
					'name'			=> 'weight',
					'description'	=> __( 'Height weight of progress bar: Ex 12, unit (px)', 'kingcomposer' ),
					'value'			=> '12',
					'options' 		=> array(
						'min'		=> 2,
						'max'		=> 40,
						'show_input'=> true,
					)
				),
				array(
					'type'			=> 'number_slider',
					'label'			=> __( 'Spacing', 'kingcomposer' ),
					'name'			=> 'margin',
					'description'	=> __( 'The spacing between items', 'kingcomposer' ),
					'value'			=> '20',
					'options' 		=> array(
						'min'		=> 0,
						'max'		=> 100,
						'show_input'=> true,
					),
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Animate Speed', 'kingcomposer' ),
					'name'			=> 'speed',
					'description'	=> __( 'Select speed for animation.', 'kingcomposer' ),
					'options'		=> array(
						'2000' => __('Normal', 'kingcomposer'),
						'1600' => __('Fast', 'kingcomposer'),
						'1200' => __('Very Fast', 'kingcomposer'),
						'2400' => __('Slow', 'kingcomposer'),
						'2800' => __('Very Slow', 'kingcomposer'),
					),
					'value'			=> '2000',
					'admin_label'	=> true,
				),
				array(
					'type'			=> 'group',
					'label'			=> __('Options', 'kingcomposer'),
					'name'			=> 'options',
					'description'	=> __( 'Repeat this fields with each item created, Each item corresponding processbar element.', 'kingcomposer' ),
					'value' => base64_encode( json_encode(array(
						"1" => array(
							"value" => "90",
							"value_color" => "#999999",
							"label" => "Development",
							"label_color" => "#0d6347",
							"prob_color" => "#1a6c9c",
							"prob_bg_color" => "#dbdbdb"
						),
						"2" => array(
							"value" => "80",
							"value_color" => "#626a0d",
							"label" => "Design",
							"label_color" => "#0d6347",
							"prob_color" => "#1a6c9c",
							"prob_bg_color" => "#dbdbdb"
						),
						"3" => array(
							"value" => "70",
							"value_color" => "#6b1807",
							"label" => "Marketing",
							"label_color" => "#0d6347",
							"prob_color" => "#1a6c9c",
							"prob_bg_color" => "#dbdbdb"
						)
					) ) ),
					'params' => array(
						array(
							'type' => 'number_slider',
							'label' => __( 'Value', 'kingcomposer' ),
							'name' => 'value',
							'description' => __( 'Enter targeted value of the bar (From 1 to 100).', 'kingcomposer' ),
							'admin_label' => true,
							'options' 		=> array(
								'min'		=> 1,
								'max'		=> 100,
							),
							'value' => '80'
						),
						array(
							'type' => 'color_picker',
							'label' => __( 'Value Color', 'kingcomposer' ),
							'name' => 'value_color',
							'description' => __( 'Color of targeted value text.', 'kingcomposer' ),
						),
						array(
							'type' => 'text',
							'label' => __( 'Label', 'kingcomposer' ),
							'name' => 'label',
							'description' => __( 'Enter text used as title of the bar.', 'kingcomposer' ),
							'admin_label' => true,
						),
						array(
							'type' => 'color_picker',
							'label' => __( 'Label Color', 'kingcomposer' ),
							'name' => 'label_color',
							'description' => __( 'Label text color', 'kingcomposer' ),
						),
						array(
							'type' => 'color_picker',
							'label' => __( 'Progressbar Color', 'kingcomposer' ),
							'name' => 'prob_color',
							'description' => __( 'Customized progress bar color.', 'kingcomposer' ),
						),
						array(
							'type' => 'color_picker',
							'label' => __( 'Progressbar background color', 'kingcomposer' ),
							'name' => 'prob_bg_color',
							'description' => __( 'Customized progress background bar color.', 'kingcomposer' ),
						),
					),
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Wrapper class name', 'kingcomposer' ),
					'name'			=> 'wrap_class',
					'description'	=> __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' ),
				)
			)

		),

		'kc_button' => array(

			'name' => __('Button', 'kingcomposer'),
			'description' => __('', 'kingcomposer'),
			'icon' => 'kc-icon-button',
			'category' => '',
			'css_box' => true,
			'live_editor' => $live_tmpl.'kc_button.php',
			'params' => array(

				array(
					'type'			=> 'text',
					'label'			=> __( 'Title', 'kingcomposer' ),
					'name'			=> 'text_title',
					'description'	=> __( 'Add the text that appears on the button.', 'kingcomposer' ),
					'value' 			=> 'Text title',
					'admin_label'	=> true
				),
				array(
					'type'			=> 'link',
					'label'			=> __( 'Link', 'kingcomposer' ),
					'name'			=> 'link',
					'description'	=> __( 'Add your relative URL. Each URL contains link, anchor text and target attributes.', 'kingcomposer' ),
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Button size', 'kingcomposer' ),
					'name'			=> 'size',
					'description'	=> __( 'Set the size of the button.', 'kingcomposer' ),
					'options'		=> array(
						'small' => __('Small', 'kingcomposer'),
						'normal' => __('Normal', 'kingcomposer'),
						'large' => __('Large', 'kingcomposer'),
						'custom' => __('Custom padding, front-size', 'kingcomposer'),
					),
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Padding', 'kingcomposer' ),
					'name'			=> 'padding_button',
					'description'	=> __( 'The CSS padding properties are used to generate space around content, (top, right, bottom, and left) . For example: "10px 25px" or "10px 25px 10px 25px"', 'kingcomposer' ),
					'relation'	=> array(
						'parent'	=> 'size',
						'show_when'	=> 'custom'
					),
					'value'			=> '10px 25px 10px 25px'
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Front size', 'kingcomposer' ),
					'name'			=> 'font_size_button',
					'description'	=> __( 'Font size for text in the button. For example: 14px', 'kingcomposer' ),
					'relation'	=> array(
						'parent'	=> 'size',
						'show_when'	=> 'custom'
					),
					'value'			=> '14px'
				),
				array(
					'type' 			=> 'checkbox',
					'label'			=> __( 'Custom style', 'kingcomposer' ),
					'name'			=> 'custom_style',
					'description'	=> __('Show all related parameters.', 'kingcomposer'),
					'options'			=> array( 'yes' => __( 'Yes, Please!', 'kingcomposer' ) )
				),
				array(
					'type'			=> 'number_slider',
					'label'			=> __( 'Border radius', 'kingcomposer' ),
					'name'			=> 'border_radius',
					'description'	=> __( 'Adjust the rounded corners of the button.', 'kingcomposer' ),
					'value'			=> 3,
					'options' => array(
						'min' => 0,
						'max' => 20,
						'unit' => 'px',
						'show_input' => true,
					),
					'relation'	=> array(
						'parent'	=> 'custom_style',
						'show_when'	=> 'yes'
					),
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Background color', 'kingcomposer' ),
					'name'			=> 'bg_color',
					'description'	=> __( 'Background color that changes when the mouse cursor hovers over the button.', 'kingcomposer' ),
					'relation'	=> array(
						'parent'	=> 'custom_style',
						'show_when'	=> 'yes'
					),
					'value'			=> '#393939'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Background color hover', 'kingcomposer' ),
					'name'			=> 'bg_color_hover',
					'description'	=> __( 'Button background color hover', 'kingcomposer' ),
					'relation'	=> array(
						'parent'	=> 'custom_style',
						'show_when'	=> 'yes'
					),
					'value'			=> '#FFFFFF'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Text color', 'kingcomposer' ),
					'name'			=> 'text_color',
					'description'	=> __( 'Set color of the button text.', 'kingcomposer' ),
					'relation'	=> array(
						'parent'	=> 'custom_style',
						'show_when'	=> 'yes'
					),
					'value'			=> '#FFFFFF'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Text color hover', 'kingcomposer' ),
					'name'			=> 'text_color_hover',
					'description'	=> __( 'Set color of the button when mouse cursor hovers over it.', 'kingcomposer' ),
					'relation'	=> array(
						'parent'	=> 'custom_style',
						'show_when'	=> 'yes'
					),
					'value'			=> '#393939'
				),
				array(
					'type' 			=> 'checkbox',
					'name' 			=> 'show_icon',
					'label' 		=> __( 'Show Icon?', 'kingcomposer' ),
					'description' 	=> '',
					'options' 		=> array(
						'yes' => __('Yes, Please', 'kingcomposer'),
					)
				),
				array(
					'type' 			=> 'icon_picker',
					'name'		 	=> 'icon',
					'label' 		=> __( 'Icon', 'kingcomposer' ),
					'admin_label' 	=> true,
					'description' 	=> __( 'Select icon for button', 'kingcomposer' ),
					'relation'		=> array(
						'parent'	=> 'show_icon',
						'show_when'	=> 'yes'
					)
				),
				array(
					'type' => 'dropdown',
					'name' => 'icon_position',
					'label' => __( 'Icon position', 'kingcomposer' ),
					'description' => '',
					'options' => array(
						'left' => __('Left', 'kingcomposer'),
						'center' => __('Center without text', 'kingcomposer'),
						'right' => __('Right', 'kingcomposer'),
					),
					'relation'		=> array(
						'parent'	=> 'show_icon',
						'show_when'	=> 'yes'
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Wrapper class name', 'kingcomposer' ),
					'name'			=> 'wrap_class',
					'description'	=> __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' ),
				),
			)
		),

		'kc_video_play' => array(

			'name' => __('Video Player', 'kingcomposer'),
			'description' => __('', 'kingcomposer'),
			'icon' => 'kc-icon-play',
			'category' => '',
			'css_box' => true,
			'live_editor' => $live_tmpl.'kc_video_play.php',
			'params' => array(

				array(
					'type'			=> 'text',
					'label'			=> __( 'Video link', 'kingcomposer' ),
					'name'			=> 'video_link',
					'description'	=> __( 'Enter the Youtube or Vimeo URL. For example: https://www.youtube.com/watch?v=iNJdPyoqt8U', 'kingcomposer' ),
					'admin_label'	=> true,
					'value'			=> 'https://www.youtube.com/watch?v=iNJdPyoqt8U'
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Title', 'kingcomposer' ),
					'name'			=> 'title',
					'description'	=> __( 'Enter title for this video. Leave blank if no title is needed.', 'kingcomposer' ),
				),
				array(
					'type'			=> 'textarea',
					'label'			=> __( 'Description', 'kingcomposer' ),
					'name'			=> 'description',
					'description'	=> __( 'The text description for your video.', 'kingcomposer' ),
				),
				array(
					'type' 			=> 'checkbox',
					'name' 	=> 'full_width',
					'label' 		=> __( 'Video Fullwidth', 'kingcomposer' ),
					'description' 	=> __('Stretch the video to fit the content width.', 'kingcomposer'),
					'options' 		=> array(
						'yes' => __('Yes, Please!', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Video Width', 'kingcomposer' ),
					'name'			=> 'video_width',
					'description'	=> __( 'Set the width of the video.', 'kingcomposer' ),
					'value'			=> 600,
					'relation'		=> array(
						'parent'	=> 'full_width',
						'hide_when' => 'yes'
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Video Height', 'kingcomposer' ),
					'name'			=> 'video_height',
					'description'	=> __( 'Set the height of the video.', 'kingcomposer' ),
					'value'			=> 250,
				),
				array(
					'type' 			=> 'checkbox',
					'name' 	=> 'auto_play',
					'label' 		=> __( 'Auto Play', 'kingcomposer' ),
					'description' 	=> __('The video automatically plays when site loaded.', 'kingcomposer'),
					'options' 		=> array(
						'yes' => __('Yes, Please!', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Wrapper class name', 'kingcomposer' ),
					'name'			=> 'wrap_class',
					'description'	=> __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' ),
				),
			)

		),
		'kc_counter_box' => array(

			'name' => __('Counter Box', 'kingcomposer'),
			'description' => __('', 'kingcomposer'),
			'icon' => 'kc-icon-counter',
			'category' => '',
			'live_editor' => $live_tmpl.'kc_counter_box.php',
			'params'		=> array(
				array(
					'type'			=> 'text',
					'label'			=> __( 'Targeted number', 'kingcomposer' ),
					'name'			=> 'number',
					'description'	=> __( 'The targeted number to count up to (From zero).', 'kingcomposer' ),
					'admin_label'	=> true,
					'value'			=> '100'
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Label', 'kingcomposer' ),
					'name'			=> 'label',
					'description'	=> __( 'The text description of the counter.', 'kingcomposer' ),
					'admin_label'	=> true,
					'value'			=> 'Percent number'
				),
				array(
					'type' 			=> 'checkbox',
					'name' 			=> 'label_above',
					'label' 		=> __( 'Label above number', 'kingcomposer' ),
					'description' 	=> __('Place the label above the number counting. By default, it is placed underneath the number counting.', 'kingcomposer'),
					'options' 		=> array(
						'yes' => __('Yes, Please!', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Style', 'kingcomposer' ),
					'name'			=> 'style',
					'description'	=> __( 'Select the style to display the counter box', 'kingcomposer' ),
					'options' => array(
						'1' => __( 'Style 1 (No icon)', 'kingcomposer' ),
						'2' => __( 'Style 2 (Box and icon)', 'kingcomposer' ),
						'3' => __( 'Style 3 (Icon & title)', 'kingcomposer' )
					),
				),
				array(
					'type'			=> 'icon_picker',
					'label'			=> __( 'Icon', 'kingcomposer' ),
					'name'			=> 'icon',
					'description'	=> __( 'Icon in counter box', 'kingcomposer' ),
					'relation'		=> array(
						'parent'	=> 'style',
						'show_when'	=> array( '2', '3' )
					)
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Number color', 'kingcomposer' ),
					'name'			=> 'number_color',
					'description'	=> __( '', 'kingcomposer' ),
					'value'			=> '#393939'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Label color', 'kingcomposer' ),
					'name'			=> 'label_color',
					'description'	=> __( '', 'kingcomposer' ),
					'value'			=> '#393939'
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Icon color', 'kingcomposer' ),
					'name'			=> 'icon_color',
					'description'	=> __( '', 'kingcomposer' ),
					'value'			=> '#393939',
					'relation'		=> array(
						'parent'	=> 'style',
						'show_when'	=> array( '2', '3')
					)
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Box background color', 'kingcomposer' ),
					'name'			=> 'box_bg_color',
					'description'	=> __( '', 'kingcomposer' ),
					'value'			=> '#d9d9d9',
					'relation'		=> array(
						'parent'	=> 'style',
						'show_when' => '2'
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Wrapper class name', 'kingcomposer' ),
					'name'			=> 'wrap_class',
					'description'	=> __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' ),
				),
			)

		),

		'kc_post_type_list' => array(

			'name' => __('Post Type List', 'kingcomposer'),
			'description' => __('', 'kingcomposer'),
			'icon' => 'kc-icon-post',
			'category' => '',
			'params'		=> array(

				array(
					'type'			=> 'text',
					'label'			=> __( 'Title', 'kingcomposer' ),
					'name'			=> 'title',
					'description'	=> __( 'The title of the Post Type List. Leave blank if no title is needed.', 'kingcomposer' ),
					'value'			=> __( 'Recent post title', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type'			=> 'number_slider',
					'label'			=> __( 'Number of posts displayed', 'kingcomposer' ),
					'name'			=> 'number_post',
					'description'	=> __( 'The number of posts you want to show.', 'kingcomposer' ),
					'value'			=> '5',
					'admin_label'	=> true,
					'options' => array(
						'min' => 1,
						'max' => 12
					)
				),
				array(
					'type'			=> 'post_taxonomy',
					'label'			=> __( 'Content Type', 'kingcomposer' ),
					'name'			=> 'post_taxonomy',
					'description'	=> __( '', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Order by', 'kingcomposer' ),
					'name'			=> 'order_by',
					'description'	=> __( '', 'kingcomposer' ),
					'admin_label'	=> true,
					'options' 		=> array(
						'ID'		=> __('Post ID', 'kingcomposer'),
						'author'	=> __('Author', 'kingcomposer'),
						'title'		=> __('Title', 'kingcomposer'),
						'name'		=> __('Post name (post slug)', 'kingcomposer'),
						'type'		=> __('Post type (available since Version 4.0)', 'kingcomposer'),
						'date'		=> __('Date', 'kingcomposer'),
						'modified'	=> __('Last modified date', 'kingcomposer'),
						'rand'		=> __('Random order', 'kingcomposer'),
						'comment_count'	=> __('Number of comments', 'kingcomposer')
					)
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Order post', 'kingcomposer' ),
					'name'			=> 'order_list',
					'description'	=> __( '', 'kingcomposer' ),
					'admin_label'	=> true,
					'options' 		=> array(
						'ASC'		=> __('ASC', 'kingcomposer'),
						'DESC'		=> __('DESC', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show thumbnail', 'kingcomposer' ),
					'name'			=> 'thumbnail',
					'description'	=> __( 'Show the post thumbnail.', 'kingcomposer' ),
					'options' 		=> array(
						 'yes' => __('Yes, Please!', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Image size', 'kingcomposer' ),
					'name'			=> 'image_size',
					'description'	=> __( 'Add your image size, For example: thumbnail, medium, large or full).', 'kingcomposer' ),
					'value'			=> 'thumbnail',
					'relation' 	=> array(
						'parent'	=> 'thumbnail',
						'show_when'		=> 'yes'
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show date', 'kingcomposer' ),
					'name'			=> 'show_date',
					'description'	=> __( 'Show the date of the post.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' => __('Yes, Please!', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show author', 'kingcomposer' ),
					'name'			=> 'show_author',
					'description'	=> __( 'Show the author of the post.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' => __('Yes, Please!', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show tags', 'kingcomposer' ),
					'name'			=> 'show_tags',
					'description'	=> __( 'Show the tags of the post.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' => __('Yes, Please!', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show categories', 'kingcomposer' ),
					'name'			=> 'show_category',
					'description'	=> __( 'Show the categories of the post.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' => __('Yes, Please!', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Number of words', 'kingcomposer' ),
					'name'			=> 'number_word',
					'description'	=> __( 'Show a certain number of words in each post.', 'kingcomposer' ),
					'value'			=> '30'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show "Read more" button', 'kingcomposer' ),
					'name'			=> 'show_button',
					'description'	=> __( 'Show the "Read more" button in the post.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' => __('Yes, Please!', 'kingcomposer'),
					),
					'value'			=> 'yes'
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Read more text', 'kingcomposer' ),
					'name'			=> 'readmore_text',
					'description'	=> __( 'Edit the text that appears on the "Read more" button.', 'kingcomposer' ),
					'relation'		=> array(
						'parent'	=> 'show_button',
						'show_when' => 'yes'
					),
					'value'			=> 'Read more'
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Wrapper class name', 'kingcomposer' ),
					'name'			=> 'wrap_class',
					'description'	=> __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' ),
				)
			)
		),
		'kc_carousel_images' => array(

			'name' => __('Image Carousel', 'kingcomposer'),
			'description' => __('', 'kingcomposer'),
			'icon' => 'kc-icon-icarousel',
			'category' => '',
			'params' => array(

				array(
					'type'			=> 'text',
					'label'			=> __( 'Title', 'kingcomposer' ),
					'name'			=> 'title',
					'description'	=> __( 'The title of the Image Carousel. Leave blank if no title is needed.', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type' 			=> 'attach_images',
					'label' 		=> __( 'Images', 'kingcomposer' ),
					'name'			=> 'images',
					'description' 	=> __( 'Select images from media library.', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type'        	=> 'text',
					'label'     	=> __( 'Image size', 'kingcomposer' ),
					'name' 		 	=> 'img_size',
					'description' 	=> __( 'Set the image size : thumbnail, medium, large or full.', 'kingcomposer' ),
					'value'       	=> 'large',
				),
				array(
					'type'     		=> 'dropdown',
					'label'  	 	=> __( 'Onclick event', 'kingcomposer' ),
					'name'			=> 'onclick',
					'options' 		=> array(
						'none' => __( 'None', 'kingcomposer' ),
						'lightbox' => __( 'Open on lightbox', 'kingcomposer' ),
						'custom_link' => __( 'Open custom links', 'kingcomposer' )
					),
					'description'	=> __( 'Select the click event when users click on an image.', 'kingcomposer' )
				),
				array(
					'type' 			=> 'number_slider',
					'label' 		=> __( 'Items per slide', 'kingcomposer' ),
					'name' 			=> 'items_number',
					'description' 	=> __( 'The number of items displayed per slide.', 'kingcomposer' ),
					'admin_label'	=> true,
					'value'			=> '3',
					'options' => array(
						'min' => 1,
						'max' => 10
					)
				),
				array(
					'type' 			=> 'number_slider',
					'label' 		=> __( 'Speed', 'kingcomposer' ),
					'name' 			=> 'speed',
					'description' 	=> __( 'Set the speed at which auto playing sliders will transition (in second).', 'kingcomposer' ),
					'value'			=> 5,
					'admin_label'	=> true,
					'options' => array(
						'min' => 1,
						'max' => 15,
						'unit' => 's'
					)
				),
				array(
					'type'        	=> 'textarea',
					'label'     	=> __( 'Custom links', 'kingcomposer' ),
					'name'  	=> 'custom_links',
					'description' 	=> __( 'Enter links for each slide (Note: divide links with linebreaks (Enter)).', 'kingcomposer' ),
					'relation'  	=> array(
						'parent'	=> 'onclick',
						'show_when' => 'custom_link'
					)
				),
				array(
					'type'        	=> 'dropdown',
					'label'     	=> __( 'Custom link target', 'kingcomposer' ),
					'name'  		=> 'custom_links_target',
					'description' 	=> __( 'Select how to open custom links.', 'kingcomposer' ),
					'options'       	=> array(
						'_self' => __( 'Same window', 'kingcomposer' ),
						'_blank' => __( 'New window', 'kingcomposer' )
					),
					'relation'  	=> array(
						'parent'	=> 'onclick',
						'show_when' => 'custom_link'
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Navigation', 'kingcomposer' ),
					'name'			=> 'navigation',
					'description'	=> __( 'Display the "Next" and "Prev" buttons.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' => __('Yes, Please!', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Pagination', 'kingcomposer' ),
					'name'			=> 'pagination',
					'description'	=> __( 'Show the pagination.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' => __('Yes, Please!', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Auto height', 'kingcomposer' ),
					'name'			=> 'auto_height',
					'description'	=> __( 'Add height to div "owl-wrapper-outer" so you can use diffrent heights on slides. Use it only for one item per page setting.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' => __('Yes, Please!', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Auto Play', 'kingcomposer' ),
					'name'			=> 'auto_play',
					'description'	=> __( 'The carousel automatically plays when site loaded.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' => __('Yes, Please!', 'kingcomposer'),
					),
					'value'			=> 'yes'
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Progress Bar', 'kingcomposer' ),
					'name'			=> 'progress_bar',
					'description'	=> __( 'Visualize the progression of displaying photos.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' => __('Yes, Please!', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show thumbnail', 'kingcomposer' ),
					'name'			=> 'show_thumb',
					'description'	=> __( 'Show the thumbnails in carousel.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' => __('Yes, Please!', 'kingcomposer'),
					)
				),
				array(
					'type' => 'text',
					'label' => __( 'Wrapper class name', 'kingcomposer' ),
					'name' => 'wrap_class',
					'description' => __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' )
				),
			)

		),

		'kc_carousel_post' => array(

			'name' => __('Post Carousel', 'kingcomposer'),
			'description' => __('', 'kingcomposer'),
			'icon' => 'kc-icon-pcarousel',
			'category' => '',
			'params' => array(

				array(
					'type'			=> 'text',
					'label'			=> __( 'Title', 'kingcomposer' ),
					'name'			=> 'title',
					'description'	=> __( 'The title of the Post Carousel. Leave blank if no title is needed.', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type'			=> 'post_taxonomy',
					'label'			=> __( 'Content Type', 'kingcomposer' ),
					'name'			=> 'post_taxonomy',
					'description'	=> __( 'Choose supported content type such as post, custom post type, etc.', 'kingcomposer' ),
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Order by', 'kingcomposer' ),
					'name'			=> 'order_by',
					'description'	=> __( '', 'kingcomposer' ),
					'admin_label'	=> true,
					'options' 		=> array(
						'ID'		=> __('Post ID', 'kingcomposer'),
						'author'	=> __('Author', 'kingcomposer'),
						'title'		=> __('Title', 'kingcomposer'),
						'name'		=> __('Post name (post slug)', 'kingcomposer'),
						'type'		=> __('Post type (available since Version 4.0)', 'kingcomposer'),
						'date'		=> __('Date', 'kingcomposer'),
						'modified'	=> __('Last modified date', 'kingcomposer'),
						'rand'		=> __('Random order', 'kingcomposer'),
						'comment_count'	=> __('Number of comments', 'kingcomposer')
					)
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Order post', 'kingcomposer' ),
					'name'			=> 'order_list',
					'description'	=> __( '', 'kingcomposer' ),
					'admin_label'	=> true,
					'options' 		=> array(
						'ASC'		=> __('ASC', 'kingcomposer'),
						'DESC'		=> __('DESC', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'number_slider',
					'label'			=> __( 'Number of posts displayed', 'kingcomposer' ),
					'name'			=> 'number_post',
					'description'	=> __( 'The number of posts you want to show.', 'kingcomposer' ),
					'value'			=> '5',
					'admin_label'	=> true,
					'options' => array(
						'min' => 1,
						'max' => 20
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show thumbnail', 'kingcomposer' ),
					'name'			=> 'thumbnail',
					'description'	=> __( 'Show the post thumbnail.', 'kingcomposer' ),
					'options' 		=> array(
						'yes'		=> __('Yes', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Image size', 'kingcomposer' ),
					'name'			=> 'image_size',
					'description'	=> __( 'Set the image size : thumbnail, medium, large or full.', 'kingcomposer' ),
					'value'			=> 'thumbnail',
					'relation' 	=> array(
						'parent'	=> 'thumbnail',
						'show_when'		=> 'yes'
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show date', 'kingcomposer' ),
					'name'			=> 'show_date',
					'description'	=> __( 'Show the post date.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' 		=> __('Yes', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Show "Read more" button', 'kingcomposer' ),
					'name'			=> 'show_button',
					'description'	=> __( 'Show "Read more" button in the post.', 'kingcomposer' ),
					'options' 		=> array(
						'yes'		=> __('Yes', 'kingcomposer'),
					),
					'value'			=> 'yes'
				),
				array(
					'type' 			=> 'number_slider',
					'label' 		=> __( 'Items per slide', 'kingcomposer' ),
					'name' 	=> 'items_number',
					'description' 	=> __( 'The number of items displayed per slide.', 'kingcomposer' ),
					'value'			=> '3',
					'options' => array(
						'min' => 1,
						'max' => 10
					)
				),
				array(
					'type' 			=> 'number_slider',
					'label' 		=> __( 'Speed', 'kingcomposer' ),
					'name' 			=> 'speed',
					'description' 	=> __( 'Set the speed at which autoplaying sliders will transition in second.', 'kingcomposer' ),
					'value'			=> '5',
					'options' => array(
						'min' => 1,
						'max' => 15,
						'unit' => 's'
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Navigation', 'kingcomposer' ),
					'name'			=> 'navigation',
					'description'	=> __( 'Display the "Next" and "Prev" buttons.', 'kingcomposer' ),
					'options' 		=> array(
						'yes'		=> __('Yes', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Pagination', 'kingcomposer' ),
					'name'			=> 'pagination',
					'description'	=> __( 'Show the pagination.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' 		=> __('Yes', 'kingcomposer'),
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Auto height', 'kingcomposer' ),
					'name'			=> 'auto_height',
					'description'	=> __( 'Add height to owl-wrapper-outer so you can use diffrent heights on slides. Use it only for one item per page setting.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' 		=> __('Yes, Please!', 'kingcomposer'),
					),
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Auto Play', 'kingcomposer' ),
					'name'			=> 'auto_play',
					'description'	=> __( 'The carousel automatically plays when site loaded.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' 		=> __('Yes, Please!', 'kingcomposer'),
					),
					'value'			=> 'yes'
				),
				array(
					'type' => 'text',
					'label' => __( 'Wrapper class name', 'kingcomposer' ),
					'name' => 'wrap_class',
					'description' => __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' )
				),

			)
		),

		'kc_image_gallery' => array(

			'name' => __('Image Gallery', 'kingcomposer'),
			'description' => __('', 'kingcomposer'),
			'icon' => 'kc-icon-gallery',
			'category' => '',
			'params' => array(

				array(
					'type'			=> 'text',
					'label'			=> __( 'Title', 'kingcomposer' ),
					'name'			=> 'title',
					'description'	=> __( 'The title of the Image Gallery. Leave blank if no title is needed.', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type'			=> 'attach_images',
					'label'			=> __( 'Images', 'kingcomposer' ),
					'name'			=> 'images',
					'description'	=> __( 'Upload multiple image to the carousel with the SHIFT key holding.', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Gallery type', 'kingcomposer' ),
					'name'			=> 'type',
					'description'	=> __( 'Select the gallery presentation type.', 'kingcomposer' ),
					'options' 		=> array(
						'grid' 		=> __( 'Images grid', 'kingcomposer' ),
						'slider' 	=> __( 'Slider', 'kingcomposer' ),
					),
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Image masonry', 'kingcomposer' ),
					'name'			=> 'image_masonry',
					'description'	=> __( 'Masonry is a JavaScript grid layout library. It works by placing elements in optimal position based on available vertical space, sort of like a mason fitting stones in a wall.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' 		=> __('Yes', 'kingcomposer'),
					),
					'relation' 	=> array(
						'parent'	=> 'type',
						'show_when' => array('grid')
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Auto Play', 'kingcomposer' ),
					'name'			=> 'auto_rotate',
					'description'	=> __( 'Slider automatically plays when site loaded.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' 		=> __('Yes', 'kingcomposer'),
					),
					'relation' 	=> array(
						'parent'	=> 'type',
						'show_when' => array('slider')
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Navigation', 'kingcomposer' ),
					'name'			=> 'navigation',
					'description'	=> __( 'Display the "Next" and "Prev" buttons.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' 		=> __('Yes', 'kingcomposer'),
					),
					'relation' 	=> array(
						'parent'	=> 'type',
						'show_when' => array('slider')
					)
				),
				array(
					'type'			=> 'checkbox',
					'label'			=> __( 'Pagination', 'kingcomposer' ),
					'name'			=> 'pagination',
					'description'	=> __( 'Show the pagination.', 'kingcomposer' ),
					'options' 		=> array(
						'yes' 		=> __('Yes', 'kingcomposer'),
					),
					'relation' 	=> array(
						'parent'	=> 'type',
						'show_when' => array('slider')
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Slider width', 'kingcomposer' ),
					'name'			=> 'slider_width',
					'description'	=> __( 'Wrapper slider width, unit (px) or (%)', 'kingcomposer' ),
					'relation' 	=> array(
						'parent'	=> 'type',
						'show_when' => array('slider')
					),
					'value'			=> '400'
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Image size', 'kingcomposer' ),
					'name'			=> 'image_size',
					'description'	=> __( 'Set the image size : thumbnail, medium, large or full.', 'kingcomposer' ),
					'value'			=> 'full'
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Onclick event', 'kingcomposer' ),
					'name'			=> 'click_action',
					'description'	=> __( 'Select the click event when users click on an image.', 'kingcomposer' ),
					'options' 		=> array(
						'none' 			=> __( 'No action', 'kingcomposer' ),
						'large_image' 	=> __( 'Open large image', 'kingcomposer' ),
						'lightbox' 		=> __( 'Open on lightbox', 'kingcomposer' ),
						'custom_link' 	=> __( 'Open on custom link', 'kingcomposer' )
					),
					'relation' 	=> array(
						'parent'	=> 'type',
						'show_when' => 'grid'
					)
				),
				array(
					'type'			=> 'textarea',
					'label'			=> __( 'Custom links', 'kingcomposer' ),
					'name'			=> 'custom_links',
					'description'	=> __( 'Each custom link per new line and corresponding to each image uploaded', 'kingcomposer' ),
					'relation'	=> array(
						'parent'	=> 'click_action',
						'show_when'	=> 'custom_link'
					)
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Wrapper class name', 'kingcomposer' ),
					'name'			=> 'wrap_class',
					'description'	=> __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' ),
				),


			)
		),

		'kc_coundown_timer' => array(
			'name' => __('Countdown Timer', 'kingcomposer'),
			'description' => __('', 'kingcomposer'),
			'icon' => 'kc-icon-coundown',
			'category' => '',
			'css_box' => true,
			'params' => array(

				array(
					'type'			=> 'text',
					'label'			=> __( 'Title', 'kingcomposer' ),
					'name'			=> 'title',
					'description'	=> __( 'The title of Countdown Timer. Leave blank if no title is needed.', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type'			=> 'dropdown',
					'label'			=> __( 'Timer Style', 'kingcomposer' ),
					'name'			=> 'timer_style',
					'options'		=> array(
						'1' => __( 'Digit and Unit Side by Side', 'kingcomposer' ),
						'2' => __( 'Digit and Unit Up and Down', 'kingcomposer' ),
						'3' => __( 'Custom style template', 'kingcomposer' )
					),
					'description'	=> __( 'Select presentation style of the countdown timer.', 'kingcomposer' ),
				),
				array(
					'type'			=> 'textarea',
					'label'			=> __( 'Custom template', 'kingcomposer' ),
					'name'			=> 'custom_template',
					'description'	=> __( "For example: %D days %H:%M:%S.\n --- %Y: \"years\", %m: \"months\", %n: \"daysToMonth\", %w: \"weeks\", %d: \"daysToWeek\", %D: \"totalDays\", %H: \"hours\", %M: \"minutes\", %S: \"seconds\"", 'kingcomposer' ),
					'relation'	=> array(
						'parent'	=> 'timer_style',
						'show_when'	=> '3'
					)
				),
				array(
					'type'			=> 'date_picker',
					'label'			=> __( 'Date time', 'kingcomposer' ),
					'name'			=> 'datetime',
					'description'	=> __( 'Target date For Countdown.', 'kingcomposer' ),
					'admin_label'	=> true
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Background color group', 'kingcomposer' ),
					'name'			=> 'bgcolor_group',
					'description'	=> __( 'Set background color for each group (days, hours, minutes, seconds)', 'kingcomposer' )
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Background color digit', 'kingcomposer' ),
					'name'			=> 'bgcolor_digit',
					'description'	=> __( 'Set background color for digit', 'kingcomposer' ),
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Text Color', 'kingcomposer' ),
					'name'			=> 'text_color',
					'description'	=> __( 'Set color for timer digit text', 'kingcomposer' ),
				),
				array(
					'type'			=> 'color_picker',
					'label'			=> __( 'Unit Color', 'kingcomposer' ),
					'name'			=> 'unit_color',
					'description'	=> __( 'Set color of the Timer Unit text.', 'kingcomposer' ),
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Digit Text Size', 'kingcomposer' ),
					'name'			=> 'digit_text_size',
					'description'	=> __( 'Set font size of the Timer Digit Text.', 'kingcomposer' ),
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Unit Text Size', 'kingcomposer' ),
					'name'			=> 'unit_text_size',
					'description'	=> __( 'Set font size of the Timer Unit text.', 'kingcomposer' ),
				),
				array(
					'type'			=> 'text',
					'label'			=> __( 'Wrapper class name', 'kingcomposer' ),
					'name'			=> 'wrap_class',
					'description'	=> __( 'Custom class for wrapper of the shortcode widget.', 'kingcomposer' ),
				),
				array(
					'type' 			=> 'css_editor',
					'label' 		=> __( 'CSS box', 'kingcomposer' ),
					'name' 			=> 'css',
					'description'	=> __( 'Default Design Options tab on VC, apply for wrapper of box', 'kingcomposer' ),
					'group' 		=> __( 'Design Options', 'kingcomposer' ),
				)

			)
		),

	)

);

