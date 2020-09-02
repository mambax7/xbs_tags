<?php declare(strict_types=1);

/**
 * Module administration language constant definitions
 *
 * This is the language specific file for UK English language
 * This file is requried only for Xoops <2.2  At 2.2 facilities will exist to
 * have a site wide administration menu that will supercede the need for this file
 *
 * @copyright (c) 2004, Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 * @package       SACC
 * @subpackage    Definitions
 * @access        private
 * @version       1
 */

/**#@+
 * Xoops required defines
 * only required for Xoops V2.0
 */
if (!mb_strstr(XOOPS_VERSION, 'XOOPS 2.2')) {
    define('_AD_NORIGHT', "You don't have the right to access this area");

    define('_AD_ACTION', 'Action');

    define('_AD_EDIT', 'Edit');

    define('_AD_DELETE', 'Delete');

    define('_AD_LASTTENUSERS', 'Last 10 registered users');

    define('_AD_NICKNAME', 'Nickname');

    define('_AD_EMAIL', 'Email');

    define('_AD_AVATAR', 'Avatar');

    define('_AD_REGISTERED', 'Registered'); //Registered Date

    define('_AD_PRESSGEN', 'This is your first time to enter the administration section. Press the button below to proceed.');

    define('_AD_LOGINADMIN', 'Logging you in..');
}
/**#@-*/

/**#@+
 * Module Admin global menu definitions
 */
define('_AD_MODADMIN', 'Administration');
define('_AD_MOD_CONFIG', 'Module Config');
define('_AD_MOD_HOME', 'Module Home Page');
define('_AD_DOCS', 'Module Documentation');
define('_AD_SUPPORT', 'Module Support');
define('_AD_DONATIONS', 'Module Donations');
