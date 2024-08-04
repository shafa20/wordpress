<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

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
define( 'AUTH_KEY',         'atNDj,y_OjDLZ2Wk+Ee-nT+2NgL#~2y2M%.|3@bv`}Q(tm1y?meJ*~m50EgVL@[u' );
define( 'SECURE_AUTH_KEY',  'oibjeee83hLxe`.o{L#?5bk9POY>&GrP,VhuUG/u*5>`f1yr#2;9{MIdC04Xu-Qb' );
define( 'LOGGED_IN_KEY',    'N]vi6Qfu !,`NNN+07Ve|8ll?-mKKU-_P_c0SvBrGw|qN8H`}w:cCt8h$tsF#O_B' );
define( 'NONCE_KEY',        'wZTQ) 0>KVJrO&?mo#t-q:Hm33{k)6NEh|$/.RG`ZO3Yj^nCwdxpj=;2E4^ ffjK' );
define( 'AUTH_SALT',        'eDT#[}xk30|f3YboAI&W2~y>NtPPj*B%]vT?m?>VLcWOLSdLN$NX3]sRT72Lx![4' );
define( 'SECURE_AUTH_SALT', 'U/<a<b}nCgVQx,G96%V^-7knt ij,V+449X #4kGrvDSgd2y=1]O84:s%Q[T0B=y' );
define( 'LOGGED_IN_SALT',   '}}1VRq)pg>mX_1=l_>d%-Dd=h72FKGj9Coh2rK+.mZB.+tGPkG,@M;o`PPci%..H' );
define( 'NONCE_SALT',       '4NRjusi1E%(ni<&2[ZF%kv08cvc![;{({!XXS.b8PKDsv4=|YewsoHWH70,ec&23' );

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
