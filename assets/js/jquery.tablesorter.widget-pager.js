/* pager wrapper, div */
.tablesorter-pager {
	padding: 5px;
}
/* pager wrapper, in thead/tfoot */
td.tablesorter-pager {
	background-color: #e6eeee;
	margin: 0; /* needed for bootstrap .pager gets a 18px bottom margin */
}
/* pager navigation arrows */
.tablesorter-pager img {
	vertical-align: middle;
	margin-right: 2px;
	cursor: pointer;
}

/* pager output text */
.tablesorter-pager .pagedisplay {
	padding: 0 5px 0 5px;
	width: auto;
	white-space: nowrap;
	text-align: center;
}

/* pager element reset (needed for bootstrap) */
.tablesorter-pager select {
	margin: 0;
	padding: 0;
}

/*** css used when "updateArrows" option is true ***/
/* the pager itself gets a disabled class when the number of rows is less than the size */
.tablesorter-pager.disabled {
	display: none;
}
/* hide or fade out pager arrows when the first or last row is visible */
.tablesorter-pager .disabled {
	/* visibility: hidden */
	opacity: 0.5;
	filter: alpha(opacity=50);
	cursor: default;
}
