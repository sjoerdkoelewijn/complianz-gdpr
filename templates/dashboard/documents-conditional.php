<?php defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
$docs = array(
	array(
		'title' => __("Privacy Statements", "complianz-gdpr"),
		'regions' => array('eu', 'us', 'uk', 'ca'),
	),
	array(
		'title' => __("Cookie Policy", 'complianz-gdpr'),
		'regions' => array('eu', 'us', 'uk', 'ca'),
	),
	array(
		'title' => __("Impressum", 'complianz-gdpr'),
		'regions' => array('eu'),
	),
	array(
		'title' => __("Do Not Sell My Info", 'complianz-gdpr'),
		'regions' => array('us'),
	),
	array(
		'title' => __("Privacy Statement for Children", 'complianz-gdpr'),
		'regions' => array('us', 'uk', 'ca'),
	),
	array(
		'title' => __("Disclaimer", 'complianz-gdpr'),
		'regions' => array('eu', 'us', 'uk', 'ca'),
	),
);

foreach ($docs as $doc) {
	if ($doc['title'] == __("Cookie Policy", "complianz-gdpr") || $doc['title'] == __("Impressum", 'complianz-gdpr')) continue;
	if (array_search($region, $doc['regions']) !== false) {
		$args = array(
			'status' => 'missing',
			'title' => $doc['title'],
			'page_exists' => '',
			'sync_icon' => cmplz_icon( 'documents-sync', 'disabled' ),
			'shortcode_icon' => cmplz_icon( 'documents-shortcode', 'disabled' ),
			'generated' => '<a href="https://complianz.io" target="_blank">'.__("Premium","complianz-gdpr").'</a>',
		);
		echo cmplz_get_template('dashboard/documents-row.php', $args);
	}
}

$args = array(
	'status' => '',
	'title' => '<h3>'.__("Other regions", "complianz-gdpr").'</h3>',
	'page_exists' => '',
	'sync_icon' => '',
	'shortcode_icon' => '',
	'generated' => '',
);
echo cmplz_get_template('dashboard/documents-row.php', $args);

foreach ($docs as $doc) {
	if (($key = array_search($region, $doc['regions'])) !== false) {
		unset($doc['regions'][$key]);
		$doc['regions'] = array_values($doc['regions']);
	}

	unset($doc['regions'][$region]);
	if (!empty($doc['regions'])) {
		$flag_1 = isset($doc['regions'][0]) ? cmplz_flag( $doc['regions'][0] , false ) : '';
		$flag_2 = isset($doc['regions'][1]) ? cmplz_flag( $doc['regions'][1] , false ) : '';
		$flag_3 = isset($doc['regions'][2]) ? cmplz_flag( $doc['regions'][2] , false ) : '';
		$args = array(
			'status' => 'missing',
			'title' => $doc['title'],
			'page_exists' => $flag_1,
			'sync_icon' => $flag_2,
			'shortcode_icon' => $flag_3,
			'generated' => '<a href="https://complianz.io" target="_blank">'.__("Premium","complianz-gdpr").'</a>',
		);
		echo cmplz_get_template('dashboard/documents-row.php', $args);
	}
}


