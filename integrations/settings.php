<?php
add_filter( 'cmplz_fields_load_types', 'cmplz_filter_integrations_field_types', 10, 1 );

function cmplz_filter_integrations_field_types( $fields ) {
	$fields = $fields + array(
			'thirdparty_scripts' => array(
				'source'                  => 'custom-scripts',
				'type'                    => 'textarea',
				'optional'                => true,
				'default'                 => '',
				'revoke_consent_onchange' => true,
				'placeholder'             => 'domain.com, domain.org',
				'label'                   => __( "(part of) URL's or unique string from the inline scripts of third-party scripts & plugins that should be blocked before consent.",
					'complianz-gdpr' ),
				'help'                    => sprintf( __( 'The most common third-party cookies are blocked automatically. If you want to block other third-party cookies, enter a unique part of the script source, or unique string from the inline script here, comma separated. For more information on this, please read %sthis%s article',
					'complianz-gdpr' ),
					'<a target="_blank" href="https://complianz.io/articles/blocking-custom-third-party-scripts">',
					'</a>' ),
				'table'                   => true,
			),

			'thirdparty_iframes' => array(
				'source'                  => 'custom-scripts',
				'type'                    => 'textarea',
				'optional'                => true,
				'default'                 => '',
				'placeholder'             => 'domain.com, domain.org',
				'revoke_consent_onchange' => true,
				'label'                   => __( "URL's from iFrames that should be blocked before consent.",
					'complianz-gdpr' ),
				'help'                    => sprintf( __( 'The most common third-party cookies are blocked automatically. If you want to block other third-party cookies, enter the iframe source here, comma separated. For more information on this, please read %sthis%s article',
					'complianz-gdpr' ),
					'<a target="_blank" href="https://complianz.io/articles/blocking-custom-third-party-scripts">',
					'</a>' ),
				'table'                   => true,

			),

			'statistics_script' => array(
				'source'                  => 'custom-scripts',
				'type'                    => 'javascript',
				'default'                 => '',
				'revoke_consent_onchange' => true,
				'label'                   => __( "Statistics script",
					'complianz-gdpr' ),
				'callback_condition'      => array(
					'compile_statistics' => 'NOT google-analytics,NOT matomo,NOT google-tag-manager,NOT no,NOT yes-anonymous',
				),
				'help'                    => __( 'Paste here all your scripts that activate cookies. Enter the scripts without the script tags',
						'complianz-gdpr' ) . '.&nbsp;'
				                             . sprintf( __( 'To be able to activate cookies when a user accepts the Cookie Policy, the scripts that are used for these cookies need to be entered here, without <script></script> tags. For more information on this, please read %sthis%s article',
						'complianz-gdpr' ),
						'<a target="_blank" href="https://complianz.io/articles/adding-scripts">',
						'</a>' ),
				'table'                   => true,
			),

			'cookie_scripts' => array(
				'source'                  => 'custom-scripts',
				'type'                    => 'javascript',
				'optional'                => true,
				'default'                 => '',
				'revoke_consent_onchange' => true,
				'label'                   => __( "Scripts to add services, for example, Facebook Pixel, Hotjar, etcetera.",
					'complianz-gdpr' ),
				'help'                    => __( "Paste here all your scripts that activate cookies. Enter the scripts without the script tags",
						'complianz-gdpr' ) . '&nbsp;'
				                             . sprintf( __( 'To be able to activate cookies when a user accepts the Cookie Policy, the scripts that are used for these cookies need to be entered here, without <script></script> tags. For more information on this, please read %sthis%s article',
						'complianz-gdpr' ),
						'<a target="_blank" href="https://complianz.io/articles/adding-scripts/">',
						'</a>' ),
				'table'                   => true,

			),

			'cookie_scripts_async' => array(
				'source'                  => 'custom-scripts',
				'type'                    => 'javascript',
				'optional'                => true,
				'default'                 => '',
				'revoke_consent_onchange' => true,
				'label'                   => __( "Async scripts to execute on consent",
					'complianz-gdpr' ),
				'help'                    => __( "Paste here all your scripts that activate cookies. Enter the scripts without the script tags",
						'complianz-gdpr' ) . '&nbsp;'
				                             . sprintf( __( 'To be able to activate cookies when a user accepts the Cookie Policy, the scripts that are used for these cookies need to be entered here, without <script></script> tags. For more information on this, please read %sthis%s article',
						'complianz-gdpr' ),
						'<a target="_blank" href="https://complianz.io/articles/adding-scripts/">',
						'</a>' ),
				'table'                   => true,
			),

		);

	return $fields;
}
