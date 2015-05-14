<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_markets-news');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '[|,o?Q]_[.e]LdF[.EX+ m.8{W^afr +PuoM-<^l^ ZGsa1)TyJsv^4 85<b/ym ');
define('SECURE_AUTH_KEY',  '~>m%FQwZJ5s>xN:|2.tr>cWE+L;>TLP~xaw$KaH[K:gDb +{bH`k([Sn*u[Ld%;c');
define('LOGGED_IN_KEY',    'Z] J{>%5y+-[cA~n2ASSi;k=_+!a>!nU_ G-&HNS(M?Z[/^Fn>JiO{o0-:[Nh|?1');
define('NONCE_KEY',        'FC.st7#jS><mK{9TT~zOlv@+|2RX5cWh~C?C^:1+k/;AtX>8CZsb!AI{l8X-=xE_');
define('AUTH_SALT',        '#4MoJ~YLZQqvRV<mxg4~iAcIMy7L+Ow:am]c_xo|#,kWSKl+f.LAi</4-+=ArK-5');
define('SECURE_AUTH_SALT', 'LRfV6g7Ux-yn3K}-r)J.|mbNxH+dF!NgNB6+=*{n|,-?j*DR&1AESc%K)iNZQf98');
define('LOGGED_IN_SALT',   'r2goN5<JQ<7 IjxC=+)A&!yo+s`~H: <1Fn pPUe1?VO^i3/=*S=sF?1%n*dLR;n');
define('NONCE_SALT',       '#t#* `|%}DvlN=>hllWag]bu%@uG!TEQ)+BW88-]NTfe}{A]]+7wW3^t^K9W;kIo');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
