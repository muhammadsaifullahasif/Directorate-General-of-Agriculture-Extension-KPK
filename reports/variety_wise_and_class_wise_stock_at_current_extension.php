<div class="table-responsive">
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<td colspan="15" class="align-middle text-center">
					<h4><b>TOTAL VARIETY WISE AND CLASS WISE <?php echo stock_type($stock_type); ?> STOCK AT <?php echo extension_name($report_extension).' '.activity_session_title($activity_session); ?></b></h4>
					<span>Weight in <?= $stock_weight; ?></span>
				</td>
			</tr>
			<tr>
				<td rowspan="2" class="align-middle text-center"><b>S.No</b></td>
				<?php

				$variety_query_1 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s ON sv.id=s.variety WHERE s.extension_id='$report_extension' && s.type='$stock_type' && sv.stock_type_id='$stock_type' && s.activity_session='$activity_session' GROUP BY s.variety");
				if(mysqli_num_rows($variety_query_1) > 0) {
					while($variety_result_1 = mysqli_fetch_assoc($variety_query_1)) {
						$variety_id_1 = $variety_result_1['id'];
						$variety_class_query_1 = mysqli_query($conn, "SELECT sc.id FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.extension_id='$report_extension' && s.type='$stock_type' && s.activity_session='$activity_session' && s.variety='$variety_id_1' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
						echo "<td colspan='".mysqli_num_rows($variety_class_query_1)."' class='align-middle text-center'>".$variety_result_1['variety']."</td>";
					}
				}

				?>
				<td rowspan="2" class="align-middle text-center"><b>Total</b></td>
			</tr>
			<tr>
				<?php

				$variety_query_2 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s ON sv.id=s.variety WHERE s.extension_id='$report_extension' && s.type='$stock_type' && sv.stock_type_id='$stock_type' && s.activity_session='$activity_session' GROUP BY s.variety");

				if(mysqli_num_rows($variety_query_2) > 0) {
					while($variety_result_2 = mysqli_fetch_assoc($variety_query_2)) {
						$variety_id_2 = $variety_result_2['id'];
						$variety_class_query_2 = mysqli_query($conn, "SELECT sc.class_name FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.extension_id='$report_extension' && s.activity_session='$activity_session' && s.type='$stock_type' && s.variety='$variety_id_2' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
						if(mysqli_num_rows($variety_class_query_2) > 0) {
							while($variety_class_result_2 = mysqli_fetch_assoc($variety_class_query_2)) {
								echo "<td class='align-middle text-center'><b>".$variety_class_result_2['class_name']."</b></td>";
							}
						}
					}
				}

				?>
			</tr>
		</thead>
		<tbody>
			<td class="align-middle text-center">1</td>
			<?php

			$variety_query_3 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s ON sv.id=s.variety WHERE s.extension_id='$report_extension' && s.type='$stock_type' && sv.stock_type_id='$stock_type' && s.activity_session='$activity_session' GROUP BY s.variety");

			$total_row_qty = 0;
			if(mysqli_num_rows($variety_query_3) > 0) {
				while($variety_result_3 = mysqli_fetch_assoc($variety_query_3)) {
					$variety_id_3 = $variety_result_3['id'];
					$variety_class_query_3 = mysqli_query($conn, "SELECT sc.id, sc.class_name FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.extension_id='$report_extension' && s.activity_session='$activity_session' && s.type='$stock_type' && s.variety='$variety_id_3' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
					if(mysqli_num_rows($variety_class_query_3) > 0) {
						while($variety_class_result_3 = mysqli_fetch_assoc($variety_class_query_3)) {
							$variety_class_id_3 = $variety_class_result_3['id'];
							$stock_query = mysqli_query($conn, "SELECT SUM(st.stock_qty) AS stock_qty FROM stock_transactions AS st INNER JOIN stocks AS s ON st.stock_id=s.id WHERE s.activity_session='$activity_session' && s.variety='$variety_id_3' && s.class='$variety_class_id_3' && s.active_status='1' && st.active_status!='0' && s.delete_status='0' && st.delete_status='0' GROUP BY st.stock_id");
							if(mysqli_num_rows($stock_query) > 0) {
								$stock_result = mysqli_fetch_assoc($stock_query);
								echo "<td class='align-middle text-center'>".$stock_result['stock_qty']."</td>";
								$total_row_qty += $stock_result['stock_qty'];
							} else {
								echo "<td class='align-middle text-center'>0</td>";
							}
						}
					}
				}
			}

			echo "<td class='align-middle text-center'>".$total_row_qty."</td>";

			?>
		</tbody>
	</table>
</div>