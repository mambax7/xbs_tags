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
// Copyright: (c) 2006, Ashley Kitson                                        //
// URL:       http://xoobs.net                                               //
// Project:   The XOOPS Project (https://xoops.org/)                      //
// Module:    XBS MetTags (TAGS)                                             //
// ------------------------------------------------------------------------- //
/**
 * Admin page functions
 *
 * @author     Ashley Kitson http://xoobs.net
 * @copyright  2006 Ashley Kitson, UK
 * @package    TAGS
 * @subpackage Admin
 * @access     private
 * @version    1
 * @param mixed $maxwords
 */

use XoopsModules\Xbstags\Form;

/**
 * Function: Display list of keywords to add to blacklist
 *
 * Display list of words to add/remove to/from blacklist
 *
 * @param int $maxwords number of words to display in keywords list
 * @version 1
 */
function adminSelectBlacklist($maxwords = 100)
{
    //get the existing blacklist

    $blistHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Taglist');

    if ($blist = $blistHandler->getByKey(TAGS_LISTBLACK)) {
        $blacklist = $blist->getVar('keywords');

        asort($blacklist);
    } else {
        $blacklist = [];
    }

    //get the keyword trackers

    $trackHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Track');

    if (!$tracks = $trackHandler->getAllWords($maxwords, TAGS_KEYMETHD_3, $blacklist)) {
        $tracks = [];
    } else { //index the words on themselves
        $tmp = [];

        asort($tracks);

        foreach ($tracks as $track) {
            $tmp[$track] = $track;
        }

        $tracks = $tmp;
    }

    /**
     * the form will display the blacklist as it stands.  If
     * tracking is off or there are no recorded tracks and appropriate
     * message is displayed else a multiselect word list is
     * displayed to allow users to select words to add to list.
     */

    //regular submit/cancel buttons tray

    $ftray = new \XoopsFormElementTray(_AM_FRM1_COLACTION);

    $submit = new \XoopsFormButton('', 'submit', _AM_TAGS_SUBMIT, 'submit');

    $cancel = new \XoopsFormButton('', 'cancel', _AM_TAGS_CANCEL, 'submit');

    $ftray->addElement($submit);

    $ftray->addElement($cancel);

    //tracked words tray

    if (0 == !count($tracks)) {
        $ftracktray = new \XoopsFormElementTray(_AM_FRM4_COL2a);

        $ftrack = new \XoopsFormSelect(_AM_FRM4_COL2, 'track', null, 20, false);

        $ftrack->addOptionArray($tracks);

        $ftrack->setExtra("multiple='multiple'");

        $insert = new \XoopsFormButton('', 'insert', _AM_TAGS_INSERT, 'button');

        $insert->setExtra("onclick = 'blistFormAdd()'");

        $ftracktray->addElement($ftrack);

        $ftracktray->addElement($insert);
    } else {
        $ftracktray = new \XoopsFormLabel(_AM_FRM4_COL2, _AM_FRM4_COL2b);
    }

    $editForm = new \XoopsThemeForm(_AM_FRM4_TITLE, 'blistform', 'blacklist.php');

    $fblack = new \XoopsFormTextArea(_AM_FRM4_COL1, 'blacklist', implode(',', $blacklist));

    $editForm->addElement($fblack);

    $editForm->addElement($ftracktray);

    $editForm->addElement($ftray);

    //write out the javascript for the form

    $js = "\n<script type='text/javascript'>\n<!--\n";

    $js .= "function blistFormAdd() {\n";

    $js .= "  for (c=0;c<document.blistform.track.options.length;c++) {\n";

    $js .= "    if (document.blistform.track.options[c].selected) {\n";

    $js .= "      document.blistform.blacklist.value += ','+document.blistform.track.options[c].value;\n";

    $js .= "    }\n";

    $js .= "  }\n";

    $js .= "\n}\n// -->\n</script>\n";

    echo $js;

    $editForm->display();
}//end function

/**
 * Updates the blacklist
 *
 * @param string $list comma seperated list of words
 */
function adminUpdateBlacklist($list)
{
    $blistHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Taglist');

    if (!$blist = $blistHandler->getByKey(TAGS_LISTBLACK)) {
        $blist = $blistHandler->create();

        $blist->setVar('typ', TAGS_LISTBLACK);

        $blist->setVar('pid', 0);
    }

    $list = trim($list, ',');

    $blist->setVar('keywords', explode(',', $list));

    if (!$blistHandler->insert($blist)) {
        redirect_header(TAGS_URL . '/admin/blacklist.php', 1, $tagsHandler->getError());
    } else {
        redirect_header(TAGS_URL . '/admin/blacklist.php', 1, _MD_TAGS_SAVEPAGE);
    }//end if
}//end function adminUpdateBlacklist

/**
 * Function: Display list of keywords to add to whitelist
 *
 * Display list of words to add/remove to/from whitelist
 *
 * @version 1
 */
function adminSelectWhitelist()
{
    //get the existing whitelist

    $wlistHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Taglist');

    if ($wlist = $wlistHandler->getByKey(TAGS_LISTWHITE)) {
        $whitelist = $wlist->getVar('keywords');

        asort($whitelist);
    } else {
        $whitelist = [];
    }

    //regular submit/cancel buttons tray

    $ftray = new \XoopsFormElementTray(_AM_FRM1_COLACTION);

    $submit = new \XoopsFormButton('', 'submit', _AM_TAGS_SUBMIT, 'submit');

    $cancel = new \XoopsFormButton('', 'cancel', _AM_TAGS_CANCEL, 'submit');

    $ftray->addElement($submit);

    $ftray->addElement($cancel);

    $editForm = new \XoopsThemeForm(_AM_FRM5_TITLE, 'wlistform', 'whitelist.php');

    $fwhite = new \XoopsFormTextArea(_AM_FRM5_COL1, 'whitelist', implode(',', $whitelist));

    $editForm->addElement($fwhite);

    $editForm->addElement($ftray);

    $editForm->display();
}//end function

/**
 * Updates the whitelist
 *
 * @param string $list comma seperated list of words
 */
function adminUpdateWhitelist($list)
{
    $blistHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Taglist');

    if (!$blist = $blistHandler->getByKey(TAGS_LISTWHITE)) {
        $blist = $blistHandler->create();

        $blist->setVar('typ', TAGS_LISTWHITE);

        $blist->setVar('pid', 0);
    }

    $list = trim($list, ',');

    $blist->setVar('keywords', explode(',', $list));

    if (!$blistHandler->insert($blist)) {
        redirect_header(TAGS_URL . '/admin/whitelist.php', 1, $tagsHandler->getError());
    } else {
        redirect_header(TAGS_URL . '/admin/whitelist.php', 1, _MD_TAGS_SAVEPAGE);
    }//end if
}//end function adminUpdateWhitelist

/**
 * Function: Display list of Pages
 *
 * Display list of pages to allow user to choose one to edit.
 * Also allow user to insert new page details
 *
 * @version 1
 */
function adminSelectPage()
{
    //initiate the form

    $cols = [_AM_FRM1_COL1, _AM_FRM1_COL2, _AM_FRM1_COL3, _AM_FRM1_COL4];

    $table = new Form\FormTable($cols, null, false, TAGS_URL . '/admin/main.php?new=1', TAGS_URL . '/admin/main.php?edit=', TAGS_URL . '/admin/main.php?delete=');

    $tagsHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Page');

    $tags = $tagsHandler->getAllPages();

    foreach ($tags as $tag) {
        $row = [$tag->getVar('id'), $tag->getVar('tags_modname'), $tag->getVar('tags_fname'), $tag->getVar('tags_config')];

        $table->addRow($row);
    }

    $table->display();
}//end function

/**
 * Function: Display page details form
 *
 * @param int $id id of page record to edit, if 0 then new page
 * @version 1
 */
function adminEditPage($id = 0)
{
    $id = (int)$id;

    $tagsHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Page');

    if (0 == $id) {
        $tags = $tagsHandler->create();
    } else {
        $tags = $tagsHandler->get($id);
    }

    $editForm = new \XoopsThemeForm(_AM_FRM2_TITLE, 'pageform', 'index.php');

    $fid = new \XoopsFormHidden('id', $id);

    if (0 == $id) {
        $fmid = new \XoopsFormHidden('falsemid', 0);

        $fpid = new \XoopsFormHidden('pid', 0);

        $fmodule = new FormSelectModule(_AM_FRM2_COL1, 'mid');

        $fname = new \XoopsFormText(_AM_FRM2_COL2, 'tags_fname', 30, 255);
    } else {
        $fmid = new \XoopsFormHidden('mid', $tags->getVar('mid'));

        $fpid = new \XoopsFormHidden('pid', $tags->getVar('pid'));

        $fmodule = new \XoopsFormLabel(_AM_FRM2_COL1, $tags->getVar('tags_modname'));

        $fname = new \XoopsFormLabel(_AM_FRM2_COL2, $tags->getVar('tags_fname'));
    }

    //Set up additional form fields

    $ftitle = new \XoopsFormText(_AM_FRM2_COL3, 'tags_title', 30, 255, $tags->getVar('tags_title'));

    $fdesc = new \XoopsFormTextArea(_AM_FRM2_COL4, 'tags_desc', $tags->getVar('tags_desc'));

    $fkeymethod = new FormSelectMethod(_AM_FRM2_COL6, 'tags_config', $tags->getVar('tags_config'));

    $fmaxkey = new \XoopsFormText(_AM_FRM2_COL7, 'tags_maxkeyword', 3, 3, $tags->getVar('tags_maxkeyword'));

    $fkeylen = new \XoopsFormText(_AM_FRM2_COL8, 'tags_minkeylen', 3, 3, $tags->getVar('tags_minkeylen'));

    //keywords tray if required

    $configs = getTAGSModConfigs();

    if (1 == $configs['track_keys']) {
        $trackHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Track');

        $pageWords = $trackHandler->getPageWords($id);

        if (count($pageWords) > 0) {
            $tmp = [];

            foreach ($pageWords as $word) {
                $tmp[$word] = $word;
            }

            $pageWords = $tmp;

            asort($pageWords);

            $ktray = new \XoopsFormElementTray(_AM_FRM2_COL5);

            $insert = new \XoopsFormButton('', 'insert', _AM_TAGS_INSERT);

            $insert->setExtra("onclick = 'keysFormAdd()'");

            $ftrack = new \XoopsFormSelect(_AM_FRM2_COL9 . ' ' . $insert->render(), 'track', null, 5, false);

            $ftrack->addOptionArray($pageWords);

            $ftrack->setExtra("multiple='multiple'");

            $ktray->addElement(new \XoopsFormTextArea('', 'tags_keyword', $tags->getVar('tags_keyword')));

            $ktray->addElement($ftrack);

            //write out the javascript for the form

            $js = "\n<script type='text/javascript'>\n<!--\n";

            $js .= "function keysFormAdd() {\n";

            $js .= "  for (c=0;c<document.pageform.track.options.length;c++) {\n";

            $js .= "    if (document.pageform.track.options[c].selected) {\n";

            $js .= "      document.pageform.tags_keyword.value += ','+document.pageform.track.options[c].value;\n";

            $js .= "    }\n";

            $js .= "  }\n";

            $js .= "\n}\n// -->\n</script>\n";

            echo $js;
        } else {
            $ktray = new \XoopsFormTextArea(_AM_FRM2_COL5, 'tags_keyword', $tags->getVar('tags_keyword'));
        }
    } else {
        $ktray = new \XoopsFormTextArea(_AM_FRM2_COL5, 'tags_keyword', $tags->getVar('tags_keyword'));
    }

    //buttons tray

    $ftray = new \XoopsFormElementTray(_AM_FRM1_COLACTION);

    $submit = new \XoopsFormButton('', 'submit', _AM_TAGS_SUBMIT, 'submit');

    $cancel = new \XoopsFormButton('', 'cancel', _AM_TAGS_CANCEL, 'submit');

    $ftray->addElement($submit);

    $ftray->addElement($cancel);

    //Assign elements to form

    $editForm->addElement($fid);

    $editForm->addElement($fmid);

    $editForm->addElement($fpid);

    $editForm->addElement($fmodule);

    $editForm->addElement($fname);

    $editForm->addElement($ftitle);

    $editForm->addElement($fdesc);

    $editForm->addElement($ktray);

    $editForm->addElement($fkeymethod);

    $editForm->addElement($fmaxkey);

    $editForm->addElement($fkeylen);

    $editForm->addElement($ftray);

    //$editForm->assign($xoopsTpl);

    $editForm->display();
} //end function displaySetForm

/**
 * Function: Save Page details
 *
 * Write MetTags data to database
 *
 * @version 1
 */
function adminSavepage()
{
    /**
     * @global array Form Post Variables
     */

    global $_POST;

    extract($_POST);

    /**
     * @global database object
     */

    global $xoopsDB;

    $tagsHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Page');

    if (0 == $id) {
        $tags = $tagsHandler->create();

        $sql = 'SELECT max(pid) AS pid FROM ' . $xoopsDB->prefix(TAGS_TBL_TAGS) . ' WHERE mid = ' . $mid;

        if ($result = $xoopsDB->query($sql)) {
            $res = $xoopsDB->fetchArray($result);

            $tags->setVar('pid', (int)$res['pid'] + 1);
        } else {
            $tags->setVar('pid', 0);
        }

        $tags->setVar('mid', $mid);

        $tags->setVar('tags_fname', $tags_fname);
    } else {
        $tags = $tagsHandler->get($id);
    }

    //save new values to object

    $tags->setVar('tags_title', $tags_title);

    $tags->setVar('tags_desc', $tags_desc);

    $tags->setVar('tags_keyword', trim($tags_keyword, ','));

    $tags->setVar('tags_config', $tags_config);

    $tags->setVar('tags_maxkeyword', $tags_maxkeyword);

    $tags->setVar('tags_minkeylen', $tags_minkeylen);

    if (!$tagsHandler->insert($tags)) {
        redirect_header(TAGS_URL . '/admin/index.php', 1, $tagsHandler->getError());
    } else {
        redirect_header(TAGS_URL . '/admin/index.php', 1, _MD_TAGS_SAVEPAGE);
    }//end if
} //end function adminSavePage

/**
 * Delete a metatags page
 *
 * @param int $id if of page to delete from MetaTags database
 */
function adminDeletePage($id)
{
    $id = (int)$id;  //check it is an integer

    if ($id > 0) {
        $tagsHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Page');

        $tags = $tagsHandler->get($id);

        if (!$tagsHandler->delete($tags)) {
            redirect_header(TAGS_URL . '/admin/index.php', 1, _MD_TAGS_ERRDEL);
        }
    } else {
        redirect_header(TAGS_URL . '/admin/index.php', 1, _MD_TAGS_ERRDEL);
    }

    redirect_header(TAGS_URL . '/admin/index.php', 1, _MD_TAGS_DELPAGE);
}

/**
 * Allow user to select modules to add definitions for
 */
function adminSelectUpdate()
{
    $fmodule = new Form\FormSelectNewModule(_AM_FRM3_COL1, 'mid', null, 10, true);

    if (count($fmodule->_options) > 0) {
        $ftray = new \XoopsFormElementTray(_AM_FRM1_COLACTION);

        $submit = new \XoopsFormButton('', 'submit', _AM_TAGS_SUBMIT, 'submit');

        $cancel = new \XoopsFormButton('', 'cancel', _AM_TAGS_CANCEL, 'submit');

        $ftray->addElement($submit);

        $ftray->addElement($cancel);

        $editForm = new \XoopsThemeForm(_AM_FRM3_TITLE, 'pageform', 'update.php');

        $editForm->addElement($fmodule);

        $editForm->addElement($ftray);

        $editForm->display();
    } else {
        redirect_header(TAGS_URL . '/admin/index.php', 1, _MD_TAGS_ERRNOMODS);
    }
}

/**
 * Update MetaTags database with new module details
 *
 * @param array $mids array of module ids
 * @return bool
 */
function adminUpdatePage($mids)
{
    /**
     * @global object Xoopos database object
     */

    global $xoopsDB;

    /**
     * @global objet Xoops configuration
     */

    global $xoopsConfig;

    foreach ($mids as $mid) {
        /**
         * Check first if there is a metatags config file in the module directory
         * If so, process it else do a seach in Xoops for data
         */

        $moduleHandler = new \XoopsModuleHandler($xoopsDB);

        $mod = $moduleHandler->get($mid);

        $dirname = $mod->getVar('dirname');

        $fname = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/metatags_info.php';

        unset($mod);

        unset($moduleHandler);

        if (file_exists($fname)) {  //get info from configuration file
            include $fname;

            if (isset($metatags)) {
                $pid = 0;

                foreach ($metatags as $page) {
                    //add directory to script name

                    $page['script_name'] = '/modules/' . $dirname . '/' . $page['script_name'];

                    $page['script_name'] = str_replace('//', '/', $page['script_name']);

                    $sql = sprintf(
                        'INSERT INTO %s (mid,pid,tags_title,tags_fname,tags_desc,tags_config,tags_keyword,tags_minkeylen,tags_maxkeyword) VALUES (%u,%u,%s,%s,%s,%s,%s,%u,%u)',
                        $xoopsDB->prefix(TAGS_TBL_TAGS),
                        $mid,
                        $pid,
                        $xoopsDB->quoteString($page['title']),
                        $xoopsDB->quoteString($page['script_name']),
                        $xoopsDB->quoteString($page['description']),
                        $xoopsDB->quoteString($page['config']),
                        $xoopsDB->quoteString($page['keywords']),
                        $page['minkeylen'],
                        $page['maxkeys']
                    );

                    if (!$result = $xoopsDB->queryF($sql)) {
                        return false;
                    }

                    $pid++;
                }
            }

            return true;
        }    //do manual search of system for data

        //set the module filename

        $sql = 'INSERT INTO ' . $xoopsDB->prefix(TAGS_TBL_TAGS) . " (mid, tags_title, tags_fname) SELECT t1.mid, t1.name, concat('/modules/',t1.dirname,'/index.php') FROM " . $xoopsDB->prefix('modules') . ' AS t1 WHERE t1.mid=' . (int)$mid;

        if (!$result = $xoopsDB->queryF($sql)) {
            return false;
        }

        /* Look through module's xoops_version.php file and get
         * additional data for the module
         */

        $sql = 'SELECT tags_fname,tags_title FROM ' . $xoopsDB->prefix(TAGS_TBL_TAGS) . ' WHERE mid =' . (int)$mid;

        if (!$mainresult = $xoopsDB->queryF($sql)) {
            return false;
        }

        $arr = $xoopsDB->fetchArray($mainresult);

        $fbits = explode('/', $arr['tags_fname']);

        $fname = XOOPS_ROOT_PATH . '/modules/' . $fbits[2] . '/xoops_version.php';

        $lname = XOOPS_ROOT_PATH . '/modules/' . $fbits[2] . '/language/' . $xoopsConfig['language'] . '/modinfo.php';

        if (!file_exists($lname)) {
            $lname = XOOPS_ROOT_PATH . '/modules/' . $fbits[2] . '/language/english/modinfo.php';

            if (!file_exists($lname)) {
                unset($lname);
            }
        }

        $pid = 1;

        if (file_exists($fname)) {
            if (isset($lname)) {
                include $lname; //language constants
            }

            include $fname; //mod info

            //update main page description

            if (isset($modversion['description'])) {
                $sql = 'update ' . $xoopsDB->prefix(TAGS_TBL_TAGS) . ' set tags_desc =' . $xoopsDB->quoteString($modversion['description']) . ' where mid = ' . $mid;

                if (!$result = $xoopsDB->queryF($sql)) {
                    return false;
                }
            }

            if (isset($modversion['hasMain']) && 1 == $modversion['hasMain']) {  //has userside menu so process
                if (isset($modversion['sub'])) {
                    foreach ($modversion['sub'] as $menu) {
                        $fname = explode('?', $menu['url']);

                        $fname = '/modules/' . $fbits[2] . '/' . $fname[0];

                        if (file_exists(XOOPS_ROOT_PATH . $fname)) {
                            $sql = sprintf(
                                'INSERT INTO ' . $xoopsDB->prefix(TAGS_TBL_TAGS) . ' (mid, pid, tags_fname, tags_title, tags_desc) VALUES (%u, %u, %s, %s, %s)',
                                $mid,
                                $pid,
                                $xoopsDB->quoteString($fname),
                                $xoopsDB->quoteString($arr['tags_title'] . ' - ' . $menu['name']),
                                $xoopsDB->quoteString($modversion['description'])
                            );

                            if (!$result = $xoopsDB->queryF($sql)) {
                                return false;
                            }

                            $pid++;
                        }
                    }
                }
            }

            unset($modversion);
        }
    }

    return true;
}

/**
 * Function: Display list of Tracks
 *
 * Display list of tracks
 *
 * @version 1
 */
function adminSelectTrack()
{
    //initiate the form

    $cols = [_AM_FRM6_COL1, _AM_FRM6_COL2, _AM_FRM6_COL3, _AM_FRM6_COL4];

    $table = new Form\FormTable($cols, null, true, null, null, TAGS_URL . '/admin/tracks.php?delete=');

    $tagsHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Page');

    $trackHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Track');

    $tags = $tagsHandler->getAllPages();

    foreach ($tags as $tag) {
        $numTracks = $trackHandler->countTracks((int)$tag->getVar('id'));

        $script = ($numTracks > 0 ? "<a href='tracks.php?view=" . $tag->getVar('id') . "'>" . $tag->getVar('tags_fname') . '</a>' : $tag->getVar('tags_fname'));

        $row = [$tag->getVar('id'), $tag->getVar('tags_modname'), $script, $numTracks];

        $table->addRow($row);
    }

    $table->display();

    echo '<br><b>' . _AM_FRM6_FOOTER . '</b><br>';
}//end function

/**
 * Delete tracks for a page
 *
 * @param int $page page identifier
 */
function adminDeleteTracks($page)
{
    $trackHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Track');

    if (!$trackHandler->deleteForPage($page)) {
        redirect_header(TAGS_URL . '/admin/tracks.php', 1, _MD_TAGS_ERRDEL);
    } else {
        redirect_header(TAGS_URL . '/admin/tracks.php', 1, _MD_TAGS_DELPAGE);
    }
}

/**
 * View consolidated tracks data for a page
 *
 * @param int $page page identifier
 */
function adminViewtrack($page)
{
    $tagsHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Page');

    $trackHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Track');

    $tags = $tagsHandler->get($page);

    $track = $trackHandler->getPageWords($page);

    $lines = array_chunk($track, 10);

    $track = '';

    foreach ($lines as $line) {
        $track .= implode(',', $line) . ',<br>';
    }

    $editForm = new \XoopsThemeForm(_AM_FRM7_TITLE, 'viewform', 'tracks.php');

    $fmodule = new \XoopsFormLabel(_AM_FRM7_COL1, $tags->getVar('tags_modname'));

    $fscript = new \XoopsFormLabel(_AM_FRM7_COL2, $tags->getVar('tags_fname'));

    $ftrack = new \XoopsFormLabel(_AM_FRM7_COL3, $track);

    $cancel = new \XoopsFormButton('', 'cancel', _AM_TAGS_GO, 'submit');

    $editForm->addElement($fmodule);

    $editForm->addElement($fscript);

    $editForm->addElement($ftrack);

    $editForm->addElement($cancel);

    $editForm->display();
}
