$(document).ready(function () {
    $('#mainTable').tablesorter({
	theme: 'dropbox',
	widthFixed: true,
	initWidgets: true,
	widgetClass: 'widget-{name}',
	widgets: ['pager'],
	widgetOptions: {
		pager_size: 25,
		pager_pageReset: 0,
		pager_output: '{startRow} to {endRow} of ({totalRows})',
		pager_ajaxUrl: null,
		pager_updateArrows: true,
		pager_maxOptionSize: 50,
		pager_fixedHeight: false,
		pager_countChildRows: false,
		pager_removeRows: false,
		pager_css: {
				// class added to pager container
				container   : 'tablesorter-pager',
				// error information row (don't include period at beginning)
				errorRow    : 'tablesorter-errorRow',
				// class added to arrows @ extremes
				// (i.e. prev/first arrows "disabled" on first page)
				disabled    : 'disabled'
			},
		pager_selectors: {
				// target the pager markup
				container   : '.pager',
				// go to first page arrow
				first       : '.first',
				// previous page arrow
				prev        : '.prev',
				// next page arrow
				next        : '.next',
				// go to last page arrow
				last        : '.last',
				// go to page selector - select dropdown that sets the current page
				gotoPage    : '.gotoPage',
				// location of where the "output" is displayed
				pageDisplay : '.pagedisplay',
				// page size selector - select dropdown that sets the "size" option
				pageSize    : '.pagesize'
			}
	},
	debug: false
    });
} );
