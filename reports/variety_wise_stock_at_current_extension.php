<div class="table-responsive">
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<td colspan="15" class="align-middle text-center">
					<h4><b>TOTAL VARIETY WISE <?php echo stock_type($stock_type); ?> STOCK AT <?php echo extension_name($report_extension).' '.activity_session_title($activity_session); ?></b></h4>
					<span>Weight in <?= $stock_weight; ?></span>
				</td>
			</tr>
			<tr>
				<td rowspan="2" class="align-middle text-center"><b>S.No</b></td>
				<?php

				$variety_query = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s ON sv.id=s.variety WHERE s.extension_id='$report_extension' && s.type='$stock_type' && sv.stock_type_id='$stock_type' && s.activity_session='$activity_session' && sv.active_status='1' && s.active_status='1' && sv.delete_status='0' && s.delete_status='0' GROUP BY s.variety");
				if(mysqli_num_rows($variety_query) > 0) {
					while($head_variety_result = mysqli_fetch_assoc($variety_query)) {
						$head_variety_id = $head_variety_result['id'];
						$head_variety_class_query = mysqli_query($conn, "SELECT sc.id FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.extension_id='$report_extension' && s.activity_session='$activity_session' && s.type='$stock_type' && s.variety='$head_variety_id' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
						echo "<td class='align-middle text-center' colspan='".mysqli_num_rows($head_variety_class_query)."'><b>".$head_variety_result['variety']."</b></td>";
					}
				}

				?>
				<td rowspan="2" class="align-middle text-center"><b>Total Monds</b></td>
			</tr>
			<tr>
				<?php

				$class_variety_query = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv LEFT JOIN stocks AS s ON sv.id=s.variety WHERE s.extension_id='$report_extension' && s.type='$stock_type' && sv.stock_type_id='$stock_type' && s.activity_session='$activity_session' && sv.active_status='1' && s.active_status='1' && sv.delete_status='0' && s.delete_status='0' GROUP BY s.variety");
				if(mysqli_num_rows($class_variety_query) > 0) {
					while($class_variety_result = mysqli_fetch_assoc($class_variety_query)) {
						$class_variety_id = $class_variety_result['id'];
						$head_variety_class_query = mysqli_query($conn, "SELECT sc.id, sc.class_name FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.extension_id='$report_extension' && s.activity_session='$activity_session' && s.type='$stock_type' && s.variety='$class_variety_id' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
						if(mysqli_num_rows($head_variety_class_query) > 0) {
							while($head_variety_class_result = mysqli_fetch_assoc($head_variety_class_query)) {
								echo "<td class='align-middle text-center'><b>".$head_variety_class_result['class_name']."</b></td>";
							}
						}
					}
				}

				?>
			</tr>
		</thead>
		<tbody>
			<?php

			$body_variety_query = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv LEFT JOIN stocks AS s ON sv.id=s.variety WHERE s.extension_id='$report_extension' && s.type='$stock_type' && sv.stock_type_id='$stock_type' && s.activity_session='$activity_session' && sv.active_status='1' && s.active_status='1' && sv.delete_status='0' && s.delete_status='0' GROUP BY s.variety");
			if(mysqli_num_rows($body_variety_query) > 0) {
				while($body_variety_result = mysqli_fetch_assoc($body_variety_query)) {
					echo "<tr>";
					$body_variety_id = $body_variety_result['id'];
					$body_variety_class_query = mysqli_query($conn, "SELECT sc.id, sc.class_name FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.extension_id='$report_extension' && s.activity_session='$activity_session' && s.type='$stock_type' && s.variety='$body_variety_id' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
					if(mysqli_num_rows($body_variety_class_query) > 0) {
						while($body_variety_class_result = mysqli_fetch_assoc($body_variety_class_query)) {
							// echo "<td class='align-middle text-center'>".$body_variety_class_result['class_name']."</td>";
						}
					}
					echo "</tr>";
				}
			}

			?>
		</tbody>
	</table>
</div>