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
 * Class TrackHandler
 */
class TrackHandler extends \XoopsObjectHandler
{
    // Public Variables
    /**
     * Set in descendent constructor to name of object that this handler handles
     * @var string
     */

    public $classname = 'Track';
    /**
     * Set in ancestor to name of unique ID generator tag for use with insert function
     * @var string
     */

    public $ins_tagname = 'ins_tagsTrack';
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
     * @return object Track else False if failure
     */
    public function create($isNew = true)
    {
        $obj = new Track();

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
     * @return object descendent of Track
     */
    public function get($id)
    {
        $test = (is_int($id) ? ($id > 0 ? true : false) : !empty($id) ? true : false); //test validity of id

        //    $id = intval($id);

        if ($test) {
            $obj = $this->create(false);

            if ($obj) {
                $sql = 'SELECT * FROM ' . $this->db->prefix(TAGS_TBL_TRACK) . ' WHERE id = ' . $id;

                if ($result = $this->db->query($sql)) {
                    if (1 == $this->db->getRowsNum($result)) {
                        $res = $this->db->fetchArray($result);

                        $res['keywords'] = unserialize($res['keywords']);

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

        $ret = false;

        return $ret; //default return
    }

    //end function &get

    /**
     * Insert sql string
     *
     * @access private
     * @param Track object $obj
     * @return string SQL string to insert object data into database
     */
    public function _ins_insert($obj)
    {
        $keys = serialize($obj->getVar('keywords'));

        return sprintf('INSERT INTO %s (pid, keywords) VALUES (%u,%s)', $this->db->prefix(TAGS_TBL_TRACK), $obj->getVar('pid'), $this->db->quoteString($keys));
    }

    /**
     * update sql string
     *
     * @access private
     * @param Track object $obj
     * @return string SQL string to update object data into database
     */
    public function _ins_update($obj)
    {
        $keys = serialize($obj->getVar('keywords'));

        return sprintf('UPDATE %s SET pid = %u, keywords = %u WHERE id = %u', $this->db->prefix(TAGS_TBL_TRACK), $obj->getVar('pid'), $this->db->quoteString($keys), $obj->getVar('id'));
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

        //get the sql for insert or update

        $sql = ($obj->isNew() ? $this->_ins_insert($obj) : $this->_ins_update($obj));

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
        $sql = sprintf('DELETE FROM %s WHERE id = %u', $this->db->prefix(TAGS_TBL_TRACK), (int)$obj->getVar('id'));

        if (!$result = $this->db->queryF($sql)) {
            $this->setError($this->db->errno(), $this->db->error());

            return false;
        }

        unset($obj);

        return true;
    }

    //end function delete

    /**
     * Delete all tracks for a page
     *
     * @param int $page internal page identifier
     * @return bool
     */
    public function deleteForPage($page)
    {
        $tracks = $this->getPageTracks((int)$page);

        foreach ($tracks as $track) {
            if (!$this->delete($track)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Function: Retrieve all the stored Track objects as an array
     *
     *
     *
     * @return array Track object array else False on error
     * @version 1
     */
    public function getAllTracks()
    {
        $ret = [];

        $sql = 'SELECT id FROM ' . $this->db->prefix(TAGS_TBL_TRACK) . ' ORDER BY pid,id';

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
     * Function: Retrieve all the stored Track objects for a page as an array
     *
     * @param int $page internal identifier for a page
     * @return array Track object array else False on error
     * @version 1
     */
    public function getPageTracks($page)
    {
        $ret = [];

        $sql = 'SELECT id FROM ' . $this->db->prefix(TAGS_TBL_TRACK) . '  WHERE pid = ' . $page . ' ORDER BY pid,id';

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

    //end function getPageTracks

    /**
     * Return an array of words from all of the stored page tracks
     *
     * Regex expressions in the blacklist are processed
     *
     * @param int    $maxwords  maximum number of words to return
     * @param string $method    'leastorder' or 'mostorder'
     * @param array  $blacklist array of words to remove from returned list
     * @return array list of words
     * @version 2
     */
    public function getAllWords($maxwords = 50, $method = TAGS_KEYMETHD_3, $blacklist = null)
    {
        if ($tracks = $this->getAllTracks()) {
            $keywords = [];

            foreach ($tracks as $track) {
                $keywords = array_merge($keywords, $track->getVar('keywords'));
            }

            //strip out words that are too short

            $modConfig = getTAGSModConfigs();

            $minlen = (int)$modConfig['min_keylen'];

            foreach ($keywords as $keyword) {
                if (mb_strlen($keyword) >= $minlen && !is_numeric($keyword)) {
                    $tmp[] = $keyword;
                }
            }

            $keywords = $tmp;

            //strip out blacklisted words step #1 - simple word match

            if (isset($blacklist)) {
                $keywords = array_diff($keywords, $blacklist);

                //strip out blacklisted words step #2 - regex search

                $regArray = [];

                foreach ($blacklist as $value) {
                    $isRegex = 1 == preg_match("/\/.+\//i", $value);

                    if ($isRegex) {
                        $regArray[] = $value;
                    }
                }

                if (count($regArray) > 0) {  //we have regex's
                    $rBlackList = [];

                    foreach ($keywords as $keyword) {
                        foreach ($regArray as $regex) {
                            if (1 == preg_match($regex, $keyword)) {
                                $rBlackList[] = $keyword;
                            }
                        }
                    }

                    if (count($rBlackList) > 0) {
                        $keywords = array_diff($keywords, $rBlackList);
                    }
                }
            }//end if isset($blacklist)

            //sort order
            if (TAGS_KEYMETHD_2 == $method) { //least used order
                $keywords = array_count_values($keywords);

                asort($keywords);

                $keywords = array_keys($keywords);
            } else { //default most used order
                $keywords = array_count_values($keywords);

                arsort($keywords);

                $keywords = array_keys($keywords);
            }

            return array_slice($keywords, 0, $maxwords);
        }

        return false;
    }

    /**
     * Return an array of words from tracks for a given page
     *
     * @param int   $page      internal identifier for the page
     * @param array $blacklist array of words to remove from returned list
     * @return array list of words
     */
    public function getPageWords($page, $blacklist = null)
    {
        if ($tracks = $this->getPageTracks($page)) {
            $keywords = [];

            foreach ($tracks as $track) {
                $keywords = array_merge($keywords, $track->getVar('keywords'));
            }

            //reduce to unique words

            $keywords = array_unique($keywords);

            //strip out words that are too short

            $modConfig = getTAGSModConfigs();

            $minlen = (int)$modConfig['min_keylen'];

            foreach ($keywords as $keyword) {
                if (mb_strlen($keyword) >= $minlen && !is_numeric($keyword)) {
                    $tmp[] = $keyword;
                }
            }

            $keywords = $tmp;

            //strip out blacklisted words

            if (isset($blacklist)) {
                $keywords = array_diff($keywords, $blacklist);
            }

            return $keywords;
        }

        return false;
    }

    /**
     * Return number of stored tracks for a page
     *
     * @param int $page internal page id
     * @return int number of tracks for the page
     */
    public function countTracks($page)
    {
        $sql = 'SELECT count(*) AS c FROM ' . $this->db->prefix(TAGS_TBL_TRACK) . ' WHERE pid = ' . $page;

        if (!$result = $this->db->query($sql)) {
            $this->setError($this->db->errno(), $this->db->error());

            return false;
        }

        $tmp = $this->db->fetchArray($result);

        return $tmp['c'];
    }

    /**
     * Save a page keyword tracker
     *
     * @param array $keywords set of keywords
     * @param int   $pageId   unique identifer for page that keywords are for
     */
    public function saveTrack($keywords, $pageId)
    {
        $pageId = (int)$pageId;

        $modConfig = getTAGSModConfigs();

        //see if we need to save any more page keyword tracks

        $sql = 'SELECT count(pid) AS c FROM ' . $this->db->prefix(TAGS_TBL_TRACK) . ' WHERE pid = ' . $pageId;

        if ($result = $this->db->query($sql)) {
            $ret = $this->db->fetchArray($result);

            if ($ret['c'] <= $modConfig['max_tracks']) {
                //OK to save another track

                $track = $this->create();

                $track->setVar('pid', $pageId);

                $track->setVar('keywords', $keywords);

                $this->insert($track);
            }
        }
    }
} //end of class tagsTrackHandler
