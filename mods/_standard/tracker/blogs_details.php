<?php
/****************************************************************/
/* ATutor */
/****************************************************************/
/* Copyright (c) 2002-2010 */
/* Inclusive Design Institute */
/* http://atutor.ca */
/* */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License */
/* as published by the Free Software Foundation. */
/****************************************************************/
// $Id$

define('AT_INCLUDE_PATH', '../../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
require(AT_INCLUDE_PATH.'header.inc.php');

$sql = "SELECT a.main_tool_id, a.sub_tool_id as tool_id, b.title, a.average_duration, a.total_duration, a.last_accessed FROM
		(SELECT main_tool_id, sub_tool_id, SEC_TO_TIME(SUM(`duration`)/SUM(`counter`)) as average_duration, 
		SEC_TO_TIME(SUM(`duration`)) as total_duration, max(last_accessed) AS last_accessed
	FROM %stool_track 
	WHERE `member_id` = %d AND `course_id` = %d AND `tool_name` = '%s'
	GROUP BY `sub_tool_id`)a
	JOIN 
		(SELECT `post_id`, `title` 
	FROM %sblog_posts
	WHERE 1)b
	ON a.sub_tool_id = b.post_id";

$rows_hits = queryDB($sql, array(TABLE_PREFIX, $_SESSION['member_id'], $_SESSION['course_id'], "BLOGS", TABLE_PREFIX));

$savant->assign('rows_hits', $rows_hits);
$savant->display('student_stats/blogs_details.tmpl.php');
require(AT_INCLUDE_PATH.'footer.inc.php'); 
echo "<script>";	
	if($_SESSION['is_admin'] != 1) {
		require('../../../jscripts/d3js/d3.v3.min.js');
		require('js/content_pie_chart.js');
	}
echo "</script>";
?>
