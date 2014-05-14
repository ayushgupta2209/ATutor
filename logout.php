<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2010                                      */
/* Inclusive Design Institute                                   */
/* http://atutor.ca                                             */
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.                */
/****************************************************************/
// $Id$

$_user_location	= 'public';
define('AT_INCLUDE_PATH', 'include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

/**
 * Update login details for a user
 * $_SESSION['login_time'] defined in /include/lib/login_functions.inc.php
 * @author	Ayush Gupta
 * @date 14-05-2014
 */
$diff = time() - $_SESSION['login_time'];
$update_login_details = queryDB("UPDATE %smembers SET counter=counter+1, duration=duration+%d WHERE member_id=%d", array(TABLE_PREFIX, $diff, $_SESSION['member_id']));
        
$sql = "DELETE FROM %susers_online WHERE member_id=%d";
queryDB($sql, array(TABLE_PREFIX, $_SESSION['member_id']));

// Unset these Session keys at the time of logout.
$unset_session = array('login',
                       'valid_user',
                       'member_id',
                       'is_admin',
                       'course_id',
                       'prefs',
                       'dd_question_ids',
                       'flash',
                       'userAgent',
                       'IPaddress',
                       'OBSOLETE',
                       'EXPIRES',
                       'redirect_to',
                       'token',
					   'fid',
					   'pid',
					   'pid_time',
					   'login_time');
foreach ($unset_session as $session_name) {
    unset($_SESSION[$session_name]);
}
$_SESSION['isLoggedOutRecently'] = true;
$msg->addFeedback('LOGOUT');
header('Location: login.php');
exit;

?>
