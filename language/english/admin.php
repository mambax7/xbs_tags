<?php declare(strict_types=1);

/**
 * Module administration language constant definitions
 *
 * This is the language specific file for UK English language
 *
 * @copyright     Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 * @package       TAGS
 * @subpackage    Definitions
 * @access        private
 * @version       1
 */

/**#@+
 * Constants for Admin menu - non language specific
 */

/**
 * Admin menu parameters
 *
 * These MUST follow the format _AM_<ModDir>_URL_DOCS etc
 * so that the xoops_module_admin_header functions can work.  The suffix after <modDir> is not optional!
 * Leave them commented out if you do not have the functionality for your module
 *
 * Relative url from module directory for documentation
 */
define('_AM_XBS_XBSTAGS_URL_DOCS', 'admin/help.php');
/**
 * Absolute url for module support site
 */
define('_AM_XBS_XBSTAGS_URL_SUPPORT', 'http://www.xoobs.net/modules/newbb/viewforum.php?forum=7');
/**
 * absolute url for module donations site
 *
 * //define("_AM_XBS_XBSTAGS_URL_DONATIONS","");
 */

/**
 * Module configuration option - MUST follow the format _AM_<ModDir>_MODCONFIG
 *
 * Value MUST be "xoops", "module" or "none"
 */
define('_AM_XBS_XBSTAGS_MODCONFIG', 'xoops');
/**
 * If module configuration option = "module" then define the name of the script
 * to call for module configuration.  This is relative to modDir/admin/
 *
 * MUST follow the format _AM_<ModDir>_MODCONFIGURL
 */
//define("_AM_XBS_XBSTAGS_MODCONFIGURL","TAGSConfig.php");
/**
 * TAGS config is done via CDM so the config page redirects there
 */
//define("_AM_XBS_XBSTAGS_MODCONFIGREDIRECT","Configuration is done via the CDM system. You will shortly be redirected there.");

/**#@-*/

/**#@+
 * Constants for Admin menu - language specific
 */

define('_AM_XBSTAGS_CANCELEDIT', 'Page details edit cancelled');
define('_AM_XBSTAGS_CANCELUPDT', 'Page details update cancelled');
define('_AM_XBSTAGS_UPDTFAIL', 'Update failed');
define('_AM_XBSTAGS_UPDTOK', 'Update succeeded');

//Admin menu breadcrumb titles
define('_AM_XBSTAGS_ADMENU1', 'Page Index'); //display list of pages for which MetTags is operative
define('_AM_XBSTAGS_ADMENU2', 'Update');     //update MetaTags index with new module details
define('_AM_XBSTAGS_ADMENU3', 'Blacklist');   //process tracking data for keywords and allow user to create blacklist
define('_AM_XBSTAGS_ADMENU4', 'Whitelist');   //Create master page whitelist that is always added to a pages keywords
define('_AM_XBSTAGS_ADMENU5', 'Tracking Control');   //Tracks admin
define('_AM_XBSTAGS_ADMENU6', 'Docu');

//Titles for Page Index Table
define('_AM_FRM1_COL1', 'Id');
define('_AM_FRM1_COL2', 'Module');
define('_AM_FRM1_COL3', 'Script');
define('_AM_FRM1_COL4', 'Key Method');
define('_AM_FRM1_COLACTION', 'Action');

//Titles for Page Edit form
define('_AM_FRM2_TITLE', 'Page MetaTags Edit');
define('_AM_FRM2_COL1', 'Module Name');
define('_AM_FRM2_COL2', 'Script Name');
define('_AM_FRM2_COL3', 'Page Title');
define('_AM_FRM2_COL4', 'Page Description');
define('_AM_FRM2_COL5', 'Keywords');
define('_AM_FRM2_COL6', 'Key Method');
define('_AM_FRM2_COL7', 'Max. Key Words');
define('_AM_FRM2_COL8', 'Min. Key Length');
define('_AM_FRM2_COL9', 'Tracked Words');

//Titles for Page Update Form
define('_AM_FRM3_TITLE', 'Select Module to add to MetaTags');
define('_AM_FRM3_COL1', 'Module Name');

//Titles for blacklist form
define('_AM_FRM4_TITLE', 'Blacklist administration');
define('_AM_FRM4_COL1', "Blacklist\r(contain regex in /  /)");
define('_AM_FRM4_COL2', 'Tracked words');
define('_AM_FRM4_COL2a', 'Add words to blacklist');
define('_AM_FRM4_COL2b', "Either you haven't turned tracking on in the module configuration<br>or no tracks have yet been captured to provide a words list");

//Title for whitelist form
define('_AM_FRM5_TITLE', 'Whitelist administration');
define('_AM_FRM5_COL1', 'Whitelist');

//Titles for track Index Table
define('_AM_FRM6_COL1', 'Id');
define('_AM_FRM6_COL2', 'Module');
define('_AM_FRM6_COL3', 'Script');
define('_AM_FRM6_COL4', 'No. Tracks');
define('_AM_FRM6_COLACTION', 'Action');
define('_AM_FRM6_FOOTER', 'Turn tracking on/off in module configuration');

//Title for track view page
define('_AM_FRM7_TITLE', 'Track');
define('_AM_FRM7_COL1', 'Module');
define('_AM_FRM7_COL2', 'Script Name');
define('_AM_FRM7_COL3', 'Tracked Words');

//buttons
define('_AM_XBSTAGS_INSERT', 'Insert');
define('_AM_XBSTAGS_BROWSE', 'Browse');
define('_AM_XBSTAGS_SUBMIT', 'Submit');
define('_AM_XBSTAGS_CANCEL', 'Cancel');
define('_AM_XBSTAGS_RESET', 'Reset');
define('_AM_XBSTAGS_EDIT', 'Edit');
define('_AM_XBSTAGS_DEL', 'Delete');
define('_AM_XBSTAGS_GO', 'Go');

//button labels
define('_AM_XBSTAGS_INSERT_DESC', 'Create a new record');
