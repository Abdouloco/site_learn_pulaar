<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'learn_pulaar' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ' 9onS.ac8C5Zszpzxf}@pmN9OI#(;g6!g_k]pMD0I<] T/JSl^GRJNs&$BNhEsUR' );
define( 'SECURE_AUTH_KEY',  '-!SPW3,y8+*[kYH;1H8Q9[O?V8<L=AG+nTg>~F)AV?qs@OV|#/c;3lKip7cU//%V' );
define( 'LOGGED_IN_KEY',    'O9dQ+R+B8^9`-Bz63B0}CU`>PwP+gmj]AMO5@a;_YM^nj;AVoytU<gQkF/.n/i$K' );
define( 'NONCE_KEY',        '9zWeBeRk[)<t7q0NmB,KT4uHgfK(jMuI7dc-.#zO3eGz(Qhi,.Vg:*s#3SgC}tl!' );
define( 'AUTH_SALT',        '/>DOVrzVSLuW4L415Y}0j=26[t<=R@F,b_S4,?&A{:-Gbf%^OVdL^k3,:?~Cc<GG' );
define( 'SECURE_AUTH_SALT', 'I.`RGdt~pv#/.tfgxja0Y_4KR?s92bT|$L8U.|H{+LxRw*$%7Lb0bfxqyoKb>*e9' );
define( 'LOGGED_IN_SALT',   'QR[H?]Mx$jOs|j>;q7@mWUqUpob*hm&L-{/LBUQn6P=$--v}D](KwIf}lC:.N!.c' );
define( 'NONCE_SALT',       'o1+-W&UZ_;G}HH!PxYO2)YNxiB7_~`ciBtt*ALh/$rtCOsIk+*frnC|%@?n!PJF=' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define('WP_DEBUG_DISPLAY',true) ;

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
