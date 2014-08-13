<?php global $contentManager;?>
<body>
<?php
	if(count($this->rows_hits) > 0 && $_SESSION['is_admin'] != 1)
		echo '<div id="bargraph" align= "center"></div>' ; 
?>
<table class="data static" >
	<thead>
		<tr>
			<th scope="col"><?php echo 'Tool'; ?></th>
			<th scope="col"><?php echo 'Student\'s Average Time'; ?></th>
			<th scope="col"><?php echo 'Your Average Time'; ?></th>
		</tr>
	</thead>
	<?php
		if(count($this->rows_hits) > 0 && $_SESSION['is_admin'] != 1){
			foreach($this->rows_hits as $row){
				echo '<tr>';
				echo '<td><a href='.AT_BASE_HREF.url_rewrite('mods/_standard/tracker/'.strtolower($row['tool']).'_details.php').'>' . $row['tool'] . '</a> </td>';
				echo '<td>' . $row['Avg_time'] . '</td>';
				echo '<td>' . $row['Your_avg_time'] . '</td>';
				
				echo '</tr>';
			} //end while
			echo '</tbody>';
		}
		else {
			echo '<tr><td colspan="4">' . _AT('none_found') . '</td></tr>';
			echo '</tbody>';
		}
	?>
</table>
</body>