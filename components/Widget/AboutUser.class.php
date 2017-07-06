<?php 
namespace SocialProfiles\Component\Widget;

use SocialProfiles\Widget\AbstractWidget;

class AboutUser extends AbstractWidget{
	
	public function settings(){
		return array(
			'id_base'   => 'zsp_about_user',
			'name'      => __( 'About User', 'social-profiles' ),
			'classname' => 'zsp-about-user',
		);
	}

	public function widget( $args, $instance ){
		$username      = !empty($instance[ 'username' ]) ? esc_html( $instance[ 'username' ] ) : false;
		$limit         = !empty($instance[ 'limit' ]) ? absint( $instance[ 'limit' ] ) : false;
		$show_name     = !empty($instance[ 'show_name' ]) ? $instance[ 'show_name' ] : 'display_name';
		$link_username = !empty($instance[ 'link_username' ]) ? $instance[ 'link_username' ] : false;
		$show_link     = !empty($instance[ 'show_link' ]) ? $instance[ 'show_link' ] : false;
		$show_reg_date = !empty($instance[ 'show_reg_date' ]) ? $instance[ 'show_reg_date' ] : false;
		$avatar_size   = !empty($instance[ 'avatar_size' ]) ? absint( $instance[ 'avatar_size' ] ) : false;
		$avatar_style  = !empty($instance[ 'avatar_style' ]) ? sanitize_html_class( $instance[ 'avatar_style' ] ) : 'circle';
		$box_style     = !empty($instance[ 'box_style' ]) ? sanitize_html_class( $instance[ 'box_style' ] ) : 'large';
		
		if( 'inline' == $box_style ){
			$col_left = 'col-3';
			$col_right = 'col-9';
		}
		else{
			$col_left = '';
			$col_right = '';
		}

		if( empty( $username ) )
			return '';

		echo $this->openWidget( $args );
		
			echo $this->getTitle( $args, $instance );

			$user = get_user_by( 'login', $username );
			$descr = !empty($user->description) ? $user->description : '';

			if( $limit > 0 ){
				$descr = $this->userBioExcerpt( $descr, $limit );
			}

			$avatar = '';
			if( $avatar_size > 0 ){
				$avatar = '<div class="photo '. $col_left .' '. $avatar_style .'">'. get_avatar( $user->ID, $avatar_size ) .'</div>';
			}

			if( $link_username ){
				$name_before = '<div class="username"><a href="'. get_author_posts_url( $user->ID ) .'">';
				$name_after = '</a></div>';
			}
			else{
				$name_before = '<div class="username">';
				$name_after = '</div>';
			}

			if( 'username' == $show_name ){
				$the_name = $name_before . $user->user_login . $name_after;
			}
			if( '@username' == $show_name ){
				$the_name = $name_before . '@'. $user->user_login . $name_after;
			}
			elseif( 'none' == $show_name ){
				$the_name = '';
			}
			else{
				$the_name = $name_before . $user->display_name . $name_after;
			}

			// Author info
			$author_info = '<div class="author-info">';
				
				if( !empty( $show_link ) && !empty($user->user_url) ){
					$author_info .= '<span><a target="_blank" href="'. $user->user_url .'">'. $user->user_url .'</a></span>';
				}

				if( !empty( $show_reg_date ) ){
					$author_info .= '<span>';
						$format = get_option( 'date_format', 'F j, Y' );
						$author_info .= sprintf( __( 'Member since %s', 'social-profiles' ), date_i18n($format, strtotime($user->user_registered)) );
					$author_info .= '</span>';
				}

			$author_info .= '</div>';

			// Icons
			$networks = get_user_meta( $user->ID, 'social_networks', true );
			if( !empty($networks) ){ 
				$networks_list = ZSP()->parseNetworksList( $networks, array(
					'list_style' => 'icons_list',
					// 'icon_size' => 'large'
				) );
			}
			else{
				$networks_list = '';
			}

			echo apply_filters( 'zsp_about_user', '<div class="zsp-about-user-in zsp-grid '. $box_style .'">'. 
				$avatar . 
				'<div class="details '. $col_right .'">'. $the_name . $author_info . wpautop( $descr ) . $networks_list  
				.'</div>'
			.'</div>', $instance, $args );

		echo $this->closeWidget( $args );
	}

	public function options( $instance ) {
		$user = get_userdata( get_current_user_id() );

		$this->addField( 'title', 'text', $instance, array(
			'label' => __( 'Title', 'social-profiles' ),
			'description' => __( 'Recommended to leave empty.', 'social-profiles' ),
		));

		$this->addField( 'username', 'text', $instance, array(
			'label' => __( 'Username', 'social-profiles' ),
			'default' => $user->user_login,
			'description' => __( 'Enter the username to get info for.', 'social-profiles' ),
		));

		$this->addField( 'show_name', 'select', $instance, array(
			'label' => __( 'Show name', 'social-profiles' ),
			'default' => 'display_name',
			'options' => array(
				'display_name' => __( 'Display name', 'social-profiles' ),
				'username' => __( 'username', 'social-profiles' ),
				'@username' => __( '@username', 'social-profiles' ),
				'none' => __( 'None', 'social-profiles' ),
			),
		));

		$this->addField( 'show_link', 'select', $instance, array(
			'label' => __( 'Show link', 'social-profiles' ),
			'default' => '0',
			'options' => array(
				'1' => __( 'Yes', 'social-profiles' ),
				'0' => __( 'No', 'social-profiles' ),
			),
		));
		$this->addField( 'show_reg_date', 'select', $instance, array(
			'label' => __( 'Show registration date', 'social-profiles' ),
			'default' => '0',
			'options' => array(
				'1' => __( 'Yes', 'social-profiles' ),
				'0' => __( 'No', 'social-profiles' ),
			),
		));

		$this->addField( 'limit', 'number', $instance, array(
			'label' => __( 'User bio limit', 'social-profiles' ),
			'default' => '',
			'description' => __( 'Leave empty or set to 0 if you want to show the full user bio.', 'social-profiles' ),
		));

		$this->addField( 'avatar_size', 'number', $instance, array(
			'label' => __( 'Avatar size', 'social-profiles' ),
			'default' => '150',
			'description' => __( 'Leave empty or set to 0 if you want to hide the avatar.', 'social-profiles' ),
		));

		$this->addField( 'avatar_style', 'select', $instance, array(
			'label' => __( 'Avatar style', 'social-profiles' ),
			'default' => 'circle',
			'options' => array(
				'circle' => __( 'Circle', 'social-profiles' ),
				'square' => __( 'Square', 'social-profiles' ),
				'soft' => __( 'Soft', 'social-profiles' ),
			),
		));

		$this->addField( 'box_style', 'select', $instance, array(
			'label' => __( 'Box style', 'social-profiles' ),
			'default' => 'large',
			'options' => array(
				'inline' => __( 'Inline', 'social-profiles' ),
				'large' => __( 'Large', 'social-profiles' ),
			),
		));

	}

	public function userBioExcerpt( $bio, $len=20, $trim="&hellip;"){
		$limit = $len + 1;
		$bio   = explode(' ', $bio, $limit);
		$num_words = count($bio);
		if($num_words >= $len){
			$last_item = array_pop( $bio );
		}
		else{
			$trim = '';
		}
		$bio = implode(" ", $bio) . $trim;

		return $bio;
	}

}