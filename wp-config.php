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
define('DB_NAME', 'avada_without_db');

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
define('AUTH_KEY',         'T[GP#K,sZbCg9Fu!5xs}YNiu&vP82eD483SK+&B~5RdVbXHC1[JhE@3LVL* gkXo');
define('SECURE_AUTH_KEY',  '>FeL:CszyMtw9rmL@yXyc.H>Nr`q&LqAKi_?2Ftx695:)[}P<t=e0%={`r9UTJa8');
define('LOGGED_IN_KEY',    'kq}A0B5R 3o<}FMYcH^*jH.WY|Q5]j@w^Oo.)xBLI!1/C1t?oAOpY=&2ZzI^=a7m');
define('NONCE_KEY',        '?;s7YQ^P>KqH,wG #GLPW4kB07;Y7R=]}%#uw967QXjB46qvWUU9T7|+!B$+jGbG');
define('AUTH_SALT',        ' `n+Xx/ vrstXw9:]>.>G%ZYZ/zQy%TV$yG`>`2^OjjFauym,h`8zVY7T>%[7M$n');
define('SECURE_AUTH_SALT', 'wlj1azuq;etCwF-,DJj6]P;H/.=?Si0NY-~F$&i4O`hn>ZwwVGGGZqnA fYk`w4/');
define('LOGGED_IN_SALT',   'Z7_PJY_)<m{cw(sx.<o9NZl57ecLaZPe6YO!b^!Z[g5{l XwBF*MXOR6Z`u<t<s,');
define('NONCE_SALT',       'w~kJUD-3|_>?[=|*Qr:mk+,|n1+AyP/<m,vqR)>qu|Qn+>sasTTF}umF35~G)-1W');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'awd_';

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
