<#

var output = '', css_class = [ 'kc_tab', tab_id = '', 'ui-tabs-panel', 'kc_ui-tabs-hide', 'kc_clearfix' ], 
	atts = ( data.atts !== undefined ) ? data.atts : {};

if ( atts['tab_id'] !== undefined && atts['tab_id'] !== '' ){
	tab_id = kc.tools.esc_slug( atts['title'] );
}else{
	tab_id = atts['tab_id'];
}

if ( atts['class'] !== undefined && atts['class'] !== '' )
	css_class.push( atts['class'] );

if( data.content === undefined )
	data.content = '';
	
data.content += '<div class="kc-element drag-helper" data-model="-1" droppable="true" draggable="true"><a href="javascript:void(0)" class="kc-add-elements-inner"><i class="sl-plus"></i> Add Elements</a></div>';

#><div id="{{tab_id}}" class="{{{css_class.join(' ')}}}">{{{data.content}}}</div>