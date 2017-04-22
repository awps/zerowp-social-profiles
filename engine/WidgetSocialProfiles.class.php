<?php 
namespace SocialProfiles;

class WidgetSocialProfiles extends WidgetBase{
	
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
			'options' => apply_filters( 'zsp_icon_size', array(
				''            => __( 'Default', 'social-profiles' ),
				'large'       => __( 'Large', 'social-profiles' ),
				'extra-large' => __( 'Extra large', 'social-profiles' ),
			)),
			'default' => 'large',
			'class' => 'widefat',
			'grid' => 4,
		));

		$this->addField( 'icon_shape', 'select', $instance, array(
			'label'   => __( 'Shape', 'social-profiles' ),
			'options' => apply_filters( 'zsp_icon_shape', array(
				''          => __( 'Default', 'social-profiles' ),
				'burst'     => __( 'Burst', 'social-profiles' ),
				'burst-alt' => __( 'Burst alt', 'social-profiles' ),
			)),
			'class' => 'widefat',
			'grid' => 4
		));

		$this->addField( 'icon_radius', 'select', $instance, array(
			'label'   => __( 'Radius', 'social-profiles' ),
			'options' => apply_filters( 'zsp_icon_radius', array(
				''          => __( 'Default', 'social-profiles' ),
				'rounded'   => __( 'Rounded', 'social-profiles' ),
				'soft'      => __( 'Soft', 'social-profiles' ),
				'square'    => __( 'Square', 'social-profiles' ),
			)),
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

		/* Prepare icon class
		--------------------------*/
		$class = $icon_size . $icon_shape . $icon_radius;

		/* Loop
		------------*/
		foreach ( ZSP()->brands as $b => $v) {
			echo '<a href="#" class="network">
				<span  class="sp-icon-'. $b . $class .'"><i></i></span>
				<div class="details">
					<div class="on">Follow us on</div>
					<div class="title">'. ( isset( $v[1] ) ? $v[1] : ucfirst( $b ) ) .'</div>
				</div>
			</a>';
		}

		echo $this->closeWidget( $args );
	}

}