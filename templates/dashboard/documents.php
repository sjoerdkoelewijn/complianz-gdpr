<?php defined( 'ABSPATH' ) or die( "you do not have access to this page!" );?>
<?php
	if (isset($_GET['region']) && array_key_exists( $_GET['region'], cmplz_get_regions(true) ) ) {
		$region = sanitize_title($_GET['region']);
	} else {
		$region = COMPLIANZ::$company->get_default_region();
	}
?>
<script>
	jQuery(document).ready(function ($) {
		'use strict';
		$(document).on('click', '.cmplz-open-shortcode', function () {
			$(this).closest('.cmplz-document').find('.cmplz-shortcode').show();
			$(this).closest('.cmplz-document').find('a').hide();
		});
		//@todo: find a way to hide the shortcode again, while maintaining copy capability (allow click)
		// $("div:not(.cmplz-document)").click(function(e){
		// 	e.preventDefault();
		// 	console.log("not click selectable");
		// 	console.log($(this));
		// 	$('.cmplz-document .cmplz-shortcode').each(function(){
		// 		$(this).hide();
		// 		$(this).closest('.cmplz-document').find('a').show();
		// 	});
		// });
	});
</script>
<div class="cmplz-documents">
	<?php
	if ( isset( COMPLIANZ::$config->pages[ $region ] ) ) {

		foreach ( COMPLIANZ::$config->pages[ $region ] as $type => $page ) {
			if ( ! $page['public'] ) {
				continue;
			}

			//get region of this page , and maybe add it to the title
			$region_img = '<img width="25px" height="5px" src="' . cmplz_url . '/assets/images/s.png">';

			if ( isset( $page['condition']['regions'] ) ) {
				$region = $page['condition']['regions'];
				$region = is_array( $region ) ? reset( $region ) : $region;
			}
			$title     = '<a href="' . get_permalink( COMPLIANZ::$document->get_shortcode_page_id( $type, $region ) ) . '">' . $page['title'] . '</a>';
			$shortcode = COMPLIANZ::$document->get_shortcode( $type, $region, $force_classic = true );
			$title     .= '<div class="cmplz-selectable cmplz-shortcode">' . $shortcode . '</div>';
			$generated = $checked_date = date( get_option( 'date_format' ), get_option( 'cmplz_documents_update_date' ) );

			if ( COMPLIANZ::$document->page_exists( $type, $region ) ) {
				if ( ! COMPLIANZ::$document->page_required( $page, $region ) ) {
					$args = array(
						'status' => 'error',
						'title' => $title,
						'page_exists' => '',
						'sync_icon' => '',
						'shortcode_icon' => '',
						'generated' => __( "Obsolete", 'complianz-gdpr' ),
					);
				} else {
					$sync_status = COMPLIANZ::$document->syncStatus( COMPLIANZ::$document->get_shortcode_page_id( $type, $region ) );
					$status = $sync_status === 'sync' ? "success" : "disabled";
					$sync_icon = cmplz_icon( 'documents-sync', $status );
					$shortcode_icon = cmplz_icon( 'documents-shortcode', $status );
					if ( $sync_status === 'sync' ) {
						$shortcode_icon = '<span class="cmplz-open-shortcode">' . $shortcode_icon . '</span>';
					}

					$page_exists = cmplz_icon('bullet', 'success');
					$args = array(
						'status' => $status,
						'title' => $title,
						'page_exists' => $page_exists,
						'sync_icon' => $sync_icon,
						'shortcode_icon' => $shortcode_icon,
						'generated' => $generated,
					);
				}

			} elseif ( COMPLIANZ::$document->page_required( $page, $region ) ) {
				$args = array(
						'status' => 'missing',
						'title' => $page['title'],
						'page_exists' => '',
						'sync_icon' => cmplz_icon('bullet', 'disabled'),
						'shortcode_icon' => '',
						'generated' => __( "missing", 'complianz-gdpr' ),
				);
			}
			echo cmplz_get_template('dashboard/documents-row.php', $args);
		}
	}

 	require_once( apply_filters('cmplz_free_templates_path', cmplz_path . 'templates/' ) .'dashboard/documents-conditional.php'); ?>
</div>
