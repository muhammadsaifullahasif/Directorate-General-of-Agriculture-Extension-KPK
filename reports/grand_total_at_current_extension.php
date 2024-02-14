<div class="table-responsive">
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<td colspan="15" class="align-middle text-center">
					<h4><b>GRAND TOTAL OF <?php echo stock_type($stock_type); ?> STOCK AT <?php echo extension_name($report_extension).' '.activity_session_title($activity_session); ?></b></h4>
					<span>Weight in <?= $stock_weight; ?></span>
				</td>
			</tr>
			<tr>
				<td rowspan="2" class="align-middle text-center"></td>
				<td colspan="4" class="align-middle text-center"><b>Class</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Total</b></td>
			</tr>
			<tr>
			<?php

			$stock_class_query_1 = mysqli_query($conn, "SELECT * FROM stock_class WHERE active_status='1' && delete_status='0'");
			if(mysqli_num_rows($stock_class_query_1) > 0) {
				while($stock_class_result_1 = mysqli_fetch_assoc($stock_class_query_1)) {
					echo "<td class='align-middle text-center'><b>".$stock_class_result_1['class_name']."</b></td>";
				}
			}

			?>
		</tr>
		</thead>
		<tbody>
			<tr>
				<td class="align-middle text-center"><b>Total in KGs</b></td>
				<?php

				$total_row_qty = 0;
				$stock_class_query_2 = mysqli_query($conn, "SELECT * FROM stock_class WHERE active_status='1' && delete_status='0'");
				if(mysqli_num_rows($stock_class_query_2) > 0) {
					while($stock_class_result_2 = mysqli_fetch_assoc($stock_class_query_2)) {
						$stock_class_id_2 = $stock_class_result_2['id'];
						$stock_query_1 = mysqli_query($conn, "SELECT SUM(st.stock_qty) as stock_qty FROM stocks AS s INNER JOIN stock_transactions AS st ON s.id=st.stock_id WHERE st.extension_id='$report_extension' && st.class='$stock_class_id_2' && s.type='$stock_type' && s.activity_session='$activity_session' && s.active_status='1' && st.active_status!='0' && s.delete_status='0' && st.delete_status='0' GROUP BY st.class");
						$total_cell_qty = 0;
						if(mysqli_num_rows($stock_query_1) > 0) {
							$stock_result_1 = mysqli_fetch_assoc($stock_query_1);
							echo "<td class='align-middle text-center'>".$stock_result_1['stock_qty']."</td>";
							$total_row_qty += $stock_result_1['stock_qty'];
						} else {
							echo "<td class='align-middle text-center'>0</td>";
						}
					}
				}

				?>
				<td class="align-middle text-center"><b><?php echo $total_row_qty; ?></b></td>
			</tr>
			<tr>
				<td class="align-middle text-center"><b>Total in Monds</b></td>
				<?php

				$total_row_qty = 0;
				$stock_class_query_2 = mysqli_query($conn, "SELECT * FROM stock_class WHERE active_status='1' && delete_status='0'");
				if(mysqli_num_rows($stock_class_query_2) > 0) {
					while($stock_class_result_2 = mysqli_fetch_assoc($stock_class_query_2)) {
						$stock_class_id_2 = $stock_class_result_2['id'];
						$stock_query_1 = mysqli_query($conn, "SELECT SUM(st.stock_qty) as stock_qty FROM stocks AS s INNER JOIN stock_transactions AS st ON s.id=st.stock_id WHERE s.extension_id='$report_extension' && st.class='$stock_class_id_2' && s.type='$stock_type' && s.activity_session='$activity_session' && s.active_status='1' && st.active_status='1' && s.delete_status='0' && st.delete_status='0' GROUP BY s.class");
						if(mysqli_num_rows($stock_query_1) > 0) {
							$stock_result_1 = mysqli_fetch_assoc($stock_query_1);
							echo "<td class='align-middle text-center'>".($stock_result_1['stock_qty'] / 50)."</td>";
							$total_row_qty += $stock_result_1['stock_qty'];
						} else {
							echo "<td class='align-middle text-center'>0</td>";
						}
					}
				}

				?>
				<td class="align-middle text-center"><b><?php echo ($total_row_qty / 50); ?></b></td>
			</tr>
		</tbody>
	</table>
</div>