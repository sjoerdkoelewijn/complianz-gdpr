<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

$this->steps = apply_filters('cmplz_steps',array(
	'wizard' =>
		array(
			STEP_COMPANY => array(
				"id"    => "company",
				"title" => __( "General", 'complianz-gdpr' ),
				'sections' => array(
					1 => array(
						'title' => __( 'Visitors', 'complianz-gdpr' ),
						'intro' => '<p>'. sprintf(_x('The Complianz Wizard will guide you through the necessary steps to configure your website for privacy legislation around the world. Use the %smanual%s for this wizard if you need help during this process. You can also find a (?) after most questions for more info on this specific question.','intro first step', 'complianz-gdpr'),'<a target="_blank" href="https://complianz.io/manual/">', '</a>').'<br>'.
						           _x('The answers in the first step of the wizard are needed to configure your documents and consent banner specifically to your needs.','intro first step', 'complianz-gdpr').'</p><p>'.
						           sprintf(_x('Please note that you can always save and finish the wizard later, use our %sdocumentation%s for additional information or log a %ssupport ticket%s if you need our assistance.', 'intro first step', 'complianz-gdpr'),'<a target="_blank" href="https://complianz.io/documentation">', '</a>','<a target="_blank" href="https://complianz.io/support">', '</a>').'</p>',
					),
					2 => array(
						'id'    => 'general',
						'title' => __( 'Documents', 'complianz-gdpr' ),
						'intro' => '<p>'._x('Here you can select which legal documents you want to generate with Complianz. You can also use existing legal documents.', 'intro company info', 'complianz-gdpr').'</p>',
					),
					3 => array(
						'id' => 'impressum_info',
						'title' => __( 'Website information',
							'complianz-gdpr' ),
						'intro' => '<p>'._x( 'We need some information to be able to generate your documents.',
							'intro company info', 'complianz-gdpr' ).'</p>',
					),
					4 => array(
						'id' => 'impressum_info',
						'title' => __('Impressum', 'complianz-gdpr'),
						'region' => array('eu'),
					),
					6 => array(
						'title' => __( 'Purpose', 'complianz-gdpr' ),
					),
					8 => array(
						'region' => array( 'us' ),
						'id'     => 'details_per_purpose_us',
						'title'  => __( 'Details per purpose',
							'complianz-gdpr' ),
					),
					11 => array(
						'title' => __('Security & Consent', 'complianz-gdpr'),
					),
				),
			),

			STEP_COOKIES => array(
				"title"    => __( "Cookies", 'complianz-gdpr' ),
				"id"       => "cookies",
				'sections' => array(
					1 => array(
						'title' => __( 'Cookie scan', 'complianz-gdpr' ),
						'intro' =>
                            '<p>'._x( 'Complianz will scan several pages of your website for first-party cookies and known third-party scripts. The scan will be recurring monthly to keep you up-to-date!',
								'intro scan', 'complianz-gdpr' ). '</p>'
                            .'<p>'._x( 'For more information, ', 'complianz-gdpr').'<a href="TODO">'._x('read our 5 tips about the cookie scan.', 'complianz-gdpr').'</a></p>',
					),
					2 => array(
						'title' => __( 'Statistics', 'complianz-gdpr' ),
					),
					3 => array(
						'title' => __( 'Statistics - configuration',
							'complianz-gdpr' ),
					),
					4 => array(
						'title' => __( 'Integrations', 'complianz-gdpr' ),
					),

					5 => array(
						'title' => __( 'Used cookies', 'complianz-gdpr' ),
						'intro' => '<p>'.sprintf(_x( 'Complianz provides your Cookie Policy with comprehensive cookie descriptions, supplied by cookie database.org. We connect to this open-source database using an external API, which sends the results of the cookiescan (a list of found cookies, used plugins and your domain) to cookie database.org, for the sole purpose of providing you with accurate descriptions and keeping them up-to-date at a weekly schedule. For more information, please read %sthis article%s.',
                                    'complianz-gdpr' ).'</p>',
                                    '<a href="https://complianz.io/our-cookiedatabase-a-new-initiative/">', '</a>' )
					),
					6 => array(
						'title' => __( 'Used services', 'complianz-gdpr' ),
						'intro' => '<p>'._x( 'Below services use cookies on your website to add functionality. You can use cookiedatabase.org to synchronize information or edit the service if needed. Unknown services will be moderated and added by cookiedatabase.org as soon as possible.',
							'intro used cookies', 'complianz-gdpr' ).'</p>'
					),


				),
			),
			STEP_MENU    => array(
				"id"    => "menu",
				"title" => __( "Documents", 'complianz-gdpr' ),
				'intro' =>
					'<h1>' . _x( "Get ready to finish your configuration.",
						'intro menu', 'complianz-gdpr' ) . '</h1>' .
					'<p>'
					. _x( "Generate your documents, then you can add them to your menu directly or do it manually after the wizard is finished.",
						'intro menu', 'complianz-gdpr' ) . '</p>',
				'sections' => array(
					1 => array(
						'title' => __( 'Create documents', 'complianz-gdpr' ),
					),
					2 => array(
						'title' => __( 'Link to menu', 'complianz-gdpr' ),
					),
				),

			),
			STEP_FINISH  => array(
				"title" => __( "Finish", 'complianz-gdpr' ),
			),
		),
));
