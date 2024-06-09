<?php



/**
 * Plugin Name:       Research Publisher
 * Description:       Publish Research to view in the frontend
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Pratik
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       research-publisher
 *
 * @package           create-block
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */

require_once plugin_dir_path(__FILE__) . 'frontend.php';

function research_publisher_research_publisher_block_init()
{
	register_block_type(__DIR__ . '/build', [
		'render_callback' => 'renderResearchPublisherBlock'
	]);
}

function renderResearchPublisherBlock($attributes)
{
	if (!is_admin()) {
		return blockAFrontend($attributes);
	} else {
		return NULL;
	}
}
add_action('init', 'research_publisher_research_publisher_block_init');

// Enqueue Bootstrap CSS on the frontend
function enqueue_bootstrap_frontend()
{
	if (!is_admin()) {

		//Bootstrap CSS
		wp_enqueue_style('bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.0');

		// Custom Font (Aptos)
		wp_enqueue_style('aptosFont', plugin_dir_url(__FILE__) . 'inc/fonts/aptos.ttf', array(), '1.0.0', 'all');


		//Bootstrap JS
		wp_enqueue_script('bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.0');
	}
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap_frontend');
