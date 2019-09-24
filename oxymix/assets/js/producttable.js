(function($) {
$('#producttable').DataTable( {
    responsive: true,
	oLanguage: {
        sProcessing: "<div id='loader'></div>"
    },
	deferRender: true,
    processing: true,
    serverSide: true,
    ajax: ajax_url
} );
})( jQuery );