<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Stock Fumigation New';
	
	include "head.php";

	if(is_super_admin() || is_admin()) {
		header('Location: stock-fumigation.php');
	}

	if(isset($_GET['id']) && !empty( $_GET['id'] ) && $_GET['id'] != 0) {
		$id = validate($_GET['id']);

		$query = mysqli_query($conn, "SELECT * FROM stock_transactions WHERE id='$id' && delete_status='0'");

		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
		} else {
			header("Location: stock-fumigation.php");
		}
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
								<li class="breadcrumb-item"><a href="stock-fumigation.php">Fumigation</a></li>
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

					<?php

					$stock_id = $processing_quantity = $available_stock_qty = '';

					if(isset($_POST['stock_fumigation_form_btn'])) {

						$processing_stock_id = validate($_POST['stock_id']);
						$processing_quantity = validate($_POST['processing_quantity']);
						$available_stock_qty = validate($_POST['available_stock_qty']);


						if( !empty( $processing_stock_id ) && !empty( $processing_quantity ) ) {

							$available_stock_qty = stock_transaction_detail($processing_stock_id, 'stock_qty');

							if($processing_quantity > $available_stock_qty) {
								echo "<div class='alert alert-danger'>Processing Stock must be less than or equal to available stock</div>";
							} else {

								$parent_stock_id = stock_transaction_detail($processing_stock_id, 'stock_id');
								$stock_status = stock_transaction_detail($processing_stock_id, 'stock_status');

								if($processing_quantity < $available_stock_qty) {
									$new_stock_qty = $available_stock_qty - $processing_quantity;
									$update_stock_query = mysqli_query($conn, "UPDATE stock_transactions SET stock_qty='$new_stock_qty' WHERE id='$processing_stock_id'");

#================================================================================================================
# if stock is already exist in this status then merge it other wise create new stock
									$check_exist_stock_query = mysqli_query($conn, "SELECT * FROM stock_transactions WHERE stock_id='$parent_stock_id' && stock_status='$stock_status' && active_status='3' && delete_status='0'");
									if(mysqli_num_rows($check_exist_stock_query) > 0) {
										$check_exist_stock_result = mysqli_fetch_assoc($check_exist_stock_query);
										$exist_stock_id = $check_exist_stock_result['id'];
										$new_exist_stock_qty = $check_exist_stock_result['stock_qty'] + $processing_quantity;
										$fumigation_stock_query = mysqli_query($conn, "UPDATE stock_transactions SET stock_qty='$new_exist_stock_qty' WHERE id='$exist_stock_id'");
										$new_stock_id = $exist_stock_id;
									} else {
										$fumigation_stock_query = mysqli_query($conn, "INSERT INTO stock_transactions(user_id, circle_id, stock_id, stock_qty, stock_status, active_status, time_created) VALUES('$user_id', '$circle_id', '$parent_stock_id', '$processing_quantity', '$stock_status', '3', '$time_created')");
										$new_stock_id = mysqli_insert_id($conn);
									}
#================================================================================================================


								} else {

#================================================================================================================
# if stock is already exist in this status then merge it other wise create new stock

									$check_exist_stock_query = mysqli_query($conn, "SELECT * FROM stock_transactions WHERE stock_id='$parent_stock_id' && stock_status='$stock_status' && active_status='3' && delete_status='0'");
									if(mysqli_num_rows($check_exist_stock_query) > 0) {
										$check_exist_stock_result = mysqli_fetch_assoc($check_exist_stock_query);
										$exist_stock_id = $check_exist_stock_result['id'];
										$new_exist_stock_qty = $check_exist_stock_result['stock_qty'] + $processing_quantity;
										$fumigation_stock_query = mysqli_query($conn, "UPDATE stock_transactions SET stock_qty='$new_exist_stock_qty' WHERE id='$exist_stock_id'");
										$new_stock_id = $exist_stock_id;
										$del_processing_stock = mysqli_query($conn, "UPDATE stock_transactions SET stock_qty='0' && delete_status='2' WHERE id='$processing_stock_id'");
									} else {
										$fumigation_stock_query = mysqli_query($conn, "UPDATE stock_transactions SET active_status='3' WHERE id='$processing_stock_id'");
										$new_stock_id = $processing_stock_id;
									}

#================================================================================================================


								}



								$query = mysqli_query($conn, "INSERT INTO stock_fumigation(user_id, circle_id, parent_id, stock_id, processing_qty, time_created) VALUES('$user_id', '$circle_id', '$parent_stock_id', '$new_stock_id', '$processing_quantity', '$time_created')");
								$stock_fumigation_id = mysqli_insert_id($conn);
								$stock_fumigation_meta_query = mysqli_query($conn, "INSERT INTO stock_fumigation_meta(stock_fumigation_id, meta_key, meta_value) VALUES
									('$stock_fumigation_id', 'total_stock_qty', '$available_stock_qty'), 
									('$stock_fumigation_id', 'processing_qty', '$processing_quantity') 
								");


								if($query && $stock_fumigation_meta_query && $fumigation_stock_query) {
									echo "<div class='alert alert-success'>Stock is Successfully Fumigated</div>";
									echo "<meta http-equiv='refresh' content='1'>";
								} else {
									echo "<div class='alert alert-danger'>Please Try Again</div>";
								}


							}

						} else {
							echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
						}

					}

					?>
					
					<form class="form" method="post" id="stock_fumigation_form" enctype="multipart/form-data">

						<h5>Fumigation Detail</h5>

						<?php

						if(isset($id) && $id != '' && $id != 0) {
							echo "<input type='hidden' value='".$id."' name='stock_id'>";
							echo "<input type='hidden' value='".$result['stock_qty']."' name='available_stock_qty' id='available_stock_qty'>";
						} else {
						?>

						<div class="row">
							<div class="col-md-12 mb-3">
								<label>Select Stock:</label>
								<select class="form-control" id="stock_id" name="stock_id">
									<option value="">Select Stock</option>
									<?php

									$supply_stock_query = mysqli_query($conn, "SELECT st.id, s.circle_id, s.crop, s.lot_number, s.variety, s.class, st.stock_qty, st.stock_status, st.active_status FROM stocks AS s INNER JOIN stock_transactions AS st ON s.id=st.stock_id WHERE st.stock_qty!='0' && st.active_status='1' && s.delete_status='0' ORDER BY st.time_created DESC");

									if(mysqli_num_rows($supply_stock_query) > 0) {
										while($supply_stock_result = mysqli_fetch_assoc($supply_stock_query)) {
											if($supply_stock_result['id'] == $stock_id) {
												$supply_stock_selected = 'selected';
											} else {
												$supply_stock_selected = '';
											}
											if($supply_stock_result['active_status'] == 3 && $supply_stock_result['stock_status'] == 0) {
												$supply_stock_class = 'text-primary';
												$supply_stock_status = 'Uncleaned, Under Fumigation';
											} else if($supply_stock_result['active_status'] == 3 && $supply_stock_result['stock_status'] == 2) {
												$supply_stock_class = 'text-success';
												$supply_stock_status = 'Cleaned, Under Fumigation';
											} else if($supply_stock_result['stock_status'] == 0) {
												$supply_stock_class = 'text-danger';
												$supply_stock_status = 'Uncleaned';
											} else if($supply_stock_result['stock_status'] == 2) {
												$supply_stock_class = 'text-success';
												$supply_stock_status = 'Cleaned';
											}
										?>
											<option <?= $supply_stock_selected; ?> class="<?= $supply_stock_class; ?>" data-qty="<?= $supply_stock_result['stock_qty']; ?>" value="<?= $supply_stock_result['id']; ?>"><?=

												$supply_stock_result['lot_number'].' - '.stock_crop($supply_stock_result['crop']).' - '.stock_variety($supply_stock_result['variety']).' - '.stock_class($supply_stock_result['class']).' - '.$supply_stock_result['stock_qty'].' (KGs) - '.$supply_stock_status;

											?></option>
										<?php
										}
									}

									?>
								</select>
							</div>
						</div>

						<input type="hidden" value="0" id="available_stock_qty" name="available_stock_qty">

						<?php
						}

						?>

						<div class="row">
							<div class="col-md-12 mb-3">
								<label>Processing Quantity:</label>
								<input type="text" pattern="[0-9]+" value="<?php if(isset($result['stock_qty']) && !empty( $processing_quantity ) ) { echo $processing_quantity; } else if(isset($result['stock_qty']) && empty( $processing_quantity ) ) { echo $result['stock_qty']; } else { $processing_quantity; } ?>" class="form-control" placeholder="Enter Process Quantity" id="processing_quantity" name="processing_quantity">
								<div id="processing_quantity_msg"></div>
							</div>
						</div>

						<button type="submit" class="btn btn-primary" id="stock_fumigation_form_btn" name="stock_fumigation_form_btn">Submit</button>

					</form>

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

			$('#processing_quantity').on('focus', function(){
				$('#processing_quantity').removeClass('is-invalid');
				$('#processing_quantity_msg').removeClass('invalid-feedback').text('');
			});

			$('#stock_fumigation_form').on('submit', function(){
				var bool = 0;

				if($('#processing_quantity').val() == '') {
					$('#processing_quantity').addClass('is-invalid');
					$('#processing_quantity_msg').addClass('invalid-feedback').text('Processing Quantity Required');
					bool = 1;
				} else {
					<?php
					if(isset($id)) {
					?>
					if(parseInt($('#processing_quantity').val()) > <?= $result['stock_qty']; ?>) {
						$('#processing_quantity').addClass('is-invalid');
						$('#processing_quantity_msg').addClass('invalid-feedback').text('Processing Quantity must be less than or equal to actual stock');
						bool = 1;
					} else {
						$('#processing_quantity').removeClass('is-invalid');
						$('#processing_quantity_msg').removeClass('invalid-feedback').text('');
						bool = 0;
					}
					<?php
					} else {
					?>
					if(parseInt($('#processing_quantity').val()) > parseInt($('#available_stock_qty').val())) {
						$('#processing_quantity').addClass('is-invalid');
						$('#processing_quantity_msg').addClass('invalid-feedback').text('Processing Quantity must be less than or equal to actual stock');
						bool = 1;
					} else {
						$('#processing_quantity').removeClass('is-invalid');
						$('#processing_quantity_msg').removeClass('invalid-feedback').text('');
						bool = 0;
					}
					<?php
					}
					?>
				}

				if(bool == 0) {
					return true;
				} else {
					return false;
				}
			});

			<?php
			if(!isset($id)) {
			?>
			if($('#stock_id option:selected').val() != '') {
				$('#available_stock_qty').val($('#stock_id option:selected').data('qty'));
			} else {
				$('#available_stock_qty').val('0');
			}
			<?php
			}
			?>

			$('#stock_id').on('change', function(){
				var stock_id = $('#stock_id').val();
				var stock_qty = $('#stock_id option:selected').data('qty');

				if(stock_id != '') {
					$('#available_stock_qty').val(stock_qty);
				} else {
					$('#available_stock_qty').val('0');
				}
			});

		});
	</script>
</body>
</html>