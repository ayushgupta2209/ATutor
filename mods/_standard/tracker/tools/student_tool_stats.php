<?php
/************************************************************************/
/* ATutor								*/
/************************************************************************/
/* Copyright (c) 2002-2010                                              */
/* Inclusive Design Institute                                       */
/* http://atutor.ca							*/
/*									*/
/* This program is free software. You can redistribute it and/or	*/
/* modify it under the terms of the GNU General Public License		*/
/* as published by the Free Software Foundation.			*/
/************************************************************************/
// $Id$

define('AT_INCLUDE_PATH', '../../../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
authenticate(AT_PRIV_CONTENT);
require(AT_INCLUDE_PATH.'header.inc.php');

/*
 * Fetch Data for Content Stats
 * Process and sends the required values to template file
 */
$sql = "SELECT `content_id` , SUM( `counter`) as totalCount, COUNT(`member_id`) as uniqueCount, SEC_TO_TIME(SUM(`duration`)) as totalTime
	FROM `%smember_track`
	WHERE `course_id` =%d
	GROUP BY `content_id`";
	
$queryResult = queryDB($sql, array(TABLE_PREFIX, $_SESSION['course_id']));
$contentkeyValues = array (
    "maxVisits"  => array(-1,0),
    "maxVisitors" => array(-1,0),
    "maxTime"   => array(-1,'00:00:00')
);

foreach ($queryResult as $row) {
	if($contentkeyVal['maxVisits'][1] < $row['totalCount']) {
		$contentkeyVal['maxVisits'][0] = $row['content_id'];
		$contentkeyVal['maxVisits'][1] = $row['totalCount'];
	}
	if($contentkeyVal['maxVisitors'][1] < $row['uniqueCount']) {
		$contentkeyVal['maxVisitors'][0] = $row['content_id'];
		$contentkeyVal['maxVisitors'][1] = $row['uniqueCount'];
	}
	if($contentkeyVal['maxTime'][1] < $row['totalTime']) {
		$contentkeyVal['maxTime'][0] = $row['content_id'];
		$contentkeyVal['maxTime'][1] = $row['totalTime'];
	}
}

/*
 * Fetch Data for Blog
 * Process and sends the required values to template file
 */
$sql = " SELECT d.title,  totalCount, uniqueCount, d.totalComments FROM
	(SELECT `sub_tool_id` , SUM( `counter`) as totalCount, COUNT(`member_id`) as uniqueCount, SEC_TO_TIME(SUM(`duration`)) as totalTime
	FROM `%stool_track`
	WHERE `course_id` =%d AND tool_name='BLOGS' AND `sub_tool_id`>0
	GROUP BY `sub_tool_id`)a
	JOIN
	(SELECT b.post_id, b.title, c.totalComments FROM
	(SELECT `post_id`, `title` 
	FROM `%sblog_posts` 
	WHERE 1)b
	LEFT JOIN
	(SELECT COUNT(`comment_id`) as totalComments, `post_id` 
	FROM %sblog_posts_comments
	GROUP BY `post_id`)c
	ON b.post_id = c.post_id)d
	ON a.sub_tool_id = d.post_id";
	
$queryResult = queryDB($sql, array(TABLE_PREFIX, $_SESSION['course_id'], TABLE_PREFIX, TABLE_PREFIX));
$blogkeyValues = array (
    "maxVisits"  => array(-1,0),
    "maxVisitors" => array(-1,0),
    "maxComments"   => array(-1,'0')
);

foreach ($queryResult as $row) {
	if($blogkeyVal['maxVisits'][1] < $row['totalCount']) {
		$blogkeyVal['maxVisits'][0] = $row['title'];
		$blogkeyVal['maxVisits'][1] = $row['totalCount'];
	}
	if($blogkeyVal['maxVisitors'][1] < $row['uniqueCount']) {
		$blogkeyVal['maxVisitors'][0] = $row['title'];
		$blogkeyVal['maxVisitors'][1] = $row['uniqueCount'];
	}
	if($blogkeyVal['maxComments'][1] < $row['totalComments']) {
		$blogkeyVal['maxComments'][0] = $row['title'];
		$blogkeyVal['maxComments'][1] = $row['totalComments'];
	}
}

$savant->assign('contentkeyVal', $contentkeyVal);
$savant->assign('contentkeyVal', $blogkeyVal);
$savant->display('instructor/statistics/student_tool_stats.tmpl.php');
require(AT_INCLUDE_PATH.'footer.inc.php'); ?>