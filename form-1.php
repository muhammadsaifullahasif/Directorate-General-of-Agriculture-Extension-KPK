<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Form 1';

	include "head.php";

	if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] != 0 && isset($_GET['receiver_detail']) && $_GET['receiver_detail'] != '' && $_GET['receiver_detail'] != 0 && isset($_GET['activity_season']) && $_GET['activity_season'] != '' && $_GET['activity_season'] != 0) {
		$id = validate($_GET['id']);
		$receiver_detail = validate($_GET['receiver_detail']);
		$smp_activity_season = validate($_GET['activity_season']);
		$query = mysqli_query($conn, "SELECT * FROM supply WHERE id='$id'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
		} else {
			header("Location: smp-list.php");
		}
	} else {
		header("Location: smp-list.php");
	}

	?>
	<style type="text/css">
		.main-footer {
			margin-left: 0px !important;
		}
	</style>
	<style type="text/css" media="print">
		@page { size: landscape; }
	</style>
</head>
<body>

	<div class="container-fluid">
		<div class="row text-center mx-3">
			<div class="col-12">
				<h4 style="word-spacing: 10px;"><b>APPLICATION FOR FIELD INSPECTION</b></h4>
				<p>(In term of sub section 13 of the seed act, 1976)</p>
				<p class="text-right"><b>Form No: 01</b></p>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<p class="mb-0">To</p>
			</div>
		</div>
		<div class="row mx-3">
			<div class="col-12">
				<p class="mb-0">The Deputy Director FSC & RD Peshawar</p>
			</div>
		</div>
		<div class="row mx-3">
			<div class="col-4">
				<p class="mb-0">Name of the Applicant: <b><u><?= farmer_info($result['receiver_detail'], 'farmer_name', 'cnic'); ?></u></b></p>
			</div>
			<div class="col-4">
				<p class="mb-0">Address: <b><u><?= farmer_info($result['receiver_detail'], 'farmer_address', 'cnic'); ?></u></b></p>
			</div>
			<div class="col-4">
				<p class="mb-0">Telephone No: <b><u><?= farmer_info($result['receiver_detail'], 'farmer_mobile_number', 'cnic'); ?></u></b></p>
			</div>
		</div>
		<div class="row mx-3">
			<div class="col-12">
				<p class="mb-0">Name of Grower: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u><?= farmer_info($result['receiver_detail'], 'farmer_name', 'cnic'); ?></u></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Farm on which seed is produced _____________________________________ approach to the farm _____________________________________</p>
				<p>Milage from ___________________________________________ Town and exact location.</p>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead>
							<tr>
								<th class="align-bottom text-center">Crop</th>
								<th class="align-bottom text-center">Variety</th>
								<th class="align-bottom text-center">No of Acres</th>
								<th class="align-bottom text-center">Class/Tag colour of seed planted</th>
								<th class="align-bottom text-center">Fee submitted with application Rs: {} by Bank draft/chalan No: {}</th>
							</tr>
						</thead>
						<tbody>
							<?php

							$smp_query = mysqli_query($conn, "SELECT sp.id, sp.stock_qty, s.crop, s.variety, st.class FROM supply AS sp INNER JOIN stocks AS s INNER JOIN stock_transactions AS st INNER JOIN circles AS e ON sp.parent_id=s.id && st.id=sp.stock_id && s.circle_id=e.id WHERE (sp.receive_source='to_farmer' || sp.receive_source='others') && sp.receiver_detail='$receiver_detail' && sp.activity_season='$smp_activity_season' && sp.active_status='1' && s.active_status='1' && e.active_status='1' && sp.delete_status='0' && s.delete_status='0' && e.delete_status='0'");
							if(mysqli_num_rows($smp_query) > 0) {
								while($smp_result = mysqli_fetch_assoc($smp_query)) {
									echo "<tr>";
									echo "<td class='align-middle text-center'>".stock_crop($smp_result['crop'])."</td>";
									echo "<td class='align-middle text-center'>".stock_variety($smp_result['variety'])."</td>";
									echo "<td class='align-middle text-center'>".supply_meta($smp_result['id'], 'area')."</td>";
									echo "<td class='align-middle text-center'>".stock_class($smp_result['class'])."</td>";
									echo "<td class='align-middle text-center'></td>";
									echo "</tr>";
								}
							}

							?>
						</tbody>
					</table>
				</div>
				<small>I understand and agree to abide by the rules and regulation of the Federal Seed Certification Department. I confirm that the entire acreage planted and offered by me as above was planted with the seed represented by tags. I further confirm that I have taken adequate steps to prevent cntamination at the farm by other means.</small>
			</div>
		</div>

		<div class="row mt-5 justify-content-end" id="report_signature">
			<div class="col-3">
				<p class="text-center mb-0">_____________________________</p>
				<p class="text-center">Signature of the applicant/Grower</p>
				<!-- <h5 class="text-center"><b><?php echo circle_name($circle_id); ?></b></h5> -->
			</div>
		</div>
		<?php

		include "footer.php";

		?>
	</div>

	<?php

	include "javascript.php";

	?>
	<script type="text/javascript">
		$(document).ready(function(){
			// $(document).load(function(){
				window.print();
			// });
		});
	</script>
</body>
</html>