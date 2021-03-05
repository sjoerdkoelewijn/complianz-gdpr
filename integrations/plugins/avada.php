<?php
defined( 'ABSPATH' ) or die();

/**
 * Fire the load hook again
 */
function cmplz_fusion_initDomContentLoaded() {
	?>
	<script>
		jQuery(document).ready(function ($) {
			$(document).on("cmplzRunAfterAllScripts", cmplz_fusion_fire_domContentLoadedEvent);
			function cmplz_fusion_fire_domContentLoadedEvent() {
				dispatchEvent(new Event('load'));
			}
		});
	</script>
	<?php
}
add_action( 'wp_footer', 'cmplz_fusion_initDomContentLoaded' );

/**
 * Conditionally add the dependency
 * $deps['wait-for-this-script'] = 'script-that-should-wait';
 */

function cmplz_fusion_googlemaps_dependencies( $tags ) {
	$tags['maps.googleapis.com'] = 'infobox_packed.js';
	$tags['maps.googleapis.com'] = 'var map_fusion_map_';
	return $tags;
}
add_filter( 'cmplz_dependencies', 'cmplz_fusion_googlemaps_dependencies' );

/**
 * Add blocklist tags
 * @param array $tags
 *
 * @return array
 */
function cmplz_fusion_googlemaps_tags( $tags ) {
	$tags[] = 'maps.googleapis.com';
	$tags[] = 'var map_fusion_map_';
	$tags[] = 'infobox_packed.js';

	return $tags;
}
add_filter( 'cmplz_known_script_tags', 'cmplz_fusion_googlemaps_tags' );

/**
 * Add a placeholder to a div with class "my-maps-class"
 * @param $tags
 *
 * @return mixed
 */
function cmplz_fusionmaps_placeholder( $tags ) {
	$tags['google-maps'][] = "fusion-google-map";
	return $tags;
}
add_filter( 'cmplz_placeholder_markers', 'cmplz_fusionmaps_placeholder' );
