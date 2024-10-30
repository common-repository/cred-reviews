<?php
/*
Plugin Name: Cred
Plugin URI: https://cred.wheat.co/
Description: Cred Reviews displays validated reviews collected by the Cred review system.
Version: 1.0.0
Author: wheatco
Author URI: https://wheat.co/
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'cred_reviews' ) ) {

	class cred_reviews {

		protected static $instance = null;
		protected static $clear_cache = false;
		protected static $cred_reviews_name = 'cred';

		public static function &get_instance() {
			if ( self::$instance === null )
				self::$instance = new self;
			return self::$instance;
		}

		public function __construct() {
			// allow for a custom shortcode name
			if ( defined( 'cred_reviews_SHORTCODE_NAME' ) && cred_reviews_SHORTCODE_NAME )
				self::$cred_reviews_name = cred_reviews_SHORTCODE_NAME;

			if ( ! is_admin() ) {
				$this->wpautop();
				$this->add_shortcode();
			} else add_action( 'save_post', array( &$this, 'clear_cache' ), 10 );
		}

		public function wpautop() {
			$default_priority = 10;
			foreach ( array( 'get_the_excerpt', 'the_excerpt', 'the_content' ) as $filter_name ) {
				$filter_priority = has_filter( $filter_name, 'wpautop' );
				if ( $filter_priority !== false && 
					$filter_priority > $default_priority ) {
					remove_filter( $filter_name, 'wpautop' );
					add_filter( $filter_name, 'wpautop' , $default_priority );
				}
			}
		}

		public function add_shortcode() {
        		add_shortcode( self::$cred_reviews_name, array( &$this, 'do_shortcode' ) );
		}

		public function remove_shortcode() {
			remove_shortcode( self::$cred_reviews_name );
		}

		public function do_shortcode( $atts, $content = null ) { 

			$cache_expire = isset( $atts['cache'] ) ? (int) $atts['cache'] : 600;		// allow for 0 seconds (default 10 minutes)
			$add_pre = isset( $atts['pre'] ) ? self::get_bool( $atts['pre'] ) : false;	// wrap content in pre tags (default is false)
			$add_class = empty( $atts['class'] ) ? '' : ' '.$atts['class'];			// optional css class names
			$do_filter = isset( $atts['filter'] ) ? $atts['filter'] : false;		// optional content filter
			$more_link = isset( $atts['more'] ) ? self::get_bool( $atts['more'] ) : true;	// add more link (default is true)
			$body_only = isset( $atts['body'] ) ? self::get_bool( $atts['body'] ) : true;	// keep only <body></body> content

			$store = $atts['store'];
			$sku = $atts['sku'];

			$content = false;	// just in case
			$cache_salt = __METHOD__.'(url:'.$url.')';
			$cache_id = __CLASS__.'_'.md5( $cache_salt );

			if ( self::$clear_cache ) {
				delete_transient( $cache_id );
				return '<p>'.__CLASS__.': <em>cache cleared for '.$url.'</em>.</p>';
			} elseif ( $cache_expire > 0 ) {
				$content = get_transient( $cache_id );
			} else delete_transient( $cache_id );

			if ( $content === false )
				$content = file_get_contents( "https://cred.wheat.co/reviews?store=".urlencode($store)."&sku=".urlencode($sku) );
			else return $content;	// content from cache
		
			if ( $body_only && stripos( $content, '<body' ) !== false )
				$content = preg_replace( '/^.*<body[^>]*>(.*)<\/body>.*$/is', '$1', $content );

			if ( $more_link && ! is_singular() ) {
				global $post;
				$parts = get_extended( $content );
				if ( $parts['more_text'] )
					$content = $parts['main'].apply_filters( 'the_content_more_link', 
						' <a href="'.get_permalink().'#more-{'.$post->ID.'}" class="more-link">'.$parts['more_text'].'</a>', 
							$parts['more_text'] );
				else $content = $parts['main'];
			}

			$content = '<div class="cred-reviews'.$add_class.'">'."\n".
				( $add_pre ? "<pre>\n" : '' ).$content.( $add_pre ? "</pre>\n" : '' ).'</div>'."\n";

			if ( $do_filter ) {
				$this->remove_shortcode();	// prevent recursion
				$content = apply_filters( $do_filter, $content );
				$this->add_shortcode();
			}

			if ( $cache_expire > 0 )
				set_transient( $cache_id, $content, $cache_expire );	// save rendered content

			return $content;
		}

		public function clear_cache( $post_id, $rel_id = false ) {
			switch ( get_post_status( $post_id ) ) {
				case 'draft':
				case 'pending':
				case 'future':
				case 'private':
				case 'publish':
					$post_obj = get_post( $post_id, OBJECT, 'raw' );
					$is_admin = is_admin();
					if ( isset( $post_obj->post_content ) &&
						stripos( $post_obj->post_content, '['.self::$cred_reviews_name ) !== false ) {

						if ( $is_admin )
							$this->add_shortcode();
						self::$clear_cache = true;	// clear cache and return
						$content = do_shortcode( $post_obj->post_content );
						if ( $is_admin )
							$this->remove_shortcode();
					}
					break;
			}
			return $post_id;
		}

		// converts string to boolean
		public static function get_bool( $mixed ) {
			return is_string( $mixed ) ? 
				filter_var( $mixed, FILTER_VALIDATE_BOOLEAN ) : (bool) $mixed;
		}
	}

        global $cred_reviews;
        $cred_reviews = cred_reviews::get_instance();
}

?>
