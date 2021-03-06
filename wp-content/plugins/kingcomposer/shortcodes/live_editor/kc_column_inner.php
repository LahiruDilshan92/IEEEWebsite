<#

var output = '', attributes = [], col_in_class_container = 'kc_wrapper', 
	classes = [ 'kc_column_inner' ], atts = ( data.atts !== undefined ) ? data.atts : {};

if( undefined !== atts['col_in_class'] && atts['col_in_class'] !== '' )
	classes.push( atts['col_in_class'] );

if( undefined !== atts['css'] && atts['css'] !== '' )
	classes .push( atts['css'].split('|')[0] );
	
classes.push( kc.front.ui.column.width_class( atts['width'] ) );

if( undefined !== atts['col_in_class_container'] && atts['col_in_class_container'] !== '' )
	col_in_class_container += ' '+atts['col_in_class_container'];

attributes.push( 'class="'+classes.join(' ')+'"' );

if( data.content === undefined )
	data.content = '';
	
data.content += '<div class="kc-element drag-helper" data-model="-1" droppable="true" draggable="true"><a href="javascript:void(0)" class="kc-add-elements-inner"><i class="sl-plus"></i> Add Elements</a></div>';


#><div {{{attributes.join(' ')}}}>
	<div class="{{col_in_class_container}}'">{{{data.content}}}</div>
	<#
		if( atts[ 'css' ] !== undefined && atts[ 'responsive' ] !== undefined &&  atts[ 'responsive' ] !== '' ){
			#><style type="text/css">{{{kc.front.ui.style.responsive(atts['responsive'],atts['css'].split('|')[0])}}}</style><#
		}
	#>
</div>