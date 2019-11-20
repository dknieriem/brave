<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

/**
 * Local configuration information.
 *
 * If you are working in a local/desktop development environment and want to
 * keep your config separate, we recommend using a 'wp-config-local.php' file,
 * which you should also make sure you .gitignore.
 */
if (file_exists(dirname(__FILE__) . '/wp-config-local.php') && !isset($_ENV['PANTHEON_ENVIRONMENT'])):
  # IMPORTANT: ensure your local config does not include wp-settings.php
  require_once(dirname(__FILE__) . '/wp-config-local.php');

else:

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME','dannahgr_b2bb_st3');

/** MySQL database username */
define( 'DB_USER','dannahgr_st3');

/** MySQL database password */
define( 'DB_PASSWORD','rXx7mS8kAROn9dD_sZCw');

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Ou!0J*w/0bmkf-FSp/=;7-;-,{ht7=p{T^8rZP-z~%Q+lAnKbMA`(wT*L%Bbm1X7');
define('SECURE_AUTH_KEY',  'N-`L#?rA/q&Exx3{Sj-L=mmK2/RzYT*{<,xiQy)V0+N2m2*`+Ro9]*Z-A{ey~8ma');
define('LOGGED_IN_KEY',    'X2fr-3vJK(I|X$8q~,]H40=4eZ*MO7$d,dN|eYtHZD0)-3*.X mRZPJbaQ4R6kjH');
define('NONCE_KEY',        '+E$fJOS`Y]fnSrjhmP1`:#ON4c=eI|Tajsm?XW]wBs-LD3J|5ilX$PCDH|*L`J- ');
define('AUTH_SALT',        'C>8HU8Y|K7S+5QO3F|+y#e[/7v=aL>K-O3dg@Q ,2^.3sK[3?lx2!PA|{z_81J=7');
define('SECURE_AUTH_SALT', '|2NW9R>=t62{{5>HhJLf:v-:vqQ5T1K)[1?P6T/?fBr-,a>]d(@f>-Esn|+ 3sWC');
define('LOGGED_IN_SALT',   'LVyKje$f3M-ZTr@-mQfl!z~a^$zrk:%2qC*&Si9vtO@ak|l=dDeENNsVarq%`C1$');
define('NONCE_SALT',       '*jPULt&[<1&p+C&UV c~%+F%@Fn*Qh%&4T1qkmFL[]Sj%`YZy9;z;G*?+EY-}|:B');


/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
define('WP_CACHE_KEY_SALT', 'jhudjIjIx2DOSWI39HqGug');
$table_prefix = 'wp_';

endif;



/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';