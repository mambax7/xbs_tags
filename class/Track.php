<?php declare(strict_types=1);

namespace XoopsModules\Xbstags;

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
 * Classes used by XBS MetaTags system
 *
 * @package       TAGS
 * @subpackage    Track
 * @access        private
 * @copyright     Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
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
 * A Page Tracking Object
 *
 * @package    TAGS
 * @subpackage Track
 * @author     Ashley Kitson (http://xoobs.net)
 * @copyright  2006, Ashley Kitson, UK
 */
class Track extends \XoopsObject
{
    /**
     * Constructor
     *
     * The following variables  are set for retrieval with ->getVar()
     * {@source 2 10}
     */
    public function __construct()
    {
        $this->initVar('id', XOBJ_DTYPE_INT, null, true);

        $this->initVar('pid', XOBJ_DTYPE_INT, null, true);

        $this->initVar('keywords', XOBJ_DTYPE_OTHER);

        parent::__construct(); //call ancestor constructor
    }
}//end class Track
