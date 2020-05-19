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
 * Class PageHandler
 */
class PageHandler extends \XoopsObjectHandler
{
    // Public Variables
    /**
     * Set in descendent constructor to name of object that this handler handles
     * @var string
     */

    public $classname = 'Page';
    /**
     * Set in ancestor to name of unique ID generator tag for use with insert function
     * @var string
     */

    public $ins_tagname = 'ins_tagsPage';
    // Private variables
    /**
     * most recent error number
     * @access private
     * @var int
     */

    public $_errno = 0;
    /**
     * most recent error string
     * @access private
     * @var string
     */

    public $_error = '';

    /**
     * Constructor
     *
     * @param xoopsDatabase &$db handle for xoops database object
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db);
    }

    /**
     * Set error information
     *
     * @param int    $errnum =0 Error number
     * @param string $errstr ='' Error Message
     */
    public function setError($errnum = 0, $errstr = '')
    {
        $this->_errno = $errnum;

        $this->_error = $errstr;
    }

    /**
     * Return last error number
     *
     * @return int
     */
    public function errno()
    {
        return $this->_errno;
    }

    /**
     * Return last error message
     *
     * @return  string
     */
    public function error()
    {
        return $this->_error;
    }

    /**
     * return last error number and message
     *
     * @return string
     */
    public function getError()
    {
        return 'Error No ' . $this->_errno . ' - ' . $this->_error;
    }

    /**
     * Create a new object
     *
     * @param bool $isNew =true create a new object and tell it is new.  If False then create object but set it as not new
     * @return object Page else False if failure
     */
    public function create($isNew = true)
    {
        $obj = new Page();

        if ($isNew && $obj) { //if it is new and the object was created
            $obj->setNew();

            $obj->unsetDirty();
        } else {
            if ($obj) {         //it is not new (forced by caller, usually &getAll()) but obj was created
                $obj->unsetNew();

                $obj->unsetDirty();
            } else {
                $this->setError(-1, sprintf(_MD_TAGS_ERR_2, $classname));

                return false;      //obj was not created so return False to caller.
            }
        }

        return $obj;
    }

    // end create function

    /**
     * Get all data for object given id.
     *
     * @param int $id data item internal identifier
     * @return object descendent of Page
     */
    public function get($id)
    {
        $test = (is_int($id) ? ($id > 0 ? true : false) : !empty($id) ? true : false); //test validity of id

        //    $id = intval($id);

        if ($test) {
            $obj = $this->create(false);

            if ($obj) {
                $sql = 'SELECT * FROM ' . $this->db->prefix(TAGS_TBL_TAGS) . ' WHERE id = ' . $id;

                if ($result = $this->db->query($sql)) {
                    if (1 == $this->db->getRowsNum($result)) {
                        $res = $this->db->fetchArray($result);

                        $temp = (int)$res['mid'];

                        $moduleHandler = xoops_getHandler('module');
                        //                        $mod = XoopsModuleHandler::get($temp);

                        $mod = $moduleHandler->get($res['mid']);

                        if ($mod) {
                            $res['tags_modname'] = $mod->getVar('name');
                        } else {
                            $res['tags_modname'] = 'System';
                        }

                        /**     if (is_string($res['tags_keyword'])) {
                         * $res['tags_keyword'] = unserialize($res['tags_keyword']);
                         * } else {
                         * $res['tags_keyword'] = '';
                         * }
                         */

                        $obj->assignVars($res);

                        return $obj;
                    }

                    $this->setError(-1, sprintf(_MD_TAGS_ERR_1, (string)$id));
                } else {
                    $this->setError($this->db->errno(), $this->db->error());
                }//end if
            }//end if - error value set in call to create()
        } else {
            $this->setError(-1, sprintf(_MD_TAGS_ERR_1, (string)$id));
        }//end if

        return false;
        //default return
    }

    //end function &get

    /**
     * Get Page object based on user visible index
     *
     * @param mixed $page
     * @return object Page
     */
    public function getByKey($page)
    {
        $sql = 'SELECT id FROM ' . $this->db->prefix(TAGS_TBL_TAGS) . ' WHERE tags_fname = ' . $this->db->quoteString($page);

        if ($result = $this->db->query($sql)) {
            $arr = $this->db->fetchArray($result);

            return $this->get($arr['id']);
        }
    }

    /**
     * Insert sql string
     * You can generate a new variable with the same name as the key of
     * the cleanVars array and a value equal to the value element
     * of that array using;
     * <code>
     *  foreach ($cleanVars as $k => $v) {
     *    ${$k} = $v;
     *  }
     * </code>
     *
     * @access private
     * @param $cleanVars
     * @return string SQL string to insert object data into database
     */
    public function _ins_insert($cleanVars)
    {
        foreach ($cleanVars as $k => $v) {
            ${$k} = $v;
        }

        //$tags_keyword = serialize($tags_keyword);

        $sql = sprintf(
            'INSERT INTO %s (mid, pid, tags_fname, tags_title, tags_desc, tags_config, tags_maxkeyword, tags_minkeylen, tags_keyword) VALUES (%u,%u,%s,%s,%s,%s,%u,%u,%s)',
            $this->db->prefix(TAGS_TBL_TAGS),
            $mid,
            $pid,
            $this->db->quoteString($tags_fname),
            $this->db->quoteString($tags_title),
            $this->db->quoteString($tags_desc),
            $this->db->quoteString($tags_config),
            $tags_maxkeyword,
            $tags_minkeylen,
            $this->db->quoteString($tags_keyword)
        );

        return $sql;
    }

    /**
     * update sql string
     *
     * You can generate a new variable with the same name as the key of
     * the cleanVars array and a value equal to the value element
     * of that array using;
     * <code>
     *  foreach ($cleanVars as $k => $v) {
     *    ${$k} = $v;
     *  }
     * </code>
     *
     * @access private
     * @param $cleanVars
     * @return string SQL string to update object data into database
     */
    public function _ins_update($cleanVars)
    {
        foreach ($cleanVars as $k => $v) {
            ${$k} = $v;
        }

        //$tags_keyword = serialize($tags_keyword);

        $sql = sprintf(
            'UPDATE %s SET mid = %u, pid = %u, tags_fname = %s, tags_title = %s, tags_desc = %s, tags_config = %s, tags_maxkeyword = %u, tags_minkeylen = %u, tags_keyword = %s WHERE id = %u',
            $this->db->prefix(TAGS_TBL_TAGS),
            $mid,
            $pid,
            $this->db->quoteString($tags_fname),
            $this->db->quoteString($tags_title),
            $this->db->quoteString($tags_desc),
            $this->db->quoteString($tags_config),
            $tags_maxkeyword,
            $tags_minkeylen,
            $this->db->quoteString($tags_keyword),
            $id
        );

        return $sql;
    }

    /**
     * Write an object back to the database
     *
     * @param \XoopsObject $obj
     * @return  bool           True if successful
     */
    public function insert(\XoopsObject $obj)
    {
        if (!$obj->isDirty()) {
            return true;
        }    // if data is untouched then don't save

        if ($obj->isNew()) {
            //next line not really required for mysql, but left in for future compatibility

            $obj->setVar('id', $this->db->genId($this->ins_tagname));
        }

        // set up 'clean' 2 element array of data items k=>v

        if (!$obj->cleanVars()) {
            return false;
        }

        //get the sql for insert or update

        $sql = ($obj->isNew() ? $this->_ins_insert($obj->cleanVars) : $this->_ins_update($obj->cleanVars));

        if (!$result = $this->db->queryF($sql)) {
            $this->setError($this->db->errno(), $this->db->error());

            return false;
        }

        $obj->unsetDirty(); //It has been saved so now it is clean

        if ($obj->isNew()) { //retrieve the new internal id for the code and store
            $id = $this->db->getInsertId();

            $obj->setVar('id', $id);

            $obj->unsetNew();  //it's been saved so it's not new anymore
        }

        return true;
    }

    //end function insert

    /**
     * Delete object from the database
     *
     * @param \XoopsObject $obj
     * @return bool TRUE on success else False
     */
    public function delete(\XoopsObject $obj)
    {
        $sql = sprintf('DELETE FROM %s WHERE id = %u', $this->db->prefix(TAGS_TBL_TAGS), (int)$obj->getVar('id'));

        if (!$result = $this->db->queryF($sql)) {
            $this->setError($this->db->errno(), $this->db->error());

            return false;
        }

        unset($obj);

        return true;
    }

    //end function delete

    /**
     * Function: Retrieve all the stored Page objects as an array
     *
     *
     *
     * @return array Page object array else False on error
     * @version 1
     */
    public function getAllPages()
    {
        $ret = [];

        $sql = 'SELECT id FROM ' . $this->db->prefix(TAGS_TBL_TAGS) . ' ORDER BY mid,pid';

        if (!$result = $this->db->query($sql)) {
            $this->setError($this->db->errno(), $this->db->error());

            return false;
        }

        while (false !== ($obj = $this->db->fetchArray($result))) {
            if (!$ret[] = $this->get($obj['id'])) {
                return false;
            }
        }

        return $ret;
    }

    //end function getAllpages

    /**
     * Return an array which is a list of modules that exist in MetaTags
     *
     * @return array Taglist of MetaTags modules [mid => 'midname' ..]
     */
    public function getList()
    {
        $objs = $this->getAllPages();

        $ret = [];

        foreach ($objs as $page) {
            $ret[(int)$page->getVar('id')] = $page->getVar('tags_modname');
        }

        return $ret;
    }
} //end of class tagsPageHandler
