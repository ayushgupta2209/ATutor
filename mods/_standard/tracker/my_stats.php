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
?>

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

	$sql = "SELECT a.tool, a.Avg_time, b.Your_avg_time FROM
					(SELECT `tool_name` as tool, SUM(`duration`)/SUM(`counter`) as Avg_time 
							FROM %stool_track 
							WHERE `course_id` = %d 
							GROUP BY `tool_name`)a
					JOIN (SELECT `tool_name` as tool, SUM(`duration`)/SUM(`counter`) as Your_avg_time 
				FROM %stool_track 
				WHERE `member_id` = %d AND `course_id` = %d
				GROUP BY `tool_name`)b
				ON a.tool = b.tool";
	$rows_hits1 = queryDB($sql, array(TABLE_PREFIX, $_SESSION['course_id'], TABLE_PREFIX, $_SESSION['member_id'], $_SESSION['course_id']));
 
    if(count($rows_hits1) > 0){
	    foreach($rows_hits1 as $row){
			
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

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>
<!DOCTYPE html>
<meta charset="utf-8">
<style>

.y.axisRight text {
    fill: orange;
}

.y.axisLeft text {
    fill: steelblue;
}

.axis path,
.axis line {
  fill: none;
  stroke: #000;
  shape-rendering: crispEdges;
}

.bar1 {
  fill: steelblue;
}

.bar2 {
  fill: orange;
}

.x.axis path {
  display: none;
}

</style>
<body>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script>

var margin = {top: 80, right: 80, bottom: 80, left: 80},
    width = 600 - margin.left - margin.right,
    height = 400 - margin.top - margin.bottom;

var x = d3.scale.ordinal()
    .rangeRoundBands([0, width], .1);

var y0 = d3.scale.linear().domain([0, 80]).range([height, 0]),
y1 = d3.scale.linear().domain([0, 80]).range([height, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

// create left yAxis
var yAxisLeft = d3.svg.axis().scale(y0).ticks(10).orient("left");
// create right yAxis
var yAxisRight = d3.svg.axis().scale(y1).ticks(10).orient("right");

var svg = d3.select("#bargraph").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("class", "graph")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
var data = <?php echo json_encode($rows_hits1); ?>;
console.log(data[0]);
  x.domain(data.map(function(d) { return d.tool; }));
  
  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis);

  svg.append("g")
	  .attr("class", "y axis axisLeft")
	  .attr("transform", "translate(0,0)")
	  .call(yAxisLeft)
	.append("text")
	  .attr("y", 20)
	  .attr("dy", "-3em")
	  .style("text-anchor", "end")
	  .text("Avg_time");
	
  svg.append("g")
	  .attr("class", "y axis axisRight")
	  .attr("transform", "translate(" + (width) + ",0)")
	  .call(yAxisRight)
	.append("text")
	  .attr("y", 20)
	  .attr("dy", "-3em")
	  .attr("dx", "2em")
	  .style("text-anchor", "end")
	  .text("Your_Avg_time");

  bars = svg.selectAll(".bar").data(data).enter();

  bars.append("rect")
      .attr("class", "bar1")
      .attr("x", function(d) { return x(d.tool); })
      .attr("width", x.rangeBand()/2)
      .attr("y", function(d) { return y0(d.Avg_time); })
	  .attr("height", function(d,i,j) { return height - y0(d.Avg_time); }); 

  bars.append("rect")
      .attr("class", "bar2")
      .attr("x", function(d) { return x(d.tool) + x.rangeBand()/2; })
      .attr("width", x.rangeBand() / 2)
      .attr("y", function(d) { return y1(d.Your_avg_time); })
	  .attr("height", function(d,i,j) { return height - y1(d.Your_avg_time); }); 

</script>