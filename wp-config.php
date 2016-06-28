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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ieeeweb');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'CU;9:uO#j5!JlTN8+kz`~4BrYC{@N&f&x)aAUe4(XVfFJ@/~,l_+lQ9%alj/L~sP');
define('SECURE_AUTH_KEY',  'bgc&4E`OQf8+{<`i~:)7bR0J_@.@(;N@c/z 6WTm.sq06Z8rv?WcKeIj3*%WS)O<');
define('LOGGED_IN_KEY',    'zIo9w*R{n,MYeUcAj8l|I#w<YndcTcsvF{c7 [%=+25aS&WJv78eyQUx_uy|:9P@');
define('NONCE_KEY',        'F=s !JWF9Xl4i*@vpy(g?B~lMjkc|%4*#0nT_BR3^D1Cr}MB,qx!U!Cm},}Cw wr');
define('AUTH_SALT',        'I;a8;kJ>ei)b2|]e92GE)G1(?ds_ WvTH>GNONq9Nq_K)y]x}>+K eAu`OOE[+_G');
define('SECURE_AUTH_SALT', '+q>,:=z>sk,r1oTuXi6E=b2wa[KoK_x-&](/z`!%MjQpA{uR$zmZQhz6TVae6j9!');
define('LOGGED_IN_SALT',   'tsLfua^]EK^oQ~Hf/S~avjxhPsOHodD@s2:s6BU2=n9Gi<J$<-r4J58J(gS2$epN');
define('NONCE_SALT',       '%}Wreq=Z~Ono>~ZIC6b}0gF0ad+1NmHM~=i>(oix991{~%;;U0m6R3}qI(UhAg<H');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
