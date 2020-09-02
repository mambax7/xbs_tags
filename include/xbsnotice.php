<?php declare(strict_types=1);

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
 * As part of your usage of this module you agree that installations and
 * uninstalls are registered with the code owner.  You may not remove this
 * code nor may you remove any includes and requires elsewhere in the
 * module that utilise it
 *
 * Why are we doing this?  So that we can keep track of who is using our
 * software and decide our support strategy etc.  The other reason is that
 * it is the start of another XBS utility that will eventually be made
 * available to Xoopsters and this is a good way of testing it.  We will not
 * use the information to extract payment from you!!
 *
 * @param mixed $data
 * @param mixed $keyprefix
 * @param mixed $keypostfix
 * @copyright (c) 2006 Ashley Kitson, Great Britain
 * @access        private
 * @package       XBSNOTIFY
 * @subpackage    Notification
 * @author        Ashley Kitson http://xoobs.net
 */

/**
 * encode post data.  recursive function to deal with multi dimensional arrays
 *
 * Thanks to skyogre __at__ yandex __dot__ ru
 * from the php manual on curl_setopt
 *
 * @param array  $data
 * @param string $keyprefix
 * @param string $keypostfix
 * @return string
 */
function xbsTagsLogData_encode($data, $keyprefix = '', $keypostfix = '')
{
    assert(is_array($data));

    $vars = null;

    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $vars .= xbsTagsLogData_encode($value, $keyprefix . $key . $keypostfix . urlencode('['), urlencode(']'));
        } else {
            $vars .= $keyprefix . $key . $keypostfix . '=' . urlencode($value) . '&';
        }
    }

    return $vars;
}

/**
 * @param        $status
 * @param string $host
 */
function xbsTagsLogNotify($status, $host = 'xbs')
{
    //Do not subtract from this array.  By all means add to it.

    $queries = ['xbs' => 'http://xoobs.net/modules/xbsnotify/getnotify.php'];

    $query = $queries[$host];

    //bomb out if invalid query url

    if (!isset($query)) {
        return;
    }

    //bomb out if no curl library

    //2DO - use an alternate method if no curl

    if (!function_exists('curl_init')) {
        return;
    }

    //Get module information. It won't matter if this has already

    //been read in.  We need to ensure it is our module.

    if (is_file(TAGS_PATH . '/language/english/modinfo.php')) {
        require_once TAGS_PATH . '/language/english/modinfo.php';
    }

    include TAGS_PATH . '/xoops_version.php';

    $name = $modversion['name'] ?? 'Unknown';

    $ver = $modversion['version'] ?? 'Unknown';

    //set up the data

    $postData = [];

    $postData['xnotv'] = 1.0;

    $postData['pkg'] = $name;

    $postData['ver'] = $ver;

    $postData['sts'] = $status;

    $cleanData = '&' . rtrim(xbsTagsLogData_encode($postData), '&');

    if ($handle = curl_init($query)) {
        //set up the method of connection

        curl_setopt($handle, CURLOPT_FRESH_CONNECT, true);

        curl_setopt($handle, CURLOPT_HEADER, true);

        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true); //not interested in return data

        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, false);

        curl_setopt($handle, CURLOPT_FAILONERROR, true);

        curl_setopt($handle, CURLOPT_FORBID_REUSE, true);

        //set up POST data

        curl_setopt($handle, CURLOPT_POST, true);

        curl_setopt($handle, CURLOPT_POSTFIELDS, $cleanData);

        //do the damn thing!
        $ret = curl_exec($handle); //we don't do anything with the returned data
        //and check for errors
        if (curl_errno($handle)) {
            //print out an error (quite often unresolved domain error 26)

            echo '<br>';

            print curl_errno($handle);

            echo '<br>';

            print curl_error($handle);

            echo '<br>';
        }

        curl_close($handle);
    }
}//end function
