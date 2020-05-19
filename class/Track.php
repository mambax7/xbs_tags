<?php declare(strict_types=1);

namespace XoopsModules\Xbstags;

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
// Copyright: (c) 2006, Ashley Kitson                                        //
// URL:       http://xoobs.net                                               //
// Project:   The XOOPS Project (https://xoops.org/)                      //
// Module:    XBS MetaTags (TAGS)                                            //
// ------------------------------------------------------------------------- //
/**
 * Classes used by XBS MetaTags system
 *
 * @package       TAGS
 * @subpackage    Track
 * @access        private
 * @author        Ashley Kitson http://xoobs.net
 * @copyright (c) 2006 Ashley Kitson, Great Britain
 */

/**
 * Require Xoops kernel objects so we can extend them
 */
require_once XOOPS_ROOT_PATH . '/kernel/object.php';
/**
 * MetaTags constant defines
 */
require_once XOOPS_ROOT_PATH . '/modules/xbstags/include/defines.php';
/**
 * @global Xoops configuration
 */
global $xoopsConfig;
xoops_loadLanguage('main');

/**
 * A Page Tracking Object
 *
 * @package    TAGS
 * @subpackage Track
 * @author     Ashley Kitson (http://xoobs.net)
 * @copyright  2006, Ashley Kitson, UK
 */
class Track extends \XoopsObject
{
    /**
     * Constructor
     *
     * The following variables  are set for retrieval with ->getVar()
     * {@source 2 10}
     */
    public function __construct()
    {
        $this->initVar('id', XOBJ_DTYPE_INT, null, true);

        $this->initVar('pid', XOBJ_DTYPE_INT, null, true);

        $this->initVar('keywords', XOBJ_DTYPE_OTHER);

        parent::__construct(); //call ancestor constructor
    }
}//end class Track
