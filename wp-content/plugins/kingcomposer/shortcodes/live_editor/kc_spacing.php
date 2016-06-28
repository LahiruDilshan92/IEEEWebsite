<#

if( data === undefined )
	data = {};

var el_class = '', height = 0, 
	atts = ( data.atts !== undefined ) ? data.atts : {};

if( atts['class'] !== undefined )
	el_class = atts['class'];

if( atts['height'] !== undefined )
	height = parseInt( atts['height'] );

#>

<div class="{{el_class}}" style="height: {{height}}px; clear: both; width:100%;"></div>