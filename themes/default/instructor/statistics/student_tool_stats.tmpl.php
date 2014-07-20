<?php global $contentManager;?>
<div class = "contentbox-stats">
	<div class = "contentbox-report">
		<table class="data static" summary="">
			<thead>
				<tr>
					<th scope="col" colspan = 3>Content Usage Report</th>
				</tr>
			</thead>
			<tr>
				<td	title= "Content with Maximum Visits">	Maximum Visits	</td>
				<td><?php echo $contentManager->_menu_info[$this->contentkeyVal['maxVisits'][0]]['title']; ?></td>
				<td><?php echo $this->contentkeyVal['maxVisits'][1]; ?></td>
			</tr>
			<tr>
				<td title = "Content with Maximum Time devoted">	Maximum Time devoted	</td>
				<td><?php echo $contentManager->_menu_info[$this->contentkeyVal['maxTime'][0]]['title']; ?></td>
				<td><?php echo $this->contentkeyVal['maxTime'][1]; ?></td>
			</tr>
			<tr>
				<td	title= "Content with Maximum Visitors">	Maximum Visitors	</td>
				<td><?php echo $contentManager->_menu_info[$this->contentkeyVal['maxVisitors'][0]]['title']; ?></td>
				<td><?php echo $this->contentkeyVal['maxVisitors'][1]; ?></td>
			</tr>
		</table>
	</div>
	<div class = "contentbox-report">
				<table class="data static" summary="">
			<thead>
				<tr>
					<th scope="col" colspan = 3>Blog Usage Report</th>
				</tr>
			</thead>
			<tr>
				<td	title= "Blog with Maximum Visits" >	Maximum Visits	</td>
				<td><?php echo $this->contentkeyVal['maxVisits'][0]; ?></td>
				<td><?php echo $this->contentkeyVal['maxVisits'][1]; ?></td>
			</tr>
			<tr>
				<td title= "Blog with Maximum Comments">	Maximum Comments	</td>
				<td><?php echo $this->contentkeyVal['maxComments'][0]; ?></td>
				<td><?php echo $this->contentkeyVal['maxComments'][1]; ?></td>
			</tr>
			<tr>
				<td	title= "Blog with Maximum Visitors">	Maximum Visitors	</td>
				<td><?php echo $this->contentkeyVal['maxVisitors'][0]; ?></td>
				<td><?php echo $this->contentkeyVal['maxVisitors'][1]; ?></td>
			</tr>
		</table>
	</div>
	<br>
	<div class = "contentbox-report">
		<table class="data static" summary="">
			<thead>
				<tr>
					<th scope="col" colspan = 3>Forum Usage Report</th>
				</tr>
			</thead>
			<tr>
				<td	title= "Forum with Maximum Visits">	Maximum Visits	</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td title = "Forum with Maximum Posts">	Maximum Posts	</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td	title= "Forum with Maximum Visitors">	Maximum Visitors	</td>
				<td></td>
				<td></td>
			</tr>
		</table>
	</div>
	<div class = "contentbox-report">
		<table class="data static" summary="">
			<thead>
				<tr>
					<th scope="col" colspan = 3>Students Report</th>
				</tr>
			</thead>
			<tr>
				<td	title= "Percentage of enrolled Students active">	Percent of Active Students	</td>
				<td></td>
			</tr>
			<tr>
				<td title = "Average time devoted by students">	Average Time Devoted	</td>
				<td></td>
			</tr>
			<tr>
				
				<td	title= "Most Active student in course">	Most Active Student	</td>
				<td></td>
			</tr>
		</table>
	</div>
</div>