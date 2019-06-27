<?php
namespace SocialProfiles\GeneralForm;

class FieldBrandsRepeater extends AbstractFieldType{

	public function show( $id, $settings, $saved_value ){
		$output = '';

		$brands = ZSP()->brands();

		$output .= '<input class="zsp-placeholder-field" type="hidden" value="" name="'. $id .'" />';

		$output .= '<div class="zsp-brands-selected-list '. (!empty( $settings[ 'no_label' ] ) ? 'no-label' : '') .'">';
			if( !empty($saved_value) && is_array($saved_value) ){
				foreach ($saved_value as $brand => $b) {
					$output .= '<div class="zsp-single-brand '. $brand .'">';

					$output .= '<div class="brand-label">'. esc_attr( $brands[ $brand ][1] ) .'</div>';

					$output .= '<input type="text" class="widefat" value="'. esc_attr( $b['url'] ) .'" ';
					$output .= 'name="'. $id .'['. $brand .'][url]" ';
					$output .= '/>';

					if( empty( $settings[ 'no_label' ] ) ){
						$label = !empty( $b['label'] ) ? esc_attr( $b['label'] ) : '';
						$output .= '<input type="text" class="widefat" value="'. $label .'" ';
						$output .= 'name="'. $id .'['. $brand .'][label]" placeholder="'. __( 'Follow us on', 'zerowp-social-profiles' ) .'" ';
						$output .= '/>';
					}

					$output .= apply_filters( 'zsp_delete_handle', '<span class="dashicons dashicons-dismiss zsp-delete-single-brand"></span>' );
					$output .= apply_filters( 'zsp_move_handle', '<span class="dashicons dashicons-menu zsp-move-single-brand"></span>' );
					$output .= '</div>';
				}
			}
		$output .= '</div>';

		$output .= '<select class="zsp-brands-dropdown" data-nameholder="'. $id .'">';

			if( !empty( $settings[ 'options_label' ] ) ){
				$output .= '<option value="">'. $settings[ 'options_label' ] .'</option>';
			}

			foreach ($brands as $brand => $b) {
				$output .= '<option value="'. $brand .'">'. $b[1] .'</option>';
			}
		$output .= '</select>';

		return $output;
	}

	public function sanitize( $data, $settings ){
		if( is_array( $data ) ){
			foreach ($data as $brand_id => $brand) {
				$url = strip_tags( $brand['url'] );

				if( empty($url) ){
					unset( $data[ $brand_id ] );
				}
				else{
					$data[ $brand_id ]['url'] = $url;
				}

			}
		}

		return $data;
	}

}
