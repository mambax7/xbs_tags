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
 * @subpackage    Page
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
 * A Page MetaTags Object
 *
 * @package    TAGS
 * @subpackage Page
 * @author     Ashley Kitson (http://xoobs.net)
 * @copyright  2006, Ashley Kitson, UK
 */
class Page extends \XoopsObject
{
    /**
     * Constructor
     *
     * The following variables  are set for retrieval with ->getVar()
     * {@source 2 10}
     */
    public function __construct()
    {
        $configs = getTAGSModConfigs();

        $this->initVar('id', XOBJ_DTYPE_INT, null, true);

        $this->initVar('mid', XOBJ_DTYPE_INT, null, true);

        $this->initVar('pid', XOBJ_DTYPE_INT, 0);

        $this->initVar('tags_fname', XOBJ_DTYPE_TXTBOX, null, true, 255);

        $this->initVar('tags_title', XOBJ_DTYPE_TXTBOX, null, false, 255);

        $this->initVar('tags_desc', XOBJ_DTYPE_TXTAREA);

        $this->initVar('tags_keyword', XOBJ_DTYPE_OTHER);

        $this->initVar('tags_config', XOBJ_DTYPE_OTHER, $configs['key_method'], true);

        $this->initVar('tags_maxkeyword', XOBJ_DTYPE_INT, $configs['max_keys'], true);

        $this->initVar('tags_minkeylen', XOBJ_DTYPE_INT, $configs['min_keylen'], true);

        $this->initVar('tags_modname', XOBJ_DTYPE_OTHER, null, false);

        parent::__construct(); //call ancestor constructor
    }

    /**
     * Function: get a list of keywords from a template object
     *
     * Parses a XoopsTpl object to create a keyword array.
     * This function is substantially based on work by Hervé Thouzard (http://www.herve-thouzard.com)
     * in his xoogle for xoops hack.  However, whereas Herve has content for creation of keywords
     * passed to his function, this finds content in a template object
     *
     * @param XoopsTpl $template XoopsTpl template object
     * @param int      $pageId   internal identifier for page
     * @return string list of keywords
     * @version 2
     */
    public function getKeywords($template, $pageId)
    {
        global $xoopsConfig;

        //get the page output

        $content = $template->fetch($xoopsConfig['theme_set'] . '/theme.html');

        //strip out garbage

        $myts = MyTextSanitizer::getInstance();

        $content = str_replace('<br>', ' ', $content);

        $content = $myts->undoHtmlSpecialChars($content);

        $content = strip_tags($content);

        $content = mb_strtolower($content);

        $search_pattern = ['&nbsp;', "\t", "\r\n", "\r", "\n", ',', '.', "'", ';', ':', ')', '(', '"', '?', '!', '{', '}', '[', ']', '<', '>', '/', '+', '-', '_', '\\', '*'];

        $replace_pattern = [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        $content = str_replace($search_pattern, $replace_pattern, $content);

        //create keywords array

        $keywords = explode(' ', $content);

        //if keyword tracking is enable then save list to database

        // This is the unsorted complete list

        $modConfig = getTAGSModConfigs();

        if (1 == (int)$modConfig['track_keys']) {
            $trackHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Track');

            $trackHandler->saveTrack($keywords, $pageId);
        }

        switch ($this->getVar('tags_config')) {
            case TAGS_KEYMETHD_1:   // Returns keywords in the same order that they were created in the text
                $keywords = array_unique($keywords);
                break;
            case TAGS_KEYMETHD_2:   // the keywords order is made according to the reverse keywords frequency (so the less frequent words appear in first in the list)
                $keywords = array_count_values($keywords);
                asort($keywords);
                $keywords = array_keys($keywords);
                break;
            case TAGS_KEYMETHD_3:   // Same as previous, the only difference is that the most frequent words will appear in first in the list
                $keywords = array_count_values($keywords);
                arsort($keywords);
                $keywords = array_keys($keywords);
                break;
        } //end switch

        //strip out words that are too short

        $minlen = (int)$this->getVar('tags_minkeylen');

        foreach ($keywords as $keyword) {
            if (mb_strlen($keyword) >= $minlen && !is_numeric($keyword)) {
                $tmp[] = $keyword;
            }
        }

        //remove blacklisted words if they exist

        $listHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Taglist');

        if ($blist = $listHandler->getByKey(TAGS_LISTBLACK)) {
            $blacklist = $blist->getVar('keywords');

            $tmp = array_diff($tmp, $blacklist);
        }

        //reduce array to max number of keywords

        $tmp = array_slice($tmp, 0, (int)$this->getVar('tags_maxkeyword'));

        //add whitelisted words if they exist

        if ($wlist = $listHandler->getByKey(TAGS_LISTWHITE)) {
            $whitelist = $wlist->getVar('keywords');

            $tmp = array_merge($tmp, $whitelist);
        }

        //shuffle the words so that they appear differently each time

        shuffle($tmp);

        return implode(',', $tmp);
    }
    //end function getKeywords
}//end class Page
