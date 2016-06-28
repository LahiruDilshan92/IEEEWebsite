<#

if( data === undefined )
	data = {};

var atts = ( data.atts !== undefined ) ? data.atts : {},
	textbutton = kc.std( atts, 'text_title', 'Button Text'),
	link = kc.std( atts, 'link', '#'),
	wrap_class = kc.std( atts, 'wrap_class', ''),
	size = kc.std( atts, 'size', 'small'),
	show_icon = kc.std( atts, 'show_icon', 'no'),
	icon = kc.std( atts, 'icon', 'fa-leaf'),
	icon_position = kc.std( atts, 'icon_position', 'left'),
	custom_class = 'button_'+parseInt( Math.random()*100000 ),
	button_attributes = [],
	el_classess = [ 'kc_button', wrap_class, custom_class ];

if( atts['css'] !== undefined && atts['css'] !== '' )
	el_classess.push( atts['css'].split('|')[0] );

link = link.split('|');

el_classess.push( 'button_size_'+size );

button_attributes.push( 'class="'+el_classess.join(' ')+'"');

if( link[0] !== undefined )
	button_attributes.push( 'href="'+link[0]+'"' );

if( link[1] !== undefined )
	button_attributes.push( 'title="'+link[1]+'"' );

if( link[2] !== undefined )
	button_attributes.push( 'target="'+link[2]+'"' );

if('yes' === show_icon){
	if( icon_position == 'left' ){
		textbutton = '<i class="'+icon+'"></i> '+textbutton;
	}else if( icon_position == 'right'){
		textbutton += ' <i class="'+icon+'"></i>';
	}else{
		textbutton = '<i class="'+icon+'"></i>';
	}
}

css = '';
if( 'custom' == size ){
	if( atts['padding_button'] !== undefined && atts['padding_button'] !== '' )
		css += 'padding:'+atts['padding_button']+';';
	if( atts['font_size_button'] !== undefined && atts['font_size_button'] !== '' )
		css += 'font-size:'+atts['font_size_button']+';';
}

var bg_color = kc.std( atts, 'bg_color', '#393939' ),
	text_color = kc.std( atts, 'text_color', '#FFFFFF' ),
	border_radius = kc.std( atts, 'border_radius', 3 ),
	
	bg_color_hover = kc.std( atts, 'bg_color_hover', '#FFFFFF' ),
	text_color_hover = kc.std( atts, 'text_color_hover', '#393939' );

css += 'background-color: '+bg_color+';';
css += 'color: '+text_color+';';
css += 'border-radius: '+parseInt(border_radius)+'px;';
css += 'border: 1px solid '+bg_color+';';

if( atts['custom_style'] !== undefined && atts['custom_style'] == 'yes' ){
	data.css = css;
	data.css_hover = 'background-color:'+bg_color_hover+';color:'+text_color_hover+';border: spx solid '+text_color_hover+';';
	data.selector = custom_class;
	
	data.callback = function( wrp, $, data ){
		top.kc.front.ui.style.add( '.'+data.selector, data.css );
		top.kc.front.ui.style.add( '.'+data.selector+':hover', data.css_hover );
	}
}
#><a {{{button_attributes.join(' ')}}}>{{{textbutton}}}</a>