<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*
*/
if(!defined('KC_FILE')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

global $kc_front;

?>
<div id="kc-element-placeholder" class="kc-boxholder">
	<div class="mpb-tooltip move">
		<ul>
			<li data-action="double">
				<i class="sl-docs"></i>
				<span class="tips"><?php _e( 'Double this element or', 'kingcomposer' ); ?> <a href="#copy" data-action="copy"><?php _e( 'Click Here to Copy', 'kingcomposer' ); ?></a></span>
			</li>		
			<li data-action="edit">
				<i class="sl-pencil"></i>
				<span class="tips"><?php _e( 'Open settings (double-click on the element)', 'kingcomposer' ); ?></span>
			</li>
		</ul>
		<i data-action="delete" title="<?php _e( 'Delete this element', 'kingcomposer' ); ?>" class="sl-close delete"></i>
		<span class="label" title="<?php _e( 'Drag & drop to arrange this element', 'kingcomposer' ); ?>"></span>
	</div>
	<div class="mpb mpb-top"></div>
	<div class="mpb mpb-right"></div>
	<div class="mpb mpb-bottom"></div>
	<div class="mpb mpb-left"></div>
</div>
<div id="kc-row-placeholder" class="kc-boxholder">
	<div class="mpb-tooltip move" title="<?php _e( 'Drag & drop to arrange rows', 'kingcomposer' ); ?>">
		<ul>
			<li data-action="columns">
				<i class="sl-list"></i>
				<span class="tips"><?php _e( 'Set number of columns for this row', 'kingcomposer' ); ?></span>
			</li>
			<li data-action="double">
				<i class="sl-docs"></i>
				<span class="tips"><?php _e( 'Double this row or', 'kingcomposer' ); ?> <a href="#copy" data-action="copy"><?php _e( 'Click Here to Copy', 'kingcomposer' ); ?></a></span>
			</li>		
			<li data-action="edit">
				<i class="sl-pencil"></i>
				<span class="tips"><?php _e( 'Open row settings', 'kingcomposer' ); ?></span>
			</li>
		</ul>
		<i data-action="delete" title="<?php _e( 'Delete row', 'kingcomposer' ); ?>" class="sl-close delete"></i>
		<span class="label"></span>
	</div>
	<div class="mpb mpb-top"></div>
	<div class="mpb mpb-right"></div>
	<div class="mpb mpb-bottom"></div>
	<div class="mpb mpb-left"></div>
</div>
<div id="kc-column-0-placeholder" class="kc-boxholder" data-col-index="0">
	<div class="mpb mpb-top">
		<ul class="center top">
			<li class="tip" data-action="add-element">
				<i class="sl-plus"></i>
				<span class="tips">
					<?php _e( 'Add element to column', 'kingcomposer' ); ?>
				</span>
			</li>
			<li class="tip" data-action="edit">
				<i class="sl-pencil"></i>
				<span class="tips">
					<?php _e( 'Open the settings of column', 'kingcomposer' ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="mpb mpb-right"></div>
	<div class="mpb mpb-bottom">
		<ul class="center">
			<li class="tip" data-action="add-element">
				<i class="sl-plus"></i>
				<span class="tips">
					<?php _e( 'Add element to column', 'kingcomposer' ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="mpb mpb-left"></div>
</div>
<div id="kc-column-1-placeholder" class="kc-boxholder" data-col-index="1">
	<div class="mpb mpb-top">
		<ul class="center top">
			<li class="tip" data-action="add-element">
				<i class="sl-plus"></i>
				<span class="tips">
					<?php _e( 'Add element to column', 'kingcomposer' ); ?>
				</span>
			</li>
			<li class="tip" data-action="edit">
				<i class="sl-pencil"></i>
				<span class="tips">
					<?php _e( 'Open the settings of column', 'kingcomposer' ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="mpb mpb-right"></div>
	<div class="mpb mpb-bottom">
		<ul class="center">
			<li class="tip" data-action="add-element">
				<i class="sl-plus"></i>
				<span class="tips">
					<?php _e( 'Add element to column', 'kingcomposer' ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="mpb mpb-left">
		<div class="handle-resize">
			<i class="fa-arrow-left" data-action="col-wid-left" title="<?php _e( 'Increasing the width of the right column and reduce the width of the left column', 'kingcomposer' ); ?>"></i>
			<i class="fa-arrow-right" data-action="col-wid-right" title="<?php _e( 'Increasing the width of the left column and reduce the width of the right column', 'kingcomposer' ); ?>"></i>
			<i class="fa-exchange" data-action="col-exchange" title="<?php _e( 'Exchange columns', 'kingcomposer' ); ?>"></i>
		</div>
	</div>
</div>
<div id="kc-column-2-placeholder" class="kc-boxholder" data-col-index="2">
	<div class="mpb mpb-top">
		<ul class="center top">
			<li class="tip" data-action="add-element">
				<i class="sl-plus"></i>
				<span class="tips">
					<?php _e( 'Add element to column', 'kingcomposer' ); ?>
				</span>
			</li>
			<li class="tip" data-action="edit">
				<i class="sl-pencil"></i>
				<span class="tips">
					<?php _e( 'Open the settings of column', 'kingcomposer' ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="mpb mpb-right"></div>
	<div class="mpb mpb-bottom">
		<ul class="center">
			<li class="tip" data-action="add-element">
				<i class="sl-plus"></i>
				<span class="tips">
					<?php _e( 'Add element to column', 'kingcomposer' ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="mpb mpb-left">
		<div class="handle-resize">
			<i class="fa-arrow-left" data-action="col-wid-left" title="<?php _e( 'Increasing the width of the right column and reduce the width of the left column', 'kingcomposer' ); ?>"></i>
			<i class="fa-arrow-right" data-action="col-wid-right" title="<?php _e( 'Increasing the width of the left column and reduce the width of the right column', 'kingcomposer' ); ?>"></i>
			<i class="fa-exchange" data-action="col-exchange" title="<?php _e( 'Exchange columns', 'kingcomposer' ); ?>"></i>
		</div>
	</div>
</div>
<div id="kc-column-3-placeholder" class="kc-boxholder" data-col-index="3">
	<div class="mpb mpb-top">
		<ul class="center top">
			<li class="tip" data-action="add-element">
				<i class="sl-plus"></i>
				<span class="tips">
					<?php _e( 'Add element to column', 'kingcomposer' ); ?>
				</span>
			</li>
			<li class="tip" data-action="edit">
				<i class="sl-pencil"></i>
				<span class="tips">
					<?php _e( 'Open the settings of column', 'kingcomposer' ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="mpb mpb-right"></div>
	<div class="mpb mpb-bottom">
		<ul class="center">
			<li class="tip" data-action="add-element">
				<i class="sl-plus"></i>
				<span class="tips">
					<?php _e( 'Add element to column', 'kingcomposer' ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="mpb mpb-left">
		<div class="handle-resize">
			<i class="fa-arrow-left" data-action="col-wid-left" title="<?php _e( 'Increasing the width of the right column and reduce the width of the left column', 'kingcomposer' ); ?>"></i>
			<i class="fa-arrow-right" data-action="col-wid-right" title="<?php _e( 'Increasing the width of the left column and reduce the width of the right column', 'kingcomposer' ); ?>"></i>
			<i class="fa-exchange" data-action="col-exchange" title="<?php _e( 'Exchange columns', 'kingcomposer' ); ?>"></i>
		</div>
	</div>
</div>
<div id="kc-column-4-placeholder" class="kc-boxholder" data-col-index="4">
	<div class="mpb mpb-top">
		<ul class="center top">
			<li class="tip" data-action="add-element">
				<i class="sl-plus"></i>
				<span class="tips">
					<?php _e( 'Add element to column', 'kingcomposer' ); ?>
				</span>
			</li>
			<li class="tip" data-action="edit">
				<i class="sl-pencil"></i>
				<span class="tips">
					<?php _e( 'Open the settings of column', 'kingcomposer' ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="mpb mpb-right"></div>
	<div class="mpb mpb-bottom">
		<ul class="center">
			<li class="tip" data-action="add-element">
				<i class="sl-plus"></i>
				<span class="tips">
					<?php _e( 'Add element to column', 'kingcomposer' ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="mpb mpb-left">
		<div class="handle-resize">
			<i class="fa-arrow-left" data-action="col-wid-left" title="<?php _e( 'Increasing the width of the right column and reduce the width of the left column', 'kingcomposer' ); ?>"></i>
			<i class="fa-arrow-right" data-action="col-wid-right" title="<?php _e( 'Increasing the width of the left column and reduce the width of the right column', 'kingcomposer' ); ?>"></i>
			<i class="fa-exchange" data-action="col-exchange" title="<?php _e( 'Exchange columns', 'kingcomposer' ); ?>"></i>
		</div>
	</div>
</div>
<div id="kc-column-5-placeholder" class="kc-boxholder" data-col-index="5">
	<div class="mpb mpb-top">
		<ul class="center top">
			<li class="tip" data-action="add-element">
				<i class="sl-plus"></i>
				<span class="tips">
					<?php _e( 'Add element to column', 'kingcomposer' ); ?>
				</span>
			</li>
			<li class="tip" data-action="edit">
				<i class="sl-pencil"></i>
				<span class="tips">
					<?php _e( 'Open the settings of column', 'kingcomposer' ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="mpb mpb-right"></div>
	<div class="mpb mpb-bottom">
		<ul class="center">
			<li class="tip" data-action="add-element">
				<i class="sl-plus"></i>
				<span class="tips">
					<?php _e( 'Add element to column', 'kingcomposer' ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="mpb mpb-left">
		<div class="handle-resize">
			<i class="fa-arrow-left" data-action="col-wid-left" title="<?php _e( 'Increasing the width of the right column and reduce the width of the left column', 'kingcomposer' ); ?>"></i>
			<i class="fa-arrow-right" data-action="col-wid-right" title="<?php _e( 'Increasing the width of the left column and reduce the width of the right column', 'kingcomposer' ); ?>"></i>
			<i class="fa-exchange" data-action="col-exchange" title="<?php _e( 'Exchange columns', 'kingcomposer' ); ?>"></i>
		</div>
	</div>
</div>
<div id="kc-sections-placeholder" class="kc-boxholder">
	<div class="mpb-tooltip move">
		<ul>
			<li data-action="double">
				<i class="sl-docs"></i>
				<span class="tips"><?php _e( 'Double this element or', 'kingcomposer' ); ?> <a href="#copy" data-action="copy"><?php _e( 'Click Here to Copy', 'kingcomposer' ); ?></a></span>
			</li>		
			<li data-action="add-section">
				<i class="sl-plus"></i>
				<span class="tips"><?php _e( 'Add section', 'kingcomposer' ); ?></span>
			</li>
			<li data-action="edit">
				<i class="sl-pencil"></i>
				<span class="tips"><?php _e( 'Open settings', 'kingcomposer' ); ?></span>
			</li>
		</ul>
		<i data-action="delete" title="<?php _e( 'Delete element', 'kingcomposer' ); ?>" class="sl-close delete"></i>
		<span class="label"></span>
	</div>
	<div class="mpb mpb-top"></div>
	<div class="mpb mpb-right"></div>
	<div class="mpb mpb-bottom">
		<ul class="center">
			<li class="tip" data-action="add-section">
				<i class="sl-plus"></i> 
				<?php _e( 'Add section', 'kingcomposer' ); ?>
			</li>
		</ul>
	</div>
	<div class="mpb mpb-left"></div>
</div>
<div id="kc-section-placeholder" class="kc-boxholder">
	<div class="mpb mpb-top">
		<ul class="center top">
			<li class="tip" data-action="add-element">
				<i class="sl-plus"></i>
				<span class="tips">
					<?php _e( 'Add element to section', 'kingcomposer' ); ?>
				</span>
			</li>
			<li class="tip" data-action="edit">
				<i class="sl-pencil"></i>
				<span class="tips">
					<?php _e( 'Open the settings of section', 'kingcomposer' ); ?>
				</span>
			</li>
			<li class="tip" data-action="double">
				<i class="sl-docs"></i>
				<span class="tips">
					<?php _e( 'Double section', 'kingcomposer' ); ?>
				</span>
			</li>
			<li class="tip delete" data-action="delete">
				<i class="sl-close"></i>
				<span class="tips">
					<?php _e( 'Delete section', 'kingcomposer' ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="mpb mpb-right"></div>
	<div class="mpb mpb-bottom"></div>
	<div class="mpb mpb-left"></div>
</div>
<img width="50" src="<?php echo KC_URL; ?>/assets/images/drag.png" id="kc-ui-handle-image" />
<img width="50" src="<?php echo KC_URL; ?>/assets/images/drag-copy.png" id="kc-ui-handle-image-copy" />
<script type="text/javascript">
	
	if( top.kc === undefined )
		top.kc = { front : {}, frame : {} };
	
	top.kc.storage = <?php echo json_encode( $kc_front->storage ); ?>;
	
	( function ( $ ) {
		
		top.kc.frame = {
			doc : document,
			window : window,
			html : $('html').get(0),
			body : $('body').get(0),
			$ : jQuery
		}
		
		if( top.kc.detect !== undefined )
			top.kc.detect.frame = top.kc.frame;
		
		$( document ).ready( function(){ 
							
			if( top.kc.front === undefined || typeof(top.kc.front.init) != 'function' )
				top.kc.init_front_ready = true;
			else top.kc.front.init();
			 
		  }).
		  on( 'mouseover', function( e ){ top.kc.detect.hover(e) } ).
		  on( 'click', function( e ){ top.kc.detect.click(e) } ).
		  on( 'dblclick', function( e ){ top.kc.detect.dblclick(e) } );
					  
		top.kc.do_callback = function( callback, el ){
			for( var i in callback )
				eval( '('+callback[i].callback.toString()+')( jQuery(\'[data-model="'+callback[i].model+'"]\'), jQuery, callback[i] );' );
		}
	
		
	}) ( jQuery );
	
</script>

