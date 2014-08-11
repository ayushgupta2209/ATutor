<?php global $contentManager;?>
<body>
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
			if(count($this->rows_hits) > 0) {
				foreach($this->rows_hits as &$row){
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
				echo '<tr><td colspan="4">' . _AT('none_found') . '</td></tr>';
				echo '</tbody>';
			}
		?>
	</tbody>
</table>
</body>