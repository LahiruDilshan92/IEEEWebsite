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


class kc_ajax{

	public function __construct(){

		$ajax_events = array(
			'get_welcome' 		=> false,
			'get_thumbn' 		=> true,
			'load_profile'		=> false,
			'download_profile'	=> false,
			'create_profile'	=> false,
			'rename_profile'	=> false,
			'delete_profile'	=> false,
			'delete_section'	=> false,
			'update_section'	=> false,
			'instant_save'		=> false,
			'suggestion'		=> false,
			'tmpl_storage'		=> false,
			'verify_license'		=> false,
			'load_element_via_ajax'		=> false,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {

			add_action( 'wp_ajax_kc_' . $ajax_event, array( $this, esc_attr( $ajax_event ) ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_kc_' . $ajax_event, array( $this, esc_attr( $ajax_event ) ) );
			}
		}
	}

	public function get_welcome(){

		$data = array(
			'message' => __('Hello, I\'m King Composer!', 'kingcomposer')
		);

		wp_send_json( $data );
	}

	public function get_thumbn( $abc ){

		$imid = !empty( $_GET['id'] ) ? $_GET['id'] : '';

		if( $imid == '' || $imid == 'undefined' )
		{
			header( 'location: '.KC_URL.'/assets/images/get_start.jpg' );
			exit;
		}

		if( $imid == 'featured_image' )
		{

		}

		$img = wp_get_attachment_image_src( esc_attr( $_GET['id'] ), (!empty( $_GET['size'] )?esc_attr( $_GET['size'] ):'medium') );

		if( !empty( $img[0] ) )
		{
			header( 'location: '.$img[0] );
		}
		else
		{
			header( 'location: '.KC_URL.'/assets/images/default.jpg' );
		}
	}
	
	public function download_profile(){
		
		$name = isset( $_GET['name'] ) ? $_GET['name'] : '';
		
		if( empty( $name ) ){
			echo '[]';
			exit;
		}
		
		$name = sanitize_title( esc_attr( $name ) );
		
		if( get_option( 'kc-profile-'.$name ) !== false ){
			
			$data = get_option( 'kc-profile-'.$name, true );
			
			if( isset( $data[1] ) && !empty( $data[1] ) )
				echo base64_decode( $data[1] );
			else echo '[]';
			
		}else echo '[]';
		
		exit;
		
	}
		
	public function load_profile(){

		$kc = KingComposer::globe();
		$profile_section_paths = $kc->get_profile_sections();
		
		$name =  !empty( $_POST['name'] ) ? $_POST['name'] : '';
		$name = str_replace( array('..'), array( '' ), esc_attr( $name )  );
		
		$data = '';
		$slug = sanitize_title( $name );
		
		if( $name == '' ){
			
			$result = array(
				'message' =>  esc_html__('Error #623! The name must not be empty', 'kingcomposer'),
				'status' => 'fail'
			);
			
		}
		else{
			
			if( isset( $profile_section_paths[ $name ] ) && is_file( untrailingslashit( ABSPATH ).$profile_section_paths[ $name ] ) ){
				
				$profile = $kc->get_data_profile( $name );
			
				if( $profile !== false ){
					
					if( isset( $profile[0] ) && !empty( $profile[0] ) && $profile[0] !== null )
						$name = $profile[0];
					if( isset( $profile[1] ) && !empty( $profile[1] ) && $profile[1] !== null )
						$slug = $profile[1];
					if( isset( $profile[2] ) && !empty( $profile[2] ) && $profile[2] !== null )
						$data = $profile[2];
					
				}else{
					
					$message = esc_html__('Error #795! opening file Permission denied', 'kingcomposer').': '.
								$profile_section_paths[ $name ];
					wp_send_json(
						array( 'message' => $message, 'status' => 'fail' )
					);
					
					return;
					
				}
				
			} 
			else if( get_option( 'kc-profile-'.$name ) !== false ){
				
				$getDB =  get_option( 'kc-profile-'.$name, true );
				
				$slug = $name;
				if( isset( $getDB[0] ) && !empty( $getDB[0] ) && $getDB[0] !== null )
					$name = $getDB[0];
				else $name = '';
				
				if( isset( $getDB[1] ) && !empty( $getDB[1] ) && $getDB[1] !== null )
					$data = $getDB[1];
				else $data = base64_encode('');
				
			}
			else{
				
				$message = esc_html__('Error #528! profile not found', 'kingcomposer').': '.$name;
				wp_send_json(
					array( 'message' => $message, 'status' => 'fail' )
				);
				return;
			
			}

		}
		
		$result = array(

			'message' => '<div class="mgs-c-status"><i class="et-happy"></i></div><h1 class="mgs-t02">'.
						 esc_html__('Your sections profile has been downloaded successful', 'kingcomposer').'</h1>'.
						 '<h2>'.esc_html__('Now you can use sections from new profile', 'kingcomposer').'</h2>',
			'status' => 'success',
			'name' => $name,
			'slug' => $slug,
			'data' => $data

		);
			
		wp_send_json( $result );

		exit;

	}
	
	public function create_profile(){
		
		$name =  !empty( $_POST['name'] ) ? $_POST['name'] : '';
		
		if( $name == '' ){
			
			$result = array(
				'message' =>  esc_html__('Error #140! The name must not be empty', 'kingcomposer'),
				'status' => 'fail'
			);
			
		}else{
		
			$slug =  !empty( $_POST['slug'] ) ? $_POST['slug'] : sanitize_title( $name );
			$data =  !empty( $_POST['data'] ) ? $_POST['data'] : '';
			
			if( get_option( 'kc-profile-'.$slug ) === false ){
				
				add_option( 'kc-profile-'.$slug, array( $name, $data ), null, 'no' );
				
				$result = array(
					'message' => __('Your sections profile has been created successful', 'kingcomposer'),
					'status' => 'success',
					'name' => $name,
					'slug' => $slug
				);
				
			}else{
				
				$result = array(
					'message' =>  esc_html__('Error #101! The name must not be empty', 'kingcomposer'),
					'status' => 'fail',
					'name' => $name,
					'slug' => $slug
				);
			}
		
		}
			
		wp_send_json( $result );

		exit;
		
	}
	
	public function rename_profile(){
		
		
		$name =  !empty( $_POST['name'] ) ? $_POST['name'] : '';
		
		if( $name == '' ){
			
			$result = array(
				'message' =>  esc_html__('Error #197! The name must not be empty', 'kingcomposer'),
				'status' => 'fail'
			);
			
		}else{
		
			$slug =  !empty( $_POST['slug'] ) ? $_POST['slug'] : sanitize_title( $name );
			$data =  !empty( $_POST['data'] ) ? $_POST['data'] : '';
				
			if( get_option( 'kc-profile-'.$slug ) === false ){
					
				$result = array(
					'message' => __('Error #501! could not find profile', 'kingcomposer'),
					'status' => 'fail',
					'name' => $name,
					'slug' => $slug
				);
				
			}else{
				
				$data_db = get_option( 'kc-profile-'.$slug, true );
				
				$data_db[0] = $name;
				
				update_option( 'kc-profile-'.$slug, $data_db );
				
				
				$result = array(
					'message' =>  esc_html__('The profile has been changed', 'kingcomposer'),
					'status' => 'success',
					'name' => $name,
					'slug' => $slug
				);
				
			}
		
		}
			
		wp_send_json( $result );

		exit;
		
	}
		
	public function delete_profile(){
		
		
		$slug =  !empty( $_POST['slug'] ) ? $_POST['slug'] : '';
		
		if( $slug == '' ){
			
			$result = array(
				'message' =>  esc_html__('Error #167! The slug must not be empty', 'kingcomposer'),
				'status' => 'fail'
			);
			
		}else{
				
			if( get_option( 'kc-profile-'.$slug ) === false ){
			
				$result = array(
					'message' => __('Error #723! could not find profile', 'kingcomposer'),
					'status' => 'fail',
					'slug' => $slug
				);
			}else{
				
				delete_option( 'kc-profile-'.$slug );
				
				$result = array(
					'message' =>  esc_html__('The profile has been deleted', 'kingcomposer'),
					'status' => 'success',
					'slug' => $slug
				);
			}
			
		
		}
			
		wp_send_json( $result );

		exit;
		
	}
	
	public function update_section(){
		
		$slug =  !empty( $_POST['slug'] ) ? $_POST['slug'] : '';
		
		if( $slug == '' ){
			
			$result = array(
				'message' =>  esc_html__('Error #193! The slug must not be empty', 'kingcomposer'),
				'status' => 'fail'
			);
			
		}else{
			
			$id =  !empty( $_POST['id'] ) ? $_POST['id'] : '';
			$name =  !empty( $_POST['name'] ) ? $_POST['name'] : '';
			$data =  !empty( $_POST['data'] ) ? $_POST['data'] : '';
			
			if( !empty( $data ) )
				$data = json_decode( base64_decode( $data ) );
				
			if( get_option( 'kc-profile-'.$slug ) === false ){
				
				$kc = KingComposer::globe();
				$profile = $kc->get_data_profile( $slug );
				
				if( $profile !== false ){
					
					$profile_data = json_decode( base64_decode( $profile[2] ) );
					$found = false;
					
					foreach( $profile_data as $key => $value ){
						if( $value->id == $id ){
							$profile_data[ $key ] = $data;
							$found = true;
						}
					}
					
					if( $found === false )
						array_push( $profile_data, $data );
					
					$data = base64_encode( json_encode( $profile_data ) );
				
				}else{
				
					$data = base64_encode( json_encode( array( $data ) ) );
				
				}
				
				add_option( 'kc-profile-'.$slug, array( $name, $data ) , null, 'no' );
				
				$result = array(
					'message' =>  esc_html__('The section has been updated', 'kingcomposer'),
					'status' => 'success',
					'name' => $name,
					'data' => $data,
					'slug' => $slug
				);
				
				
			}
			else
			{
				
				$data_db = get_option( 'kc-profile-'.$slug, true );
				
				$from_db = json_decode( base64_decode( $data_db[1] ) );
				
				if( is_array( $from_db ) ){
				
					$found = false;
					
					if( is_array( $from_db ) ){
						foreach( $from_db as $key => $val ){
							
							if( $val->id == $id ){
								$from_db[ $key ] = $data;
								$found = true;
							}
							
						}
					}
					
					if( !$found )
						array_push( $from_db, $data );
				
				}else{
					$from_db = array( $data );
				}
					
				$from_db = base64_encode( json_encode( $from_db ) );
				
				update_option( 'kc-profile-'.$slug, array( $data_db[0], $from_db ) );
				
				
				$result = array(
					'message' =>  esc_html__('The section has been updated', 'kingcomposer'),
					'status' => 'success',
					'name' => $data_db[0],
					'data' => $from_db,
					'slug' => $slug
				);
				
			}
		
		}
			
		wp_send_json( $result );

		exit;
		

	}
	
	public function delete_section(){ 
		
		$name =  isset( $_POST['name'] ) ? $_POST['name'] : '';
		$id =  isset( $_POST['id'] ) ? $_POST['id'] : '';
		$slug =  !empty( $_POST['slug'] ) ? $_POST['slug'] : sanitize_title( $name );
		$data =  !empty( $_POST['data'] ) ? $_POST['data'] : '';
			
		if( get_option( 'kc-profile-'.$slug ) === false ){
			
			$sections = json_decode( base64_decode( $data ) );
			
			if( is_array( $sections ) ){
				
				$data = array();
				
				foreach( $sections as $key => $value ){
					
					if( !isset( $value->id ) )
						$value->id = rand( 100000, 1000000 );
					
					if( $value->id != $id )
						array_push( $data, $value );
				}
				
				$data = base64_encode( json_encode( $data ) );
				
				add_option( 'kc-profile-'.$slug, array( $name, $data ) , null, 'no' );
			
				$result = array(
					'message' =>  esc_html__('The section has been removed', 'kingcomposer'),
					'status' => 'success',
					'name' => $name,
					'data' => $data,
					'slug' => $slug
				);
				
			}else{
				
				$result = array(
					'message' =>  esc_html__('Error profile data structure #416', 'kingcomposer'),
					'status' => 'fail',
					'name' => $name,
					'slug' => $slug
				);
				
			}
			
		}else{
			
			$data_db = get_option( 'kc-profile-'.$slug, true );
			
			$sections = @json_decode( base64_decode( $data_db[1] ) );
			
			if( is_array( $sections ) ){
				
				$data = array();
				
				foreach( $sections as $key => $value ){
					
					if( !isset( $value->id ) )
						$value->id = rand( 100000, 1000000 );
					
					if( $value->id != $id )
						array_push( $data, $value );
						
				}
				
				$data_db[1] = base64_encode( json_encode( $data ) );
				
				update_option( 'kc-profile-'.$slug, $data_db );
			
			
				$result = array(
					'message' =>  esc_html__('The section has been removed', 'kingcomposer'),
					'status' => 'success',
					'name' => $data_db[0],
					'data' => $data_db[1],
					'slug' => $slug
				);
				
			}else{
				
				$result = array(
					'message' =>  esc_html__('Error profile data structure #426', 'kingcomposer'),
					'status' => 'fail',
					'name' => $data_db[0],
					'slug' => $slug
				);
				
			}
			
		}
		
		wp_send_json( $result );

		exit;
	
	}

	public function instant_save(){
		
		check_ajax_referer( 'kc-nonce', 'security' );
		
		if( !isset( $_POST['id'] ) || !isset( $_POST['title'] ) || !isset( $_POST['content'] ) ){
			echo $this->msg( __('Error: Invalid Post ID', 'kingcomposer'), 0 );
			exit;
		}
		
		$id = esc_attr( $_POST['id'] );
		if( get_post_status( $id ) === false ){
			echo $this->msg( __('Error: Post not exist', 'kingcomposer'), 0 );
			exit;
		}
		
		$kc = KingComposer::globe();
		$get_post = get_post( $id );
		
		if( !isset( $get_post ) || $kc->user_can_edit( $get_post ) === false ){
			echo $this->msg( __('Error: You do not have permission to edit this post', 'kingcomposer'), 0 );
			exit;
		}
		
		$args = sanitize_post( array(
			
			'ID'           => $_POST['id'],
			'post_title'   => $_POST['title'],
			'post_content' => $_POST['content'],
			'css' => $_POST['css'],
			'classes' => $_POST['classes'],
			
		), 'db' );

		$data = array(
			'ID' => $args['ID'],
			'post_title'   => $args['post_title'],
			'post_content' => $args['post_content']
		);
		
		if( current_user_can( 'publish_pages' ) ){
			$data['post_status']  = 'publish';
		}
		
		if( isset( $_POST['task'] ) && $_POST['task'] == 'frontend' ){
			
			unset( $data['post_title'] );
			if( wp_update_post( $data ) )
				echo $this->msg( __('Your content has been saved Successful', 'kingcomposer'), 1 );
			else echo $this->msg( __('Error: could not save the content', 'kingcomposer'), 0 );
			
			exit;
			
		}
		
		echo wp_update_post( $data );
		
		$param = get_post_meta( $id, 'kc_data' );
		if( $param === false ){
			
			add_post_meta( $id, 'kc_data', array( 'mode' => 'kc', 'css' => $args['css'], 'classes' => $args['classes'] ) );
		
		}else{
			
			$param['mode'] = 'kc';
			$param['css'] = $args['css'];
			$param['classes'] = $args['classes'];
			
			update_post_meta( $id, 'kc_data', $param );
			
		}
		
		exit;
		
	}

	public function suggestion(){
		
		check_ajax_referer( 'kc-nonce', 'security' );
		
		$data = array( '__session' => isset($_POST['session'])?$_POST['session']:'' );
		$args = array( 's' => isset($_POST['s'])?$_POST['s']:'', 'post_type' => 'any' );
		
		$args['numberposts'] = 120;
		if ( 0 === strlen( $args['s'] ) ) {
			unset( $args['s'] );
		}
		add_filter( 'posts_search', 'kc_filter_search', 500, 2 );
		$posts = get_posts( $args );
		if ( is_array( $posts ) && ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				if( !isset( $data[ $post->post_type ] ) )
					$data[ $post->post_type ] = array();	
				$data[ $post->post_type ][] = $post->ID.':'.esc_html(str_replace( array(':',','), array('',''), $post->post_title));
			}
		}
	
		wp_send_json( $data );

	}
	
	public function tmpl_storage(){
		
		check_ajax_referer( 'kc-nonce', 'security' );
		
		$kc = KingComposer::globe();
		$kc->convert_paramTypes_cache();
		require_once KC_PATH.'/includes/kc.templates.php';
		
		exit;
		
	}
	
	public function verify_license(){
		
		check_ajax_referer( 'kc-verify-nonce', 'security' );
		
		$license = isset( $_POST['license'] ) ? esc_html( $_POST['license'] ) : '';
		
		if( strlen( $license ) != 41 )
		{
			echo '-2';
			exit;
		}
		
		$theme = esc_html( wp_get_theme() );
		$domain = str_replace( '=', '-d', base64_encode( site_url() ) );
		$url = 'https://kingcomposer.com/?kc_store_action=verify_license&domain='.$domain.'&theme='.$theme.'&license='.$license;
	
		$request = wp_remote_get( $url );
		$response = wp_remote_retrieve_body( $request );
		
		$response = json_decode( $response );
		
		$data = array(
			'code' => '',
			'theme' => $theme,
			'domain' => $domain,
			'date' => date('Y-m-d H:i:s'),
			'stt' => 0
		);
		
		if( isset( $response->stt ) )
			$data['stt'] = $response->stt;
		
		if( isset( $response->code ) )
			$data['code'] = $response->code;
		
		if( isset( $response->date ) )
			$data['date'] = $response->date;
		
		if( $data['stt'] == 1 ){
			
			if( get_option( 'kc_tkl_cc' ) === false ){			
				add_option( 'kc_tkl_cc', $data['code'] , null, 'no' );
			}else{
				update_option( 'kc_tkl_cc', $data['code'] );
			}
			if( get_option( 'kc_tkl_dd' ) === false ){			
				add_option( 'kc_tkl_dd', $data['date'] , null, 'no' );
			}else{
				update_option( 'kc_tkl_dd', $data['date'] );
			}
			
		}
			
		wp_send_json( $data );
		
		exit;
		
	}
	
	public function load_element_via_ajax(){
		
		if( !isset( $_POST['model'] ) || !isset( $_POST['code'] ) ){
			wp_send_json( array( 'status' => '-1' ) );
			exit;
		}
		
		if( isset( $_POST['ID'] ) && get_post_status( $_POST['ID'] ) !== false ){
			global $post;
			$post = get_post( $_POST['ID'] );
		}
		
		include 'kc.front.php';
		
		global $kc, $kc_front, $shortcode_tags;
		
		$code = isset( $_POST['code'] ) ? $_POST['code'] : '';
		
		$code = $kc_front->do_filter_shortcode( base64_decode( $code ) );

		wp_send_json( array( 
			'status' => '1',
			'model' => $_POST['model'],
			'html' => '<!--kc s '.$_POST['model'].'-->'.trim( do_shortcode( $code ) ).'<!--kc e '.$_POST['model'].'-->',
			'css' => $kc_front->get_global_css(),
			'callback' => $kc->live_js_callback
		));
		
		exit;
		
	}

	public function msg( $s = '', $t = 1 ){
		if( $t == 1 )
			return '<h3 class="mesg success"><i class="et-happy"></i><br />'.$s.'</h3>';
		else return '<h3 class="mesg error"><i class="et-sad"></i><br />'.$s.'</h3>';
	}

}

#Start kc_Ajax
new kc_ajax();
