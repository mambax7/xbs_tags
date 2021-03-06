<?php declare(strict_types=1);

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Programing specific definitions
 *
 * Constant definitions that are programming specific rather than
 * module or language specific
 *
 * @copyright (c) 2004, Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 * @package       TAGS
 * @subpackage    Definitions
 * @version       1
 */

/**#@+
 * Constants for paths to XBS ADRR objects
 */

define('TAGS_DIR', 'xbstags');
define('TAGS_PATH', XOOPS_ROOT_PATH . '/modules/' . TAGS_DIR);
define('TAGS_URL', XOOPS_URL . '/modules/' . TAGS_DIR);
/**#@-*/

/**
 * Function: Get the current module's configuration options
 *
 * Because TAGS can be nested inside other modules the $xoopsModuleConfig
 * variable will be pointing to whatever module is currently in scope
 * We therefore need to retrieve the TAGS options
 *
 * @return array Module config options
 * @version 1
 * @access  private
 */
function getTAGSModConfigs()
{
    static $TAGSModuleConfig;

    if (isset($TAGSModuleConfig)) {
        return $TAGSModuleConfig;
    }

    global $xoopsDB;

    $moduleHandler = new \XoopsModuleHandler($xoopsDB);

    $Module = $moduleHandler->getByDirname(TAGS_DIR);

    if ($Module) {
        $configHandler = xoops_getHandler('config');

        $TAGSModuleConfig = $configHandler->getConfigsByCat(0, $Module->getVar('mid'));

        return $TAGSModuleConfig;
    }   //module couldn't be instantiated - usually because we are trying to install the module and it doesn't exist yet!

    return false;
}

/**#@+
 * Constants for configuration items
 *
 * $cfg is a copy of the TAGS module config array
 */
$cfg = getTAGSModConfigs();
if (isset($cfg)) {
    /*
    define('TAGS_CFG_DEFCURR',$cfg['def_currency']);
    define('TAGS_CFG_DEFORG',$cfg['def_org']);
    define('TAGS_CFG_USEPRNT',$cfg['use_parent']);
    define('TAGS_CFG_DECPNT',$cfg['dec_point']);
    */
}   //values assigned as backstop defaults
/**
 * @ignore
 */
//define('TAGS_CFG_DEFCURR','GBP');
/**
 * @ignore
 */
//define('TAGS_CFG_DEFORG',0);
/**
 * @ignore
 */
//define('TAGS_CFG_USEPRNT',0);
/**
 * @ignore
 */
//define('TAGS_CFG_DECPNT',2);

/**#@-*/

/**#@+
 * Constant defs for tables used by TAGS
 */
define('TAGS_TBL_TAGS', 'xbstags_index');       //TAGS Configuration
define('TAGS_TBL_TRACK', 'xbstags_track');      //TAGS keyword tracking
define('TAGS_TBL_LIST', 'xbstags_list');        //TAGS keyword lists
/**#@-*/

/**#@+
 * Other constant definitions
 */
define('TAGS_KEYMETHD_0', 'db');
define('TAGS_KEYMETHD_1', 'textorder');
define('TAGS_KEYMETHD_2', 'leastorder');
define('TAGS_KEYMETHD_3', 'mostorder');
define('TAGS_KEYMETHD_4', 'xoops');
define('TAGS_CFGMETHODXOOPS', 0);    //The Xoops method
define('TAGS_CFGMETHODTAGS', 1);     //The MetaTags method
define('TAGS_LISTBLACK', 'black');   //a blacklist
define('TAGS_LISTWHITE', 'white');   //a whitelist
define('TAGS_LISTPAGE', 'page');     //a pagelist

/**#@-*/

/**
 * Logging functionality
 */
require_once TAGS_PATH . '/include/xbsnotice.php';
