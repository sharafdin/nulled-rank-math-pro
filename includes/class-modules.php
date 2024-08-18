<?php
/**
 * The Pro Module loader
 *
 * @since      1.0.0
 * @package    RankMath
 * @subpackage RankMathPro
 * @author     RankMath <support@rankmath.com>
 */

namespace RankMathPro;

use RankMath\Helper;
use RankMath\Helpers\Param;
use RankMath\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Modules class.
 */
class Modules {

	use Hooker;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		if ( Helper::is_heartbeat() ) {
			return;
		}

		$this->filter( 'rank_math/modules', 'setup_core', 1 );
		$this->action( 'admin_enqueue_scripts', 'enqueue' );
		$this->action( 'rank_math/module_changed', 'flush_rewrite_rules', 10, 2 );
	}

	/**
	 * Setup core modules.
	 *
	 * @param array $modules Array of modules.
	 *
	 * @return array
	 */
	public function setup_core( $modules ) {
		$active_modules = get_option( 'rank_math_modules', [] );

		$modules['news-sitemap'] = [
			'title'         => esc_html__( 'News Sitemap', 'rank-math-pro' ),
			'desc'          => esc_html__( 'Create a News Sitemap for your news-related content. You only need a News sitemap if you plan on posting news-related content on your website.', 'rank-math-pro' ),
			'class'         => 'RankMathPro\Sitemap\News_Sitemap',
			'icon'          => 'post',
			'settings'      => Helper::get_admin_url( 'options-sitemap' ) . '#setting-panel-news-sitemap',
			'probadge'      => defined( 'RANK_MATH_PRO_FILE' ),
			'disabled'      => ( ! in_array( 'sitemap', $active_modules, true ) ),
			'dep_modules'   => [ 'sitemap' ],
			'disabled_text' => esc_html__( 'Please activate Sitemap module to use this module.', 'rank-math-pro' ),
		];

		$modules['video-sitemap'] = [
			'title'         => esc_html__( 'Video Sitemap', 'rank-math-pro' ),
			'desc'          => esc_html__( 'For your video content, a Video Sitemap is a recommended step for better rankings and inclusion in the Video search.', 'rank-math-pro' ),
			'class'         => 'RankMathPro\Sitemap\Video_Sitemap',
			'icon'          => 'video',
			'settings'      => Helper::get_admin_url( 'options-sitemap' ) . '#setting-panel-video-sitemap',
			'probadge'      => defined( 'RANK_MATH_PRO_FILE' ),
			'disabled'      => ( ! in_array( 'rich-snippet', $active_modules, true ) || ! in_array( 'sitemap', $active_modules, true ) ),
			'dep_modules'   => [ 'sitemap', 'rich-snippet' ],
			'disabled_text' => esc_html__( 'Please activate Schema & Sitemap module to use this module.', 'rank-math-pro' ),
		];

		$modules['podcast'] = [
			'title'         => esc_html__( 'Podcast', 'rank-math-pro' ),
			'desc'          => esc_html__( 'Make your podcasts discoverable via Google Podcasts, Apple Podcasts, and similar services with Podcast RSS feed and Schema Markup generated by Rank Math.', 'rank-math-pro' ),
			'class'         => 'RankMathPro\Podcast\Podcast',
			'icon'          => 'podcast',
			'betabadge'     => true,
			'settings'      => Helper::get_admin_url( 'options-general' ) . '#setting-panel-podcast',
			'probadge'      => defined( 'RANK_MATH_PRO_FILE' ),
			'disabled'      => ! in_array( 'rich-snippet', $active_modules, true ),
			'dep_modules'   => [ 'rich-snippet' ],
			'disabled_text' => esc_html__( 'Please activate Schema module to use this module.', 'rank-math-pro' ),
		];

		// Replace Schema Loader.
		$modules['rich-snippet']['class'] = '\RankMathPro\Schema\Schema';

		return $modules;
	}

	/**
	 * Enqueue styles and scripts.
	 */
	public function enqueue() {
		if ( 'rank-math' !== Param::get( 'page' ) ) {
			return;
		}
		wp_enqueue_script( 'rank-math-pro-dashboard', RANK_MATH_PRO_URL . 'assets/admin/js/dashboard.js', [ 'jquery' ], rank_math_pro()->version, true );
	}

	/**
	 * Function to run when Module is enabled/disabled.
	 *
	 * @param string $module Module.
	 * @param string $state  Module state.
	 */
	public function flush_rewrite_rules( $module, $state ) {
		if ( 'podcast' === $module ) {
			Helper::schedule_flush_rewrite();
		}
	}
}