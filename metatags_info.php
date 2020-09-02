<?php declare(strict_types=1);

/**
 * XBS MetaTags page definition file
 *
 * If you want to simplify the end user's installation of your module under
 * XBS MetaTags then create this file and place in your module root directory.
 * Once user has installed module, they can go to MetaTags - Admin - Update
 * and the routine will pick up this file instead of searching through Xoops
 * for relevent data.  This means that you can put much more information for
 * your module and include files that will get missed under adefault
 * installation.
 *
 * The format for the file is traightforward and similar to
 * xoops_version.php
 *
 * declare a variable metatags[] as follows:
 * $metatags[1]['module'] = 'mod_name'; //must be same name as $modversion['name']
 * $metatags[1]['title'] = 'Page Title';
 * $metatags[1]['description'] = 'Page Description';
 * $metatags[1]['script_name'] = 'filename'; //name of script relative to your module directory e.g. mypage.php or /include/mypage.php
 * $metatags[1]['keywords'] = 'keyword1,keyword2'; //manually set keywords
 * $metatags[1]['maxkeys'] = <n>; //integer, maximum keywords to include in meta_keywords
 * $metatags[1]['minkeylen'] = <n>; //integer, minimum word length to include word in keys list
 * $metatags[1]['config'] = option; //one of 'db','textorder','leastorder','mostorder','xoops' - how to structure keywords
 *
 * Simply repeat this block, incrementing the index for each file you want included in MetaTags
 * Declaring no metatags array will have the effect of preventing your module being included in the
 * Metatags database, something that is reasonable if you have no user side pages in your module.
 *
 * @copyright     Ashley Kitson
 * @copyright     XOOPS Project https://xoops.org/
 * @license       GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author        Ashley Kitson http://akitson.bbcb.co.uk
 * @author        XOOPS Development Team
 * @package       TAGS
 * @subpackage    Admin
 * @version       1
 */
