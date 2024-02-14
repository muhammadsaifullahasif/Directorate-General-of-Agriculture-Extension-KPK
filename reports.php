<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Reports';

	include "head.php";

	if(is_super_admin() || is_admin() || is_storekeeper()) {
		header('Location: index.php');
	}

	?>
	<style>
		#report_signature {
			display: none;
		}
	    @media print {
	        /* styles here */
	        #report_form, #report_print_btn {
	            display: none;
	        }
	        #report_signature {
	        	display: flex;
	        }
	    }
	</style>
</head>
<!--
`body` tag options:

Apply one or more of the following classes to to the body tag
to get the desired effect

* sidebar-collapse
* sidebar-mini
-->
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed text-sm sidebar-collapse">

	<div class="wrapper">
		
		<?php include "nav.php"; ?>
		<?php include "sidebar.php"; ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 d-inline"><?= $page_title; ?></h1>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i></a></li>
								<li class="breadcrumb-item active"><?= $page_title; ?></li>
							</ol>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">

					<form class="form mb-3" enctype="multipart/form-data" id="report_form" method="get">
						<input type="hidden" value="generate" name="report">

						<div class="row">

							<div class="<?php if(isset($_GET['report_type']) && $_GET['report_type'] == 'cleaning_report_of_other_circle') { echo 'col-md-3'; } else { echo 'col-md-4'; } ?> mb-3" id="report_type_container">
								<label>Report Type:</label>
								<select class="form-control" id="report_type" name="report_type">
									<option value="">Select Report Type</option>
									<option <?php if(isset($_GET['report_type']) && $_GET['report_type'] == 'cleaning_report_of_current_circle') { echo 'selected'; } ?> value="cleaning_report_of_current_circle">Cleaning Report of <?php echo circle_name($circle_id); ?></option>
									<option <?php if(isset($_GET['report_type']) && $_GET['report_type'] == 'cleaning_report_of_other_circle') { echo 'selected'; } ?> value="cleaning_report_of_other_circle">Cleaning Report of Other circle at <?php echo circle_name($circle_id); ?></option>
									<option <?php if(isset($_GET['report_type']) && $_GET['report_type'] == 'variety_wise_cleaning_report_at_current_circle') { echo 'selected'; } ?> value="variety_wise_cleaning_report_at_current_circle">Variety Wise Cleaning Report At <?php echo circle_name($circle_id); ?></option>
									<option <?php if(isset($_GET['report_type']) && $_GET['report_type'] == 'district_wise_cleaning_report') { echo 'selected'; } ?> value="district_wise_cleaning_report">District Wise Cleaning Report At <?php echo circle_name($circle_id); ?></option>
									<option <?php if(isset($_GET['report_type']) && $_GET['report_type'] == 'variety_wise_and_class_wise_stock_at_current_circle') { echo 'selected'; } ?> value="variety_wise_and_class_wise_stock_at_current_circle">Variety Wise and Class Wise Stock At <?php echo circle_name($circle_id); ?></option>
									<option <?php if(isset($_GET['report_type']) && $_GET['report_type'] == 'current_circle_to_other_district') { echo 'selected'; } ?> value="current_circle_to_other_district">Supply to Other District From <?php echo circle_name($circle_id); ?></option>
									<option <?php if(isset($_GET['report_type']) && $_GET['report_type'] == 'current_circle_to_within_district') { echo 'selected'; } ?> value="current_circle_to_within_district">Supply to Within District From <?php echo circle_name($circle_id); ?></option>
									<option <?php if(isset($_GET['report_type']) && $_GET['report_type'] == 'grand_total_at_current_circle') { echo 'selected'; } ?> value="grand_total_at_current_circle">Grand Total At <?php echo circle_name($circle_id); ?></option>
								</select>
							</div>
							<div class="<?php if(isset($_GET['report_type']) && $_GET['report_type'] == 'cleaning_report_of_other_circle') { echo 'col-md-3 mb-3'; } ?>" id="report_circle_container">
								<?php

								if(isset($_GET['report_type']) && $_GET['report_type'] == 'cleaning_report_of_other_circle') {
								?>
								<label>Circle:</label>
								<select class="form-control" id="report_circle" name="report_circle">
									<option value="">Select Circle</option>
									<?php

									$report_circle_query = mysqli_query($conn, "SELECT * FROM circles WHERE active_status='1' && delete_status='0' ORDER BY name ASC");
									if(mysqli_num_rows($report_circle_query) > 0) {
										while($report_circle_result = mysqli_fetch_assoc($report_circle_query)) {
											if(isset($_GET['report_circle']) && $_GET['report_circle'] == $report_circle_result['id']) {
												$report_circle_selected = 'selected';
											} else {
												$report_circle_selected = '';
											}
											echo "<option ".$report_circle_selected." value='".$report_circle_result['id']."'>".$report_circle_result['name']."</option>";
										}
									}

									?>
								</select>
								<?php
								}

								?>
							</div>
							<div class="<?php if(isset($_GET['report_type']) && $_GET['report_type'] == 'cleaning_report_of_other_circle') { echo 'col-md-3'; } else { echo 'col-md-4'; } ?> mb-3" id="stock_crop_container">	
								<label>Stock Crop:</label>
								<select class="form-control" id="stock_crop" name="stock_crop">
									<option value="">Select Stock Crop</option>
									<?php

									$stock_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
									if(mysqli_num_rows($stock_crop_query) > 0) {
										while($stock_crop_result = mysqli_fetch_assoc($stock_crop_query)) {
											if(isset($_GET['stock_crop']) && $_GET['stock_crop'] == $stock_crop_result['id']) {
												$stock_crop_selected = 'selected';
											} else {
												$stock_crop_selected = '';
											}
											echo "<option ".$stock_crop_selected." value='".$stock_crop_result['id']."'>".$stock_crop_result['crop']."</option>";
										}
									}

									?>
								</select>
							</div>
							<div class="<?php if(isset($_GET['report_type']) && $_GET['report_type'] == 'cleaning_report_of_other_circle') { echo 'col-md-3'; } else { echo 'col-md-4'; } ?> mb-3" id="activity_season_container">
								<label>Activity Season:</label>
								<select class="form-control" id="activity_season" name="activity_season">
									<option value="">Select Activity Season</option>
									<?php

									if(isset($_GET['stock_crop']) && $_GET['stock_crop'] != '' && $_GET['stock_crop'] != 0) {
										$stock_crop = validate($_GET['stock_crop']);
										$activity_season_query = mysqli_query($conn, "SELECT * FROM stock_activity_season WHERE stock_crop_id='$stock_crop' && active_status='1' && delete_status='0' ORDER BY time_created DESC");
										if(mysqli_num_rows($activity_season_query) > 0) {
											while($activity_season_result = mysqli_fetch_assoc($activity_season_query)) {
												if(isset($_GET['activity_season']) && $_GET['activity_season'] == $activity_season_result['id']) {
													$activity_season_selected = 'selected';
												} else {
													$activity_season_selected = '';
												}
												echo "<option ".$activity_season_selected." value='".$activity_season_result['id']."'>".$activity_season_result['season_title']."</option>";
											}
										}
									}

									?>
								</select>
							</div>

						</div>

						<button class="btn btn-primary" type="submit" id="report_form_btn" name="report_form_btn">Generate Report</button>

					</form>

					<div class="row">
						<div class="col-md-12">

							<button class="btn btn-primary mb-3" id="report_print_btn">Print</button>
							
							<?php

							if(isset($_GET['report']) && $_GET['report'] == 'generate') {
								$report_type = validate($_GET['report_type']);
								if($report_type == 'cleaning_report_of_other_circle') {
									$report_circle = validate($_GET['report_circle']);
								} else if($report_type == 'cleaning_report_of_current_circle' || $report_type == 'variety_wise_stock_at_current_circle' || $report_type == 'current_circle_to_other_district' || $report_type == 'current_circle_to_within_district' || $report_type == 'variety_wise_and_class_wise_stock_at_current_circle' || $report_type == 'variety_wise_cleaning_report_at_current_circle' || $report_type == 'district_wise_cleaning_report' || $report_type == 'grand_total_at_current_circle') {
									$report_circle = $circle_id;
								}
								$stock_crop = validate($_GET['stock_crop']);
								$activity_season = validate($_GET['activity_season']);


								require_once('report.php');
								if($report_type == 'cleaning_report_of_current_circle') {
									// require_once('reports/cleaning_report_of_current_circle.php');
								} else if($report_type == 'cleaning_report_of_other_circle') {
									// require_once('reports/cleaning_report_of_other_circle.php');
								} else if($report_type == 'variety_wise_stock_at_current_circle') {
									// require_once('reports/variety_wise_stock_at_current_circle.php');
								} else if($report_type == 'current_circle_to_other_district') {
									// require_once('reports/current_circle_to_other_district.php');
								} else if($report_type == 'current_circle_to_within_district') {
									// require_once('reports/current_circle_to_within_district.php');
								} else if($report_type == 'variety_wise_and_class_wise_stock_at_current_circle') {
									// require_once('reports/variety_wise_and_class_wise_stock_at_current_circle.php');
								} else if($report_type == 'variety_wise_cleaning_report_at_current_circle') {
									// require_once('reports/variety_wise_cleaning_report_at_current_circle.php');
								} else if($report_type == 'district_wise_cleaning_report') {
									// require_once('reports/district_wise_cleaning_report.php');
								} else if($report_type == 'grand_total_at_current_circle') {
									// require_once('reports/grand_total_at_current_circle.php');
								}

							}

							?>

						</div>
					</div>

					<div class="row mt-5 justify-content-end" id="report_signature">
						<div class="col-3">
							<h5 class="text-center"><b>Store Keeper</b></h5>
							<h5 class="text-center"><b><?php echo circle_name($circle_id); ?></b></h5>
						</div>
					</div>

				</div><!-- /.container-fluid -->
			</section>
			<!-- /.content -->

		</div>
		<!-- /.content-wrapper -->

		<!-- Control Sidebar -->
		<?php include "footer.php"; ?>

	</div>

	<?php include "javascript.php"; ?>
	<script type="text/javascript">
		$(document).ready(function(){

			$('#report_print_btn').on('click', function(){
		        window.print();
		    });

			$('#report_type').on('change', function(){
				var report_type = $(this).val();
				if(report_type == 'cleaning_report_of_other_circle') {
					$("#report_type_container").removeClass('col-md-4').addClass('col-md-3');
					$("#stock_crop_container").removeClass('col-md-4').addClass('col-md-3');
					$("#activity_season_container").removeClass('col-md-4').addClass('col-md-3');
					$("#report_circle_container").addClass('col-md-3 mb-3').html(
						'<label>circle:</label>' + 
						'<select class="form-control" id="report_circle" name="report_circle">' + 
							'<option value="">Select circle</option>' + 
							<?php

							$report_circle_query = mysqli_query($conn, "SELECT * FROM circles WHERE active_status='1' && delete_status='0' ORDER BY name ASC");
							if(mysqli_num_rows($report_circle_query) > 0) {
								while($report_circle_result = mysqli_fetch_assoc($report_circle_query)) {
									echo "'<option value=\"".$report_circle_result['id']."\">".$report_circle_result['name']."</option>' + ";
								}
							}

							?>
						'</select>'
					);
				} else {
					$("#report_type_container").removeClass('col-md-3').addClass('col-md-4');
					$("#report_circle_container").removeClass('col-md-3 mb-3').html('');
					$("#stock_crop_container").removeClass('col-md-3').addClass('col-md-4');
					$("#activity_season_container").removeClass('col-md-3').addClass('col-md-4');
				}
			});

			$('#stock_crop').on('change', function(){
				var stock_crop = $(this).val();
				if(stock_crop != '' && stock_crop != 0) {
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action: 'display_report_activity_season', stock_crop:stock_crop },
						success: function(result) {
							$('#activity_season').html(result);
						}
					});
				}
			});

		});
	</script>
</body>
</html>