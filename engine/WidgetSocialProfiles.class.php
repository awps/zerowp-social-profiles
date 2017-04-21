<?php 
namespace SocialProfiles;

class WidgetSocialProfiles extends WidgetBase{
	public function settings(){
		return array(
			'id_base' => 'zsp_social_profiles_widget',
			'name'    => __( 'Social Profiles', 'social-profiles' ),
		);
	}
}