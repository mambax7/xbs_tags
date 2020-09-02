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
 * Define metatags for the current page if eligible
 *
 * This file needs to be included in the Xoops main footer.php file
 *  - You will need to hack xoops to do this
 *  - Read the manual!
 *
 * @copyright     Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 * @package       TAGS
 * @subpackage    Interface
 * @version       1.1
 */

/**
 * MetaTags constant definitions
 */

use XoopsModules\Xbstags\Helper;

require_once XOOPS_ROOT_PATH . '/modules/xbstags/include/defines.php';

//check to see if MetaTags module is active
if (mb_strstr(XOOPS_VERSION, 'XOOPS 2.2')) {
    $moduleHandler = xoops_getHandler('module');

    $module = $moduleHandler->create();

    $module->loadInfoAsVar(TAGS_DIR);

    $v2014 = false; //see below
} else {
    $module = XoopsModule::getByDirname(TAGS_DIR);

    //Need to know if we are running Xoops V2.0.14 as

    //they changed theme handling in it

    $v2014 = (XOOPS_VERSION == 'XOOPS 2.0.14');
}
//check validity of $module
if (!$module) {
    return;
}
if (!isset($module)) {
    return;
}
if (!$module->getVar('isactive')) {
    return;
}

//get current page
/**
 * @global array HTTP Server variables
 */
global $_SERVER;
/**
 * @global abject Xoops Template Object
 */
global $xoopsTpl;

$page = $_SERVER['SCRIPT_NAME'];
//subtract the root directory from the page name
$t1  = explode('//', XOOPS_URL);  //get rid of 'http://'
$t2  = explode('/', $t1[1]); //split up domain name from any subdirectories
$c   = count($t2);
$sub = '';
if ($c > 1) { // there must be subdirectories
    for ($i = 1; $i < $c; $i++) {
        $sub .= '/' . $t2[$i];
    }

    $t1 = explode($sub, $page);  //and get rid of sub directories

    $page = $t1[1];
}
//$page is now our key into the MetaTags database

//get the Page object for our page if it exists
$tagsHandler = Helper::getInstance()->getHandler('Page');
if ($tagsPage = $tagsHandler->getByKey($page)) {
    //get TAGS module configs

    $configs = getTAGSModConfigs();

    //xoops text sanitizer

    $myts = MyTextSanitizer::getInstance();

    //Set Page Title

    if ($configs['use_title']) {
        //Set the page title from MetaTags database

        //This overwrites the default site slogan

        $content = $myts->undoHtmlSpecialChars($tagsPage->getVar('tags_title'));

        $xoopsTpl->assign('xoops_pagetitle', strip_tags($content));
    }

    //Set Page Description

    if ($configs['use_desc']) {
        //Set the description from MetaTags database

        //This overwrites the default site description

        $content = $myts->undoHtmlSpecialChars($tagsPage->getVar('tags_desc'));

        if ($v2014) {
            $xoopsTpl->_tpl_vars['xoTheme']->metas['meta']['description'] = strip_tags($content);
        }

        $xoopsTpl->assign('xoops_meta_description', strip_tags($content));
    }

    //Set Page Keywords dependent on setting for page

    switch ($tagsPage->getVar('tags_config')) {
        case TAGS_KEYMETHD_0:       //retrieve list from database
            $content = $tagsPage->getVar('tags_keyword');
            break;
        case TAGS_KEYMETHD_1 || TAGS_KEYMETHD_2 || TAGS_KEYMETHD_3:
            $content = $tagsPage->getKeywords($xoopsTpl, $tagsPage->getVar('id'));
            break;
        case TAGS_KEYMETHD_4:       //Use xoops default keyword list
            unset($content);
            break;
        default:
            unset($content);
            break;
    }

    if (isset($content)) {
        if ($v2014) {
            $xoopsTpl->_tpl_vars['xoTheme']->metas['meta']['keywords'] = $content;
        }

        $xoopsTpl->assign('xoops_meta_keywords', $content);
    }
}
