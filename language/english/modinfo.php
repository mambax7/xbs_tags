<?php declare(strict_types=1);

/**
 * Module installation language definitions
 *
 * English UK language definitions for module installation
 * Read the source file for definitions
 *
 * @copyright (c) 2004, Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 * @package       TAGS
 * @subpackage    Definitions
 * @access        private
 * @version       1
 */

/**
 * The name of this module
 */
define('_MI_XBSTAGS_NAME', 'XBS MetaTags');

/**
 * A brief description of this module
 */
define('_MI_XBSTAGS_DESC', 'Dynamically generated and module specific Meta Tag generator for Xoops');

/**#@+
 * Admin menu titles
 */
/*
define("_MI_XBSTAGS_ADMENU1","Organisations");
define("_MI_XBSTAGS_ADMENU2","Account Setup");
*/
/**#@-*/

/**#@+
 * Configuration item names and descriptions
 */

define('_MI_XBSTAGS_USETITLESNAME', 'Page title to use');
define('_MI_XBSTAGS_USETITLESDESC', 'Select the MetaTags page title or the<br>default Xoops page title to display for pages');
define('_MI_XBSTAGS_USESDESCNAME', 'Page description');
define('_MI_XBSTAGS_USEDESCDESC', 'Select the MetTags page description or the<br>default Xoops page description to display for pages');
define('_MI_XBSTAGS_KEYMETHODNAME', 'Keyword generation method');
define('_MI_XBSTAGS_KEYMETHODDESC', 'Generate a keyword list according to this setting for a page (default method)');
define('_MI_XBSTAGS_MAXKEYWORDNAME', 'Maximum keywords');
define('_MI_XBSTAGS_MAXKEYWORDDESC', 'Maximum number of keywords to generate (default value)');
define('_MI_XBSTAGS_MINKEYWORDNAME', 'Min. Keyword Length');
define('_MI_XBSTAGS_MINKEYWORDDESC', 'Minimum length of a word that can be included in the keyword list (default length)');
define('_MI_XBSTAGS_TRKKEYNAME', 'Track Keywords');
define('_MI_XBSTAGS_TRKKEYDESC', 'Switch on the keyword tracking feature');
define('_MI_XBSTAGS_MAXTRKNAME', 'Max. Track Pages');
define('_MI_XBSTAGS_MAXTRKDESC', 'Maximum number of key tracks to store for any page');

define('_MI_XBSTAGS_KYD0', 'Use Database');
define('_MI_XBSTAGS_KYD1', 'Auto - text order');
define('_MI_XBSTAGS_KYD2', 'Auto - least frequent first');
define('_MI_XBSTAGS_KYD3', 'Auto - most frequent first');
define('_MI_XBSTAGS_KYD4', 'Use Xoops Default');
define('_MI_XBSTAGS_TTL0', 'Xoops Title');
define('_MI_XBSTAGS_TTL1', 'MetaTags Title');
define('_MI_XBSTAGS_DESC0', 'Xoops Description');
define('_MI_XBSTAGS_DESC1', 'MetaTags Description');

//Menu
define('_MI_XBSTAGS_MENU_HOME', 'Home');
define('_MI_XBSTAGS_MENU_01', 'Admin');
define('_MI_XBSTAGS_MENU_ABOUT', 'About');

//Help
define('_MI_XBSTAGS_DIRNAME', basename(dirname(__DIR__, 2)));
define('_MI_XBSTAGS_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_XBSTAGS_BACK_2_ADMIN', 'Back to Administration of ');
define('_MI_XBSTAGS_OVERVIEW', 'Overview');

//define('_MI_XBSTAGS_HELP_DIR', __DIR__);

//help multi-page
define('_MI_XBSTAGS_DISCLAIMER', 'Disclaimer');
define('_MI_XBSTAGS_LICENSE', 'License');
define('_MI_XBSTAGS_SUPPORT', 'Support');
