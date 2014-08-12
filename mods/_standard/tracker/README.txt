 --------------------------
 |ATutor Statistics Module|
 --------------------------
 This module generates a variety of usage statistics
 and is presented to students and instructors

 -------------------
 |Tables associated|
 -------------------
 The tables associated with the module are defined in
 .\include\install\db\atutor_schema.sql
 
 Tables:
 1. tool_track	:	keep record of movement of user from any content
					to forum or blog
 2. member_track	:	keep a track of movement of user from one content
						to another

 ------------------------
 |Data Gathering Section|
 ------------------------
 Gathering of data is done for students only.
 Three functions are devoted for the job:
 
 save_last_cid()	:	This stores the last content Id in the table `member_track`.
						Used for content movement statistics.
 save_last_pid()	:	This stores the blog post id in `tool_track` with the content id.
						Used for content to blog post movement with counter and duration.
 save_last_fid()	:	This stores the forum and forum post id in `tool_track`.
						Used for content to forum post movement with counter and duration

 ------------------
 |Module Structure|
 ------------------
	
	-my_stats.php		-- Displays statistics of content,forum and blog with bar graph.
							Compares Student's Average Time with class Average time 
							For bar graph Refer: ./js/student_bar_graph.js
							
	-blogs_details.php		-- Child of my_stats.php
								Display details of blogs a student has visited with the
								average time, total time spent and last visit.
								Pie Chart displays the percentage of time devoted.
								For Pie chart Refer: .js/content_pie_chart.js
								
	-content_details.php		-- Child of my_stats.php
									Display details of contents a student has visited with the time
									spent there, number of visits and last visit. 
									Pie Chart displays the percentage of time devoted.
									For Pie chart Refer: .js/content_pie_chart.js
									
	-forums_details.php			-- Child of my_stats.php
									Display details of forums a student has visited with the
									average time, total time spent and last visit.
									Pie Chart displays the percentage of time devoted.	
									For Pie chart Refer: .js/content_pie_chart.js
									
	-module.php			-- Defines the pages in the module with their relation with others.
							Has attributes such as children, parent, title var.

	-module.xml			-- simple XML file of the module
	
	-sublinks.php		-- Find and displays the sublinks for a particular page.
							Current sublinks limit is 3
	
	-./css/			-- Contains all the CSS file related specifically for the module.

	-./css/student_bar_graph.css		-- Contains styles for the bar graph used in the my_stats.php
											Bar Graph defined here : .\mods\_standard\tracker\js\student_bar_graph.js
	
	-./js/			-- Contains the Java script files relevant to the module
	
	-./js/student_bar_graph.js		-- Java script file to create a bar graph.
										Used in my_stats.php
	
	-./js/content_pie_chart.js		-- Java script file to create a pie chart.
										Used in content_details.php, blogs_details.php, forums_details.php
	
	-./tools/student_tool_stats.php			-- Used to display report of tool usage to instructor.
												Goto Manage > Statistics > Student Usage Report
	