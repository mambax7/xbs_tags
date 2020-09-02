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
 * @copyright    The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use Xmf\Module\Admin;
use XoopsModules\Xbstags\Helper;

include dirname(__DIR__) . '/preloads/autoloader.php';

require dirname(__DIR__, 3) . '/include/cp_header.php';
require $GLOBALS['xoops']->path('www/class/xoopsformloader.php');
//require  dirname(__DIR__) . '/include/common.php';

$moduleDirName = basename(dirname(__DIR__));

/** @var \XoopsModules\Xbstags\Helper $helper */
$helper = Helper::getInstance();

/** @var \Xmf\Module\Admin $adminObject */
$adminObject = Admin::getInstance();

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('common');
$helper->loadLanguage('main');

xoops_cp_header();

/**
 * Include the CDM system constant defines as SACC relies on CDM
 */
require_once XOOPS_ROOT_PATH . '/modules/xbscdm/include/defines.php';
/**
 * Include SACC constant defines
 */
require_once dirname(__DIR__) . '/include/defines.php';

/**
 * CDM functions
 */
//require_once CDM_PATH . '/include/functions.php';
/**
 * SACC functions
 */
//require_once TAGS_PATH . '/include/functions.php';

require_once __DIR__ . '/functions.php';
