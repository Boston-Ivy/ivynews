<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TitanFrameworkOptionMapCoordinates extends TitanFrameworkOption {

	public $defaultSecondarySettings = array(
		'coord_lat' => '10',
	);

	/*
	 * Display for options and meta
	 */
	public function display() {
		$this->echoOptionHeader();
		
		//print_r($this->settings);
		?>		
		<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true'></script>
		<script>

			var geocoder;
			var map;
			
			function initialize() {
				geocoder = new google.maps.Geocoder();
				var latlng = new google.maps.LatLng(-34.397, 150.644);
			}
		
			function codeAddress() {
				var address = document.getElementById('wapp_map_coordinates').value;
				geocoder.geocode( { 'address': address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						
						var lat = document.getElementById('wapp_lat').value = results[0].geometry.location.lat();
						var long = document.getElementById('wapp_lon').value = results[0].geometry.location.lng();
						
					} else {
						alert('Geocode was not successful for the following reason: ' + status);
					}
				});
			}
			
			google.maps.event.addDomListener(window, 'load', initialize);
		</script>
		
		<?php

		// The default value (nothing is selected)
				
		printf("<input class=\"regular-text\" name=\"%s\" placeholder=\"%s\" maxlength=\"%s\" id=\"%s\" type=\"textbox\" value=\"%s\"\> %s",
			$this->getID(),
			$this->settings['placeholder'],
			$this->settings['maxlength'],
			$this->getID(),
			esc_attr( $this->getValue() ), 
			$this->settings['unit'] 
		);
		echo '<input type="button" value="Geocode" onclick="codeAddress()" class="button button-primary">';

		$this->echoOptionFooter();
	}

	/*
	 * Display for theme customizer
	 */
	public function registerCustomizerControl( $wp_customize, $section, $priority = 1 ) {
		$wp_customize->add_control( new TitanFrameworkOptionMapCoordinates( $wp_customize, $this->getID(), array(
			'coord_lat' => $this->settings['coord_lat'],
			'settings' => $this->getID(),
			'priority' => $priority,
		) ) );
	}
}
