<?php
/**
 * Core plugin functionality.
 *
 * @package Display_Git_Branch
 */

namespace Display_Git_Branch\Core;

// Security check - stop direct file access
defined( 'DISPLAY_GIT_BRANCH_VERSION' ) || die();

/**
 * Default setup routine
 *
 * @return void
 */
function setup() {

	// Useful helper function
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'admin_bar_menu', $n( 'wp_admin_bar' ), 100, 1 );

	add_action( 'admin_head', $n( 'style' ), 10, 0 );
	add_action( 'wp_head', $n( 'style' ), 10, 0 );

	if ( in_array( branch(), restricted_branches(), true ) ) {
		add_action( 'admin_head', $n( 'warning_style' ), 10, 0 );
		add_action( 'wp_head', $n( 'warning_style' ), 10, 0 );
	}

}

/**
 * WP Admin Bar Node
 *
 * Add a node to the WP Admin Bar with the Git Branch
 *
 * @return void
 */
function wp_admin_bar( $wp_admin_bar ) {

	$wp_admin_bar->add_node(
		[
			'id'    => 'show-git-branch',
			/* Translators: The branch name */
			'title' => sprintf( __( 'Git branch: %s', 'display-git-branch' ), branch() ),
		]
	);

}

/**
 * Find Git branch
 *
 * @return string $branch - the git branch name, if found. Sane default if not.
 */
function branch() {

	// Sane default, assume this isn't using Git
	$branch = esc_html( __( 'Not detected', 'display-git-branch' ) );

	// Paths to search for a git branch
	$paths = apply_filters( 'display_git_branch_paths',
		[
			trailingslashit( WP_CONTENT_DIR ), // Content directory
			trailingslashit( ABSPATH ),        // Site root
		]
	);

	// Run through the paths and check each, break on success
	// PHPCS suggests wp_remote_get instead of file_get_contents - this does not work as our path is relative
	foreach ( $paths as $location ) {
		if ( file_exists( $location . '.git/HEAD' ) ) {
			$branch = str_replace( "\n", '', implode( '/', array_slice( explode( '/', file_get_contents( $location . '.git/HEAD' ) ), 2 ) ) );
			break;
		}
	}

	// This includes a line break... Strip it out and return the branch name.
	return esc_html( $branch );

}

/**
 * Restricted Branches
 *
 * If you're on one of these branches, the WP Admin Bar node turns red
 */
function restricted_branches() {

	return apply_filters( 'display_git_branch_restricted_branches',
		[
			'trunk',
			'master',  // WARNING: this will be deprecated in a future release
			'develop', // WARNING: this will be deprecated in a future release
		]
	);

}

/**
 * WP Admin Bar CSS
 *
 * Defines the style for the WP Admin Bar node
 */
function style() {

	?>
		<style type="text/css">#wp-admin-bar-show-git-branch .ab-item:before { content: "\f237"; top: 2px; }</style>
	<?php

}

/**
 * WP Admin Bar Warning CSS
 *
 * Defines the style for the WP Admin Bar node
 */
function warning_style() {

	?>
		<style type="text/css">#wp-admin-bar-show-git-branch .ab-item { background: #c00; }</style>
	<?php

}
