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

/*  // hide child rows after pager update
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
    // $(this).closest('tr').nextUntil('tr:not(.tablesorter-childRow)').find('td').toggle();
    // in v2.5.12, the parent row now has the class tablesorter-hasChildRow
    // so you can use this code as well
    // $(this).closest('tr').nextUntil('tr.tablesorter-hasChildRow').find('td').toggle();

    return false;
  });


 $(".tablesorter").tablesorter({ sortList: [[0,0], [1,0]] });



});

