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
 * Admin menu declaration
 *
 * This script conforms to the Xoops standard for admin/menu.php
 *
 * @copyright (c) 2004, Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 * @package       TAGS
 * @subpackage    Admin
 * @version       1
 * @access        private
 */

/**
 * @global Xoop Configuration
 */

use Xmf\Module\Admin;
use XoopsModules\Xbstags\Helper;

include dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName      = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);
/** @var \XoopsModules\Xbstags\Helper $helper */
$helper = Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');
$helper->loadLanguage('admin');

$pathIcon32 = Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->url($helper->getModule()->getInfo('modicons32'));
}

/**
 * Whilst you can link your menu options to a single file, typically admin/index.php
 * and use a switch statement on a variable passed to it from here, to keep things
 * simple, use one script per menu option;
 */
$adminmenu              = [];
$i                      = 0;
$adminmenu[$i]['title'] = _MI_XBSTAGS_MENU_HOME;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/home.png';
$i++;
$adminmenu[$i]['title'] = _AM_XBSTAGS_ADMENU1;
$adminmenu[$i]['link']  = 'admin/main.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/administration.png';
$i++;
$adminmenu[$i]['title'] = _AM_XBSTAGS_ADMENU2;
$adminmenu[$i]['link']  = 'admin/update.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/update.png';
$i++;
$adminmenu[$i]['title'] = _AM_XBSTAGS_ADMENU3;
$adminmenu[$i]['link']  = 'admin/blacklist.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/delete.png';
$i++;
$adminmenu[$i]['title'] = _AM_XBSTAGS_ADMENU4;
$adminmenu[$i]['link']  = 'admin/whitelist.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/button_ok.png';
$i++;
$adminmenu[$i]['title'] = _AM_XBSTAGS_ADMENU5;
$adminmenu[$i]['link']  = 'admin/tracks.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/cart_add.png';
$i++;
$adminmenu[$i]['title'] = _MI_XBSTAGS_MENU_ABOUT;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/about.png';
$i++;
$adminmenu[$i]['title'] = _AM_XBSTAGS_ADMENU6;
$adminmenu[$i]['link']  = 'admin/help.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/faq.png';
