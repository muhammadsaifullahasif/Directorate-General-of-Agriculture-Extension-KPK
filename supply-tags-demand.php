<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Supply Tags Demand';

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

							<div class="col-md-4 mb-3">
								<label>Crop:</label>
								<select class="form-control" id="stock_crop" name="stock_crop">
									<option value="">Select Crop</option>
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

							<div class="col-md-4 mb-3">
								<label>Variety:</label>
								<select class="form-control" id="stock_variety" name="stock_variety">
									<option value="">Select Variety</option>
									<?php

									if(isset($_GET['stock_crop']) && $_GET['stock_crop'] != '') {
										$stock_crop_id = validate($_GET['stock_crop']);
										$stock_variety_query = mysqli_query($conn, "SELECT * FROM stock_variety WHERE stock_crop_id='$stock_crop_id' && active_status='1' && delete_status='0'");
										if(mysqli_num_rows($stock_variety_query) > 0) {
											while($stock_variety_result = mysqli_fetch_assoc($stock_variety_query)) {
												if(isset($_GET['stock_variety']) && $_GET['stock_variety'] == $stock_variety_result['id']) {
													$stock_variety_selected = 'selected';
												} else {
													$stock_variety_selected = '';
												}
												echo "<option ".$stock_variety_selected." value='".$stock_variety_result['id']."'>".$stock_variety_result['variety']."</option>";
											}
										}
									}

									?>
								</select>
							</div>

							<div class="col-md-4 mb-3">
								<label>Class:</label>
								<select class="form-control" id="stock_class" name="stock_class">
									<option value="">Select Class</option>
									<?php

									$stock_class_query = mysqli_query($conn, "SELECT * FROM stock_class WHERE active_status='1' && delete_status='0'");
									if(mysqli_num_rows($stock_class_query) > 0) {
										while($stock_class_result = mysqli_fetch_assoc($stock_class_query)) {
											if(isset($_GET['stock_class']) && $_GET['stock_class'] == $stock_class_result['id']) {
												$stock_class_selected = 'selected';
											} else {
												$stock_class_selected = '';
											}
											echo "<option ".$stock_class_selected." value='".$stock_class_result['id']."'>".$stock_class_result['class_name']."</option>";
										}
									}

									?>
								</select>
							</div>

						</div>

						<button class="btn btn-primary" type="submit" id="report_form_btn" name="report_form_btn">Generate Demand</button>

					</form>

					<div class="row">
						<div class="col-md-12">

							<?php

							if(isset($_GET['report']) && $_GET['report'] == 'generate') {

							?>
							<div class="table-responsive">
								<table class="table table-bordered table-sm mb-3">
									<thead>
										<tr>
											<th class='align-middle text-center'>#</th>
											<th class='align-middle text-center'>Lot Number</th>
											<th class='align-middle text-center'>Crop</th>
											<th class='align-middle text-center'>Variety</th>
											<th class='align-middle text-center'>Class</th>
											<th class='align-middle text-center'>Label Quantity</th>
										</tr>
									</thead>
									<tbody>
										<?php

										$total_lable_qty = 0;

										if(isset($_GET['report']) && $_GET['report'] == 'generate') {

											$stock_crop = validate($_GET['stock_crop']);
											$stock_variety = validate($_GET['stock_variety']);
											$stock_class = validate($_GET['stock_class']);

											$sql_query = "SELECT s.lot_number, s.crop, s.variety, st.class, SUM(st.stock_qty) AS stock_qty FROM stocks AS s INNER JOIN stock_transactions AS st ON s.id=st.stock_id WHERE st.supply_status='0' && st.stock_qty!='0' && st.stock_status='2'";
											if($stock_crop != '' && $stock_crop != 0) {
												$sql_query .= " && s.crop='$stock_crop' ";
											}
											if($stock_variety != '' && $stock_variety != 0) {
												$sql_query .= " && s.variety='$stock_variety' ";
											}
											if($stock_class != '' && $stock_class != 0) {
												$sql_query .= " && st.class='$stock_class' ";
											}
											$sql_query .= " GROUP BY s.lot_number, s.variety, st.class";
											$query = mysqli_query($conn, $sql_query);

											// echo $sql_query."<br>";
											// echo mysqli_num_rows($query);
											if(mysqli_num_rows($query) > 0) {
												$i = 1;
												while($result = mysqli_fetch_assoc($query)) {
													echo "<tr>";
													echo "<td class='align-middle text-center'>".$i++."</td>";
													echo "<td class='align-middle text-center'>".$result['lot_number']."</td>";
													echo "<td class='align-middle text-center'>".stock_crop($result['crop'])."</td>";
													echo "<td class='align-middle text-center'>".stock_variety($result['variety'])."</td>";
													echo "<td class='align-middle text-center'>".stock_class($result['class'])."</td>";
													echo "<td class='align-middle text-center'>".ceil($result['stock_qty'] / 50)."</td>";
													echo "</tr>";
													$total_lable_qty += ceil($result['stock_qty'] / 50);
												}
												echo "<tr>";
												echo "<td colspan='5'><b>Total Label Quantity</b></td>";
												echo "<td class='align-middle text-center'>".$total_lable_qty."</td>";
												echo "</tr>";
											} else {
												echo "<tr><td colspan='6' class='align-middle text-center'>No Record Found</td></tr>";
											}

										}

										?>
									</tbody>
								</table>
							</div>

							<button class="btn btn-primary mb-3" id="report_print_btn">Print</button>
							<?php

							}

							?>

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

			$('#stock_crop').on('change', function(){
				var stock_crop = $(this).val();
				if(stock_crop != '' && stock_crop != 0) {
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action: 'display_stock_variety', stock_crop:stock_crop },
						success: function(result) {
							$('#stock_variety').html(result);
						}
					});
				}
			});

		});
	</script>
</body>
</html>