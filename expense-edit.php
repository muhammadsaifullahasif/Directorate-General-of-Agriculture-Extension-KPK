<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Expense Edit';

	include "head.php";

	if(is_storekeeper()) {
		header('Location: index.php');
	}

	if(isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] != 0) {
		$id = validate($_GET['id']);

		$query = mysqli_query($conn, "SELECT * FROM transactions WHERE id='$id' && active_status!='0' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
		} else {
			header('Location: account-book.php');
		}
	} else {
		header('Location: account-book.php');
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
							<a href="expense-new.php" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add Expense</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i></a></li>
								<li class="breadcrumb-item"><a href="account-book.php">Account Book</a></li>
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

					if(isset($_POST['expense_edit_form_btn'])) {

						// begin transaction
						mysqli_begin_transaction($conn);

						$expense_circle = $result['circle_id'];
						$expense_type = validate($_POST['expense_type']);
						$expense_flow = validate($_POST['expense_flow']);
						if(isset($_POST['labour_cost'])) {
							$labour_cost = validate($_POST['labour_cost']);
						} else {
							$labour_cost = 0;
						}
						if(isset($_POST['packing_bag_cost'])) {
							$packing_bag_cost = validate($_POST['packing_bag_cost']);
						} else {
							$packing_bag_cost = 0;
						}
						if(isset($_POST['miscellaneous_cost'])) {
							$miscellaneous_cost = validate($_POST['miscellaneous_cost']);
						} else {
							$miscellaneous_cost = 0;
						}
						if(isset($_POST['fumigation_cost'])) {
							$fumigation_cost = validate($_POST['fumigation_cost']);
						} else {
							$fumigation_cost = 0;
						}

						if( $expense_type != '' && $expense_flow != '' ) {

							$finance_id = finance_id($result['circle_id']);
							$finance_amount = finance_amount($result['circle_id']);
							$total_cost = $labour_cost + $packing_bag_cost + $fumigation_cost + $miscellaneous_cost;
							$old_total_cost = ( transaction_meta($id, 'labour_cost') == '' ? 0 : transaction_meta($id, 'labour_cost') ) + ( transaction_meta($id, 'packing_bag_cost') == '' ? 0 : transaction_meta($id, 'packing_bag_cost') ) + ( transaction_meta($id, 'fumigation_cost') == '' ? 0 : transaction_meta($id, 'fumigation_cost') ) + ( transaction_meta($id, 'miscellaneous_cost') == '' ? 0 : transaction_meta($id, 'miscellaneous_cost') );

							$transaction_query = mysqli_query($conn, "UPDATE transactions SET amount='$total_cost', type='$expense_type', trans_flow='$expense_flow' WHERE id='$id'");

							$transaction_meta_query = mysqli_query($conn, "UPDATE transaction_meta SET meta_value= CASE
									WHEN meta_key='labour_cost' THEN '$labour_cost'
									WHEN meta_key='packing_bag_cost' THEN '$packing_bag_cost'
									WHEN meta_key='fumigation_cost' THEN '$fumigation_cost'
									WHEN meta_key='miscellaneous_cost' THEN '$miscellaneous_cost'
									ELSE `meta_value`
									END
								WHERE transaction_id='$id'");

							if($result['type'] == 1 && $expense_type != 1) {
								$meta_query = mysqli_query($conn, "INSERT INTO transaction_meta(transaction_id, meta_key, meta_value) VALUES('$id', 'packing_bag_cost', '$packing_bag_cost')");
								$delete_query = mysqli_query($conn, "DELETE FROM transaction_meta WHERE transaction_id='$id' && meta_key='fumigation_cost'");
							} else if($result['type'] != 1 && $expense_type == 1) {
								$meta_query = mysqli_query($conn, "INSERT INTO transaction_meta(transaction_id, meta_key, meta_value) VALUES('$id', 'fumigation_cost', '$fumigation_cost')");
								$delete_query = mysqli_query($conn, "DELETE FROM transaction_meta WHERE transaction_id='$id' && meta_key='packing_bag_cost'");
							}

							if($result['trans_flow'] == 0) {
								$finance_amount += $old_total_cost;
							} else if($result['trans_flow'] == 1) {
								$finance_amount -= $old_total_cost;
							}

							if($expense_flow == 0) {
								$finance_amount -= $total_cost;
							} else if($expense_flow == 1) {
								$finance_amount += $total_cost;
							}
							$update_finance = mysqli_query($conn, "UPDATE finance SET amount='$finance_amount' WHERE id='$finance_id'");

							if($transaction_query && $transaction_meta_query && $update_finance) {
								// commit transaction
								mysqli_commit($conn);
								echo "<div class='alert alert-success'>Expense Successfully Updated</div>";
								echo "<meta http-equiv='refresh' content='1'>";
							} else {
								// rollback transaction on error
								mysqli_rollback($conn);
								echo "<div class='alert alert-danger'>Please Try Again</div>";
							}

						} else {
							// rollback transaction on error
							mysqli_rollback($conn);
							echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
						}

					}

					?>
					
					<form class="form" method="post" enctype="multipart/form-data" id="expense_edit_form">

						<?php

						if(is_super_admin() || is_admin()) {
						?>
						<div class="row">
							<div class="col-md-12 mb-3">
								<label>circle:</label>
								<select class="form-control" disabled name="expense_circle" id="expense_circle">
									<option value="">Select AO Circle</option>
									<?php 

									if(is_admin()) {
										echo select_circle($user_city, $result['circle_id']);
									} else {
										echo select_circle('', $result['circle_id']);
									}

									?>
								</select>
							</div>
						</div>
						<?php
						}

						?>

						<div class="row">
							<div class="col-md-6 mb-3">
								<label>Expense Flow:</label>
								<select class="form-control" name="expense_flow" id="expense_flow">
									<option value="">Select Expense Type</option>
									<option value="0" <?php if($result['trans_flow'] == 0) { echo 'selected'; } ?>>Debit</option>
									<option value="1" <?php if($result['trans_flow'] == 1) { echo 'selected'; } ?>>Credit</option>
								</select>
							</div>
							
							<div class="col-md-6 mb-3">
								<label>Expense Type:</label>
								<select class="form-control" name="expense_type" id="expense_type">
									<option value="">Select Expense Type</option>
									<option value="0" <?php if($result['type'] == 0) { echo 'selected'; } ?>>Procure Stock</option>
									<option value="1" <?php if($result['type'] == 1) { echo 'selected'; } ?>>Fumigate Stock</option>
									<option value="2" <?php if($result['type'] == 2) { echo 'selected'; } ?>>Clean Stock</option>
									<option value="4" <?php if($result['type'] == 4) { echo 'selected'; } ?>>Supply Cost</option>
									<option value="6" <?php if($result['type'] == 6) { echo 'selected'; } ?>>Receive Cost</option>
									<option value="7" <?php if($result['type'] == 7) { echo 'selected'; } ?>>Other</option>
								</select>
							</div>
						</div>

						<div id="expense_detail_container">
							
							<?php
							if($result['type'] == 0 || $result['type'] == 2 || $result['type'] == 4 || $result['type'] == 6 || $result['type'] == 7) {
							?>
							<div class='row'>
								<div class='col-md-4 mb-3'>
									<label>Labour Cost:</label>
									<input type="text" pattern="[0-9]+" value="<?= transaction_meta($result['id'], 'labour_cost'); ?>" class="form-control" placeholder="Enter Labour Cost" name='labour_cost' id='labour_cost'>
								</div>
								<div class='col-md-4 mb-3'>
									<label>Packing Bag Cost:</label>
									<input type='text' pattern="[0-9]+" value="<?= transaction_meta($result['id'], 'packing_bag_cost'); ?>" class='form-control' placeholder='Enter Packing Bag Cost' name='packing_bag_cost' id='packing_bag_cost'>
								</div>
								<div class='col-md-4 mb-3'>
									<label>Miscellaneous Cost:</label>
									<input type='text' pattern="[0-9]+" value="<?= transaction_meta($result['id'], 'miscellaneous_cost'); ?>" class='form-control' placeholder='Enter Miscellaneous Cost' name='miscellaneous_cost' id='miscellaneous_cost'>
								</div>
							</div>
							<?php
							} else if($result['type'] == 1) {
							?>
							<div class='row'>
								<div class='col-md-4 mb-3'>
									<label>Labour Cost:</label>
									<input type='text' pattern="[0-9]+" value="<?= transaction_meta($result['id'], 'labour_cost'); ?>" class='form-control' placeholder='Enter Labour Cost' name='labour_cost' id='labour_cost'>
								</div>
								<div class='col-md-4 mb-3'>
									<label>Fumigation Cost:</label>
									<input type='text' pattern="[0-9]+" value="<?= transaction_meta($result['id'], 'fumigation_cost'); ?>" class='form-control' placeholder='Enter Fumigation Cost' name='fumigation_cost' id='fumigation_cost'>
								</div>
								<div class='col-md-4 mb-3'>
									<label>Miscellaneous Cost:</label>
									<input type='text' pattern="[0-9]+" value="<?= transaction_meta($result['id'], 'miscellaneous_cost'); ?>" class='form-control' placeholder='Enter Miscellaneous Cost' name='miscellaneous_cost' id='miscellaneous_cost'>
								</div>
							</div>
							<?php
							}
							?>

						</div>

						<button class="btn btn-primary" type="submit" id="expense_edit_form_btn" name="expense_edit_form_btn">Submit</button>

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

			$('#expense_type').on('change', function(){

				var expense_type = $('#expense_type').val();

				if(expense_type == 0 || expense_type == 2 || expense_type == 4 || expense_type == 6 || expense_type == 7) {
					$('#expense_detail_container').html(
						"<div class='row'>" + 
							"<div class='col-md-4 mb-3'>" + 
								"<label>Labour Cost:</label>" + 
								"<input type='text' pattern='[0-9]+' value='<?= transaction_meta($result['id'], 'labour_cost'); ?>' class='form-control' placeholder='Enter Labour Cost' name='labour_cost' id='labour_cost'>" + 
							"</div>" + 
							"<div class='col-md-4 mb-3'>" + 
								"<label>Packing Bag Cost:</label>" + 
								"<input type='text' pattern='[0-9]+' value='<?= transaction_meta($result['id'], 'packing_bag_cost'); ?>' class='form-control' placeholder='Enter Packing Bag Cost' name='packing_bag_cost' id='packing_bag_cost'>" + 
							"</div>" + 
							"<div class='col-md-4 mb-3'>" + 
								"<label>Miscellaneous Cost:</label>" + 
								"<input type='text' pattern='[0-9]+' value='<?= transaction_meta($result['id'], 'miscellaneous_cost'); ?>' class='form-control' placeholder='Enter Miscellaneous Cost' name='miscellaneous_cost' id='miscellaneous_cost'>" + 
							"</div>" + 
						"</div>"
					);
				} else if(expense_type == 1) {
					$('#expense_detail_container').html(
						"<div class='row'>" + 
							"<div class='col-md-4 mb-3'>" + 
								"<label>Labour Cost:</label>" + 
								"<input type='text' pattern='[0-9]+' value='<?= transaction_meta($result['id'], 'labour_cost'); ?>' class='form-control' placeholder='Enter Labour Cost' name='labour_cost' id='labour_cost'>" + 
							"</div>" + 
							"<div class='col-md-4 mb-3'>" + 
								"<label>Fumigation Cost:</label>" + 
								"<input type='text' pattern='[0-9]+' value='<?= transaction_meta($result['id'], 'fumigation_cost'); ?>' class='form-control' placeholder='Enter Fumigation Cost' name='fumigation_cost' id='fumigation_cost'>" + 
							"</div>" + 
							"<div class='col-md-4 mb-3'>" + 
								"<label>Miscellaneous Cost:</label>" + 
								"<input type='text' pattern='[0-9]+' value='<?= transaction_meta($result['id'], 'miscellaneous_cost'); ?>' class='form-control' placeholder='Enter Miscellaneous Cost' name='miscellaneous_cost' id='miscellaneous_cost'>" + 
							"</div>" + 
						"</div>"
					);
				}

			});

		});
	</script>
</body>
</html>