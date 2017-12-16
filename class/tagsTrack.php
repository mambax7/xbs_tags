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
// Copyright: (c) 2006, Ashley Kitson                                        //
// URL:       http://xoobs.net			                                     //
// Project:   The XOOPS Project (http://www.xoops.org/)                      //
// Module:    XBS MetaTags (TAGS)                                            //
// ------------------------------------------------------------------------- //
/** 
 * Classes used by XBS MetaTags system
 * 
 * @package TAGS
 * @subpackage tagsTrack
 * @access private
 * @author Ashley Kitson http://xoobs.net
 * @copyright (c) 2006 Ashley Kitson, Great Britain
*/

/**
* Require Xoops kernel objects so we can extend them
*/
require_once XOOPS_ROOT_PATH."/kernel/object.php";
/**
 * MetaTags constant defines
 */
require_once(XOOPS_ROOT_PATH."/modules/xbs_tags/include/defines.php");
/**
 * @global Xoops configuration
 */
global $xoopsConfig;
if (file_exists(TAGS_PATH."/language/".$xoopsConfig['language']."/main.php")) {
	include_once(TAGS_PATH."/language/".$xoopsConfig['language']."/main.php");
} else {
	include_once(TAGS_PATH."/language/english/main.php");
}

 /**
 *
 * A Page Tracking Object
 * 
 * @package TAGS
 * @subpackage tagsTrack 
 * @author Ashley Kitson (http://xoobs.net)
 * @copyright 2006, Ashley Kitson, UK
 */
class tagsTrack extends XoopsObject {

	/**
	* Constructor
	*
	* The following variables  are set for retrieval with ->getVar()
	* {@source 2 10}
	*/
  function tagsTrack() { 
    $this->initVar('id',XOBJ_DTYPE_INT,null,TRUE);
    $this->initVar('pid',XOBJ_DTYPE_INT,null,TRUE);
    $this->initVar('keywords',XOBJ_DTYPE_OTHER);
    $this->xoopsObject(); //call ancestor constructor 
  }
  
  
}//end class tagsTrack


class xbs_tagstagsTrackHandler extends XoopsObjectHandler {

  // Public Variables
  /**
   * Set in descendent constructor to name of object that this handler handles
   * @var string 
   */
  var $classname = 'tagsTrack'; 
  /**
   * Set in ancestor to name of unique ID generator tag for use with insert function
   * @var string
   */
  var $ins_tagname ='ins_tagsTrack';
   
  
  // Private variables 
  /**
  * most recent error number
  * @access private
  * @var int
  */
  var $_errno = 0;  
  /**
  * most recent error string
  * @access private
  * @var string
  */
  var $_error = ''; 
  

  /**
   * Constructor
   *
   * @param  xoopsDatabase &$db handle for xoops database object
   */
  function tagsTrackHandler(&$db) {
    $this->xoopsObjectHandler($db);
  }

  /**
   * Set error information
   *
   * @param int $errnum=0 Error number
   * @param string $errstr='' Error Message
   */
  function setError($errnum = 0,$errstr = '') {
    $this->_errno = $errnum;
    $this->_error = $errstr;
  }
  
  /**
  * Return last error number
  *
  * @return int
  */
  function errno() {
    return $this->_errno;
  }
  
  /**
  * Return last error message
  *
  * @return  string
  */
  function error() {
    return $this->_error;
  }

  /**
  * return last error number and message
  *
  * @return string
  */
  function getError() {
    $e = "Error No ".strval($this->_errno)." - ".$this->_error;
    return $e;
  }

  /**
  * Create a new object
  *
  * @param boolean $isNew=true create a new object and tell it is new.  If False then create object but set it as not new
  * @return object tagsTrack else False if failure
  */
  function &create($isNew = true) {
    $obj = new tagsTrack();
    if ($isNew && $obj) { //if it is new and the object was created
      $obj->setNew();
      $obj->unsetDirty();
    } else {
      if ($obj) {         //it is not new (forced by caller, usually &getall()) but obj was created
	$obj->unsetNew();
	$obj->unsetDirty();
      } else {
	$this->setError(-1,sprintf(_MD_TAGS_ERR_2,$classname));
	return FALSE;      //obj was not created so return False to caller.
      }
    }
    return $obj;
  }// end create function

  /**
  * Get all data for object given id.
  *
  * @param  int $id data item internal identifier
  * @return object descendent of tagsTrack
  */
  function &get($id) {
    $test = (is_int($id) ? ($id > 0 ? TRUE : FALSE) : !empty($id) ? TRUE : FALSE); //test validity of id
    //    $id = intval($id);
    if ($test) {
      $obj =& $this->create(FALSE);
      if ($obj) {
	$sql = "select * from ".$this->db->prefix(TAGS_TBL_TRACK)." where id = ".$id;

	if ($result = $this->db->query($sql)) {
	  if ($this->db->getRowsNum($result)==1) {
	  	$res = $this->db->fetchArray($result);
	  	$res['keywords'] = unserialize($res['keywords']);
	    $obj->assignVars($res);
	    return $obj;
	  } else {
	    $this->setError(-1,sprintf(_MD_TAGS_ERR_1,strval($id)));
	  }
	} else {
	  $this->setError($this->db->errno(),$this->db->error());
	}//end if
      }//end if - error value set in call to create()
    } else {
      $this->setError(-1,sprintf(_MD_TAGS_ERR_1,strval($id)));
    }//end if
    $ret = false;
    return $ret; //default return
  }//end function &get

  /**
   * Insert sql string
   *
   * @access private
   * @param tagsTrack object $obj
   * @return string SQL string to insert object data into database
   */
  function _ins_insert($obj) {
  	$keys = serialize($obj->getVar('keywords'));
    $sql = sprintf("insert into %s (pid, keywords) values (%u,%s)",$this->db->prefix(TAGS_TBL_TRACK),$obj->getVar('pid'),$this->db->quoteString($keys));
    return $sql;
  }

  /**
   * update sql string
   *
   * @access private
   * @param tagsTrack object $obj
   * @return string SQL string to update object data into database
    */
  function _ins_update($obj) {
  	$keys = serialize($obj->getVar('keywords'));
    $sql = sprintf("update %s set pid = %u, keywords = %u where id = %u",$this->db->prefix(TAGS_TBL_TRACK),$obj->getVar('pid'),$this->db->quoteString($keys),$obj->getVar('id'));
    return $sql;
  }

  /**
   * Write an object back to the database
   *
   * @param   Object &$obj   reference to a TAGS object
   * @return  bool           True if successful
   */

  function insert(&$obj) {
   if (!$obj->isDirty()) { return true; }    // if data is untouched then don't save
   if ($obj->isNew()) {    
      //next line not really required for mysql, but left in for future compatibility
      $obj->setVar('id',$this->db->genId($this->ins_tagname));
   }
    //get the sql for insert or update
    $sql = ($obj->isNew() ? $this->_ins_insert($obj) : $this->_ins_update($obj));
    if(!$result = $this->db->queryF($sql)) {
      $this->setError($this->db->errno(),$this->db->error());
      return false; 
    } else {
      $obj->unsetDirty(); //It has been saved so now it is clean
    }

    if ($obj->isNew()) { //retrieve the new internal id for the code and store
      $id = $this->db->getInsertId(); 
      $obj->setVar('id',$id);
      $obj->unsetNew();  //it's been saved so it's not new anymore
    }
  
    return true;
  }//end function insert

  /**
   * Delete object from the database
   *
   * @param Object    tagsTrack Object to delete
   * @return bool TRUE on success else False
   */
  function delete(&$obj) {
    $sql = sprintf("delete from %s where id = %u",$this->db->prefix(TAGS_TBL_TRACK),intval($obj->getVar('id')));
    if(!$result = $this->db->queryF($sql)) {
      $this->setError($this->db->errno(),$this->db->error());
      return false; 
    } else {
    	unset($obj);
    	return true;
    }
  } //end function delete

  /**
   * Delete all tracks for a page
   *
   * @param int $page internal page identifier
   */
  function deleteForPage($page) {
  	$tracks = $this->getPageTracks(intval($page));
  	foreach ($tracks as $track) {
  		if (!$this->delete($track)) {
  			return false;
  		}
  	}
  	return true;
  }
  
  /**
  * Function: Retrieve all the stored tagsTrack objects as an array 
  *
  * 
  *
  * @version 1
  * @return array tagsTrack object array else False on error
  */
  function &getAllTracks() {
  	$ret = array();
  	$sql = "select id from ".$this->db->prefix(TAGS_TBL_TRACK)." order by pid,id";
    if(!$result = $this->db->query($sql)) {
      $this->setError($this->db->errno(),$this->db->error());
      return false; 
    } else {
    	while ($obj = $this->db->fetchArray($result)) {
    		if (!$ret[] = $this->get($obj['id'])) {
    			return false;
    		}
    	}
    	return $ret;
    }
  }//end function getAllpages

  /**
  * Function: Retrieve all the stored tagsTrack objects for a page as an array 
  *
  * @version 1
  * @param int $page internal identifier for a page
  * @return array tagsTrack object array else False on error
  */
  function &getPageTracks($page) {
  	$ret = array();
  	$sql = "select id from ".$this->db->prefix(TAGS_TBL_TRACK)."  where pid = ".$page." order by pid,id";
    if(!$result = $this->db->query($sql)) {
      $this->setError($this->db->errno(),$this->db->error());
      return false; 
    } else {
    	while ($obj = $this->db->fetchArray($result)) {
    		if (!$ret[] = $this->get($obj['id'])) {
    			return false;
    		}
    	}
    	return $ret;
    }
  }//end function getPageTracks
  
   
  /**
   * Return an array of words from all of the stored page tracks
   * 
   * Regex expressions in the blacklist are processed
   *
   * @param int $maxwords maximum number of words to return
   * @param string $method 'leastorder' or 'mostorder'
   * @param array $blacklist array of words to remove from returned list
   * @return array list of words
   * @version 2
   */
  function getAllWords($maxwords = 50,$method = TAGS_KEYMETHD_3,$blacklist = null) {
  	if ($tracks = $this->getAllTracks()) {
	  	$keywords = array();
	  	foreach ($tracks as $track) {
	  		$keywords = array_merge($keywords,$track->getVar('keywords'));
	  	}
  		//strip out words that are too short
  		$modConfig = getTAGSModConfigs();
		$minlen = intval($modConfig['min_keylen']);
		foreach($keywords as $keyword) {
			if(strlen($keyword) >= $minlen && !is_numeric($keyword)) {
				$tmp[]=$keyword;
			}
		}
		$keywords = $tmp;
		//strip out blacklisted words step #1 - simple word match
	  	if (isset($blacklist)) {
	  		$keywords = array_diff($keywords,$blacklist);
	  		
		  	//strip out blacklisted words step #2 - regex search
		  	$regArray = array();
		  	foreach ($blacklist as $value) {
		  		$isRegex = (preg_match("/\/.+\//i",$value)==1);
		  		if ($isRegex) {
		  			$regArray[] = $value;
		  		}
		  	}
		  	if (count($regArray)>0) {  //we have regex's
		  		$rBlackList = array();
		  		foreach ($keywords as $keyword) {
		  			foreach ($regArray as $regex) {
			  			if (preg_match($regex,$keyword)==1) {
			  				$rBlackList[] = $keyword;
			  			}
		  			}
		  		}
		  		if (count($rBlackList)>0) {
		  			$keywords = array_diff($keywords,$rBlackList);
		  		}
		  	}
	  	}//end if isset($blacklist)
	  		  	
	  	//sort order
	  	if ($method == TAGS_KEYMETHD_2) { //least used order
	  		$keywords=array_count_values($keywords);
			asort($keywords);
			$keywords=array_keys($keywords);
	  	} else { //default most used order
	  		$keywords=array_count_values($keywords);
			arsort($keywords);
			$keywords=array_keys($keywords);
	  	}
	  	$keywords=array_slice($keywords,0,$maxwords);
  		return $keywords;
  	} else {
  		return false;
  	}
  }

  /**
   * Return an array of words from tracks for a given page
   *
   * @param int $page internal identifier for the page
   * @param array $blacklist array of words to remove from returned list
   * @return array list of words
   */
  function getPageWords($page,$blacklist = null) {
  	if ($tracks = $this->getPageTracks($page)) {
	  	$keywords = array();
	  	foreach ($tracks as $track) {
			$keywords = array_merge($keywords,$track->getVar('keywords'));
	  	}
	  	//reduce to unique words
	  	$keywords = array_unique($keywords);
  		//strip out words that are too short
  		$modConfig = getTAGSModConfigs();
		$minlen = intval($modConfig['min_keylen']);
		foreach($keywords as $keyword) {
			if(strlen($keyword) >= $minlen && !is_numeric($keyword)) {
				$tmp[]=$keyword;
			}
		}
		$keywords = $tmp;
		//strip out blacklisted words
	  	if (isset($blacklist)) {
	  		$keywords = array_diff($keywords,$blacklist);
	  	} 
  		return $keywords;
  	} else {
  		return false;
  	}
  }

  /**
   * Return number of stored tracks for a page
   *
   * @param int $page internal page id
   * @return int number of tracks for the page
   */
  function countTracks($page) {
  	$sql = "select count(*) as c from ".$this->db->prefix(TAGS_TBL_TRACK)." where pid = ".$page;
  	if(!$result = $this->db->query($sql)) {
      $this->setError($this->db->errno(),$this->db->error());
      return false; 
    } else {
    	$tmp = $this->db->fetchArray($result);
    	return $tmp['c'];
    }
  }
  
  /**
   * Save a page keyword tracker
   *
   * @param array $keywords set of keywords
   * @param int $pageId unique identifer for page that keywords are for
   */
  function saveTrack($keywords,$pageId) {
  	$pageId = intval($pageId);
  	$modConfig = getTAGSModConfigs();
  	//see if we need to save any more page keyword tracks
  	$sql = "select count(pid) as c from ".$this->db->prefix(TAGS_TBL_TRACK)." where pid = ".$pageId;
  	if($result = $this->db->query($sql)) {
  		$ret = $this->db->fetchArray($result);
  		if ($ret['c']<=$modConfig['max_tracks']) {
  			//OK to save another track
  			$track =& $this->create();
  			$track->setVar('pid',$pageId);
  			$track->setVar('keywords',$keywords);
  			$this->insert($track);
  		}
  	}
  	
  }
  
} //end of class tagsTrackHandler

?>