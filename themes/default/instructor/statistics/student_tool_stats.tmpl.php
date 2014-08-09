<?php global $contentManager;?>
<div class = "contentbox-stats">
	<div class = "contentbox-report">
		<table class="data static" summary="">
			<thead>
				<tr>
					<th scope="col" colspan = 3>Content Usage Report</th>
				</tr>
			</thead>
			<?php 
				if ($this->contentkeyVal == NULL)
					echo '<tr><td> Details Not Found </td></tr>';
				else 
					echo	'<tr>
								<td	title= "Content with Maximum Visits">	Maximum Visits	</td>
								<td> <a href='.AT_BASE_HREF.url_rewrite('content.php?cid='.$this->contentkeyVal['maxVisits'][0]). '>'. $contentManager->_menu_info[$this->contentkeyVal['maxVisits'][0]]['title']. '<a> </td>
								<td>'. $this->contentkeyVal['maxVisits'][1].'</td>
							</tr>
							<tr>
								<td title = "Content with Maximum Time devoted">	Maximum Time devoted	</td>
								<td> <a href='.AT_BASE_HREF.url_rewrite('content.php?cid='.$this->contentkeyVal['maxTime'][0]). '>'. $contentManager->_menu_info[$this->contentkeyVal['maxTime'][0]]['title']. '<a></td>
								<td>'.$this->contentkeyVal['maxTime'][1].'</td>
							</tr>
							<tr>
								<td	title= "Content with Maximum Visitors">	Maximum Visitors	</td>
								<td> <a href='.AT_BASE_HREF.url_rewrite('content.php?cid='.$this->contentkeyVal['maxVisitors'][0]). '>'. $contentManager->_menu_info[$this->contentkeyVal['maxVisitors'][0]]['title']. '<a></td>
								<td>'.$this->contentkeyVal['maxVisitors'][1].'</td>
							</tr>'; 
			?>
		</table>
	</div>
	<div class = "contentbox-report">
				<table class="data static" summary="">
			<thead>
				<tr>
					<th scope="col" colspan = 3>Blog Usage Report</th>
				</tr>
			</thead>
			<?php
				if ($this->blogkeyVal == NULL)
					echo '<tr><td> Details Not Found </td></tr>';
				else 
					echo	'<tr>
								<td	title= "Blog with Maximum Visits" >	Maximum Visits	</td>
								<td><a href='.AT_BASE_HREF.url_rewrite('mods/_standard/blogs/post.php?ot=1&oid='. $this->blogkeyVal['maxVisits'][2].'&id='. $this->blogkeyVal['maxVisits'][3]).'>' .  $this->blogkeyVal['maxVisits'][0]. '</a></td>
								<td>'.$this->blogkeyVal['maxVisits'][1].'</td>
							</tr>
							<tr>
								<td title= "Blog with Maximum Comments">	Maximum Comments	</td>
								<td><a href='.AT_BASE_HREF.url_rewrite('mods/_standard/blogs/post.php?ot=1&oid='. $this->blogkeyVal['maxComments'][2].'&id='. $this->blogkeyVal['maxComments'][3]).'>' .  $this->blogkeyVal['maxComments'][0]. '</a></td>
								<td>'.$this->blogkeyVal['maxComments'][1].'</td>
							</tr>
							<tr>
								<td	title= "Blog with Maximum Visitors">	Maximum Visitors	</td>
								<td><a href='.AT_BASE_HREF.url_rewrite('mods/_standard/blogs/post.php?ot=1&oid='. $this->blogkeyVal['maxVisitors'][2].'&id='. $this->blogkeyVal['maxVisitors'][3]).'>' .  $this->blogkeyVal['maxVisitors'][0]. '</a></td>
								<td>'.$this->blogkeyVal['maxVisitors'][1].'</td>
							</tr>';
			?>
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
			<?php
				if ($this->forumkeyVal == NULL)
					echo '<tr><td> Details Not Found </td></tr>';
				else 
					echo	'<tr>
								<td	title= "Forum with Maximum Visits">	Maximum Visits	</td>
								<td><a href='.AT_BASE_HREF.url_rewrite('mods/_standard/forums/forum/index.php?fid='.$this->forumkeyVal['maxVisits'][2]). '>' . $this->forumkeyVal['maxVisits'][0]. '</a></td>
								<td>'.$this->forumkeyVal['maxVisits'][1].'</td>
							</tr>
							<tr>
								<td title = "Forum with Maximum Posts">	Maximum Posts	</td>
								<td><a href='.AT_BASE_HREF.url_rewrite('mods/_standard/forums/forum/index.php?fid='.$this->forumkeyVal['maxPosts'][2]). '>' . $this->forumkeyVal['maxPosts'][0]. '</a></td>
								<td>'.$this->forumkeyVal['maxPosts'][1].'</td>
							</tr>
							<tr>
								<td	title= "Forum with Maximum Visitors">	Maximum Visitors	</td>
								<td><a href='.AT_BASE_HREF.url_rewrite('mods/_standard/forums/forum/index.php?fid='.$this->forumkeyVal['maxVisitors'][2]). '>' . $this->forumkeyVal['maxVisitors'][0]. '</a></td>
								<td>'.$this->forumkeyVal['maxVisitors'][1].'</td>
							</tr>';
			?>
		</table>
	</div>
	<div class = "contentbox-report">
		<table class="data static" summary="">
			<thead>
				<tr>
					<th scope="col" colspan = 3>Students Report</th>
				</tr>
			</thead>
			<?php
				if($this->percentage == 0)
					echo '<tr><td>Details Not Found</td></tr>';
				else
					echo	'<tr>
								<td	title= "Percentage of enrolled Students active">	Percent of Active Students	</td>
								<td>'. $this->percentage."%".'</td>
							</tr>
							<tr>
								<td title = "Average time devoted by students">	Average Time Devoted	</td>
								<td>'. $this->average .'</td>
							</tr>
							<tr>
								
								<td	title= "Most Active student in course">	Most Active Student	</td>
								<td>'. $this->activeStudent .'</td>
							</tr>';
			?>
		</table>
	</div>
</div>