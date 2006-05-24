<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2006 by Greg Gay, Joel Kronenberg,				*/
/* Heidi Hazelton, and Jonathan Hung									*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
// $Id$
define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
authenticate(AT_PRIV_GROUPS);

/* Get type ID */
$id = intval($_REQUEST['id']);

$sql = "SELECT * FROM ".TABLE_PREFIX."groups_types WHERE type_id=$id AND course_id=$_SESSION[course_id]";
$result = mysql_query($sql,$db);
if (!($type_row = mysql_fetch_assoc($result))) {
	require (AT_INCLUDE_PATH.'header.inc.php');
	$msg->printErrors('GROUP_TYPE_NOT_FOUND');
	require (AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}

$tmp_groups = array();
$sql = "SELECT group_id, title FROM ".TABLE_PREFIX."groups WHERE type_id=$id ORDER BY title";
$result = mysql_query($sql, $db);
while ($row = mysql_fetch_assoc($result)) {
	$tmp_groups[$row['group_id']] = $row['title'];
}
$groups_keys = array_keys($tmp_groups);
$groups_keys = implode($groups_keys, ',');

if (isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: index.php');
	exit;
} else if (isset($_POST['submit'])) {
	$sql = "DELETE FROM ".TABLE_PREFIX."groups_members WHERE group_id IN ($groups_keys)";
	mysql_query($sql, $db);

	$sql = '';
	foreach ($_POST['groups'] as $mid => $gid) {
		$mid = abs($mid);
		$gid = abs($gid);
		if ($gid) {
			$sql .= "($gid, $mid),";
		}
	}
	if ($sql) {
		$sql = substr($sql, 0, -1);
		$sql = "INSERT INTO ".TABLE_PREFIX."groups_members VALUES $sql";
		mysql_query($sql, $db);
	}

	$msg->addFeedback('GROUP_MEMBERS_SAVED');

	header('Location: index.php');
	exit;
} else if (isset($_POST['assign'])) {

	$groups_counts = array();
	$sql = "SELECT group_id, COUNT(*) AS cnt FROM ".TABLE_PREFIX."groups_members WHERE group_id IN ($groups_keys) GROUP BY group_id ORDER BY cnt ASC";
	$result = mysql_query($sql, $db);
	while ($row = mysql_fetch_assoc($result)) {
		$groups_counts[$row['group_id']] = $row['cnt'];
	}
	$total_assigned = array_sum($groups_counts);

	if (is_array($_POST['groups'])) {
		foreach ($_POST['groups'] as $mid => $gid) {
			if ($gid) {
				unset($_POST['groups'][$mid]);
			}
		}
		$students = array_keys($_POST['groups']);

		$total_unassigned = count($students);

		shuffle($students);
		reset($students);
	}

	$total_students = $total_unassigned + $total_assigned;

	$num_groups = count($tmp_groups);

	if ($total_students > 0) {
		// to uniformly distribute all the groups we place the remaining students
		// into the first n groups, where n is the number of remaining students.
		$remainder = $total_students % $num_groups;
		if ($remainder) {
			$num_students_per_group = floor($total_students / $num_groups);
		} else {
			$num_students_per_group = $total_students / $num_groups;
		}

		$sql = '';
		foreach($tmp_groups as $group_id => $garbage) {

			if (!isset($groups_counts[$group_id])) {
				$groups_counts[$group_id] = 0;
			}
			while (($groups_counts[$group_id] < $num_students_per_group) && ($mid = current($students))) {
				$sql .= "($group_id, $mid),";
				$groups_counts[$group_id]++;
				next($students);
			}

			if ($remainder) {
				$mid = current($students);
				if ($mid) {
					$sql .= "($group_id, $mid),";
					$remainder--;
					next($students);
					$groups_counts[$group_id]++;
				}
			}
		}
		if ($sql) {
			$sql = substr($sql, 0, -1);
			$sql = "INSERT INTO ".TABLE_PREFIX."groups_members VALUES " . $sql;
			mysql_query($sql, $db);
		}
	}

	$msg->addFeedback('GROUP_MEMBERS_SAVED');
	header('Location: index.php');
	exit;
}

require(AT_INCLUDE_PATH.'header.inc.php');


echo '<h2>'.$type_row['title'].'</h2>';


if (isset($_GET['gid'])) {
	$_GET['gid'] = abs($_GET['gid']);
} else {
	$_GET['gid'] = 0;
}

$groups_members = array();
$sql = "SELECT member_id, group_id FROM ".TABLE_PREFIX."groups_members WHERE group_id IN ($groups_keys) ORDER BY member_id";
$result = mysql_query($sql, $db);
while ($row = mysql_fetch_assoc($result)) {
	$groups_members[$row['member_id']] = $row['group_id'];
}
$groups_members_keys = array_keys($groups_members);
$groups_members_keys = implode($groups_members_keys, ',');

$owner = $system_courses[$_SESSION['course_id']]['member_id'];

$sql = "SELECT M.member_id, M.login, M.first_name, M.last_name FROM ".TABLE_PREFIX."members M INNER JOIN ".TABLE_PREFIX."course_enrollment E USING (member_id) WHERE E.course_id=$_SESSION[course_id] AND E.privileges=0 AND E.approved='y' AND E.member_id<>$owner ORDER BY M.login";
$result = mysql_query($sql, $db);

$count = 0;
?>
<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<table class="data static" style="width: 60%" rules="rows">
<thead>
	<tr>
		<th style="text-align:left;"><?php echo _AT('login');      ?></th>
		<th style="text-align:left;"><?php echo _AT('first_name'); ?></th>
		<th style="text-align:left;"><?php echo _AT('last_name');  ?></th>
		<th style="text-align:left;"><?php echo _AT('groups');     ?></th>
	</tr>
</thead>
<tfoot>
	<tr>
		<td colspan="4">
			<input type="submit" name="submit" value="<?php echo _AT('save'); ?>" />
			<input type="submit" name="assign" value="<?php echo _AT('assign_unassigned'); ?>" />
			<input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?>" />
		</td>
	</tr>
</tfoot>
<tbody>
	<?php while ($row = mysql_fetch_assoc($result)): ?>
		<tr <?php if ($_GET['gid'] && $_GET['gid'] == $groups_members[$row['member_id']]) { echo 'style="background-color: #dfd; font-weight: bold;"';} ?> id="r<?php echo ++$count; ?>">
			<!-- Using mutliple labels is acceptable, but JAWS only reads the last one 
				see: http://www.access-matters.com/2005/09/10/speaking-form-labels-summary/#mult -->
			<td><label for="m<?php echo $row['member_id']; ?>"><?php echo $row['login']; ?></label></td>
			<td><label for="m<?php echo $row['member_id']; ?>"><?php echo $row['first_name']; ?></label></td>
			<td><label for="m<?php echo $row['member_id']; ?>"><?php echo $row['last_name']; ?></label></td>
			<td>
				<select name="groups[<?php echo $row['member_id']; ?>]" id="m<?php echo $row['member_id']; ?>" onchange="javascript:hirow(this, <?php echo $count; ?>);">
					<option value="0"></option>
					<?php foreach ($tmp_groups as $group => $title): ?>
						<option value="<?php echo $group; ?>" <?php if ($groups_members[$row['member_id']] == $group) { echo 'selected="selected"'; } ?>><?php echo $title; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
	<?php endwhile; ?>
</tbody>
</table>
</form>

<script type="text/javascript" language="javascript">
// <!--
function hirow(obj, row) {
	if (obj.value && obj.value == <?php echo $_GET['gid']; ?>) {
		e = document.getElementById("r" + row);
		e.style.backgroundColor = "#dfd";
		e.style.fontWeight = "bold";
	} else {
		e = document.getElementById("r" + row);
		e.style.backgroundColor = "";
		e.style.fontWeight = "";
	}
}
// -->
</script>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>