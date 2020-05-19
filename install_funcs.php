<?php declare(strict_types=1);

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://xoops.org>                             //
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
// Copyright: (c) 2005, Ashley Kitson                                        //
// URL:       http://xoobs.net                                               //
// Project:   The XOOPS Project (https://xoops.org/)                      //
// Module:    XBS MetaTags (TAGS)                                             //
// ------------------------------------------------------------------------- //

/**
 * Installation callback functions
 *
 * Functions called during the module installation, update or delete process
 *
 * @author     Ashley Kitson http://xoobs.net
 * @copyright  2005 Ashley Kitson, UK
 * @package    TAGS
 * @subpackage Installation
 * @access     private
 * @version    1
 */

/**
 * Must have module defines
 */
require_once XOOPS_ROOT_PATH . '/modules/xbstags/include/defines.php';

/**
 * Function: Module Update callback
 *
 * Called during update process to alter data table structure or values in tables
 *
 * @param xoopsModule &$module     handle to the module object being updated
 * @param int          $oldVersion version * 100 prior to update
 * @return bool True if successful else False
 * @version 1
 */
function xoops_module_update_xbs_tags(&$module, $oldVersion)
{
    global $xoopsDB;

    if ($oldVersion < 30) { //upgrading from any version prior to .03
        $sql = 'CREATE TABLE ' . $xoopsDB->prefix('tags_track') . ' (id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,tid SMALLINT(5) NOT NULL,keywords TEXT NULL,PRIMARY KEY(id),INDEX k_mid(tid))';

        if (!$result = $xoopsDB->queryF($sql)) {
            return false;
        }
    }

    if ($oldVersion < 40) { //upgrading from any version prior to .04
        $sql = 'CREATE TABLE ' . $xoopsDB->prefix('tags_list') . " (id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,typ ENUM('black','white','page') DEFAULT 'page',pid SMALLINT(5) NOT NULL,keywords TEXT NULL,PRIMARY KEY(id),INDEX k_pid(pid))";

        if (!$result = $xoopsDB->queryF($sql)) {
            return false;
        }

        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tags_track') . ' CHANGE tid pid SMALLINT(5) DEFAULT 0';

        if (!$result = $xoopsDB->queryF($sql)) {
            return false;
        }
    }

    //notify xoobs.net of update

    xbsTagsLogNotify('Updated');

    return true;
}//end function

/**
 * Function: Module Post Install callback
 *
 *
 * @param xoopsModule &$module Handle to module object being installed
 * @return bool True if successful else False
 * @version 2
 */
function xoops_module_install_xbs_tags(&$module)
{
    //The basic SQL install is done via the SQL script

    // This finds every module userside menu script and inserts it into the TAGS database

    global $xoopsDB, $xoopsConfig;

    //get the system module id.  This changes between XOOPS V2.0 and 2.2

    $sql = 'SELECT mid FROM ' . $xoopsDB->prefix('modules') . " WHERE name = 'System'";

    if (!$result = $xoopsDB->query($sql)) {
        return false;
    }

    $arr = $xoopsDB->fetchArray($result);

    $sysid = $arr['mid'];

    //Insert the site main page

    $sql = 'INSERT INTO ' . $xoopsDB->prefix('tags_index') . '(mid,tags_title,tags_fname) VALUES (' . $sysid . ',' . $xoopsDB->quoteString($xoopsConfig['sitename']) . ", '/index.php')";

    if (!$result = $xoopsDB->queryF($sql)) {
        return false;
    }

    //get main page description

    $sql = 'SELECT conf_value FROM ' . $xoopsDB->prefix('config') . ' WHERE conf_modid = ' . $sysid . " AND conf_name = 'meta_description'";

    if (!$result = $xoopsDB->queryF($sql)) {
        return false;
    }

    $arr = $xoopsDB->fetchArray($result);

    //update main page description

    $sql = 'update ' . $xoopsDB->prefix('tags_index') . ' set tags_desc =' . $xoopsDB->quoteString($arr['conf_value']) . ' where mid = ' . $sysid;

    if (!$result = $xoopsDB->queryF($sql)) {
        return false;
    }

    /**
     * V0.2 (V1RC2)
     * Only the min page (system/index.php) is included in this update
     * as all other page inclusions are done via the update page in the
     * module admin.  It can handle the new metatags_info.php files
     */

    /**
     * V1 Final
     * Fixed to work with Xoops V2.0.14 as theme handling changed by
     * xoops developers.
     */

    //notify xoobs.net of install

    xbsTagsLogNotify('Installed');

    return true;
}//end function

/**
 * Function: Module deletion callback
 *
 * TAGS tables are deleted via the Xoops uninstaller
 *
 * @param xoopsModule &$module Handle to module object being installed
 * @return bool True if successful else False
 * @version 1
 */
function xoops_module_uninstall_xbs_tags(&$module)
{
    //Notify Xoobs.net of uninstall

    xbsTagsLogNotify('Uninstall');

    return true;
}//end function
