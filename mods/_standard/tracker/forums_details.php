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
?>

<div id="piechart" align= "center"></div>
<table class="data static" summary="">
	<thead>
		<tr>
			<th scope="col"><?php echo "Forum"; ?></th>
			<th scope="col"><?php echo "Average Duration"; ?></th>
			<th scope="col"><?php echo _AT('duration'); ?></th>
			<th scope="col"><?php echo _AT('last_accessed'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			$sql = "SELECT a.main_tool_id as tool_id, b.title, a.average_duration, a.total_duration, a.last_accessed FROM
						(SELECT main_tool_id, SEC_TO_TIME(SUM(`duration`)/SUM(`counter`)) as average_duration, 
						SEC_TO_TIME(SUM(`duration`)) as total_duration, last_accessed
								FROM %stool_track 
								WHERE `member_id` = %d AND `course_id` = %d AND `tool_name` = '%s'
								GROUP BY `main_tool_id`)a
						JOIN (SELECT `forum_id`, `title` 
					FROM %sforums
					WHERE 1)b
					ON a.main_tool_id = b.forum_id";

			$rows_hits = queryDB($sql, array(TABLE_PREFIX, $_SESSION['member_id'], $_SESSION['course_id'], "FORUMS", TABLE_PREFIX));
			if(count($rows_hits) > 0) {
				foreach($rows_hits as &$row){
					echo '<tr>';
					echo '<td><a href='.AT_BASE_HREF.url_rewrite('mods/_standard/forums/forum/index.php?fid='.$row['tool_id']). '>' . $row['title']. '</a></td>';
					echo '<td>' . $row['average_duration'] . '</td>';
					echo '<td>' . $row['total_duration'] . '</td>';
					if ($row['last_accessed'] == '') {
						echo '<td>' . _AT('na') . '</td>';
					} 
					else {
						echo '<td>' . AT_date(_AT('forum_date_format'), $row['last_accessed'], AT_DATE_MYSQL_DATETIME) . '</td>';
					}
					echo '</tr>';
					$row['tool_id'] = $row['title'];
				} //end foreach
				echo '</tbody>';
			}
			else {
				if($_SESSION['is_admin'] == 1){
					$msg->printInfos('TRACKING_NO');
				}
				echo '<tr><td colspan="4">' . _AT('none_found') . '</td></tr>';
				echo '</tbody>';
			}
		?>
	</tbody>
</table>
<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>
<script>
	<?php	
		require('../../../jscripts/d3js/d3.v3.min.js');
		require('js/content_pie_chart.js');
	?>
</script>
