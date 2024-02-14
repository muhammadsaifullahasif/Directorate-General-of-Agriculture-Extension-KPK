<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Stock Fumigation Edit';
	
	include "head.php";

	if(is_super_admin() || is_admin() || is_storekeeper()) {
		header('Location: stocks.php');
	}

	if(isset($_GET['id']) && !empty( $_GET['id'] ) && $_GET['id'] != 0) {
		$id = validate($_GET['id']);

		$query = mysqli_query($conn, "SELECT * FROM stock_fumigation WHERE id='$id' && delete_status='0'");

		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
		} else {
			header("Location: stock-fumigation.php");
		}
	} else {
		header("Location: stock-fumigation.php");
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

					<?php

					$stock_id = $processing_quantity = $available_stock_qty = '';

					if(isset($_POST['stock_fumigation_form_btn'])) {
						$processing_stock_id = validate($_POST['stock_id']);
						$processing_quantity = validate($_POST['processing_quantity']);

						if( !empty( $processing_stock_id ) && !empty( $processing_quantity ) ) {

							$total_amount = $labour_cost + $miscellaneous_cost + $fumigation_cost;
							$available_stock_qty = stock_transaction_detail($processing_stock_id, 'stock_qty');

							if($processing_quantity > $available_stock_qty) {
								echo "<div class='alert alert-danger'>Processing Stock must be less than or equal to available stock</div>";
							} else {

								if($processing_quantity < $available_stock_qty) {
									
									$new_stock_qty = $available_stock_qty - $processing_quantity;

									if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stock_transactions WHERE circle_id='$circle_id' && stock_id='$parent_stock_id' && active_status='3'")) == 1)

									echo "UPDATE stock_transactions SET stock_qty='$new_stock_qty' WHERE id='$processing_stock_id'";

								} else {
									echo "<div class='alert alert-danger'>Stock must be less than or equal to available stock quantity</div>";
								}

							}


						} else {
							echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
						}
					}

					?>
					
					<form class="form" method="post" id="stock_fumigation_form" enctype="multipart/form-data">

						<h5>Fumigation Input</h5>

						<input type="hidden" value="<?= $result['stock_id']; ?>" name="stock_id">

						<div class="row">
							<div class="col-md-12 mb-3">
								<label>Processing Quantity:</label>
								<input type="text" pattern="[0-9]+" value="<?= $result['processing_qty']; ?>" class="form-control" placeholder="Enter Process Quantity" id="processing_quantity" name="processing_quantity">
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