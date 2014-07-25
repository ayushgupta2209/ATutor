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
$sql = " SELECT a.main_tool_id, a.sub_tool_id, d.title,  totalCount, uniqueCount, d.totalComments FROM
	(SELECT `main_tool_id`, `sub_tool_id` , SUM( `counter`) as totalCount, COUNT(DISTINCT `member_id`) as uniqueCount, SEC_TO_TIME(SUM(`duration`)) as totalTime
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

foreach ($queryResult as $row) {
	if($blogkeyVal['maxVisits'][1] < $row['totalCount']) {
		$blogkeyVal['maxVisits'][0] = $row['title'];
		$blogkeyVal['maxVisits'][1] = $row['totalCount'];
		$blogkeyVal['maxVisits'][2] = $row['main_tool_id'];
		$blogkeyVal['maxVisits'][3] = $row['sub_tool_id'];
	}
	if($blogkeyVal['maxVisitors'][1] < $row['uniqueCount']) {
		$blogkeyVal['maxVisitors'][0] = $row['title'];
		$blogkeyVal['maxVisitors'][1] = $row['uniqueCount'];
		$blogkeyVal['maxVisitors'][2] = $row['main_tool_id'];
		$blogkeyVal['maxVisitors'][3] = $row['sub_tool_id'];
	}
	if($blogkeyVal['maxComments'][1] < $row['totalComments']) {
		$blogkeyVal['maxComments'][0] = $row['title'];
		$blogkeyVal['maxComments'][1] = $row['totalComments'];
		$blogkeyVal['maxComments'][2] = $row['main_tool_id'];
		$blogkeyVal['maxComments'][3] = $row['sub_tool_id'];
	}
}

/*
 * Fetch Data for Forum
 * Process and sends the required values to template file
 */

 $sql = " SELECT a.main_tool_id, d.title,  totalCount, uniqueCount, d.totalPosts FROM
	(SELECT `main_tool_id` , SUM( `counter`) as totalCount, COUNT(DISTINCT `member_id`) as uniqueCount, SEC_TO_TIME(SUM(`duration`)) as totalTime
	FROM `%stool_track`
	WHERE `course_id` =%d AND tool_name='FORUMS'
	GROUP BY `main_tool_id`)a
	JOIN
	(SELECT b.forum_id, b.title, c.totalPosts FROM
	(SELECT `forum_id`, `title` 
	FROM `%sforums` 
	WHERE 1)b
	LEFT JOIN
	(SELECT COUNT(`post_id`) as totalPosts, `forum_id` 
	FROM %sforums_threads
	GROUP BY `forum_id`)c
	ON b.forum_id = c.forum_id)d
	ON a.main_tool_id = d.forum_id";
	
$queryResult = queryDB($sql, array(TABLE_PREFIX, $_SESSION['course_id'], TABLE_PREFIX, TABLE_PREFIX));

foreach ($queryResult as $row) {
	if($forumkeyVal['maxVisits'][1] < $row['totalCount']) {
		$forumkeyVal['maxVisits'][0] = $row['title'];
		$forumkeyVal['maxVisits'][1] = $row['totalCount'];
		$forumkeyVal['maxVisits'][2] = $row['main_tool_id'];
	}
	if($forumkeyVal['maxVisitors'][1] < $row['uniqueCount']) {
		$forumkeyVal['maxVisitors'][0] = $row['title'];
		$forumkeyVal['maxVisitors'][1] = $row['uniqueCount'];
		$forumkeyVal['maxVisitors'][2] = $row['main_tool_id'];
	}
	if($forumkeyVal['maxPosts'][1] < $row['totalPosts']) {
		$forumkeyVal['maxPosts'][0] = $row['title'];
		$forumkeyVal['maxPosts'][1] = $row['totalPosts'];
		$forumkeyVal['maxPosts'][2] = $row['main_tool_id'];
	}
}
 
/*
 * Fetch Data for Student Use
 * Process and sends the required values to template file
 */
 
 $sql ="SELECT member_id, SEC_TO_TIME(AVG(time)) as AVG, COUNT(b.member_id) as count FROM
			(SELECT a.member_id , SUM(a.duration) as time 
		FROM(SELECT member_id, SUM(duration) as duration
			FROM %smember_track
			GROUP BY member_id
		UNION ALL
			SELECT member_id, SUM(duration) as duration
			FROM %stool_track
			GROUP BY member_id) a
		GROUP BY a.member_id
        ORDER BY time desc )b
	LIMIT 1";

$queryResult = queryDB($sql, array(TABLE_PREFIX, TABLE_PREFIX));
$member_id = $queryResult[0]['member_id'];
$average = $queryResult[0]['AVG'];
$activeCount = $queryResult[0]['count'];

$sql = "SELECT COUNT(member_id) count
	FROM %scourse_enrollment
	WHERE course_id = %d LIMIT 1";

$queryResult = queryDB($sql, array(TABLE_PREFIX, $_SESSION['course_id']));
$totalCount = $queryResult[0]['count'];

$sql = "SELECT first_name, last_name
	FROM %smembers
	WHERE member_id = %d
	LIMIT 1";

$queryResult = queryDB($sql, array(TABLE_PREFIX, $member_id));
$activeStudent = $queryResult[0]['first_name']." ".$queryResult[0]['last_name'];
$savant->assign('contentkeyVal', $contentkeyVal);
$savant->assign('blogkeyVal', $blogkeyVal);
$savant->assign('forumkeyVal', $forumkeyVal);
$savant->assign('percentage', (int)(($activeCount/$totalCount)*100));
$savant->assign('average', $average);
$savant->assign('activeStudent', $activeStudent);
$savant->display('instructor/statistics/student_tool_stats.tmpl.php');
require(AT_INCLUDE_PATH.'footer.inc.php'); ?>