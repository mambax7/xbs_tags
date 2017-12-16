<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author:    Ashley Kitson                                                  //
// Copyright: (c) 2004, Ashley Kitson
// URL:       http://xoobs.net                                               //
// Project:   The XOOPS Project (http://www.xoops.org/)                      //
// Module:    XBS MetaTags (TAGS)                                            //
// ------------------------------------------------------------------------- //
/**
* Programing specific definitions 
*
* Constant definitions that are programming specific rather than 
* module or language specific
*
* @author Ashley Kitson http://xoobs.net
* @copyright 2005 Ashley Kitson, UK
* @package TAGS
* @subpackage Definitions
* @version 1 
*/

/**#@+
 * Constants for paths to XBS ADRR objects
 *
 */

define('TAGS_DIR','xbs_tags');
define('TAGS_PATH',XOOPS_ROOT_PATH."/modules/".TAGS_DIR);
define('TAGS_URL',XOOPS_URL."/modules/".TAGS_DIR);
/**#@-*/

/**
* Function: Get the current module's configuration options 
*
* Because TAGS can be nested inside other modules the $xoopsModuleConfig
* variable will be pointing to whatever module is currently in scope
* We therefore need to retrieve the TAGS options
*
* @version 1
* @access private
* @return array Module config options
*/
function getTAGSModConfigs() {
	static $TAGSModuleConfig;
	if (isset($TAGSModuleConfig)) {
		return $TAGSModuleConfig;
	}
	global $xoopsDB;
    $modHandler =& new XoopsModuleHandler($xoopsDB);
	$Module =& $modHandler->getByDirname(TAGS_DIR);
	if ($Module) {
		$config_handler =& xoops_gethandler('config');
		$TAGSModuleConfig =& $config_handler->getConfigsByCat(0, $Module->getVar('mid'));
		return $TAGSModuleConfig;
	} else { //module couldn't be instantiated - usually because we are trying to install the module and it doesn't exist yet!
		return false;
	}
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
} else { //values assigned as backstop defaults
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
}
/**#@-*/



/**#@+
 * Constant defs for tables used by TAGS
 *
 */
define("TAGS_TBL_TAGS","tags_index");      	//TAGS Configuration
define("TAGS_TBL_TRACK","tags_track");      //TAGS keyword tracking
define("TAGS_TBL_LIST","tags_list");      	//TAGS keyword lists
/**#@-*/

/**#@+
 * Other constant definitions
 */
define("TAGS_KEYMETHD_0","db");
define("TAGS_KEYMETHD_1","textorder");
define("TAGS_KEYMETHD_2","leastorder");
define("TAGS_KEYMETHD_3","mostorder");
define("TAGS_KEYMETHD_4","xoops");
define("TAGS_CFGMETHODXOOPS",0);	//The Xoops method
define("TAGS_CFGMETHODTAGS",1);		//The MetaTags method
define("TAGS_LISTBLACK",'black');   //a blacklist
define("TAGS_LISTWHITE",'white');   //a whitelist
define("TAGS_LISTPAGE",'page');     //a pagelist

/**#@-*/

/**
 * Logging functionality
 */
require_once(TAGS_PATH.'/include/xbsnotice.php');
?>