<?php
defined( 'ABSPATH' ) or die( "you do not have acces to this page!" );

/**
 * Install cookiebanner table
 * */

add_action( 'plugins_loaded', 'cmplz_install_cookiebanner_table', 10 );
function cmplz_install_cookiebanner_table() {
	if ( get_option( 'cmplz_cbdb_version' ) !== cmplz_version ) {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . 'cmplz_cookiebanners';
		$sql        = "CREATE TABLE $table_name (
             `ID` int(11) NOT NULL AUTO_INCREMENT,
             `banner_version` int(11) NOT NULL,
             `default` int(11) NOT NULL,
             `archived` int(11) NOT NULL,
             `title` varchar(255) NOT NULL,
            `position` varchar(255) NOT NULL,
            `theme` varchar(255) NOT NULL,
            `checkbox_style` varchar(255) NOT NULL,
            `revoke` varchar(255) NOT NULL,
            `header` varchar(255) NOT NULL,
            `dismiss` varchar(255) NOT NULL,
            `save_preferences` varchar(255) NOT NULL,
            `view_preferences` varchar(255) NOT NULL,
            `accept_all` varchar(255) NOT NULL,
            `category_functional` varchar(255) NOT NULL,
            `category_all` varchar(255) NOT NULL,
            `category_stats` varchar(255) NOT NULL,
            `category_prefs` varchar(255) NOT NULL,
            `accept` varchar(255) NOT NULL,
            `message_optin` text NOT NULL,
            `readmore_optin` varchar(255) NOT NULL,
            `use_categories` varchar(255) NOT NULL,
            `tagmanager_categories` text NOT NULL,
            `use_categories_optinstats` varchar(255) NOT NULL,
            `hide_revoke` int(11) NOT NULL,
            `disable_cookiebanner` int(11) NOT NULL,
            `banner_width` int(11) NOT NULL,
            `soft_cookiewall` int(11) NOT NULL,
            `dismiss_on_scroll` int(11) NOT NULL,
            `dismiss_on_timeout` int(11) NOT NULL,
            `dismiss_timeout` varchar(255) NOT NULL,
            `accept_informational` varchar(255) NOT NULL,
            `message_optout` text NOT NULL,
            `readmore_optout` varchar(255) NOT NULL,
            `readmore_optout_dnsmpi` varchar(255) NOT NULL,
            `readmore_privacy` varchar(255) NOT NULL,
            `readmore_impressum` varchar(255) NOT NULL,
            `use_custom_cookie_css` varchar(255) NOT NULL,
            `custom_css` text NOT NULL,
            `custom_css_amp` text NOT NULL,
            `statistics` text NOT NULL,
            `colorpalette_background` text NOT NULL,
            `colorpalette_text` text NOT NULL,
            `colorpalette_toggles` text NOT NULL,
            `colorpalette_border_radius` text NOT NULL,
            `colorpalette_border_width` text NOT NULL,
            `colorpalette_button_accept` text NOT NULL,
            `colorpalette_button_deny` text NOT NULL,
            `colorpalette_button_settings` text NOT NULL,
            `colorpalette_buttons_border_radius` text NOT NULL,
            `animation` text NOT NULL,
            `use_box_shadow` int(11) NOT NULL,
              PRIMARY KEY  (ID)
            ) $charset_collate;";
		dbDelta( $sql );
		update_option( 'cmplz_cbdb_version', cmplz_version );

	}
}

if ( ! class_exists( "cmplz_cookiebanner" ) ) {
	class CMPLZ_COOKIEBANNER {
		public $id = false;
		public $banner_version = 0;
		public $title;
		public $default = false;
		public $archived = false;

		/* styling */
		public $position;
		public $theme;
		public $checkbox_style;
		public $use_custom_cookie_css;
		public $custom_css;
		public $custom_css_amp;
        public $colorpalette_background;
        public $colorpalette_text;
        public $colorpalette_toggles;
        public $colorpalette_border_radius;
        public $colorpalette_border_width;
        public $colorpalette_button_accept;
        public $colorpalette_button_deny;
        public $colorpalette_button_settings;
        public $colorpalette_buttons_border_radius;
        public $animation;
        public $use_box_shadow;

		/* texts */
        public $header;
		public $revoke;
		public $dismiss;
		public $accept;
		public $message_optin;
		public $readmore_optin;
		public $accept_informational;
		public $message_optout;
		public $readmore_optout;
		public $readmore_optout_dnsmpi;
		public $readmore_privacy;
		public $readmore_impressum;
		public $tagmanager_categories;
		public $save_preferences;
		public $accept_all;
		public $view_preferences;
		public $category_functional;
		public $category_all;
		public $category_stats;
		public $category_prefs;
		public $use_categories;

		public $use_categories_optinstats;
		public $hide_revoke;
		public $disable_cookiebanner;
		public $banner_width;
		public $soft_cookiewall;
		public $dismiss_on_scroll;
		public $dismiss_on_timeout;
		public $dismiss_timeout;

		public $save_preferences_x;
		public $accept_all_x;
		public $view_preferences_x;
		public $category_functional_x;
		public $category_all_x;
		public $category_stats_x;
		public $category_prefs_x;
		public $accept_x;
		public $dismiss_x;
		public $revoke_x;
		public $message_optin_x;
		public $readmore_optin_x;
		public $accept_informational_x;
		public $message_optout_x;
		public $readmore_optout_x;
		public $readmore_optout_dnsmpi_x;
		public $readmore_privacy_x;
		public $readmore_impressum_x;
		public $translation_id;

		public $statistics;
		public $set_defaults;




        function __construct( $id = false, $set_defaults = true ) {

			$this->translation_id = $this->get_translation_id();
			$this->id = $id;
			$this->set_defaults = $set_defaults;

			if ( $this->id !== false ) {
				//initialize the cookiebanner settings with this id.
				$this->get();
			}

		}





		/**
		 * Add a new cookiebanner database entry
		 */

		private function add() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return false;
			}
			$array = array(
				'title' => __( 'New cookie banner', 'complianz-gdpr' )
			);

			global $wpdb;
			//make sure we have at least one default banner
			$cookiebanners
				= $wpdb->get_results( "select * from {$wpdb->prefix}cmplz_cookiebanners as cb where cb.default = true" );
			if ( empty( $cookiebanners ) ) {
				$array['default'] = true;
			}

			$wpdb->insert(
				$wpdb->prefix . 'cmplz_cookiebanners',
				$array
			);
			$this->id = $wpdb->insert_id;

		}


		public function process_form( $post ) {

			if ( ! current_user_can( 'manage_options' ) ) {
				return false;
			}

			if ( ! isset( $post['cmplz_nonce'] ) ) {
				return false;
			}

			//check nonce
			if ( ! isset( $post['cmplz_nonce'] )
			     || ! wp_verify_nonce( $post['cmplz_nonce'],
					'complianz_save' )
			) {
				return false;
			}

			foreach ( $this as $property => $value ) {
				if ( isset( $post[ 'cmplz_' . $property ] ) ) {
					$this->{$property} = $post[ 'cmplz_' . $property ];
				}
			}

			$this->save();
		}

		/**
		 * Load the cookiebanner data
		 * If ID has value 'default', we get the one with the value 'default'
		 */

		private function get() {
			global $wpdb;

			if ( ! intval( $this->id ) > 0 ) {
				return;
			}

			$cookiebanners
				= $wpdb->get_results( $wpdb->prepare( "select * from {$wpdb->prefix}cmplz_cookiebanners where ID = %s",
				intval( $this->id ) ) );

			if ( isset( $cookiebanners[0] ) ) {
				$cookiebanner         = $cookiebanners[0];
				$this->banner_version = $cookiebanner->banner_version;
				$this->title          = $cookiebanner->title;
				$this->default        = $cookiebanner->default;
				$this->archived       = $cookiebanner->archived;
				$this->position       = ! empty( $cookiebanner->position )
					? $cookiebanner->position
					: $this->get_default( 'position' );
				$this->theme          = ! empty( $cookiebanner->theme )
					? $cookiebanner->theme : $this->get_default( 'theme' );
				$this->checkbox_style          = ! empty( $cookiebanner->checkbox_style )
					? $cookiebanner->checkbox_style : $this->get_default( 'checkbox_style' );
				$this->revoke         = ! empty( $cookiebanner->revoke )
					? $cookiebanner->revoke : $this->get_default( 'revoke' );
				$this->header     = ! empty( $cookiebanner->header )
                    ? $cookiebanner->header
                    : $this->get_default('header');
				$this->dismiss        = ! empty( $cookiebanner->dismiss )
					? $cookiebanner->dismiss : $this->get_default( 'dismiss' );
				$this->save_preferences = ! empty( $cookiebanner->save_preferences )
					? $cookiebanner->save_preferences
					: $this->get_default( 'save_preferences' );

				$this->accept_all = ! empty( $cookiebanner->accept_all )
					? $cookiebanner->accept_all
					: $this->get_default( 'accept_all' );
				$this->view_preferences = ! empty( $cookiebanner->view_preferences )
					? $cookiebanner->view_preferences
					: $this->get_default( 'view_preferences' );
				$this->category_functional
				                      = ! empty( $cookiebanner->category_functional )
					? $cookiebanner->category_functional
					: $this->get_default( 'category_functional' );
				$this->category_all
				                      = ! empty( $cookiebanner->category_all )
					? $cookiebanner->category_all
					: $this->get_default( 'category_all' );
				$this->category_stats = ! empty( $cookiebanner->category_stats )
					? $cookiebanner->category_stats
					: $this->get_default( 'category_stats' );
				$this->category_prefs = ! empty( $cookiebanner->category_prefs )
					? $cookiebanner->category_prefs
					: $this->get_default( 'category_prefs' );
				$this->accept         = ! empty( $cookiebanner->accept )
					? $cookiebanner->accept : $this->get_default( 'accept' );
				$this->message_optin = ! empty( $cookiebanner->message_optin )
					? $cookiebanner->message_optin
					: $this->get_default( 'message_optin' );
				$this->readmore_optin = ! empty( $cookiebanner->readmore_optin )
					? $cookiebanner->readmore_optin
					: $this->get_default( 'readmore_optin' );

				$this->use_categories = ! empty( $cookiebanner->use_categories )
					? $cookiebanner->use_categories
					: $this->get_default( 'use_categories' );
				$this->tagmanager_categories = ! empty( $cookiebanner->tagmanager_categories )
					? $cookiebanner->tagmanager_categories
					: $this->get_default( 'tagmanager_categories' );

				$this->use_categories_optinstats = ! empty( $cookiebanner->use_categories_optinstats )
					?  $cookiebanner->use_categories_optinstats
					: $this->get_default( 'use_categories_optinstats' );

				$this->hide_revoke = ! empty( $cookiebanner->hide_revoke )
					? $cookiebanner->hide_revoke
					: $this->get_default( 'hide_revoke' );
				$this->disable_cookiebanner = ! empty( $cookiebanner->disable_cookiebanner )
                    ? $cookiebanner->disable_cookiebanner
                    : $this->get_default('disable_cookiebanner');
				$this->banner_width = ! empty( $cookiebanner->banner_width )
					? $cookiebanner->banner_width
					: $this->get_default( 'banner_width' );
				$this->soft_cookiewall = ! empty( $cookiebanner->soft_cookiewall )
					? $cookiebanner->soft_cookiewall
					: $this->get_default( 'soft_cookiewall' );
				$this->dismiss_on_scroll = ! empty( $cookiebanner->dismiss_on_scroll )
					? $cookiebanner->dismiss_on_scroll
					: $this->get_default( 'dismiss_on_scroll' );
				$this->dismiss_on_timeout = ! empty( $cookiebanner->dismiss_on_timeout )
					? $cookiebanner->dismiss_on_timeout
					: $this->get_default( 'dismiss_on_timeout' );
				$this->dismiss_timeout = ! empty( $cookiebanner->dismiss_timeout )
					? $cookiebanner->dismiss_timeout
					: $this->get_default( 'dismiss_timeout' );
				$this->accept_informational = ! empty( $cookiebanner->accept_informational )
					? $cookiebanner->accept_informational
					: $this->get_default( 'accept_informational' );
				$this->message_optout = ! empty( $cookiebanner->message_optout )
					? $cookiebanner->message_optout
					: $this->get_default( 'message_optout' );
				$this->readmore_optout = ! empty( $cookiebanner->readmore_optout )
					? $cookiebanner->readmore_optout
					: $this->get_default( 'readmore_optout' );
				$this->readmore_optout_dnsmpi = ! empty( $cookiebanner->readmore_optout_dnsmpi )
					? $cookiebanner->readmore_optout_dnsmpi
					: $this->get_default( 'readmore_optout_dnsmpi' );
				$this->readmore_privacy = ! empty( $cookiebanner->readmore_privacy )
					? $cookiebanner->readmore_privacy
					: $this->get_default( 'readmore_privacy' );
				$this->readmore_impressum
					= ! empty( $cookiebanner->readmore_impressum )
					? $cookiebanner->readmore_impressum
					: $this->get_default( 'readmore_impressum' );
				$this->use_custom_cookie_css
				                   = ! empty( $cookiebanner->use_custom_cookie_css )
					? $cookiebanner->use_custom_cookie_css
					: $this->get_default( 'use_custom_cookie_css' );
				$this->custom_css
				                   = ! empty( $cookiebanner->custom_css )
					? htmlspecialchars_decode( $cookiebanner->custom_css )
					: $this->get_default( 'custom_css' );
				$this->custom_css_amp
				                   = ! empty( $cookiebanner->custom_css_amp )
					? htmlspecialchars_decode( $cookiebanner->custom_css_amp )
					: $this->get_default( 'custom_css_amp' );

                $this->colorpalette_background = ! empty( $cookiebanner->colorpalette_background )
                    ? unserialize( $cookiebanner->colorpalette_background )
                    : $this->get_default( 'colorpalette_background' );
                $this->colorpalette_text = ! empty( $cookiebanner->colorpalette_text )
                    ? unserialize( $cookiebanner->colorpalette_text )
                    : $this->get_default( 'colorpalette_text' );
                $this->colorpalette_toggles = ! empty( $cookiebanner->colorpalette_toggles )
                    ? unserialize( $cookiebanner->colorpalette_toggles )
                    : $this->get_default( 'colorpalette_toggles' );
                $this->colorpalette_border_radius = ! empty( $cookiebanner->colorpalette_border_radius )
                    ? unserialize( $cookiebanner->colorpalette_border_radius )
                    : $this->get_default( 'colorpalette_border_radius' );
                $this->colorpalette_border_width = ! empty( $cookiebanner->colorpalette_border_width )
                    ? unserialize( $cookiebanner->colorpalette_border_width )
                    : $this->get_default( 'colorpalette_border_width' );
                $this->colorpalette_button_accept = ! empty( $cookiebanner->colorpalette_button_accept )
                    ? unserialize( $cookiebanner->colorpalette_button_accept )
                    : $this->get_default( 'colorpalette_button_accept' );
                $this->colorpalette_button_deny = ! empty( $cookiebanner->colorpalette_button_deny )
                    ? unserialize( $cookiebanner->colorpalette_button_deny )
                    : $this->get_default( 'colorpalette_button_deny' );
                $this->colorpalette_button_settings = ! empty( $cookiebanner->colorpalette_button_settings )
                    ? unserialize( $cookiebanner->colorpalette_button_settings )
                    : $this->get_default( 'colorpalette_button_settings' );
                $this->colorpalette_buttons_border_radius = ! empty( $cookiebanner->colorpalette_buttons_border_radius )
                    ? unserialize( $cookiebanner->colorpalette_buttons_border_radius )
                    : $this->get_default( 'colorpalette_buttons_border_radius' );

                $this->animation = ! empty( $cookiebanner->animation )
                    ? $cookiebanner->animation
                    : $this->get_default( 'animation' );
                $this->use_box_shadow  = ! empty( $cookiebanner->use_box_shadow)
                    ? $cookiebanner->use_box_shadow
                    : $this->get_default('use_box_shadow');

				//translated fields
				$this->save_preferences_x
					= $this->translate( $this->save_preferences,
					'save_preferences' );
				$this->accept_all_x
					= $this->translate( $this->accept_all,
					'accept_all' );
				$this->view_preferences_x
					= $this->translate( $this->view_preferences,
					'view_preferences' );
				$this->category_functional_x
					= $this->translate( $this->category_functional,
					'category_functional' );
				$this->category_all_x
					= $this->translate( $this->category_all, 'category_all' );
				$this->category_stats_x
					= $this->translate( $this->category_stats,
					'category_stats' );
				$this->category_prefs_x
					= $this->translate( $this->category_prefs,
					'category_prefs' );
				$this->accept_x
					= $this->translate( $this->accept, 'accept' );
				$this->revoke_x
					= $this->translate( $this->revoke, 'revoke' );
				$this->dismiss_x
					= $this->translate( $this->dismiss, 'dismiss' );
				$this->message_optin_x
					= $this->translate( $this->message_optin, 'message_optin' );
				$this->readmore_optin_x
					= $this->translate( $this->readmore_optin,
					'readmore_optin' );
				$this->accept_informational_x
					= $this->translate( $this->accept_informational,
					'accept_informational' );
				$this->message_optout_x
					= $this->translate( $this->message_optout,
					'message_optout' );
				$this->readmore_optout_x
					= $this->translate( $this->readmore_optout,
					'readmore_optout' );
				$this->readmore_optout_dnsmpi_x
					= $this->translate( $this->readmore_optout_dnsmpi,
					'readmore_optout_dnsmpi' );
				$this->readmore_privacy_x
					= $this->translate( $this->readmore_privacy,
					'readmore_privacy' );
				$this->readmore_impressum_x
					= $this->translate( $this->readmore_impressum,
					'readmore_impressum' );

				$this->statistics = unserialize( $cookiebanner->statistics );

				/**
				 * Fallback if upgrade didn't complete successfully
				 */

				if ( $this->set_defaults ) {
					if ($this->use_categories === true ) {
						$this->use_categories = 'legacy';
					} elseif ( $this->use_categories === false ) {
						$this->use_categories = 'no';
					}
					if ($this->use_categories_optinstats  === true) {
						$this->use_categories_optinstats = 'legacy';
					} elseif ( $this->use_categories_optinstats === false ) {
						$this->use_categories_optinstats = 'no';
					}
				}

			}

		}

		/**
		 * Check if this field is translatable
		 *
		 * @param $fieldname
		 *
		 * @return bool
		 */

		private function translate( $value, $fieldname ) {
			$key = $this->translation_id;

			if ( function_exists( 'pll__' ) ) {
				$value = pll__( $value );
			}

			if ( function_exists( 'icl_translate' ) ) {
				$value = icl_translate( 'complianz', $fieldname . $key,
					$value );
			}

			$value = apply_filters( 'wpml_translate_single_string', $value,
				'complianz', $fieldname . $key );

			return $value;

		}

		private function register_translation( $string, $fieldname ) {
			$key = $this->translation_id;
			//polylang
			if ( function_exists( "pll_register_string" ) ) {
				pll_register_string( $fieldname . $key, $string, 'complianz' );
			}

			//wpml
			if ( function_exists( 'icl_register_string' ) ) {
				icl_register_string( 'complianz', $fieldname . $key, $string );
			}

			do_action( 'wpml_register_single_string', 'complianz', $fieldname,
				$string );

		}

		/**
		 * Get a prefix for translation registration
		 * For backward compatibility we don't use a key when only one banner, or when the lowest.
		 * If we don't use this, all field names from each banner will be the same, registering won't work.
		 *
		 * @return string
		 */

		public function get_translation_id() {
			//if this is the banner with the lowest ID's, no ID
			global $wpdb;
			$lowest = $wpdb->get_var( "select min(ID) from {$wpdb->prefix}cmplz_cookiebanners" );
			if ( $lowest == $this->id ) {
				return '';
			} else {
				return $this->id;
			}
		}

		/**
		 * Get a default value
		 *
		 * @param $fieldname
		 *
		 * @return string
		 */

		private function get_default( $fieldname ) {
			if (!$this->set_defaults) return false;

			$default
				= ( isset( COMPLIANZ::$config->fields[ $fieldname ]['default'] ) )
				? COMPLIANZ::$config->fields[ $fieldname ]['default'] : '';

			return $default;
		}


		/**
		 * Save the edited data in the object
		 *
		 * @param bool $is_default
		 *
		 * @return void
		 */

		public function save() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( ! $this->id ) {
				$this->add();
			}

			$this->banner_version ++;

			//register translations fields
			$this->register_translation( $this->save_preferences, 'save_preferences' );
			$this->register_translation( $this->accept_all, 'accept_all' );
			$this->register_translation( $this->view_preferences, 'view_preferences' );
			$this->register_translation( $this->category_functional, 'category_functional' );
			$this->register_translation( $this->category_all, 'category_all' );
			$this->register_translation( $this->category_stats, 'category_stats' );
			$this->register_translation( $this->category_prefs, 'category_prefs' );
			$this->register_translation( $this->accept, 'accept' );
			$this->register_translation( $this->revoke, 'revoke' );
			$this->register_translation( $this->dismiss, 'dismiss' );
			$this->register_translation( $this->message_optin, 'message_optin' );
			$this->register_translation( $this->readmore_optin, 'readmore_optin' );
			$this->register_translation( $this->accept_informational, 'accept_informational' );
			$this->register_translation( $this->message_optout, 'message_optout' );
			$this->register_translation( $this->readmore_optout, 'readmore_optout' );
			$this->register_translation( $this->readmore_optout_dnsmpi, 'readmore_optout_dnsmpi' );
			$this->register_translation( $this->readmore_privacy, 'readmore_privacy' );
			$this->register_translation( $this->readmore_impressum, 'readmore_impressum' );

			if ( ! is_array( $this->statistics ) ) {
				$this->statistics = array();
			}
			$statistics   = serialize( $this->statistics );
			$update_array = array(
				'position'                  => sanitize_title( $this->position ),
				'banner_version'            => intval( $this->banner_version ),
				'archived'                  => boolval( $this->archived ),
				'title'                     => sanitize_text_field( $this->title ),
				'theme'                     => sanitize_title( $this->theme ),
				'checkbox_style'            => sanitize_title( $this->checkbox_style ),
				'revoke'                    => sanitize_text_field( $this->revoke ),
				'header'                    => sanitize_text_field( $this->header ),
				'dismiss'                   => sanitize_text_field( $this->dismiss ),
				'save_preferences'          => sanitize_text_field( $this->save_preferences ),
				'accept_all'                => sanitize_text_field( $this->accept_all ),
				'view_preferences'          => sanitize_text_field( $this->view_preferences ),
				'category_functional'       => sanitize_text_field( $this->category_functional ),
				'category_all'              => sanitize_text_field( $this->category_all ),
				'category_stats'            => sanitize_text_field( $this->category_stats ),
				'category_prefs'            => sanitize_text_field( $this->category_prefs ),
				'accept'                    => sanitize_text_field( $this->accept ),
				'message_optin'             => wp_kses( $this->message_optin, cmplz_allowed_html() ),
				'readmore_optin'            => sanitize_text_field( $this->readmore_optin ),
				'use_categories'            => sanitize_text_field( $this->use_categories ),
				'use_categories_optinstats' => sanitize_text_field( $this->use_categories_optinstats ),
				'tagmanager_categories'     => sanitize_text_field( $this->tagmanager_categories ),
				'hide_revoke'               => sanitize_title( $this->hide_revoke ),
				'disable_cookiebanner'      => sanitize_title( $this->disable_cookiebanner ),
				'banner_width'              => intval( $this->banner_width ),
				'soft_cookiewall'           => sanitize_title( $this->soft_cookiewall ),
				'dismiss_on_scroll'         => boolval( $this->dismiss_on_scroll ),
				'dismiss_on_timeout'        => boolval( $this->dismiss_on_timeout ),
				'dismiss_timeout'           => intval( $this->dismiss_timeout ),
				'accept_informational'      => sanitize_text_field( $this->accept_informational ),
				'message_optout'            => wp_kses( $this->message_optout, cmplz_allowed_html() ),
				'readmore_optout'           => sanitize_text_field( $this->readmore_optout ),
				'readmore_optout_dnsmpi'    => sanitize_text_field( $this->readmore_optout_dnsmpi ),
				'readmore_privacy'          => sanitize_text_field( $this->readmore_privacy ),
				'readmore_impressum'        => sanitize_text_field( $this->readmore_impressum ),
				'use_custom_cookie_css'         => boolval( $this->use_custom_cookie_css ),
				'statistics'                    => $statistics,
                'colorpalette_background'              => $this->sanitize_hex_array( $this->colorpalette_background ),
                'colorpalette_text'                    => $this->sanitize_hex_array( $this->colorpalette_text ),
                'colorpalette_toggles'                 => $this->sanitize_hex_array( $this->colorpalette_toggles ),
                'colorpalette_border_radius'    => $this->sanitize_int_array( $this->colorpalette_border_radius ),
                'colorpalette_border_width'     => $this->sanitize_int_array( $this->colorpalette_border_width ),
                'colorpalette_button_accept'                  => $this->sanitize_hex_array( $this->colorpalette_button_accept ),
                'colorpalette_button_deny'                    => $this->sanitize_hex_array( $this->colorpalette_button_deny ),
                'colorpalette_button_settings'                => $this->sanitize_hex_array( $this->colorpalette_button_settings ),
                'colorpalette_buttons_border_radius'   => $this->sanitize_int_array( $this->colorpalette_buttons_border_radius ),
                'animation'                 => sanitize_title( $this->animation ),
                'use_box_shadow'            => sanitize_title( $this->use_box_shadow ),
			);



			if ( $this->use_custom_cookie_css ) {
				$update_array['custom_css']
					= htmlspecialchars( $this->custom_css );
				$update_array['custom_css_amp']
					= htmlspecialchars( $this->custom_css_amp );
			}

			global $wpdb;
			$updated = $wpdb->update( $wpdb->prefix . 'cmplz_cookiebanners',
				$update_array,
				array( 'ID' => $this->id )
			);

			if ( $updated === 0 ) {
				if ( !get_option( 'cmplz_generate_new_cookiepolicy_snapshot') ) update_option( 'cmplz_generate_new_cookiepolicy_snapshot', time() );
			}

			//get database value for "default"
			$db_default
				= $wpdb->get_var( $wpdb->prepare( "select cdb.default from {$wpdb->prefix}cmplz_cookiebanners as cdb where cdb.ID=%s",
				$this->id ) );
			if ( $this->default && ! $db_default ) {
				$this->enable_default();
			} elseif ( ! $this->default && $db_default ) {
				$this->remove_default();
			}

		}

		/**
		 * Sanitize an array or string as hex
		 * @param array|string $hex
		 *
		 * @return string|array
		 */
		public function sanitize_hex_array( $hex ) {
			if ( is_array($hex) ) {
				$hex = serialize( array_map('sanitize_hex_color', $hex ) );
			} else {
				$hex = sanitize_hex_color($hex);
			}
			return $hex;
		}

		/**
		 * Sanitize an array or int as int
		 * @param array|int $hex
		 *
		 * @return int|array
		 */
		public function sanitize_int_array( $int ) {
			if ( is_array($int) ) {
				$int = serialize( array_map('intval', $int ) );
			} else {
				$int = intval($int);
			}
			return $int;
		}

		/**
		 * santize the css to remove any commented or empty classes
		 *
		 * @param string $css
		 *
		 * @return string
		 */

		private function sanitize_custom_css( $css ) {
			$css = preg_replace( '/\/\*(.|\s)*?\*\//i', '', $css );
			$css = str_replace( array(
				'.cmplz-slider-checkbox{}',
				'.cmplz-soft-cookiewall{}',
				'.cc-window .cc-check{}',
				'.cc-btn{}',
				'.cc-category{}',
				'.cc-message{}',
				'.cc-revoke{}',
				'.cc-dismiss{}',
				'.cc-allow{}',
				'.cc-accept-all{}',
				'.cc-window{}'
			), '', $css );
			$css = trim( $css );

			return $css;
		}

		/**
		 * Delete a cookie variation
		 *
		 * @return bool $success
		 * @since 2.0
		 */

		public function delete( $force = false ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return false;
			}

			$error = false;
			global $wpdb;

			//do not delete the last one.
			$count
				= $wpdb->get_var( "select count(*) as count from {$wpdb->prefix}cmplz_cookiebanners" );
			if ( $count == 1 && ! $force ) {
				$error = true;
			}

			if ( ! $error ) {
				if ( $this->default ) {
					$this->remove_default();
				}

				$wpdb->delete( $wpdb->prefix . 'cmplz_cookiebanners', array(
					'ID' => $this->id,
				) );

				//clear all statistics regarding this banner
				$sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}cmplz_statistics SET cookiebanner_id = 0 where poc_url=%s", $this->id) ;
				$wpdb->query($sql);
			}

			return ! $error;
		}

		/**
		 * Archive this cookie banner
		 *
		 * @return void
		 */

		public function archive() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			//don't archive the last one
			if ( count( cmplz_get_cookiebanners() ) === 1 ) {
				return;
			}

//            //generate the stats
			$statuses            = $this->get_available_categories();
			$consenttypes        = cmplz_get_used_consenttypes();
			$consenttypes['all'] = "all";

			$stats = array();
			foreach ( $consenttypes as $consenttype => $label ) {
				foreach ( $statuses as $status ) {
					$count                            = $this->get_count( $status, $consenttype );
					$stats[ $consenttype ][ $status ] = $count;
				}
			}
			$this->archived   = true;
			$this->statistics = $stats;

			$this->save();

			if ( $this->default ) {
				$this->remove_default();
			}
		}

		/**
		 * Restore this cookiebanner
		 *
		 * @return void
		 */

		public function restore() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$this->archived = false;
			$this->save();
		}

		/**
		 * Get available categories, taking into account TM cats with labels
		 * @param bool $labels
		 * @param bool $exclude_no_warning
		 * @return array
		 */

		public function get_available_categories( $labels = true, $exclude_no_warning = false){
			//get all categories
			$available_cats = array(
				'do_not_track' => __("Do Not Track", "complianz-gdpr"),
				'no_choice' => __("No choice", "complianz-gdpr"),
			);

			if ( ! $exclude_no_warning && cmplz_get_value( 'use_country' )) {
				$available_cats['no_warning'] = __("No warning", "complianz-gdpr");

			}

			$available_cats['functional'] = __("Functional", "complianz-gdpr");

			if (cmplz_consent_api_active() ) {
				$available_cats['prefs'] = __("Preferences", "complianz-gdpr");
			}

			if ( !COMPLIANZ::$cookie_admin->tagmamanager_fires_scripts() ) {
				$available_cats['stats'] = __( "Statistics", "complianz-gdpr" );
			}

			if ( COMPLIANZ::$cookie_admin->tagmamanager_fires_scripts() ) {
				$tm_cats = explode( ',', $this->tagmanager_categories );
				foreach ( $tm_cats as $i => $tm_category ) {
					if ( $i > 1 ) continue;
					if ( empty( $tm_category ) ) {
						continue;
					}
					$available_cats['event_'.$i] = trim( $tm_category );
				}
			}

			if ( cmplz_uses_marketing_cookies() ) {
				$available_cats['marketing'] = __("Marketing", "complianz-gdpr");
			}

			if ( !$labels ) {
				$available_cats = array_keys( $available_cats );
			}

			return $available_cats;
		}

		/**
		 * Check if current banner is the default, and if so move it to another banner.
		 */

		public function remove_default() {
			if ( current_user_can( 'manage_options' ) ) {

				global $wpdb;
				//first, set one  of the other banners random to default.
				$cookiebanners
					= $wpdb->get_results( "select * from {$wpdb->prefix}cmplz_cookiebanners as cb where cb.default = false and cb.archived=false LIMIT 1" );
				if ( ! empty( $cookiebanners ) ) {
					$wpdb->update( $wpdb->prefix . 'cmplz_cookiebanners',
						array( 'default' => true ),
						array( 'ID' => $cookiebanners[0]->ID )
					);
				}

				//now set this one to not default and save
				$wpdb->update( $wpdb->prefix . 'cmplz_cookiebanners',
					array( 'default' => false ),
					array( 'ID' => $this->id )
				);

			}
		}

		/**
		 * Check if current banner is not default, and if so disable the current default
		 */

		public function enable_default() {
			if ( current_user_can( 'manage_options' ) ) {

				global $wpdb;
				//first set the current default to false
				$cookiebanners = $wpdb->get_results( "select * from {$wpdb->prefix}cmplz_cookiebanners as cb where cb.default = true LIMIT 1" );
				if ( ! empty( $cookiebanners ) ) {
					$wpdb->update( $wpdb->prefix . 'cmplz_cookiebanners',
						array( 'default' => false ),
						array( 'ID' => $cookiebanners[0]->ID )
					);
				}

				//now set this one to default
				$wpdb->update( $wpdb->prefix . 'cmplz_cookiebanners',
					array( 'default' => true ),
					array( 'ID' => $this->id )
				);
			}

		}

		/**
		 * @param $statistics
		 *
		 * @return mixed
		 */

		public function report_conversion_count( $statistics ) {
			return $statistics['all'];
		}


		/**
		 * Get the conversion to marketing for a cookie banner
		 * @param string $filter_consenttype
		 * @return float percentage
		 */

		public function conversion_percentage( $filter_consenttype ) {
			if ( $this->archived ) {
				if ( ! isset( $this->statistics[ $filter_consenttype ] ) ) {
					return 0;
				}
				$total = 0;
				$all   = 0;
				foreach (
					$this->statistics[ $filter_consenttype ] as $status =>
					$count
				) {
					$total += $count;
					if ( $status === 'all' ) {
						$all = $count;
					}
				}

				$total = ( $total == 0 ) ? 1 : $total;
				$score = ROUND( 100 * ( $all / $total ) );
			} else {
				$categories = $this->get_available_categories();
				$revers_arr = array_reverse($categories);
				$highest_level_cat = array_key_first($revers_arr);
				$total = 0;
				$highest_conversion_count = $this->get_count( $highest_level_cat, $filter_consenttype );
				foreach ( $categories as $category ) {
					$count = $this->get_count( $category, $filter_consenttype );
					$total += $count;
				}

				$total = ( $total == 0 ) ? 1 : $total;
				$score = ROUND( 100 * ( $highest_conversion_count / $total ) );
				return $score;
			}

			return $score;
		}

		/**
		 * Get the count for this category and consent type.
		 *
		 * @param string $consent_category
		 * @param string $consenttype
		 *
		 * @return int $count
		 */

		public function get_count( $consent_category, $consenttype ) {
			$available_categories  = $this->get_available_categories( false );
			$ab_testing_start_time = get_option('cmplz_tracking_ab_started');
			//sanitize status

			if ( !in_array( $consent_category, $available_categories ) ) return 0;

			global $wpdb;
			$consenttype_sql = " AND consenttype='$consenttype'";

			if ( $consenttype === 'all' ) {
				$consenttypes    = cmplz_get_used_consenttypes();
				$consenttype_sql = " AND (consenttype='" . implode( "' OR consenttype='", $consenttypes ) . "')";
			}

			$sql = $wpdb->prepare("SELECT count(*) from {$wpdb->prefix}cmplz_statistics WHERE time> %s AND $consent_category = 1 $consenttype_sql" , $ab_testing_start_time );

			if ( cmplz_ab_testing_enabled() ) {
				$sql = $wpdb->prepare( $sql . " AND cookiebanner_id=%s", $this->id );
			}
			$count = $wpdb->get_var( $sql );

			return $count;
		}

		/**
		 * @param        $category
		 * @param        $label
		 * @param string $context
		 * @param bool   $force_template
		 * @param bool   $checked
		 * @param bool   $disabled
		 * @param bool   $force_color
		 *
		 * @return string|string[]
		 */

		public function get_consent_checkbox($category, $label, $context = 'banner', $force_template = false, $checked=false, $disabled=false, $force_color=false){
			$checked = $checked ? 'checked' : '';
			$disabled = $disabled ? 'disabled' : '';
			$color = '';
			if ($context === 'banner' ){
				$color = 'color:' . $this->colorpalette_text['color'];
				if ($force_color) $color = 'color:' .$force_color;
			}

			$id = "cmplz_$category";
			if ($context === 'document'){
				$id = $id.'_document';
			}
			//no line breaks, to enable simple regex matching
			$category_template = $force_template ?: $this->checkbox_style;
			if ($category_template === 'square' ) $color = '';
			if (empty($category_template)) $category_template = 'square';
			$html = cmplz_get_template("category-checkbox-$category_template.php");

            $html = str_replace(array('{category}','{label}', '{id}', '{checked}', '{disabled}', '{color}'),array($category, $label, $id, $checked, $disabled, $color), $html);
            if ($context === 'document'){
                $html = '<div>'.$html.'</div>';
            } else {
                $html = '<div class="cmplz-categories-wrap">'.$html.'</div>';
            }

            return $html;
		}

		/**
		 * Get list of checkboxes for a banner or revoke link
		 * @param string $context
		 * @param bool   $consenttype
		 * @param bool   $force_template
		 * @param bool   $force_color
		 * @param bool   $functional_only
		 *
		 * @return string|string[]
		 */


		public function get_consent_checkboxes($context = 'banner', $consenttype = false, $force_template = false, $force_color = false, $functional_only = false){

			$checkbox_functional  = $this->get_consent_checkbox('functional', $this->category_functional_x, $context, $force_template,true,true, $force_color);
			$output = $checkbox_functional;
			if (!$functional_only) {
				$use_cats = false;
				$uses_marketing_cookies = cmplz_uses_marketing_cookies();
				if ($consenttype) {
					if ($consenttype !== 'optout' && (
							$this->use_categories !== 'no' ||
							( $consenttype === 'optinstats' && $this->use_categories_optinstats !== 'no')
						)){
						$use_cats = true;
					}
				} else {
					if ( $this->use_categories !== 'no' || $this->use_categories_optinstats !== 'no' ){
						$use_cats = true;
					}
				}

				if ( $use_cats) {

					if ( COMPLIANZ::$cookie_admin->tagmamanager_fires_scripts() ) {
						$categories = explode( ',', $this->tagmanager_categories );
						foreach ( $categories as $i => $category ) {
							if ( empty( $category ) ) {
								continue;
							}
							$output .= $this->get_consent_checkbox( $i, trim( $category ), $context, $force_template, false, false, $force_color);
						}
					}

                    $output .= cmplz_uses_preferences_cookies() ?  $this->get_consent_checkbox( 'prefs',  $this->category_prefs_x , $context , $force_template, false, false, $force_color) : '';
                    $output .= cmplz_uses_statistic_cookies() ? $this->get_consent_checkbox( 'stats',  $this->category_stats_x , $context , $force_template, false, false, $force_color) : '';
                    if ($uses_marketing_cookies) $output .= $this->get_consent_checkbox('marketing', $this->category_all_x, $context, $force_template, false, false, $force_color);
				} else {
					if ($uses_marketing_cookies) $output .= $this->get_consent_checkbox('marketing', $this->category_all_x, $context, $force_template, false, false, $force_color);
				}
			}
			$category_template = $force_template ?: $this->checkbox_style;


			if ($category_template === 'slider'){
				$output .= '<style>
					.cmplz-slider-checkbox input:checked + .cmplz-slider {
						background-color: '.$this->colorpalette_toggles['background'].'
					}
					.cmplz-slider-checkbox input:focus + .cmplz-slider {
						box-shadow: 0 0 1px '.$this->colorpalette_toggles['background'].';
					}
					.cmplz-slider-checkbox .cmplz-slider:before {
						background-color: '.$this->colorpalette_toggles['bullet'].';
					}.cmplz-slider-checkbox .cmplz-slider-na:before {
						color:'.$this->colorpalette_toggles['bullet'].';
					}
					.cmplz-slider-checkbox .cmplz-slider {
					    background-color: '.$this->colorpalette_toggles['inactive'].';
					}
					</style>';
			}

			if ($category_template = 'square') {
				$output .= '<style>';
				$output .= '#cc-window.cc-window .cmplz-categories-wrap .cc-check svg {stroke: '.$this->colorpalette_text['color'].'}';
				$output .= '</style>';
			}

			$output = preg_replace( "/\r|\n/", "", $output );

			return apply_filters('cmplz_categories_html',$output, $context);
		}



		/**
		 * Get array to output to front-end
		 *
		 * @return array
		 */
		public function get_settings_array() {
			$records_of_consent = cmplz_get_value('records_of_consent') === 'yes';

			$this->dismiss_on_scroll  = $this->dismiss_on_scroll ? 400 : false;
			$this->dismiss_on_timeout = $this->dismiss_on_timeout ? 1000 * $this->dismiss_timeout : false;

			$output = array(
				'static'                    => false,
				//cookies to set on acceptance, in order array('cookiename=>array('consent value', 'revoke value');
				'set_cookies'               => apply_filters( 'cmplz_set_cookies_on_consent', array() ),
				'block_ajax_content'        => cmplz_get_value('enable_cookieblocker_ajax'),
				'banner_version'            => $this->banner_version,
				'version'                   => cmplz_version,
				'a_b_testing'               => cmplz_ab_testing_enabled() || $records_of_consent,
				'do_not_track'              => apply_filters( 'cmplz_dnt_enabled', false ),
				'consenttype'               => COMPLIANZ::$company->get_default_consenttype(),
				'region'                    => COMPLIANZ::$company->get_default_region(),
				'geoip'                     => cmplz_geoip_enabled(),
				'categories'                => '',
				'position'                  => $this->position,
				'title'                     => $this->title,
				'theme'                     => $this->theme,
				'checkbox_style'            => $this->checkbox_style,
				'use_categories'            => $this->use_categories,
				'use_categories_optinstats' => $this->use_categories_optinstats,
				'header'                    => $this->header,
				'accept'                    => $this->accept_x,
				'revoke'                    => $this->revoke_x,
				'dismiss'                   => $this->dismiss_x,
				'dismiss_timeout'           => $this->dismiss_timeout,
				'use_custom_cookie_css'     => $this->use_custom_cookie_css,
				'custom_css'                => $this->sanitize_custom_css( $this->custom_css ),
				'custom_css_amp'            => $this->sanitize_custom_css( $this->custom_css_amp ),
				'readmore_optin'            => $this->readmore_optin_x,
				'readmore_impressum'        => $this->readmore_impressum_x,
				'accept_informational'      => $this->accept_informational_x,
				'message_optout'            => $this->message_optout_x,
				'message_optin'             => $this->message_optin_x,
				'readmore_optout'           => $this->readmore_optout_x,
				'readmore_optout_dnsmpi'    => $this->readmore_optout_dnsmpi_x,
				'hide_revoke'               => $this->hide_revoke ? 'cc-hidden' : '',
				'disable_cookiebanner'      => $this->disable_cookiebanner,
				'banner_width'              => $this->banner_width,
				'soft_cookiewall'           => $this->soft_cookiewall,
				'type'                      => 'opt-in',
				'layout'                    => 'basic',
				'dismiss_on_scroll'         => $this->dismiss_on_scroll,
				'dismiss_on_timeout'        => $this->dismiss_on_timeout,
				'cookie_expiry'             => cmplz_get_value( 'cookie_expiry' ),
				'nonce'                     => wp_create_nonce( 'set_cookie' ),
				'url'                       => add_query_arg('lang',  substr(get_locale(),0,2), get_rest_url().'complianz/v1/' ),
				'set_cookies_on_root'       => cmplz_get_value( 'set_cookies_on_root' ),
				'cookie_domain'             => COMPLIANZ::$cookie_admin->get_cookie_domain(),
				'current_policy_id'         => COMPLIANZ::$cookie_admin->get_active_policy_id(),
				'cookie_path'               => COMPLIANZ::$cookie_admin->get_cookie_path(),
				'tcf_active'                => cmplz_tcf_active(),
                'colorpalette_background_color'               => $this->colorpalette_background['color'],
                'colorpalette_background_border'              => $this->colorpalette_background['border'],
                'colorpalette_text_color'                     => $this->colorpalette_text['color'],
                'colorpalette_text_hyperlink_color'           => $this->colorpalette_text['hyperlink'],
                'colorpalette_toggles_background'             => $this->colorpalette_toggles['background'],
                'colorpalette_toggles_bullet'                 => $this->colorpalette_toggles['bullet'],
                'colorpalette_toggles_inactive'               => $this->colorpalette_toggles['inactive'],
                'colorpalette_border_radius'                  => $this->get_colorpalette_border_radius(),
                'colorpalette_border_width'                   => $this->get_colorpalette_border_width(),
                'colorpalette_button_accept_background'       => $this->colorpalette_button_accept['background'],
                'colorpalette_button_accept_border'           => $this->colorpalette_button_accept['border'],
                'colorpalette_button_accept_text'             => $this->colorpalette_button_accept['text'],
                'colorpalette_button_deny_background'         => $this->colorpalette_button_deny['background'],
                'colorpalette_button_deny_border'             => $this->colorpalette_button_deny['border'],
                'colorpalette_button_deny_text'               => $this->colorpalette_button_deny['text'],
                'colorpalette_button_settings_background'     => $this->colorpalette_button_settings['background'],
                'colorpalette_button_settings_border'         => $this->colorpalette_button_settings['border'],
                'colorpalette_button_settings_text'           => $this->colorpalette_button_settings['text'],
                'colorpalette_buttons_border_radius'          => $this->get_colorpalette_button_border_radius(),
                'box_shadow'            => $this->get_box_shadow(),
                'animation'             => $this->animation,
                'animation_fade'        => $this->get_animation_fade(),
                'animation_slide'       => $this->get_animation_slide(),
			);

			if ( $output['position'] == 'static' ) {
				$output['static']   = true;
				$output['position'] = 'top';
			}

			if ($output['position'] === 'bottom' || $output['position'] === 'top' || $output['position'] === 'static') {
				$output['banner_width'] = '';
			}

			//When theme is edgeless, don't set border color
			if ( $output['theme'] === 'edgeless' ) {
				$output['border_color'] = false;
			}

			/**
			 *
			 * Banners with categories
			 *
			 */

			if ( $output['use_categories']!=='no' || $output['use_categories_optinstats'] !== 'no'
			) {
				$output['categories'] = $this->get_consent_checkboxes();

				if ( COMPLIANZ::$cookie_admin->tagmamanager_fires_scripts() ) {
					$output['tm_categories'] = true;
					$categories = explode( ',', $this->tagmanager_categories );
					$output['cat_num']    = count( $categories );
				}
				$output['view_preferences'] = $this->view_preferences_x;
				$output['save_preferences'] = $this->save_preferences_x;
				$output['accept_all'] = $this->accept_all_x;
			}

			$regions = cmplz_get_regions();
			foreach ( $regions as $region => $label ) {
				$privacy_link = '';
				$output['readmore_url'][ $region ] = cmplz_get_document_url( 'cookie-statement' ,$region );

				$tmpl = '<span class="cc-divider">&nbsp;-&nbsp;</span><a aria-label="learn more about privacy in our {type}" class="cc-link {type}" href="{link}">{description}</a>';

				if ( ($region=='us' || $region=='ca') ){
					$privacy_link = cmplz_get_document_url( 'privacy-statement', $region );

					if ($privacy_link !== '#') {
						$privacy_link = cmplz_get_document_url( 'privacy-statement', $region );
						$privacy_link = str_replace( array(
							'{link}',
							'{description}',
							'{type}'
						), array(
							$privacy_link,
							$this->readmore_privacy_x,
							'privacy-statement'
						), $tmpl );
					}
				}

				if ( $region == 'eu' && cmplz_get_value( 'eu_consent_regions' ) === 'yes' ){
					$privacy_link = cmplz_get_document_url( 'impressum', $region );
					if ( $privacy_link !== '#' ) {
						$privacy_link = str_replace( array(
							'{link}',
							'{description}',
							'{type}'
						), array(
							$privacy_link,
							$this->readmore_impressum_x,
							"impressum"
						), $tmpl );
					}
				}

				$output['privacy_link'][ $region ] = ( !empty( $privacy_link ) &&  $privacy_link !== '#' )
					? $privacy_link
					: '';
			}

			/**
			 * dynamically set the readmore link on the cookie policy banner, depending on ccpa and region
			 */
			$geoip = cmplz_geoip_enabled();
			if ((!$geoip && cmplz_has_region('us') || ($geoip && COMPLIANZ::$geoip->region()==='us'))){
				if (cmplz_ccpa_applies()){
					$output['readmore_optout'] = $output['readmore_optout_dnsmpi'];
				}
			}

			return apply_filters( 'cmplz_cookiebanner_settings', $output, $this );

		}


        private function get_colorpalette_border_radius() {
            $type   = $this->colorpalette_border_radius['type'] == 0 ? 'px' : '%';

            $top    = $this->colorpalette_border_radius['top'] .  $type . ' ';
            $right  = $this->colorpalette_border_radius['right'] .  $type . ' ';
            $bottom = $this->colorpalette_border_radius['bottom'] .  $type . ' ';
            $left   = $this->colorpalette_border_radius['left'] .  $type;

            return $top . $right . $bottom . $left;
        }

        private function get_colorpalette_button_border_radius() {
            $type   = $this->colorpalette_buttons_border_radius['type'] == 0 ? 'px' : '%';

            $top    = $this->colorpalette_buttons_border_radius['top'] .  $type . ' ';
            $right  = $this->colorpalette_buttons_border_radius['right'] .  $type . ' ';
            $bottom = $this->colorpalette_buttons_border_radius['bottom'] .  $type . ' ';
            $left   = $this->colorpalette_buttons_border_radius['left'] .  $type;

            return $top . $right . $bottom . $left;
        }

        private function get_colorpalette_border_width() {
            $top    = $this->colorpalette_border_width['top'] . 'px ';
            $right  = $this->colorpalette_border_width['right'] . 'px ';
            $bottom = $this->colorpalette_border_width['bottom'] . 'px ';
            $left   = $this->colorpalette_border_width['left'] .  'px';

            return $top . $right . $bottom . $left;
        }

        private function get_box_shadow() {
		    return $this->use_box_shadow ? '0 0 10px rgba(0, 0, 0, .4)' : '';
        }

        private function get_animation_fade() {
            $animation_fade = '';
            if ($this->animation === 'fade') {
                $animation_fade = 'opacity 1s ease';
            }
            return $animation_fade;
        }

        private function get_animation_slide() {
            $animation_slide = '';
            if ($this->animation === 'slide') {
                if ($this->position == 'bottom-left' || $this->position == 'bottom-right' || $this->position == 'bottom' ) {
                    $animation_slide = 'slideInUpBottom 1s';
                }
                if ($this->position == 'center') {
                    $animation_slide = 'slideInUpCenter 1s';
                }
                if ($this->position == 'top') {
                    $animation_slide = 'slideInUpTop 1s';
                }
            }
            return $animation_slide;
        }

	}


}

