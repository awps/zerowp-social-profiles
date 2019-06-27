<?php
namespace SocialProfiles\Create;

class ParseNetworks{
	private $net; 

	public function __construct( $networks = array() ){
		$this->net = $networks;
		$this->net = $this->parse();
	}

	public function getParsed(){
		return $this->net;
	}

	public function parse(){
		if( !empty($this->net) && is_array($this->net) ){
			$networks = $this->net;
			foreach ($networks as $brand_id => $brand) {

				// Set label...
				if( empty($brand['label']) ){
					$this->net[ $brand_id ]['label'] = __( 'Follow us on', 'zerowp-social-profiles' );
				}

				$url = !empty($brand['url']) ? $brand['url'] : false;
				
				// Unset this item if the URL is not set
				if( empty($url) ){
					unset( $this->net[ $brand_id ] );
				}

				// Parse special cases
				else{
					switch ($brand_id) {
						case 'skype':
						case 'icq':
							if( strpos( $url, $brand_id .':' ) === false ){
								$this->net[ $brand_id ]['url'] = strtolower( $brand_id ) . $url;
							}
							break;
						
						case 'viber':
							if( strpos( $url, $brand_id .':' ) === false ){
								$this->net[ $brand_id ]['url'] = strtolower( $brand_id ) .'://add?number='. $url;
							}
							break;
						
						case 'whatsapp':
							if( strpos( $url, $brand_id .':' ) === false ){
								$this->net[ $brand_id ]['url'] = strtolower( $brand_id ) .'://send?abid='. $url;
							}
							break;
						
						default:
							$this->net[ $brand_id ]['url'] = esc_url_raw( $url );
							break;
					}
				}

			}

			// The parsed result
			return $this->net;
		}
	}

	public function parseList( $settings ){
		$networks = $this->getParsed();

		$icon_size   = !empty( $settings[ 'icon_size' ] ) ? ' '. sanitize_html_class( $settings[ 'icon_size' ] ) : '';
		$icon_shape  = !empty( $settings[ 'icon_shape' ] ) ? ' '. sanitize_html_class( $settings[ 'icon_shape' ] ) : '';
		$icon_radius = !empty( $settings[ 'icon_radius' ] ) ? ' '. sanitize_html_class( $settings[ 'icon_radius' ] ) : '';
		$list_style  = !empty( $settings[ 'list_style' ] ) ? $settings[ 'list_style' ] : '';
		
		$class = $icon_size . $icon_shape . $icon_radius;

		$brands = ZSP()->brands();

		$output = '';

		if( !empty($networks) ){
			$output .= '<div class="zsp-zerowp-social-profiles-list">';
				if( 'icons_list' === $list_style ){
					foreach ( $networks as $net_name => $net) {
						$output .= '<a href="'. esc_attr( $net['url'] ) .'"> 
							<span class="sp-icon-'. $net_name . $class .'" 
								title="'. esc_attr( $brands[$net_name][1] ) .'">
								<i></i>
							</span>
						</a>';
					}
				}

				else{
					foreach ( $networks as $net_name => $net) {
						$output .= '<a href="'. esc_attr( $net['url'] ) .'" class="network'. $class .'">
							<span  class="sp-icon-'. $net_name . $class .'"><i></i></span>
							<div class="details">
								<div class="on">'. $net['label'] .'</div>
								<div class="title">'. esc_attr( $brands[$net_name][1] ) .'</div>
							</div>
						</a>';
					}
				}
			$output .= '</div>';
		}

		return $output;
	}
}