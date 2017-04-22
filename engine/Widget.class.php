<?php 
namespace SocialProfiles;

class Widget extends WidgetBase{
	
	public function settings(){
		return array(
			'id_base'   => 'zsp_social_profiles_widget',
			'name'      => __( 'Social Profiles', 'social-profiles' ),
			'classname' => 'zsp-social-profiles-widget',
		);
	}

	public function options( $instance ){
		
		$this->addField( 'title', 'text', $instance, array(
			'label'       => __( 'Title', 'social-profiles' ),
		));

		$this->addField( 'icon_size', 'select', $instance, array(
			'label'   => __( 'Icon size', 'social-profiles' ),
			'options' => ZSP()->config( 'icon_size' ),
			'default' => 'large',
			'class' => 'widefat',
			'grid' => 4,
		));

		$this->addField( 'icon_shape', 'select', $instance, array(
			'label'   => __( 'Shape', 'social-profiles' ),
			'options' => ZSP()->config( 'icon_shape' ),
			'class' => 'widefat',
			'grid' => 4
		));

		$this->addField( 'icon_radius', 'select', $instance, array(
			'label'   => __( 'Radius', 'social-profiles' ),
			'options' => ZSP()->config( 'icon_radius' ),
			'default' => 'rounded',
			'class' => 'widefat',
			'grid' => 4
		));

		$this->addField( 'list_style', 'select', $instance, array(
			'label'   => __( 'List style', 'social-profiles' ),
			'options' => array(
				'networks_list' => __( 'Networks list', 'social-profiles' ),
				'icons_list'    => __( 'Icons list', 'social-profiles' ),
			),
			'default' => 'rounded',
			'class' => 'widefat',
			'grid' => 4
		));

		do_action( 'zsp_social_profiles_widget_options', $this );

	}

	public function widget( $args, $instance ){
		echo $this->openWidget( $args );
		echo $this->getTitle( $args, $instance );

		/* Get saved data
		----------------------*/
		$icon_size   = !empty( $instance[ 'icon_size' ] ) ? ' '. sanitize_html_class( $instance[ 'icon_size' ] ) : '';
		$icon_shape  = !empty( $instance[ 'icon_shape' ] ) ? ' '. sanitize_html_class( $instance[ 'icon_shape' ] ) : '';
		$icon_radius = !empty( $instance[ 'icon_radius' ] ) ? ' '. sanitize_html_class( $instance[ 'icon_radius' ] ) : '';
		$list_style  = !empty( $instance[ 'list_style' ] ) ? $instance[ 'list_style' ] : '';

		/* Prepare icon class
		--------------------------*/
		$class = $icon_size . $icon_shape . $icon_radius;

		/* Loop
		------------*/
		if( 'icons_list' === $list_style ){
			foreach ( ZSP()->brands() as $b => $v) {
				echo '<a href="#"> 
					<span class="sp-icon-'. $b . $class .'" 
						title="'. esc_attr( isset( $v[1] ) ? $v[1] : ucfirst( $b ) ) .'">
						<i></i>
					</span>
				</a>';
			}
		}
		else{
			foreach ( ZSP()->brands() as $b => $v) {
				echo '<a href="#" class="network">
					<span  class="sp-icon-'. $b . $class .'"><i></i></span>
					<div class="details">
						<div class="on">Follow us on</div>
						<div class="title">'. ( isset( $v[1] ) ? $v[1] : ucfirst( $b ) ) .'</div>
					</div>
				</a>';
			}
		}

		echo $this->closeWidget( $args );
	}

}