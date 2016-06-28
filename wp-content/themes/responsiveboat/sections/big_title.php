<div class="home-header-wrap" id="topWrapHeader">
<canvas class="my-canvas"></canvas>
    
    <script type="application/javascript">
    var canvas = document.querySelector("canvas");
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    $('#topWrapHeader').height(window.innerHeight);
    $('.container-sd').height(window.innerHeight);
    $('.header-content-wrap').width(window.innerWidth);
        

var ctx = canvas.getContext("2d");

var TAU = 2 * Math.PI;

times = [];
function loop() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  update();
  draw();
  requestAnimationFrame(loop);
}

function Ball (startX, startY, startVelX, startVelY) {
  this.x = startX || Math.random() * canvas.width;
  this.y = startY || Math.random() * canvas.height;
  this.vel = {
    x: startVelX || Math.random() * 2 - 1,
    y: startVelY || Math.random() * 2 - 1
  };
  this.update = function(canvas) {
    if (this.x > canvas.width + 50 || this.x < -50) {
      this.vel.x = -this.vel.x;
    }
    if (this.y > canvas.height + 50 || this.y < -50) {
      this.vel.y = -this.vel.y;
    }
    this.x += this.vel.x;
    this.y += this.vel.y;
  };
  this.draw = function(ctx, can) {
    ctx.fillStyle = '#001c33';
    ctx.beginPath();
    ctx.globalAlpha = 0;
    
    ctx.arc((0.5 + this.x) | 0, (0.5 + this.y) | 0, 3, 0,Math.PI*2, false);
    ctx.closePath();
    ctx.fill();
  }
}

var balls = [];
for (var i = 0; i < canvas.width * canvas.height / (75*75); i++) {
  balls.push(new Ball(Math.random() * canvas.width, Math.random() * canvas.height));
}

var lastTime = Date.now();
function update() {
  var diff = Date.now() - lastTime;
  for (var frame = 0; frame * 16.6667 < diff; frame++) {
    for (var index = 0; index < balls.length; index++) {
      balls[index].update(canvas);
    }
  }
  lastTime = Date.now();
}
var mouseX = -1e9, mouseY = -1e9;
document.addEventListener('mousemove', function(event) {
  mouseX = event.clientX;
  mouseY = event.clientY;
});

function distMouse(ball) {
  return Math.hypot(ball.x - mouseX, ball.y - mouseY);
}

function draw() {
//  ctx.globalAlpha=1;
//  ctx.fillStyle = '#001c33';
  ctx.fillRect(0,0,canvas.width, canvas.height);
  for (var index = 0; index < balls.length; index++) {
    var ball = balls[index];
    ball.draw(ctx, canvas);
    ctx.beginPath();
    for (var index2 = balls.length - 1; index2 > index; index2 += -1) {
      var ball2 = balls[index2];
      var dist = Math.hypot(ball.x - ball2.x, ball.y - ball2.y);
        if (dist < 100) {
          ctx.strokeStyle = "#FFFFFF";
          ctx.globalAlpha = 1 - (dist > 100 ? .8 : dist / 150);
          ctx.lineWidth = "4px";
          ctx.moveTo((0.5 + ball.x) | 0, (0.5 + ball.y) | 0);
          ctx.lineTo((0.5 + ball2.x) | 0, (0.5 + ball2.y) | 0);
        }
    }
    ctx.stroke();
  }
}

// Start
loop();
    
    </script>
    
     <div class="container-sd">
			<!-- Top Navigation -->
			<div id="boxgallery" class="boxgallery" data-effect="effect-1">
				<div class="panel-sd">
                    <div class="anm-desc"><span style="background:#FFDD00">Talk on PhD Opportunities in USA</span></div>
                    <img src="<?php bloginfo('template_directory'); ?>/img/1.jpg" alt="Image 1"/></div>
				<div class="panel-sd">
                    <div class="anm-desc"><span style="background:#FFDD00">WIE Tech Forum 2016</span></div>
                    <img src="<?php bloginfo('template_directory'); ?>/img/2.jpg" alt="Image 2"/></div>
				<div class="panel-sd">
                    <div class="anm-desc"><span style="background:#FFDD00">IEEE Sri Lanka Section, Family Get Together</span></div>
                    <img src="<?php bloginfo('template_directory'); ?>/img/3.jpg" alt="Image 3"/></div>
				<div class="panel-sd">
                    <div class="anm-desc"><span style="background:#FFDD00">IEEE Sri Lanka Section Student YP, WIE Congress Sri Lanka 2015</span></div>
                    <img src="<?php bloginfo('template_directory'); ?>/img/4.jpg" alt="Image 4"/></div>
                <div class="panel-sd">
                    <div class="anm-desc"><span style="background:#FFDD00">IEEE Sri Lanka Section, Family Get Together</span></div>
                    <img src="<?php bloginfo('template_directory'); ?>/img/5.jpg" alt="Image 5"/></div>
                <div class="panel-sd">
                    <div class="anm-desc"><span style="background:#FFDD00">IEEE R10 SYW Congress 2015</span></div>
                    <img src="<?php bloginfo('template_directory'); ?>/img/6.jpg" alt="Image 6"/></div>
			</div>
    </div><!-- /container -->
<!--
<script type='text/javascript' src='http://localhost/ieeeuop/wp-content/themes/zerif-lite/js/classie.js?ver=20120208'></script>
<script type='text/javascript' src='http://localhost/ieeeuop/wp-content/themes/zerif-lite/js/boxesFx.js?ver=20120208'></script>
-->
    
<?php

	global $wp_customize;

	$zerif_parallax_img1 = get_theme_mod('zerif_parallax_img1',get_template_directory_uri() . '/images/background1.jpg');
	$zerif_parallax_img2 = get_theme_mod('zerif_parallax_img2',get_template_directory_uri() . '/images/background2.png');
	$zerif_parallax_use = get_theme_mod('zerif_parallax_show');

	if ( $zerif_parallax_use == 1 && (!empty($zerif_parallax_img1) || !empty($zerif_parallax_img2)) ) {
//		echo '<ul id="parallax_move">';
//	
//			if( !empty($zerif_parallax_img1) ) { 
//				echo '<li class="layer layer1" data-depth="0.10" style="background-image: url(' . $zerif_parallax_img1 . ');"></li>';
//			}
//			if( !empty($zerif_parallax_img2) ) { 
//				echo '<li class="layer layer2" data-depth="0.20" style="background-image: url(' . $zerif_parallax_img2 . ');"></li>';
//			}
//
//		echo '</ul>';
        
        ?>
  
  
    
    <?php
        
        
        
	
	}

	$zerif_bigtitle_show = get_theme_mod('zerif_bigtitle_show');
	
	if( isset($zerif_bigtitle_show) && $zerif_bigtitle_show != 1 ):
	
		echo '<div class="header-content-wrap">';
	
	elseif ( isset( $wp_customize ) ):
	
		echo '<div class="header-content-wrap zerif_hidden_if_not_customizer">';
	
	endif;

	if( (isset($zerif_bigtitle_show) && $zerif_bigtitle_show != 1) || isset( $wp_customize ) ):

		echo '<div class="container big-title-container">';
		
		 $rb_bigtitle_logo = get_theme_mod('rb_bigtitle_logo',get_stylesheet_directory_uri().'/images/logo-small.png');

        if( !empty($rb_bigtitle_logo) ):

             echo '<a href="'.esc_url( home_url( '/' ) ).'" class="">';

                echo '<img src="'.esc_url( $rb_bigtitle_logo ).'" alt="'.get_bloginfo('title').'" class="rb_logo">';

             echo '</a>';

        endif;

		/* Big title */
		$responsiveboat_parent_theme = get_template();
	
		if( !empty($responsiveboat_parent_theme) && ($responsiveboat_parent_theme == 'zerif-pro') ):
		
			$zerif_bigtitle_title = get_theme_mod( 'zerif_bigtitle_title', __('To add a title here please go to Customizer, "Big title section"','responsiveboat') );
		
		else:
		
			$zerif_bigtitle_title = get_theme_mod('zerif_bigtitle_title',__('ONE OF THE TOP 10 MOST POPULAR THEMES ON WORDPRESS.ORG','responsiveboat'));
		
		endif;
		
		if( !empty($zerif_bigtitle_title) ):

			echo '<h1 class="intro-text">'.$zerif_bigtitle_title.'</h1>';
			
		elseif ( isset( $wp_customize ) ):
		
			echo '<h1 class="intro-text zerif_hidden_if_not_customizer"></h1>';

		endif;	

		/* Buttons */
		
		if( !empty($responsiveboat_parent_theme) && ($responsiveboat_parent_theme == 'zerif-pro') ):
			$zerif_bigtitle_redbutton_label = get_theme_mod( 'zerif_bigtitle_redbutton_label',__('One button','responsiveboat') );
			$zerif_bigtitle_redbutton_url = get_theme_mod( 'zerif_bigtitle_redbutton_url','#' );

			$zerif_bigtitle_greenbutton_label = get_theme_mod( 'zerif_bigtitle_greenbutton_label',__('Another button','responsiveboat') );
			$zerif_bigtitle_greenbutton_url = get_theme_mod( 'zerif_bigtitle_greenbutton_url','#' );
		else:
			$zerif_bigtitle_redbutton_label = get_theme_mod('zerif_bigtitle_redbutton_label',__('About','responsiveboat'));
			$zerif_bigtitle_redbutton_url = get_theme_mod('zerif_bigtitle_redbutton_url', esc_url( home_url( '/' ) ).'#aboutus');
			
			$zerif_bigtitle_greenbutton_label = get_theme_mod('zerif_bigtitle_greenbutton_label',__("What's inside",'responsiveboat'));
			$zerif_bigtitle_greenbutton_url = get_theme_mod('zerif_bigtitle_greenbutton_url',esc_url( home_url( '/' ) ).'#latestnews');
		endif;

		if( (!empty($zerif_bigtitle_redbutton_label) && !empty($zerif_bigtitle_redbutton_url)) ||

		(!empty($zerif_bigtitle_greenbutton_label) && !empty($zerif_bigtitle_greenbutton_url))):


			echo '<div class="buttons make-up">';
				if( function_exists('zerif_big_title_buttons_top_trigger') ):
					zerif_big_title_buttons_top_trigger();
				endif;	

				/* Red button */
			
				if (!empty($zerif_bigtitle_redbutton_label) && !empty($zerif_bigtitle_redbutton_url)):

					echo '<a href="'.$zerif_bigtitle_redbutton_url.'" class="btn btn-primary custom-button red-btn">'.$zerif_bigtitle_redbutton_label.'</a>';
					
				elseif ( isset( $wp_customize ) ):

					echo '<a href="" class="btn btn-primary custom-button red-btn zerif_hidden_if_not_customizer"></a>';

				endif;

				/* Green button */

				if (!empty($zerif_bigtitle_greenbutton_label) && !empty($zerif_bigtitle_greenbutton_url)):

					echo '<a href="'.$zerif_bigtitle_greenbutton_url.'" class="btn btn-primary custom-button green-btn">'.$zerif_bigtitle_greenbutton_label.'</a>';

				elseif ( isset( $wp_customize ) ):

					echo '<a href="" class="btn btn-primary custom-button green-btn zerif_hidden_if_not_customizer"></a>';

				endif;

				if( function_exists('zerif_big_title_buttons_bottom_trigger') ):
					zerif_big_title_buttons_bottom_trigger();
				endif;

			echo '</div>';


		endif;

		echo '</div>';

	echo '</div><!-- .header-content-wrap -->';
	
	endif;

	echo '<div class="clear"></div>';


?>

</div><!--.home-header-wrap -->

<script>
document.getElementById('topWrapHeader').height =  window.innerHeight;
</script>