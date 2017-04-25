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
define('DB_NAME', 'cv43457_0');

/** MySQL database username */
define('DB_USER', 'cv43457_0');

/** MySQL database password */
define('DB_PASSWORD', 'qweasdzxc777');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
//define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
//define('DB_COLLATE', '');

//define( 'WP_DEBUG', true );
//define( 'WP_DEBUG_LOG', true );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '*yjQsUVLg4ARn(EDqwfx8#uXYJR%r!0br=BTKQC;6,L*@zWQ6q@PP~QD%`5H,Q0T');
define('SECURE_AUTH_KEY',  '/V>F:C2B{u3S~w;u77gzG,*?Bn5s-lvL*(Ijh1WJr~]C~whiF&(K A:FOFiRfBr0');
define('LOGGED_IN_KEY',    'o8?&yOiwVSBirr`@k#pEauc;/FvsTNjV-(C;{$mJbW[A]E,(@C7dibx8! D!V1!s');
define('NONCE_KEY',        '&U.no9!n:+6%}5VRi8XZ@2x`e65V+;&Bb4wLe[utguO`f@[,Tsl~DcZDi@)WX`17');
define('AUTH_SALT',        'C+_wxZdo(@d|Tpls 2!Op;7t*yA/B2:_5gDFH@crUKGk34B*x1j(.{W^e`A, H2_');
define('SECURE_AUTH_SALT', ')_YIPhGlD$8mA)t70g&/,rVNhBGM~f@s ,sp/qGNTM#4t,k#Ew?cJSkr_Wx:L;8A');
define('LOGGED_IN_SALT',   '3{kZd].y,x1kx*>=psDCHcQxsw012`7tf 3l[!jycp6WS65ZBa<=I3-rTixC7af^');
define('NONCE_SALT',       'RD pTOlSGx@uMR.+F-|?:Mc71Mm8uF|BH^BhhDF|MZ!kaeyo*.Y8}f2mA{)qS/y_');

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

define('WP_MEMORY_LIMIT', '256M');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
