<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Dashboard';

	include "head.php";

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
							<h1 class="m-0">Welcome <?php echo user_display_name($user_id).' ('.user_role(user_detail($user_id, 'role'), user_detail($user_id, 'type')).')'; ?></h1>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<!-- <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li> -->
								<li class="breadcrumb-item active"><i class="fas fa-home"></i></li>
							</ol>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<?php
					if(is_super_admin() || is_admin()) {

					?>

					<!-- Small boxes (Stat box) -->
					<div class="row">
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-info">
								<div class="inner">
									<h3>
										<?php

										$total_user_sql = "SELECT * FROM users WHERE active_status='1' && delete_status='0'";
										if(is_admin()) {
											$total_user_sql .= " && district='$user_district' ";
										}
										echo mysqli_num_rows(mysqli_query($conn, $total_user_sql));

										?>
									</h3>

									<p>Total Users</p>
								</div>
								<div class="icon">
									<i class="ion ion-bag"></i>
								</div>
								<a href="users.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-success">
								<div class="inner">
									<h3>
										<?php

										$total_circle_sql = "SELECT * FROM circles WHERE active_status='1' && delete_status='0'";

										if(is_admin()) {
											$total_circle_sql .= " && district='$user_district' ";
										}

										echo mysqli_num_rows(mysqli_query($conn, $total_circle_sql));

										?>
									</h3>

									<p>Total circles</p>
								</div>
								<div class="icon">
									<i class="ion ion-stats-bars"></i>
								</div>
								<a href="circles.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-warning">
								<div class="inner">
									<h3>
										<?php

										$total_stock_sql = "SELECT SUM(st.stock_qty) AS stock_qty";
										$total_stock_sql .= " FROM stocks AS s";
										if(is_admin()) {
											$total_stock_sql .= " INNER JOIN circles AS e ON s.circle_id=e.id ";
										}
										$total_stock_sql .= " INNER JOIN stock_transactions AS st ON s.id=st.stock_id ";
										if(is_admin()) {
											$total_stock_sql .= " && st.circle_id=e.id && e.district='$user_district' ";
										}
										$total_stock_sql .= "  WHERE s.active_status='1' && s.delete_status='0' && st.active_status!='2' && st.delete_status='0' ";

										$total_stock_query = mysqli_query($conn, $total_stock_sql);
										if(mysqli_num_rows($total_stock_query) > 0) {
											$total_stock_result = mysqli_fetch_assoc($total_stock_query);
											echo $total_stock_result['stock_qty'];
										} else {
											echo 0;
										}

										?>
										<small> (KGs)</small>
									</h3>

									<p>Available Stock</p>
								</div>
								<div class="icon">
									<i class="ion ion-person-add"></i>
								</div>
								<a href="stocks.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-danger">
								<div class="inner">
									<h3>
										<?php

										echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM configurations WHERE type='district' && active_status='1' && delete_status='0'"));

										?>
									</h3>

									<p>Total Districts</p>
								</div>
								<div class="icon">
									<i class="ion ion-pie-graph"></i>
								</div>
								<a href="districts.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<!-- ./col -->
					</div>
					<!-- /.row -->

					<?php
					}
					?>

					<?php
					if(is_manager() || is_storekeeper()) {
					?>
					<!-- Small boxes (Stat box) -->
					<div class="row">
						<div class="col-lg-4 col-6">
							<!-- small box -->
							<div class="small-box bg-info">
								<div class="inner">
									<h3>
										<?php

										$available_stock_query = mysqli_query($conn, "SELECT SUM(st.stock_qty) AS stock_qty FROM stocks AS s INNER JOIN stock_transactions AS st ON s.id=st.stock_id WHERE ( (s.stock_source='from_farmer' || s.stock_source='others') && s.stock_status='1' ) || (s.stock_source <> 'from_farmer' && s.stock_source <> 'others') && s.active_status='1' && s.delete_status='0' && st.active_status!='0' && st.delete_status='0' && st.circle_id='$circle_id'");
										if(mysqli_num_rows($available_stock_query) > 0) {
											$available_stock_result = mysqli_fetch_assoc($available_stock_query);
											echo $available_stock_result['stock_qty'];
										} else {
											echo 0;
										}

										?>
										<small> (KGs)</small>
									</h3>

									<p>Available Stock</p>
								</div>
								<div class="icon">
									<i class="ion ion-bag"></i>
								</div>
								<a href="stock-available.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-4 col-6">
							<!-- small box -->
							<div class="small-box bg-success">
								<div class="inner">
									<h3>
										<?php

										$clean_stock_query = mysqli_query($conn, "SELECT SUM(st.stock_qty) AS stock_qty FROM stocks AS s INNER JOIN stock_transactions AS st ON s.id=st.stock_id WHERE s.active_status='1' && s.delete_status='0' && st.active_status!='0' && st.delete_status='0' && st.stock_status='2' && s.circle_id='$circle_id'");
										if(mysqli_num_rows($clean_stock_query) > 0) {
											$clean_stock_result = mysqli_fetch_assoc($clean_stock_query);
											if($clean_stock_result['stock_qty'] == '') {
												echo 0;
											} else {
												echo $clean_stock_result['stock_qty'];
											}
										} else {
											echo 0;
										}

										?>
										<small> (KGs)</small>
									</h3>

									<p>Clean Stock</p>
								</div>
								<div class="icon">
									<i class="ion ion-stats-bars"></i>
								</div>
								<a href="stock-cleaning.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-4 col-6">
							<!-- small box -->
							<div class="small-box bg-warning">
								<div class="inner">
									<h3>
										<?php

										$fumigate_stock_query = mysqli_query($conn, "SELECT SUM(st.stock_qty) AS stock_qty FROM stocks AS s INNER JOIN stock_transactions AS st ON s.id=st.stock_id WHERE s.active_status='1' && s.delete_status='0' && st.active_status='3' && st.delete_status='0' && s.circle_id='$circle_id'");
										if(mysqli_num_rows($fumigate_stock_query) > 0) {
											$fumigate_stock_result = mysqli_fetch_assoc($fumigate_stock_query);
											if($fumigate_stock_result['stock_qty'] == '') {
												echo 0;
											} else {
												echo $fumigate_stock_result['stock_qty'];
											}
										} else {
											echo 0;
										}

										?>
										<small> (KGs)</small>
									</h3>

									<p>Under Fumigation</p>
								</div>
								<div class="icon">
									<i class="ion ion-person-add"></i>
								</div>
								<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<!-- ./col -->
						<!-- <div class="col-lg-3 col-6">
							<div class="small-box bg-danger">
								<div class="inner">
									<h3><small>Rs: </small><?= finance_amount($circle_id); ?></h3>

									<p>Available Budget</p>
								</div>
								<div class="icon">
									<i class="ion ion-pie-graph"></i>
								</div>
								<a href="#" class="small-box-footer"><?= circle_name($circle_id); ?></a>
							</div>
						</div> -->
						<!-- ./col -->
					</div>
					<!-- /.row -->
					<?php
					}

					?>

				</div><!-- /.container-fluid -->
			</section>
			<!-- /.content -->

		</div>
		<!-- /.content-wrapper -->

		<!-- Control Sidebar -->
		<?php include "footer.php"; ?>

	</div>

	<?php include "javascript.php"; ?>
	<!-- OPTIONAL SCRIPTS -->
	<script src="plugins/chart.js/Chart.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){

			$(function () {
				'use strict'

				var ticksStyle = {
					fontColor: '#495057',
					fontStyle: 'bold'
				}

				var mode = 'index'
				var intersect = true

				var $salesChart = $('#sales-chart')
				// eslint-disable-next-line no-unused-vars
				var salesChart = new Chart($salesChart, {
					type: 'bar',
					data: {
						labels: ['JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
						datasets: [{
							backgroundColor: '#007bff',
							borderColor: '#007bff',
							data: [1000, 2000, 3000, 2500, 2700, 2500, 3000]
						},
						{
							backgroundColor: '#ced4da',
							borderColor: '#ced4da',
							data: [700, 1700, 2700, 2000, 1800, 1500, 2000]
						}]
					},
					options: {
						maintainAspectRatio: false,
						tooltips: {
							mode: mode,
							intersect: intersect
						},
						hover: {
							mode: mode,
							intersect: intersect
						},
						legend: {
							display: false
						},
						scales: {
							yAxes: [{
							// display: false,
								gridLines: {
									display: true,
									lineWidth: '4px',
									color: 'rgba(0, 0, 0, .2)',
									zeroLineColor: 'transparent'
								},
								ticks: $.extend({
									beginAtZero: true,

									// Include a dollar sign in the ticks
									callback: function (value) {
										if (value >= 1000) {
											value /= 1000
											value += 'k'
										}

										return '$' + value
									}
								}, ticksStyle)
							}],
							xAxes: [{
								display: true,
								gridLines: {
									display: false
								},
								ticks: ticksStyle
							}]
						}
					}
				});

			});

		});
	</script>
</body>
</html>