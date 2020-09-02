<?php declare(strict_types=1);

namespace XoopsModules\Xbstags\Form;

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
 * Classes used by TAGS system to present form data
 *
 * @package       TAGS
 * @subpackage    Form_Handling
 * @copyright     Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 */

/**
 * Xoops form objects
 */
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

/**
 * Create a name selection list of modules that are not yet in the MetaTags database
 *
 * @package    TAGS
 * @subpackage Form_Handling
 */
class FormSelectNewModule extends \XoopsFormSelect
{
    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param string $name    "name" attribute
     * @param mixed  $value   Pre-selected value (or array of them).
     * @param int    $size    Number of rows. "1" makes a drop-down-list
     * @param bool   $multi   Allow multiple selections
     */
    public function __construct($caption, $name, $value = null, $size = 1, $multi = false)
    {
        global $xoopsDB;

        parent::__construct($caption, $name, $value, $size, $multi);

        //get all loaded modules

        $mod = new \XoopsModuleHandler($xoopsDB);

        $modArray = $mod->getList();

        //get modules that are in MetaTags

        $tagsHandler = \XoopsModules\Xbstags\Helper::getInstance()->getHandler('Page');

        $tagsArray = $tagsHandler->getList();

        //finds the ones we haven't got yet

        $arr = array_diff($modArray, $tagsArray);

        $this->addOptionArray($arr);
    }
}
