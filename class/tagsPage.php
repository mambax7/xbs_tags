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
 * @subpackage tagsPage 
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
 * A Page MetaTags Object
 * 
 * @package TAGS
 * @subpackage tagsPage 
 * @author Ashley Kitson (http://xoobs.net)
 * @copyright 2006, Ashley Kitson, UK
 */
class tagsPage extends XoopsObject {

	/**
	* Constructor
	*
	* The following variables  are set for retrieval with ->getVar()
	* {@source 2 10}
	*/
  function tagsPage() { 
  	$configs = getTAGSModConfigs();
    $this->initVar('id',XOBJ_DTYPE_INT,null,TRUE);
    $this->initVar('mid',XOBJ_DTYPE_INT,null,TRUE);
    $this->initVar('pid',XOBJ_DTYPE_INT,0);
    $this->initVar('tags_fname',XOBJ_DTYPE_TXTBOX,null,TRUE,255);   
    $this->initVar('tags_title',XOBJ_DTYPE_TXTBOX,null,FALSE,255);   
    $this->initVar('tags_desc',XOBJ_DTYPE_TXTAREA);   
    $this->initVar('tags_keyword',XOBJ_DTYPE_OTHER);
    $this->initVar('tags_config',XOBJ_DTYPE_OTHER,$configs['key_method'],TRUE);      
    $this->initVar('tags_maxkeyword',XOBJ_DTYPE_INT,$configs['max_keys'],TRUE);      
    $this->initVar('tags_minkeylen',XOBJ_DTYPE_INT,$configs['min_keylen'],TRUE);      
    $this->initVar('tags_modname',XOBJ_DTYPE_OTHER,null,FALSE);      
    
    $this->xoopsObject(); //call ancestor constructor 
  }
  
  /**
  * Function: get a list of keywords from a template object 
  *
  * Parses a XoopsTpl object to create a keyword array.
  * This function is substantially based on work by Hervé Thouzard (http://www.herve-thouzard.com)
  * in his xoogle for xoops hack.  However, whereas Herve has content for creation of keywords
  * passed to his function, this finds content in a template object
  *
  * @version 2
  * @param XoopsTpl $template XoopsTpl template object
  * @param int $pageId internal identifier for page
  * @return string list of keywords
  */
  function getKeywords($template,$pageId) {
  	global $xoopsConfig;
  	//get the page output
  	$content = $template->fetch($xoopsConfig['theme_set'].'/theme.html');
  	//strip out garbage
  	$myts =& MyTextSanitizer::getInstance();
  	$content = str_replace ("<br />", " ", $content);
	$content= $myts->undoHtmlSpecialChars($content);
	$content= strip_tags($content);
	$content=strtolower($content);
	$search_pattern=array("&nbsp;","\t","\r\n","\r","\n",",",".","'",";",":",")","(",'"','?','!','{','}','[',']','<','>','/','+','-','_','\\','*');
	$replace_pattern=array(' ',' ',' ',' ',' ',' ',' ',' ','','','','','','','','','','','','','','','','','','','');
	$content = str_replace($search_pattern, $replace_pattern, $content);

	//create keywords array
	$keywords=explode(' ',$content);
	//if keyword tracking is enable then save list to database
	// This is the unsorted complete list
	$modConfig = getTAGSModConfigs();
	if (intval($modConfig['track_keys'])==1) {
		$trackHandler =& xoops_getmodulehandler("tagsTrack",TAGS_DIR);
		$trackHandler->saveTrack($keywords,$pageId);
	}
	
	switch($this->getVar('tags_config')) {
		case TAGS_KEYMETHD_1:	// Returns keywords in the same order that they were created in the text
			$keywords=array_unique($keywords);
			break;

		case TAGS_KEYMETHD_2:	// the keywords order is made according to the reverse keywords frequency (so the less frequent words appear in first in the list)
			$keywords=array_count_values($keywords);
			asort($keywords);
			$keywords=array_keys($keywords);
			break;

		case TAGS_KEYMETHD_3:	// Same as previous, the only difference is that the most frequent words will appear in first in the list
			$keywords=array_count_values($keywords);
			arsort($keywords);
			$keywords=array_keys($keywords);
			break;
	} //end switch

	//strip out words that are too short
	$minlen = intval($this->getVar('tags_minkeylen'));
	foreach($keywords as $keyword) {
		if(strlen($keyword) >= $minlen && !is_numeric($keyword)) {
			$tmp[]=$keyword;
		}
	}
	//remove blacklisted words if they exist
	$listHandler =& xoops_getmodulehandler("tagsList",TAGS_DIR);
	if ($blist =& $listHandler->getByKey(TAGS_LISTBLACK)) {
		$blacklist = $blist->getVar('keywords');
		$tmp = array_diff($tmp,$blacklist);
	}
	//reduce array to max number of keywords
	$tmp=array_slice($tmp,0,intval($this->getVar('tags_maxkeyword')));
	//add whitelisted words if they exist
	if ($wlist =& $listHandler->getByKey(TAGS_LISTWHITE)) {
		$whitelist = $wlist->getVar('keywords');
		$tmp = array_merge($tmp,$whitelist);
	}
	//shuffle the words so that they appear differently each time
	shuffle($tmp);
	
	return implode(',',$tmp);
  } //end function getKeywords

}//end class tagsPage


class xbs_tagstagsPageHandler extends XoopsObjectHandler {

  // Public Variables
  /**
   * Set in descendent constructor to name of object that this handler handles
   * @var string 
   */
  var $classname = 'tagsPage'; 
  /**
   * Set in ancestor to name of unique ID generator tag for use with insert function
   * @var string
   */
  var $ins_tagname ='ins_tagsPage';
   
  
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
  function tagsPageHandler(&$db) {
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
  * @return object tagsPage else False if failure
  */
  function &create($isNew = true) {
    $obj = new tagsPage();
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
  * @return object descendent of tagsPage
  */
  function &get($id) {
    $test = (is_int($id) ? ($id > 0 ? TRUE : FALSE) : !empty($id) ? TRUE : FALSE); //test validity of id
    //    $id = intval($id);
    if ($test) {
      $obj =& $this->create(FALSE);
      if ($obj) {
	$sql = "select * from ".$this->db->prefix(TAGS_TBL_TAGS)." where id = ".$id;

	if ($result = $this->db->query($sql)) {
	  if ($this->db->getRowsNum($result)==1) {
	  	$res = $this->db->fetchArray($result);
	    $mod =& XoopsModuleHandler::get(intval($res['mid']));
		if ($mod) {
			$res['tags_modname'] = $mod->getVar('name');
		} else {
			$res['tags_modname'] = 'System';
		}
/**		if (is_string($res['tags_keyword'])) {
			$res['tags_keyword'] = unserialize($res['tags_keyword']);
		} else {
			$res['tags_keyword'] = '';
		}
*/	    $obj->assignVars($res);
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
   * Get tagsPage object based on user visible index
   *
   * @param string name of page relative to XOOPS_ROOT_PATH (including leading / )
   * @return object tagsPage
   */
  function getByKey($page) {
    $sql = "select id from ".$this->db->prefix(TAGS_TBL_TAGS)." where tags_fname = ".$this->db->quoteString($page);
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
   * @param array $cleanvars array of cleaned up variable name/value pairs as returned by $this->cleanVars()
   * @return string SQL string to insert object data into database
   */
  function _ins_insert($cleanVars) {
  	foreach ($cleanVars as $k => $v) {
       ${$k} = $v;
    }
    //$tags_keyword = serialize($tags_keyword);
    $sql = sprintf("insert into %s (mid, pid, tags_fname, tags_title, tags_desc, tags_config, tags_maxkeyword, tags_minkeylen, tags_keyword) values (%u,%u,%s,%s,%s,%s,%u,%u,%s)",$this->db->prefix(TAGS_TBL_TAGS),$mid,$pid,$this->db->quoteString($tags_fname),$this->db->quoteString($tags_title),$this->db->quoteString($tags_desc),$this->db->quoteString($tags_config),$tags_maxkeyword,$tags_minkeylen,$this->db->quoteString($tags_keyword));
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
   * @param array $cleanvars array of cleaned up variable name/value pairs as returned by $this->cleanVars()
   * @return string SQL string to update object data into database
    */
  function _ins_update($cleanVars) {
  	foreach ($cleanVars as $k => $v) {
       ${$k} = $v;
    }
    //$tags_keyword = serialize($tags_keyword);
    $sql = sprintf("update %s set mid = %u, pid = %u, tags_fname = %s, tags_title = %s, tags_desc = %s, tags_config = %s, tags_maxkeyword = %u, tags_minkeylen = %u, tags_keyword = %s where id = %u",$this->db->prefix(TAGS_TBL_TAGS),$mid,$pid,$this->db->quoteString($tags_fname),$this->db->quoteString($tags_title),$this->db->quoteString($tags_desc),$this->db->quoteString($tags_config),$tags_maxkeyword,$tags_minkeylen,$this->db->quoteString($tags_keyword),$id);
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
    // set up 'clean' 2 element array of data items k=>v
    if (!$obj->cleanVars()) { return false; } 
    //get the sql for insert or update
    $sql = ($obj->isNew() ? $this->_ins_insert($obj->cleanVars) : $this->_ins_update($obj->cleanVars));
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
   * @param Object    tagsPage Object to delete
   * @return bool TRUE on success else False
   */
  function delete(&$obj) {
    $sql = sprintf("delete from %s where id = %u",$this->db->prefix(TAGS_TBL_TAGS),intval($obj->getVar('id')));
    if(!$result = $this->db->queryF($sql)) {
      $this->setError($this->db->errno(),$this->db->error());
      return false; 
    } else {
    	unset($obj);
    	return true;
    }
  } //end function delete

  /**
  * Function: Retrieve all the stored tagsPage objects as an array 
  *
  * 
  *
  * @version 1
  * @return array tagsPage object array else False on error
  */
  function &getAllPages() {
  	$ret = array();
  	$sql = "select id from ".$this->db->prefix(TAGS_TBL_TAGS)." order by mid,pid";
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
   * Return an array which is a list of modules that exist in MetaTags
   *
   * @return array List of MetaTags modules [mid => 'midname' ..]
   */
  function getList() {
  	$objs =& $this->getAllPages();
  	$ret = array();
  	foreach ($objs as $page) {
  		$ret[intval($page->getVar('id'))] = $page->getVar('tags_modname');
  	}
  	return $ret;
  }
} //end of class tagsPageHandler

?>