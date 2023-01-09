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
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_test' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
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
define( 'AUTH_KEY',         '4,ZD.VF=j5[ZT{1BYFLxkr;SuC55vli.k_%kJCv)I/,Q ^|v5Mi6xy#N[g|k3;H+' );
define( 'SECURE_AUTH_KEY',  'DD+T,F63!>I}vu1I.s$4`BeMG=,p{zeyf)QywJNtli|hBk+4(gJ[$moJ+@l@BLy&' );
define( 'LOGGED_IN_KEY',    '?OQI&2^:h5[;5w*~PiZG:{%WorAkOY{~k.R#hnKF_}NLIzwH-^:7_/U0fnksT:y0' );
define( 'NONCE_KEY',        'Oz/X4GLkSek93HKN3|U{l?{&7?ECAnPg9X~@S&TmJ_Eh=/5?[*UFH1`-};f-#x3*' );
define( 'AUTH_SALT',        'r+UGLo,!)/M>IrTz%;Xt45@.LHgk}/IZ|Qj#DcD9vt*ii#[b{W}<[R_LR(+f0wEw' );
define( 'SECURE_AUTH_SALT', 'iyS>l A(R3 eaiSs;IfT1Y%BM%sd6ht]=ZE~omxW.nR@ZuXqsMDpCPI<{28i!dfV' );
define( 'LOGGED_IN_SALT',   'hC4lU*ZrUofV*n[luf$aBlv]3ekr&|}^V!@&B~DsP`!^#R$]^5&oqdIF$}?o5WCC' );
define( 'NONCE_SALT',       '~HLoP6@_WH?F,XXT$*l?~V4p{<Ib2lzyexkbtN/ej[T0xl(&9>?NKhX[tWolsMXj' );

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
