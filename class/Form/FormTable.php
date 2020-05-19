<?php declare(strict_types=1);

namespace XoopsModules\Xbstags\Form;

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
 * Classes used by TAGS system to present form data
 *
 * @package       TAGS
 * @subpackage    Form_Handling
 * @author        Ashley Kitson http://xoobs.net
 * @copyright (c) 2006 Ashley Kitson, Great Britain
 */

/**
 * Xoops form objects
 */
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

/**
 * A table with edit capabilities per row
 *
 * @package    TAGS
 * @subpackage Form_Handling
 */
class FormTable
{
    /**
     * Private variables
     * @access private
     */

    public $_title     = '';           //title for table
    public $_cols      = [];       //column names
    public $_rows      = [];       //array of arrays, containing data for each column per row
    public $_hasInsert = false;    //Display a new record insert button
    public $_insertUrl = '';       //url to redirect user to if new record required
    public $_hasEdit   = false;      //Display edit button for each row
    public $_editUrl   = '';         //url to redirect user to edit a record
    public $_hasDelete = false;    //Display delete button for each row
    public $_deleteUrl = '';       //url to redirect user to delete a record
    public $_dispKey   = 1;          //display key column (first column in table display)

    /**
     * Constructor
     *
     * For the three url parameters you should supply something like
     *  http:/myserver.com/modules/mymod/admin/tableprocess.php?op=edit&id=
     * i.e they are absolute urls.  Note trailing =.  The value of column 0 (KeyId)
     * will be suffixed to the url string before processing
     *
     * @param array  $colNames names of columns [0 => rowKeyName, 1 => Col1name .. n => Colnname]
     * @param string $title    title of table if required
     * @param bool   $dispKey  display the row key as first column.  If false, you must still supply a column name as the first column in $colNames but it will be ignored and can safely be set to null or ''
     * @param string $newUrl   url to redirect to add a new record
     * @param string $editUrl  url to redirect to edit a record
     * @param string $delUrl   url to redirect to delete a record
     */
    public function __construct($colNames, $title = null, $dispKey = true, $newUrl = null, $editUrl = null, $delUrl = null)
    {
        $this->_title = $title;

        $this->_hasInsert = (null != $newUrl);

        $this->_insertUrl = $newUrl;

        $this->_hasEdit = (null != $editUrl);

        $this->_editUrl = $editUrl;

        $this->_hasDelete = (null != $delUrl);

        $this->_deleteUrl = $delUrl;

        $this->_dispKey = ($dispKey ? 0 : 1);

        if ($this->_hasEdit || $this->_hasDelete) {
            $colNames[] = _AM_FRM1_COLACTION;
        }

        $this->_cols = $colNames;
    }

    //end function constructor

    /**
     * Add a row of data to the table
     *
     * @param array $row one row of data to display [0 => KeyId, 1 => Col1Data 2, n => ColnData]
     */
    public function addRow($row)
    {
        if ($this->_hasEdit) {
            $content = '<a href="' . $this->_editUrl . $row[0] . '">' . _AM_TAGS_EDIT . '</a>';

            if ($this->_hasDelete) {
                $content .= ' - <a href="' . $this->_deleteUrl . $row[0] . '">' . _AM_TAGS_DEL . '</a>';
            }

            $row[] = $content;
        } elseif ($this->_hasDelete) {
            $content = '<a href="' . $this->_deleteUrl . $row[0] . '">' . _AM_TAGS_DEL . '</a>';

            $row[] = $content;
        }

        $this->_rows[] = $row;
    }

    //end function addRow

    /**
     * output the table as html
     *
     * @param bool $render If true then echo html to output else return html to caller
     * @return mixed string if $render = false, else void
     */
    public function display($render = true)
    {
        $numcols = count($this->_cols);

        $content = "\n\n<!-- Table Edit Display -->\n\n<table border='0' cellpadding='4' cellspacing='1' width='100%' class='outer'>";

        if ($this->_title) {
            $content .= '<caption><b>' . $this->_title . "</b></caption>\n";  //title
        }

        //set column names

        $content .= "<tr align=\"center\">\n  ";

        for ($i = $this->_dispKey; $i < $numcols; $i++) {
            $content .= '<th>' . $this->_cols[$i] . '</th>';
        }

        $content .= "\n</tr>\n";

        //display data

        $class = 'even';

        foreach ($this->_rows as $row) {
            $class = ('even' == $class ? 'odd' : 'even');

            $content .= "<tr align='center' class=\"" . $class . "\">\n  ";

            for ($i = $this->_dispKey; $i < $numcols; $i++) {
                $content .= '<td>' . $row[$i] . '</td>';
            }

            $content .= "\n</tr>\n";
        }

        //Put in an insert button if required

        if ($this->_hasInsert) {
            $content .= "<tr>\n  <td colspan=" . $numcols . ' align="right"><form action="' . $this->_insertUrl . '" method="POST"><input type="SUBMIT" value="' . _AM_TAGS_INSERT . "\"></form></td>\n</tr>\n";
        }

        $content .= "</table>\n<!-- End Table Edit Display -->\n";

        if ($render) {
            echo $content;
        } else {
            return $content;
        }
    }
    //end function display
}//end class FormTable
