<?php 
namespace SocialProfiles\Widget;

class NetworksList extends AbstractWidget{
	
	public function settings(){
		return array(
			'id_base'   => 'zsp_social_profiles_list_widget',
			'name'      => __( 'Social Profiles List', 'social-profiles' ),
			'classname' => 'zsp-social-profiles-list',
		);
	}

	public function options( $instance ){
		
		$this->addField( 'title', 'text', $instance, array(
			'label'       => __( 'Title', 'social-profiles' ),
		));

		echo '<div class="zsp-widget-tabs-header">';
			echo '<span data-id="settings" class="wp-ui-text-highlight">'. __( 'Settings', 'social-profiles' ) .'</span>';
			echo '<span data-id="styles">'. __( 'Styles', 'social-profiles' ) .'</span>';
		echo '</div>';

		echo '<div class="zsp-widget-tabs-body">';
			echo '<div class="zsp-widget-tab zsp-grid settings active">';
				$this->addField( 'networks', 'networks', $instance, array(
					'options_label'   => __( 'Select network', 'social-profiles' ),
				));
			echo '</div>';

			echo '<div class="zsp-widget-tab zsp-grid styles">';
				$this->addField( 'icon_size', 'select', $instance, array(
					'label'   => __( 'Icon size', 'social-profiles' ),
					'options' => ZSP()->config( 'icon_size' ),
					'default' => 'large',
					'class' => 'widefat',
					'grid' => 6,
				));

				$this->addField( 'icon_shape', 'select', $instance, array(
					'label'   => __( 'Shape', 'social-profiles' ),
					'options' => ZSP()->config( 'icon_shape' ),
					'class' => 'widefat',
					'grid' => 6
				));

				$this->addField( 'icon_radius', 'select', $instance, array(
					'label'   => __( 'Radius', 'social-profiles' ),
					'options' => ZSP()->config( 'icon_radius' ),
					'default' => 'rounded',
					'class' => 'widefat',
					'grid' => 6
				));

				$this->addField( 'list_style', 'select', $instance, array(
					'label'   => __( 'List style', 'social-profiles' ),
					'options' => apply_filters( 'zsp_widget_networks_list_style', array(
						'networks_list' => __( 'Networks list', 'social-profiles' ),
						'icons_list'    => __( 'Icons list', 'social-profiles' ),
					)),
					'default' => 'rounded',
					'class' => 'widefat',
					'grid' => 6
				));	
			echo '</div>';
		echo '</div>';

		do_action( 'zsp_social_profiles_list_widget_options', $this );

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
		$networks    = !empty( $instance[ 'networks' ] ) && is_array($instance[ 'networks' ]) ? $instance[ 'networks' ] : false;

		/* Prepare icon class
		--------------------------*/
		$class = $icon_size . $icon_shape . $icon_radius;

		/* Short-circuit the widget output. Useful to pass a user function in `zsp_widget` action hook.
		----------------------------------------------------------------------------------------------------*/
		$custom_widget_output = apply_filters( 'zsp_custom_widget_list_output', false, $args, $instance );

		/* If a custom widget output is not available, display default
		-------------------------------------------------------------------*/
		$brands = ZSP()->brands();

		if( false === $custom_widget_output ){

			if( 'icons_list' === $list_style ){
				foreach ( $networks as $net_name => $net) {
					echo '<a href="'. esc_attr( $net['url'] ) .'"> 
						<span class="sp-icon-'. $net_name . $class .'" 
							title="'. esc_attr( $brands[$net_name][1] ) .'">
							<i></i>
						</span>
					</a>';
				}
			}
			
			else{
				foreach ( $networks as $net_name => $net) {
					echo '<a href="'. esc_attr( $net['url'] ) .'" class="network'. $class .'">
						<span  class="sp-icon-'. $net_name . $class .'"><i></i></span>
						<div class="details">
							<div class="on">'. ( !empty($net['label']) ? $net['label'] : __( 'Follow us on', 'social-profiles' ) ) .'</div>
							<div class="title">'. esc_attr( $brands[$net_name][1] ) .'</div>
						</div>
					</a>';
				}
			}

		}

		do_action( 'zsp_widget_networks_list', $args, $instance );

		echo $this->closeWidget( $args );
	}

}