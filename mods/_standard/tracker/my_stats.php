<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2010                                      */
/* Inclusive Design Institute                                   */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$

define('AT_INCLUDE_PATH', '../../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
require(AT_INCLUDE_PATH.'header.inc.php');
require('./css/student_bar_graph.css');

?>
<head>
<link rel="stylesheet" href="CSS/student_bar_graph.css" type="text/css">
</head>
<body>
<table class="data static" summary="">
<thead>
<tr>
	<th scope="col"><?php echo _AT('page'); ?></th>
	<th scope="col"><?php echo _AT('visits'); ?></th>
	<th scope="col"><?php echo _AT('duration'); ?></th>
	<th scope="col"><?php echo _AT('last_accessed'); ?></th>
</tr>
</thead>
<tbody>
<?php
	$sql = "SELECT content_id, COUNT(*) AS unique_hits, 
	            SUM(counter) AS total_hits, 
	            SEC_TO_TIME(SUM(duration)/SUM(counter)) AS average_duration, 
	            SEC_TO_TIME(SUM(duration)) AS total_duration, last_accessed 
	        FROM %smember_track 
	        WHERE course_id=%d AND member_id=%d GROUP BY content_id ORDER BY total_hits DESC";
	$rows_hits = queryDB($sql, array(TABLE_PREFIX, $_SESSION['course_id'], $_SESSION['member_id']));

    if(count($rows_hits) > 0){
	    foreach($rows_hits as $row){
			if ($row['total'] == '') {
				$row['total'] = _AT('na');
			}

			echo '<tr>';
			echo '<td><a href='.AT_BASE_HREF.url_rewrite('content.php?cid='.$row['content_id']). '>' . $contentManager->_menu_info[$row['content_id']]['title'] . '</a></td>';
			echo '<td>' . $row['total_hits'] . '</td>';
			echo '<td>' . $row['total_duration'] . '</td>';
			if ($row['last_accessed'] == '') {
				echo '<td>' . _AT('na') . '</td>';
			} else {
				echo '<td>' . AT_date(_AT('forum_date_format'), $row['last_accessed'], AT_DATE_MYSQL_DATETIME) . '</td>';
			}
			echo '</tr>';
		} //end while

		echo '</tbody>';

	} else {
	    if($_SESSION['is_admin'] == 1){
            $msg->printInfos('TRACKING_NO');
        } 
		echo '<tr><td colspan="4">' . _AT('none_found') . '</td></tr>';
		echo '</tbody>';

	}
	?>
</tbody>
</table>


<span id = "bargraph"> 

</span>

<table class="data static" >
<thead>
<tr>
	<th scope="col"><?php echo 'Tool'; ?></th>
	<th scope="col"><?php echo 'Students_avg_time'; ?></th>
	<th scope="col"><?php echo 'Your_avg_time'; ?></th>
</tr>
</thead>
<?php

	$sql1 = "SELECT a.tool, a.Avg_time, b.Your_avg_time FROM
					(SELECT `tool_name` as tool, SUM(`duration`)/SUM(`counter`) as Avg_time 
							FROM %stool_track 
							WHERE `course_id` = %d 
							GROUP BY `tool_name`)a
					JOIN (SELECT `tool_name` as tool, SUM(`duration`)/SUM(`counter`) as Your_avg_time 
				FROM %stool_track 
				WHERE `member_id` = %d AND `course_id` = %d
				GROUP BY `tool_name`)b
				ON a.tool = b.tool";
	
	$sql2 =	"SELECT 'CONTENT' as tool, a.Avg_time, b.Your_avg_time FROM
					( SELECT SUM(`duration`)/SUM(`counter`) as Avg_time, `course_id`
							FROM %smember_track 
							WHERE `course_id` = %d )a
					JOIN (SELECT SUM(`duration`)/SUM(`counter`) as Your_avg_time,
							`course_id`
				FROM %smember_track 
				WHERE `member_id` = %d AND `course_id` = %d )b
				ON a.course_id = b.course_id";
				
	$rows_hits1 = queryDB($sql1, array(TABLE_PREFIX, $_SESSION['course_id'], TABLE_PREFIX, $_SESSION['member_id'], $_SESSION['course_id']));
	$rows_hits2 = queryDB($sql2, array(TABLE_PREFIX, $_SESSION['course_id'], TABLE_PREFIX, $_SESSION['member_id'], $_SESSION['course_id']));
	$rows_hits = array_merge($rows_hits1,$rows_hits2);
    if(count($rows_hits) > 0){
	    foreach($rows_hits as $row){
			
			echo '<tr>';
			echo '<td>' .  $row['tool'] . '</td>';
			echo '<td>' . gmdate('H:i:s', (int)$row['Avg_time']) . '</td>';
			echo '<td>' . gmdate('H:i:s', (int)$row['Your_avg_time']) . '</td>';
			
			echo '</tr>';
		} //end while
		echo '</tbody>';
	}
	?>
</table>
</body>

<script src="http://d3js.org/d3.v3.min.js"></script>
<?php require(AT_INCLUDE_PATH.'footer.inc.php');
	require('js/student_bar_graph.js'); ?>