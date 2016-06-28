<#

var output = '', element_attributes = [], el_classes = ['kc_box_wrap'],
	atts = ( data.atts !== undefined ) ? data.atts : {};

if( atts['custom_class'] !== undefined && atts['custom_class'] !== '' )
	el_classes.push( atts['custom_class'] );

element_attributes.push( 'class="'+el_classes.join(' ')+'"' );

#>

<div class="{{el_classes.join(' ')}}"><#

if( data = JSON.parse( kc.tools.base64.decode( atts['data'] ) ) )
{
	#>{{{kc.front.loop_box( data )}}}<#
	if( atts['css'] !== undefined ){
		#><style type="text/css">{{{atts['css']}}}</style><#
	}
}
else
{
	#>KC Box: Error content structure<#
}

#></div>