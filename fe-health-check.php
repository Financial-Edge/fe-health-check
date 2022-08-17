<?php

/**
 * Plugin Name: [FE] Health Check
 * Plugin URI: https://www.fe.training
 * Description: Adds a health check endpoint
 * Version: 1.0.0
 * Author: Joshua Sullivan-Small
 */

add_filter('query_vars', static function (array $vars): array {
	$vars[] = 'fe_health_check';

	return $vars;
});

add_action('parse_request', static function (WP $wp): void {
	if (isset($wp->query_vars['fe_health_check']) && $wp->query_vars['fe_health_check'] === 'true') {
		global $wpdb;

		if (!$wpdb->check_connection(false)) {
			http_response_code(500);

			exit;
		}

		header('Content-length: 0');

		exit;
	}
});

add_action('init', static function (): void {
	add_rewrite_rule('^fe-health-check$', 'index.php?fe_health_check=true', 'top');

	flush_rewrite_rules();
});