<?= $this->asset->css('plugins/Tabler/assets/css/theme.blue.css') ?>
<?= $this->asset->css('plugins/Tabler/assets/css/theme.bootstrap.css') ?>
<?= $this->asset->css('plugins/Tabler/assets/css/tablesort.columnSelector.css') ?>

<?= $this->asset->js('plugins/Tabler/assets/js/jquery.tablesorter.js') ?>
<?= $this->asset->js('plugins/Tabler/assets/js/jquery.tablesorter.widgets.js') ?>
<?= $this->asset->js('plugins/Tabler/assets/js/jquery.tablesorter.pager.js') ?>
<?= $this->asset->js('plugins/Tabler/assets/js/jquery.tablesorter.widget-columnSelector.js') ?>
<?= $this->asset->js('plugins/Tabler/assets/js/jquery.tablesorter.widget-reflow.js') ?>

<?= $this->asset->css('plugins/Tabler/assets/css/pgwmodal.min.css') ?>
<?= $this->asset->js('plugins/Tabler/assets/js/pgwmodal.min.js') ?>

<?= $this->asset->css('plugins/Tabler/assets/css/style.css') ?>
<?= $this->asset->js('plugins/Tabler/assets/js/tabler.js') ?>

<?php
// If this is the project specific table render project-header
if ($project != 'Allprojects') {
?>
<?= $this->projectHeader->render($project, 'ProjectOverviewController', 'show') ?>
<?php
}
?>

<!-- This selector markup is completely customizable -->
<div class="columnSelectorWrapper">
  <input id="colSelect1" type="checkbox" class="hidden">
  <label class="columnSelectorButton" for="colSelect1">Columns</label>
  <div id="columnSelector" class="columnSelector">
    <!-- this div is where the column selector is added -->
  </div>
</div>

<table id="" class="tablesorter custom-popup">

  <thead>
    <tr>
	<th data-priority="critical">ID</th>
	<th data-priority="1">Project</th>
	<th data-priority="critical">Task</th>
	<th data-priority="3">Status</th>
	<th data-priority="4">Column</th>
	<th data-priority="5">Swimlane</th>
	<th data-priority="5">Category</th>
	<th data-priority="5">Priority</th>
	<th data-priority="6">Start date</th>
	<th data-priority="6">Due date</th>
	<th data-priority="6">Date modified</th>
	<th data-priority="6" class="columnSelector-false">Date moved</th>
	<th data-priority="6" class="columnSelector-false">Date created</th>
	<th data-priority="6">Owner</th>
	<th data-priority="6" class="columnSelector-false">Score</th>
	<th data-priority="6" class="columnSelector-false">Time spent</th>
	<th data-priority="6" class="columnSelector-false">Time estimated</th>
    </tr>
  </thead>
  <tbody>

	<?php
	$task = array();
	foreach($TablerData as $task){
		print '<tr><td class="task-table color-' . $task['color_id'] . '" rowspan="2">';
	?>
		<?php if ($this->user->hasProjectAccess('TaskModificationController', 'edit', $task['project_id'])): ?>
                	<?= $this->render('task/dropdown', array('task' => $task)) ?>
                <?php else: ?>
                        #<?= $task['id'] ?>
                <?php endif ?>
	<?php

		print '</td>';
		print '<td rowspan="2">' . $task['project_name'] . '</td>';
		print '<td><a href="#" class="toggle">' . $task['title'];

		if (!empty($task['description'])){
			print '  <i class="fa fa-plus-square-o"></i>';
		}

		print '</a></td>';
		print '<td>';

		if(isset($task['is_active']) && $task['is_active'] == 0){
			print 'Closed';
		}elseif(isset($task['is_active']) && $task['is_active'] == 1){
			print 'Active';
		}

		print '</td>';
		print '<td>' . $task['column_name'] . '</td>';
		print '<td>' . $task['swimlane_name'] . '</td>';
		print '<td>' . $task['category_name'] . '</td>';
		print '<td>';

		if(isset($task['priority']) && $task['priority'] == 0){
			print 'None';
		}elseif(isset($task['priority']) && $task['priority'] == 1){
			print 'Low';
		}elseif(isset($task['priority']) && $task['priority'] == 2){
			print 'Medium';
		}elseif(isset($task['priority']) && $task['priority'] == 3){
			print 'High';
		}

		print '<td>';
		if(isset($task['date_started']) && $task['date_started'] != 0){print date("Y-m-d - H:i", $task['date_started']);};
		print '</td>';
		print '<td>';
		if(isset($task['date_due']) && $task['date_due'] != 0){print date("Y-m-d - H:i", $task['date_due']);};
		print '</td>';
		print '<td>';
		if(isset($task['date_modification']) && $task['date_modification'] != 0){print date("Y-m-d - H:i", $task['date_modification']);};
		print '</td>';
		print '<td>';
		if(isset($task['date_moved']) && $task['date_moved'] != 0){print date("Y-m-d - H:i", $task['date_moved']);};
		print '</td>';
		print '<td>' . date("Y-m-d - H:i", $task['date_creation']) . '</td>';
		print '<td>' . $task['owner_username'] . '</td>';
		print '<td>' . $task['score'] . '</td>';
		print '<td>' . $task['time_spent'] . '</td>';
		print '<td>' . $task['time_estimated'] . '</td>';
		print '</tr>';
	?>
	   <tr class="tablesorter-childRow">
	      <td class="subtask" colspan="">
	        <div class="bold"><?php print $task['description']; ?></div>
	      </td>
	    </tr>
	<?php
	}
	?>

  </tbody>
</table>

<div id="pager" class="pager">
  <form>
    <input type="button" value="&lt;&lt;" class="columnSelectorButton first" />
    <input type="button" value="&lt;" class="columnSelectorButton prev" />
    <input type="text" class="pagedisplay"/>
    <input type="button" value="&gt;" class="columnSelectorButton next" />
    <input type="button" value="&gt;&gt;" class="columnSelectorButton last" />
    <select class="columnSelectorButton pagesize">
      <option selected="selected"  value="10">10</option>
      <option value="20">20</option>
      <option value="30">30</option>
      <option value="40">40</option>
    </select>
  </form>
</div>

<br>
<br>
