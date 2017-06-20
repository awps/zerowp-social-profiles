<?php 
/* No direct access allowed!
---------------------------------*/
if ( ! defined( 'ABSPATH' ) ) exit;

/* Inject the component
----------------------------*/
add_action( 'zsp:widgets_init', function(){
	
	register_widget( 'SocialProfiles\Component\Widget\NetworksList' );
	register_widget( 'SocialProfiles\Component\Widget\AboutUser' );

});