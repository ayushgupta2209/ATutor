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

$sql = "SELECT content_id AS tool_id, COUNT(*) AS unique_hits,
		SUM(counter) AS total_hits,
		SEC_TO_TIME(SUM(duration)/SUM(counter)) AS average_duration,
		SEC_TO_TIME(SUM(duration)) AS total_duration, last_accessed
	FROM %smember_track
	WHERE course_id=%d AND member_id=%d GROUP BY content_id ORDER BY total_hits DESC";
$rows_hits = queryDB($sql, array(TABLE_PREFIX, $_SESSION['course_id'], $_SESSION['member_id']));

$savant->assign('rows_hits', $rows_hits);
$savant->display('student_stats/content_details.tmpl.php');
require(AT_INCLUDE_PATH.'footer.inc.php'); 
echo "<script>";	
	if($_SESSION['is_admin'] != 1) {
		require('../../../jscripts/d3js/d3.v3.min.js');
		require('js/content_pie_chart.js');
	}
echo "</script>";
?>
