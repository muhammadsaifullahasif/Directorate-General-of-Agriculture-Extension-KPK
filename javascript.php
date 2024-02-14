<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE -->
<script src="dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="dist/js/pages/dashboard3.js"></script> -->
<script type="text/javascript" src="dist/js/main.js"></script>

<script type="text/javascript">
	
	$(document).ready(function(){

		var current_sidebar_page = window.top.location.href;

		$('.nav-sidebar a').map( function() {

			$(this).attr('href', main_url+ $(this).attr('href'));

			if( (current_sidebar_page.indexOf($(this).attr('href')) != -1) ) {
			// if( current_sidebar_page === $(this).attr('href') ) {
				// console.log( $(this).attr('href') );
				$(this).addClass('active');
				$(this).parents('ul.nav').addClass('nav-treeview');
				$(this).parents('ul.nav').prev('a.nav-link').addClass('active');
				$(this).parents('ul.nav').parents('li.nav-item').addClass('menu-open menu-is-opening');
			}

		} );


		function total_unseen_notifications() {
			$.ajax({
				url: 'ajax.php',
				type: 'POST',
				data: { action:'total_unseen_notifications', user_id:'<?= $user_id; ?>' },
				success: function(result) {
					$('.total_unseen_notifications').html(result);
				}
			});
		}
		total_unseen_notifications();

		function display_notifications() {
			$.ajax({
				url: 'ajax.php',
				type: 'POST',
				data: { action:'display_notifications', user_id:'<?= $user_id; ?>' },
				success: function(result) {
					$('#notification_container').html(result);
				}
			});
		}
		display_notifications();

		$(document).on('click', '.seen_notification', function(){
			var notification_id = $(this).data('id');
			if(notification_id != '' && notification_id != 0) {
				$.ajax({
					url: 'ajax.php',
					type: 'POST',
					data: { action:'mark_notification_seen', id:notification_id, user_id:'<?= $user_id; ?>', limit:'10' },
					success: function(result) {
						if(result == 0) {
							alert('Please Try Again');
						} else {
							total_unseen_notifications();
							display_notifications();
						}
					}
				});
			}
		});

		/*$("#table").DataTable({
				"responsive": true, "lengthChange": false, "autoWidth": false,
				"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
				"pageLength": 50,
		//		"lengthMenu": [
		//			[10, 25, 50, -1],
		//			[10, 25, 50, 'All'],
		//		],
			}).buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');*/

		$("#table").DataTable({
			"responsive": true, "lengthChange": false, "autoWidth": false,
			"buttons": [
				{
					extend: 'copy',
					exportOptions: {
						columns: [ ':visible' ]
					}
				},
				{
					extend: 'csv',
					exportOptions: {
						columns: [ ':visible' ]
					}
				},
				{
					extend: 'excel',
					exportOptions: {
						columns: [ ':visible' ]
					}
				},
				{
					extend: 'pdf',
					exportOptions: {
						columns: [ ':visible' ]
					}
				},
				{
					extend: 'print',
					exportOptions: {
						columns: [ ':visible' ]
					}
				}, 
				"colvis"
			],
			"pageLength": 50,
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, 'All'],
			],
		}).buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');

	});

</script>