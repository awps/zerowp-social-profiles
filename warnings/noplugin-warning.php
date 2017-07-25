<?php 
require_once ZSP_PATH . 'warnings/abstract-warning.php';

class ZSP_NoPlugin_Warning extends ZSP_Astract_Warning{

	public function notice(){
		
		$output = '';
		
		if( count( $this->data ) > 1 ){
			$message = __( 'Please install and activate the following plugins:', 'zerowp-social-profiles' );
		}
		else{
			$message = __( 'Please install and activate this plugin:', 'zerowp-social-profiles' );
		}

		$output .= '<h2>' . $message .'</h2>';


		$output .= '<ul class="zsp-required-plugins-list">';
			foreach ($this->data as $plugin_slug => $plugin) {
				$plugin_name = '<div class="zsp-plugin-info-title">'. $plugin['plugin_name'] .'</div>';

				if( !empty( $plugin['plugin_uri'] ) ){
					$button = '<a href="'. esc_url_raw( $plugin['plugin_uri'] ) .'" class="zsp-plugin-info-button" target="_blank">'. __( 'Get the plugin', 'zerowp-social-profiles' ) .'</a>';
				}
				else{
					$button = '<a href="#" onclick="return false;" class="zsp-plugin-info-button disabled">'. __( 'Get the plugin', 'zerowp-social-profiles' ) .'</a>';
				}

				$output .= '<li>'. $plugin_name . $button .'</li>';
			}
		$output .= '</ul>';

		return $output;
	}

}