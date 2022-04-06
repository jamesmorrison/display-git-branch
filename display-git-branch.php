<?php
/**
 * Plugin Name: Display Git Branch
 * Plugin URI:  https://github.com/jamesmorrison/display-git-branch/
 * Description: Shows which Git branch you're working on. Highlights configurable restricted branches in red.
 * Version:     0.1.1
 * Author:      James Morrison
 * Author URI:  https://jamesmorrison.uk/
 * Text Domain: display-git-branch
 *
 * @package Display_Git_Branch
 **/

// Security check
defined( 'ABSPATH' ) || die();

// Useful global constants
define( 'DISPLAY_GIT_BRANCH_VERSION', '1.0.0' );
define( 'DISPLAY_GIT_BRANCH_URL', plugin_dir_url( __FILE__ ) );
define( 'DISPLAY_GIT_BRANCH_PATH', dirname( __FILE__ ) . '/' );
define( 'DISPLAY_GIT_BRANCH_INC', DISPLAY_GIT_BRANCH_PATH . 'includes/' );

// Include files.
require_once DISPLAY_GIT_BRANCH_INC . '/core.php';

// Bootstrap.
\Display_Git_Branch\Core\setup();
