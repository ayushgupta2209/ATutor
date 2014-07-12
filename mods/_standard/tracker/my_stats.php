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
<div id = "bargraph" align = "center">  </div>

<table class="data static" >
	<thead>
		<tr>
			<th scope="col"><?php echo 'Tool'; ?></th>
			<th scope="col"><?php echo 'Student\'s Average Time'; ?></th>
			<th scope="col"><?php echo 'Your Average Time'; ?></th>
		</tr>
	</thead>
	<?php
		$sql1 = "SELECT a.tool, a.Avg_time, b.Your_avg_time FROM
						(SELECT `tool_name` as tool, SEC_TO_TIME(SUM(`duration`)/SUM(`counter`)) as Avg_time 
								FROM %stool_track 
								WHERE `course_id` = %d 
								GROUP BY `tool_name`)a
						JOIN (SELECT `tool_name` as tool, SEC_TO_TIME(SUM(`duration`)/SUM(`counter`)) as Your_avg_time 
					FROM %stool_track 
					WHERE `member_id` = %d AND `course_id` = %d
					GROUP BY `tool_name`)b
					ON a.tool = b.tool";
		
		$sql2 =	"SELECT 'CONTENT' as tool, a.Avg_time, b.Your_avg_time FROM
						( SELECT SEC_TO_TIME(SUM(`duration`)/SUM(`counter`)) as Avg_time, `course_id`
								FROM %smember_track 
								WHERE `course_id` = %d )a
						JOIN (SELECT SEC_TO_TIME(SUM(`duration`)/SUM(`counter`)) as Your_avg_time,
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
				echo '<td><a href='.AT_BASE_HREF.url_rewrite('mods/_standard/tracker/'.strtolower($row['tool']).'_details.php').'>' . $row['tool'] . '</a> </td>';
				echo '<td>' . $row['Avg_time'] . '</td>';
				echo '<td>' . $row['Your_avg_time'] . '</td>';
				
				echo '</tr>';
			} //end while
			echo '</tbody>';
		}
	?>
</table>
</body>


<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>
<script>
	<?php	
		require('../../../jscripts/d3js/d3.v3.min.js'); 
		require('js/student_bar_graph.js');
	?>
</script>