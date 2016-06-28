<#

var atts = ( data.atts !== undefined ) ? data.atts : {}, el_class = 'kc_text_block';

if( atts['class'] !== undefined && atts['class'] !== '' )
	el_class += ' '+atts['class'];

#>

<div class="{{el_class}}">{{{top.switchEditors.wpautop(data._content)}}}</div>