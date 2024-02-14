<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Stock Detail';

	include "head.php";

	if(isset($_GET['id']) && !empty( $_GET['id'] ) && $_GET['id'] != 0) {

		$id = validate($_GET['id']);

		$query = mysqli_query($conn, "SELECT * FROM stocks WHERE id='$id' && active_status!='0' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
		} else {
			header('Location: stock.php');
		}

	} else {
		header('Location: stock.php');
	}

	?>
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
							<a href="stock-new.php" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i></a></li>
								<li class="breadcrumb-item"><a href="stock.php">Stocks</a></li>
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
					
					<div class="card mb-3">
						<div class="card-header">
							<h5 class="card-heading">Stock Detail</h5>
						</div>
						<div class="card-body">
							
							<div class="table-responsive">
								<table class="table table-striped table-hover table-sm">
									<tr>
										<td><b>Lot Number:</b></td>
										<td><?= $result['lot_number']; ?></td>
										<td><b>Current Circle:</b></td>
										<td><?= circle_name($result['circle_id']); ?></td>
									</tr>
									<tr>
										<td><b>Stock Crop:</b></td>
										<td><?= stock_crop($result['crop']); ?></td>
										<td><b>Variety:</b></td>
										<td><?= stock_variety($result['variety']); ?></td>
									</tr>
									<tr>
										<td><b>Class:</b></td>
										<td><?= stock_class($result['class']); ?></td>
										<td><b>Quantity:</b></td>
										<td><?= $result['stock_qty'].' (KGs)'; ?></td>
									</tr>
								</table>
							</div>

						</div>
					</div>

					<div class="card mb-3">
						<div class="card-header">
							<h5 class="card-heading">Source Detail</h5>
						</div>
						<div class="card-body">
							
							<div class="table-responsive">
								<table class="table table-striped table-hover table-sm">
									<tr>
										<td><b>Season:</b></td>
										<td colspan="3"><?= activity_season_title($result['activity_season']); ?></td>
									</tr>
									<tr>
										<td><b>Receiving Source:</b></td>
										<td><?php if($result['stock_source'] == 'from_farmer') echo 'Farmer'; else if($result['stock_source'] == 'other_province') echo 'Other Province'; else if($result['stock_source'] == 'other_circle') echo 'Other circle'; ?></td>
										<td><b>Supplier Info:</b></td>
										<td><?php if($result['stock_source'] == 'from_farmer' || $result['stock_source'] == 'other_province') echo $result['supplier_info']; else if($result['stock_source'] == 'other_circle') echo circle_name($result['supplier_info']); ?></td>
									</tr>
									<?php
									if($result['stock_source'] == 'from_farmer') {
									?>
									<tr>
										<td><b>Farmer Name/Address:</b></td>
										<td><?= farmer_info($result['supplier_info'], 'farmer_name').'<br>'.farmer_info($result['supplier_info'], 'farmer_address'); ?></td>
										<td><b>Farmer Mobile:</b></td>
										<td><?= farmer_info($result['supplier_info'], 'farmer_mobile_number'); ?></td>
									</tr>
									<?php
									}
									?>
								</table>
							</div>

						</div>
					</div>

					<div class="card mb-3">
						<div class="card-header">
							<h5 class="card-heading">Cleaning Detail</h5>
						</div>
						<div class="card-body">
							
							<div class="table-responsive">
								<table class="table table-striped table-hover table-sm">
									<thead>
										<tr>
											<th>circle</th>
											<th>Qty</th>
											<th>Grade 1</th>
											<th>Small Grains</th>
											<th>Gundi</th>
											<th>Broken</th>
											<th>Straw</th>
											<th>Dust</th>
											<th>Other</th>
											<th>%</th>
											<th>Date</th>
										</tr>
									</thead>
									<tbody>
										<?php

										$stock_cleaning_query = mysqli_query($conn, "SELECT * FROM stock_cleaning WHERE (stock_id='$id' || parent_id='$id') && delete_status='0'");

										if(mysqli_num_rows($stock_cleaning_query) > 0) {
											while($stock_cleaning_result = mysqli_fetch_assoc($stock_cleaning_query)) {
												?>
												<tr>
													<td><?= circle_name($stock_cleaning_result['circle_id']); ?></td>
													<td><?= $stock_cleaning_result['processing_qty'].' (KGs)'; ?></td>
													<td><?= ( empty( stock_cleaning_meta($stock_cleaning_result['id'], 'grade_1') ) ? 0 : stock_cleaning_meta($stock_cleaning_result['id'], 'grade_1') ).' (KGs)'; ?></td>
													<td><?= ( empty( stock_cleaning_meta($stock_cleaning_result['id'], 'small_grains') ) ? 0 : stock_cleaning_meta($stock_cleaning_result['id'], 'small_grains') ).' (KGs)'; ?></td>
													<td><?= ( empty( stock_cleaning_meta($stock_cleaning_result['id'], 'gundi') ) ? 0 : stock_cleaning_meta($stock_cleaning_result['id'], 'gundi') ).' (KGs)'; ?></td>
													<td><?= ( empty( stock_cleaning_meta($stock_cleaning_result['id'], 'broken') ) ? 0 : stock_cleaning_meta($stock_cleaning_result['id'], 'broken') ).' (KGs)' ?></td>
													<td><?= ( empty( stock_cleaning_meta($stock_cleaning_result['id'], 'straw') ) ? 0 : stock_cleaning_meta($stock_cleaning_result['id'], 'straw') ).' (KGs)'; ?></td>
													<td><?= ( empty( stock_cleaning_meta($stock_cleaning_result['id'], 'dust') ) ? 0 : stock_cleaning_meta($stock_cleaning_result['id'], 'dust') ).' (KGs)'; ?></td>
													<td><?= ( empty( stock_cleaning_meta($stock_cleaning_result['id'], 'other') ) ? 0 : stock_cleaning_meta($stock_cleaning_result['id'], 'other') ).' (KGs)'; ?></td>
													<td><?= ceil( (stock_cleaning_meta($stock_cleaning_result['id'], 'grade_1') * 100) / stock_cleaning_meta($stock_cleaning_result['id'], 'processing_qty') ).'%'; ?></td>
													<td><?= date('d-m-Y', $stock_cleaning_result['time_created']); ?></td>
												</tr>
												<?php
											}
										} else {
											echo "<tr><td colspan='12' class='text-center'>No Record Found</td></tr>";
										}

										?>
									</tbody>
								</table>
							</div>

						</div>
					</div>

					<div class="card mb-3">
						<div class="card-header">
							<h5 class="card-heading">Fumigation Detail</h5>
						</div>
						<div class="card-body">
							
							<div class="table-responsive">
								<table class="table table-striped table-hover table-sm">
									<thead>
										<tr>
											<th>circle</th>
											<th>Processing Qty</th>
											<th>Date</th>
										</tr>
									</thead>
									<tbody>
										<?php

										$stock_fumigation_query = mysqli_query($conn, "SELECT * FROM stock_fumigation WHERE (stock_id='$id' || parent_id='$id') && delete_status='0'");

										if(mysqli_num_rows($stock_fumigation_query) > 0) {
											while($stock_fumigation_result = mysqli_fetch_assoc($stock_fumigation_query)) {
												?>
												<tr>
													<td><?= circle_name($stock_fumigation_result['circle_id']); ?></td>
													<td><?= $stock_fumigation_result['processing_qty'].' (KGs)'; ?></td>
													<td><?= date('d-m-Y', $stock_fumigation_result['time_created']); ?></td>
												</tr>
												<?php
											}
										} else {
											echo "<tr><td colspan='4' class='text-center'>No Record Found</td></tr>";
										}

										?>
									</tbody>
								</table>
							</div>

						</div>
					</div>

					<div class="card mb-3">
						<div class="card-header">
							<h5 class="card-heading">Supply Detail</h5>
						</div>
						<div class="card-body">
							
							<div class="table-responsive">
								<table class="table table-striped table-hover table-sm">
									<thead>
										<tr>
											<th>Circle</th>
											<th>Destination</th>
											<th>Qty</th>
											<th>Receiver</th>
											<th>Receiving Time</th>
											<th>Status</th>
											<th>Date</th>
										</tr>
									</thead>
									<tbody>
										<?php

										$stock_supply_query = mysqli_query($conn, "SELECT * FROM supply WHERE parent_id='$id' && delete_status='0'");

										if(mysqli_num_rows($stock_supply_query) > 0) {
											while($stock_supply_result = mysqli_fetch_assoc($stock_supply_query)) {
												?>
												<tr>
													<td><?= circle_name($stock_supply_result['circle_id']); ?></td>
													<td>
														<?php
														if($stock_supply_result['receive_source'] == 'to_farmer' || $stock_supply_result['receive_source'] == 'other_province') {
															echo $stock_supply_result['receiver_detail'];
														} else if($stock_supply_result['receive_source'] == 'other_circle') {
															echo circle_name($stock_supply_result['receiver_detail']);
														}
														?>
													</td>
													<td><?= $stock_supply_result['stock_qty'].' (KGs)'; ?></td>
													<td>
														<?php
														if($stock_supply_result['receive_source'] == 'to_farmer' || $stock_supply_result['receive_source'] == 'other_province') {
															echo $stock_supply_result['receiver_info'];
														} else if($stock_supply_result['receive_source'] == 'other_circle') {
															echo user_display_name($stock_supply_result['receiver_info']).' - '.user_meta($stock_supply_result['receiver_info'], 'phone_number');
														}
														?>
													</td>
													<td><?php if( !empty( $stock_supply_result['receiver_time_created'] ) ) echo date('d-m-Y h:i A', $stock_supply_result['receiver_time_created']); ?></td>
													<td>
														<?php

														if($stock_supply_result['receive_status'] == 0) {
															echo "<span class='badge badge-warning'>On Way</span>";
														} else if($stock_supply_result['receive_status'] == 1) {
															echo "<span class='badge badge-success'>Received</span>";
														} else if($stock_supply_result['receive_status'] == 2) {
															echo "<span class='badge-badge-secondary'>Rejected</span>";
														}

														?>
													</td>
													<td><?= date('d-m-Y', $stock_supply_result['time_created']); ?></td>
												</tr>
												<?php
											}
										} else {
											echo "<tr><td colspan='7' class='text-center'>No Record Found</td></tr>";
										}

										?>
									</tbody>
								</table>
							</div>

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
</body>
</html>