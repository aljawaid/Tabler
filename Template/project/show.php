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


<?= $this->projectHeader->render($project, 'ProjectOverviewController', 'show') ?>

<style>

.tablesorter-blue {
    margin: 0px 10px
}

.tablesorter-blue th
{
    background: #f7f7f7;
    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.7);
}

.tablesorter-blue .tablesorter-header {
    padding: 10px 8px;
}

.tablesorter-blue input.tablesorter-filter {
    padding: 1px;
}

.subtask {
    font-size: 12px;
}

.tablesorter-hasChildRow {
    font-size: 13px;
}

.tablesorter-blue tbody tr.odd > td  childTaskExpanded {
   background: #f7f7f7;
}

.tablesorter-blue tbody tr.odd > td {
//    background-color: #ffffff;
}


.tablesorter-blue tbody > tr.odd.hover > td, .tablesorter-blue tbody > tr.odd:hover > td, .tablesorter-blue tbody > tr.odd:hover + tr.tablesorter-childRow > td, .tablesorter-blue tbody > tr.odd:hover + tr.tablesorter-childRow + tr.tablesorter-childRow > td {
    background-color: #bbbbbb;
}

.tablesorter-sticky-visible {
    margin-left: 10px;
}

.project-header {
    margin-bottom: 0px;
}

.pagedisplay {
    margin-left: 20px;
}

.dropdown-submenu-open li:hover:not(.no-hover),
.textarea-dropdown .active,
.textarea-dropdown li:hover {

    background: #36c

}

ul.dropdown-submenu-open {
    border-radius: 0
}

.columnSelectorButton {
    width: 100px;
    font-weight: 600;
    text-align: center;
    margin-left: 20px;
}

#columnSelector {
    position: absolute;
    z-index: 1000;

}

#pager {
    margin-left: 20px;
}

th.tablesorter-header.resizable-false {
  background-color: #e6bf99;
}
/* ensure box-sizing is set to content-box, if using jQuery versions older than 1.8;
 this page is using jQuery 1.4 */
*, *:before, *:after {
  -moz-box-sizing: content-box;
  -webkit-box-sizing: content-box;
  box-sizing: content-box;
}
/* overflow table */
.wrapper {
  overflow-x: auto;
  overflow-y: hidden;
  width: 450px;
}
.wrapper table {
  width: auto;
  table-layout: fixed;
}
.wrapper .tablesorter td {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  min-width: 10px;
}
.wrapper .tablesorter th {
  overflow: hidden;
  text-overflow: ellipsis;
  min-width: 10px;
}

.tablesorter, .tablesorter .tablesorter-filter {
    width: auto;
}
</style>

<script id="js">
$(function() {

  $(".tablesorter").tablesorter({
    theme: 'blue',
    widgets: ['zebra', 'reflow', 'resizable', 'columnSelector', 'stickyHeaders', 'filter', 'pager'],
    widgetOptions : {


      // target the column selector markup
      columnSelector_container : $('#columnSelector'),
      // column status, true = display, false = hide
      // disable = do not display on list
      columnSelector_columns : {
        0: 'disable' /* set to disabled; not allowed to unselect it */
      },
      // remember selected columns (requires $.tablesorter.storage)
      columnSelector_saveColumns: true,

      // container layout
      columnSelector_layout : '<label><input type="checkbox">{name}</label>',
      // data attribute containing column name to use in the selector container
      columnSelector_name  : 'data-name',

      /* Responsive Media Query settings */
      // enable/disable mediaquery breakpoints
      columnSelector_mediaquery: true,
      // toggle checkbox name
      columnSelector_mediaqueryName: 'Auto: ',
      // breakpoints checkbox initial setting
      columnSelector_mediaqueryState: true,
      // hide columnSelector false columns while in auto mode
      columnSelector_mediaqueryHidden: true,
      // responsive table hides columns with priority 1-6 at these breakpoints
      // see http://view.jquerymobile.com/1.3.2/dist/demos/widgets/table-column-toggle/#Applyingapresetbreakpoint
      // *** set to false to disable ***
      columnSelector_breakpoints : [ '20em', '30em', '40em', '50em', '60em', '70em' ],
      // data attribute containing column priority
      // duplicates how jQuery mobile uses priorities:
      // http://view.jquerymobile.com/1.3.2/dist/demos/widgets/table-column-toggle/
      columnSelector_priority : 'data-priority',

      // class name added to checked checkboxes - this fixes an issue with Chrome not updating FontAwesome
      // applied icons; use this class name (input.checked) instead of input:checked
      columnSelector_cssChecked : 'checked',


      reflow_headerAttrib : 'data-name',

      resizable: true,
      // These are the default column widths which are used when the table is
      // initialized or resizing is reset; note that the "Age" column is not
      // resizable, but the width can still be set to 40px here
      //resizable_widths : [ '10%', '10%', '40px', '10%', '100px' ]
      resizable_widths : [ '26px', '100px', '25%', '60px', '100px', '80px', '100px', '80px', '48px', '120px', '120px', '120px', '80px' ]




    }
  });

  // hide child rows
  //$('.tablesorter-childRow td').hide();
 $('.tablesorter-childRow td').addClass( 'hidden' );


  $(".tablesorter").tablesorter({
      theme : 'blue',
      // this is the default setting
      cssChildRow: "tablesorter-childRow"

    })

    .tablesorterPager({
      container: $("#pager"),
      positionFixed: false
    });

/*      // hide child rows after pager update
      $('.tablesorter-childRow td').hide();
    });
    */

  // Toggle child row content (td), not hiding the row since we are using rowspan
  // Using delegate because the pager plugin rebuilds the table after each page change
  // "delegate" works in jQuery 1.4.2+; use "live" back to v1.3; for older jQuery - SOL
  $('.tablesorter').delegate('.toggle', 'click' ,function(){

	$( this )
         .closest( 'tr' )
	 .toggleClass( 'childTaskExpanded')
         .nextUntil( 'tr.tablesorter-hasChildRow' )
	 .toggleClass( 'childTaskExpanded')
         .find( 'td' )
         .toggleClass( 'hidden' );

    // use "nextUntil" to toggle multiple child rows
    // toggle table cells instead of the row
//    $(this).closest('tr').nextUntil('tr:not(.tablesorter-childRow)').find('td').toggle();
    // in v2.5.12, the parent row now has the class tablesorter-hasChildRow
    // so you can use this code as well
    // $(this).closest('tr').nextUntil('tr.tablesorter-hasChildRow').find('td').toggle();

    return false;
  });


 $(".tablesorter").tablesorter({ sortList: [[0,0], [1,0]] });



});

</script>
<?php //print_r(array_values($TablerData));?>
<!-- This selector markup is completely customizable -->
<div class="columnSelectorWrapper">
  <input id="colSelect1" type="checkbox" class="hidden">
  <label class="columnSelectorButton" for="colSelect1">Column</label>
  <div id="columnSelector" class="columnSelector">
    <!-- this div is where the column selector is added -->
  </div>
</div>

<table id="" class="tablesorter custom-popup">

<!  <colgroup>
<!    <col width="85" />
<!    <col width="100" />
<!    <col width="250" />
<!    <col width="90" />
<!    <col width="80" />
<!    <col width="80" />
<!  </colgroup>


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
		//print '<tr><td class="task-table" rowspan="2">';
      
?>
		   <?php if ($this->user->hasProjectAccess('TaskModificationController', 'edit', $task['project_id'])): ?>
                        <?= $this->render('task/dropdown', array('task' => $task)) ?>
                    <?php else: ?>
                        #<?= $task['id'] ?>
                    <?php endif ?>
<?php

		print '</td>';
		print '<td rowspan="2">' . $task['project_name'] . '</td>';
		print '<td><a href="#" class="toggle">' . $task['title'] . '</a></td>';
		print '<td>';
		if(isset($task['is_active']) && $task['is_active'] == 0){
			print 'Closed';
		}elseif(isset($task['is_active']) && $task['is_active'] == 1){
			print 'Active';
		}
		//print '<td>' . $task['is_active'] . '</td>';
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
		//print '<td>' . $task['priority'] . '</td>';
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
<?php
/*

    <tr>
      <td rowspan="2"> <!-- rowspan="2" makes the table look nicer -->
        <a href="#" class="toggle">SO71774</a> <!-- link to toggle view of the child row -->
      </td>
      <td>Good Toys</td>
      <td>PO348186287</td>
      <td>Jul 20, 2007</td>
      <td>$972.78</td>
    </tr>
    <tr class="tablesorter-childRow">
      <td colspan="4">
        <div class="bold">Shipping Address</div>
        <div>99700 Bell Road<br>Auburn, California 95603</div>
      </td>
    </tr>
    <tr>
      <td rowspan="2"> <!-- rowspan="2" makes the table look nicer -->
        <a href="#" class="toggle">SO71775</a> <!-- link to toggle view of the child row -->
      </td>
      <td>Cycle Clearance</td>
      <td>PO58159451</td>
      <td>May 6, 2007</td>
      <td>$2,313.13</td>
    </tr>
    <tr class="tablesorter-childRow">
      <td colspan="4">
        <div class="bold">Shipping Address</div>
        <div>2255 254th Avenue Se<br>Albany, Oregon 97321</div>
      </td>
    </tr>
   <tr>
      <td rowspan="2"> <!-- rowspan="2" makes the table look nicer -->
        <a href="#" class="toggle">SO71774</a> <!-- link to toggle view of the child row -->
      </td>
      <td>Good Toys</td>
      <td>PO348186287</td>
      <td>Jul 20, 2007</td>
      <td>$972.78</td>
    </tr>
    <tr class="tablesorter-childRow">
      <td colspan="4">
        <div class="bold">Shipping Address</div>
        <div>99700 Bell Road<br>Auburn, California 95603</div>
      </td>
    </tr>
    <tr>
      <td rowspan="2"> <!-- rowspan="2" makes the table look nicer -->
        <a href="#" class="toggle">SO71775</a> <!-- link to toggle view of the child row -->
      </td>
      <td>Cycle Clearance</td>
      <td>P158159451</td>
      <td>May 6, 2007</td>
      <td>$2,313.13</td>
    </tr>
    <tr class="tablesorter-childRow">
      <td colspan="4">
        <div class="bold">Shipping Address</div>
        <div>2255 254th Avenue Se<br>Albany, Oregon 97321</div>
      </td>
    </tr>

    <!-- View page source for complete HTML markup -->

*/
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
