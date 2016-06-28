<#

var output = '', atts = ( data.atts !== undefined ) ? data.atts : {},
	css_classes = [ 'kc_row', 'kc_row_inner' ], attributes = [];

if( undefined !== atts['row_class'] && atts['row_class'] !== '' )
	css_classes.push( atts['row_class'] );

if( atts['css'] !== undefined && atts['css'] !== '' )
	css_classes.push( atts['css'].split('|')[0] );
	
if( undefined !== atts['row_id'] && atts['row_id'] !== '' )
	attributes.push( 'id="'+atts['row_id']+'"' );

attributes.push( 'class="'+css_classes.join(' ')+'"' );

if( undefined !== atts['equal_height'] && atts['equal_height'] !== '' )
{
	attributes.push( 'data-kc-equalheight="true"' );
	attributes.push( 'data-kc-row-action="true"' );
	data.callback = function(){
		kc_row_action(true);
	}
}

output += '<div '+attributes.join(' ')+'>';

if( undefined !== atts['row_class_container'] && atts['row_class_container'] !== '' )
	output += '<div class="'+atts['row_class_container']+'">';

output += data.content;

if( undefined !== atts['row_class_container'] && atts['row_class_container'] !== '' )
	output += '</div>';

output += '</div>';

#>

{{{output}}}