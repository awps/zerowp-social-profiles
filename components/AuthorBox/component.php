<?php 
/* No direct access allowed!
---------------------------------*/
if ( ! defined( 'ABSPATH' ) ) exit;

/* Inject the component
----------------------------*/
add_action( 'zsp:author_info', function( $settings ){
	
	$instance = wp_parse_args( $settings, array(
		'avatar_size' => 150,
		'show_link' => 1,
		'show_reg_date' => 1,
	));

	if( empty($instance['username']) ){
		if( is_singular() ){
			$this_post = get_post( get_the_ID() );
			$user_id =  absint( $this_post->post_author );
			$instance['link_username'] = true;
		}
		elseif( is_author() ){
			global $wp_query;
			$curauth = $wp_query->get_queried_object();
			$user_id =  absint( $curauth->ID );
		}

		if( !empty($user_id) ){
			$username = get_userdata( $user_id );
			$instance['username'] = $username->user_login;
		}
	}

	the_widget( 'SocialProfiles\Component\Widget\AboutUser', $instance, array() );

});