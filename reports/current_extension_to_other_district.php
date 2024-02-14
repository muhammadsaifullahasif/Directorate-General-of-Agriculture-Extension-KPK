<div class="table-responsive">
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<td colspan="100" class="align-middle text-center">
					<h4><b><?= stock_type($stock_type); ?> OUT DISTRICT ISSUE FROM <?= extension_name($extension_id); ?></b></h4>
					<span>Weight in <?= $stock_weight; ?></span>
				</td>
			</tr>
			<tr>
				<td rowspan="2" class="align-middle text-center"><b>S.No</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Name of District</b></td>
				<?php

				$variety_query_1 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN supply AS su INNER JOIN extensions AS e ON sv.id=s.variety && s.id=su.parent_id && e.id=su.receiver_detail WHERE e.district!='$user_district' && s.type='$stock_type' && s.activity_session='$activity_session' && sv.active_status='1' && s.active_status='1' && su.active_status='1' && e.active_status='1' && sv.delete_status='0' && s.delete_status='0' && su.delete_status='0' && e.delete_status='0' GROUP BY s.variety");

				if(mysqli_num_rows($variety_query_1) > 0) {
					while($head_variety_result_1 = mysqli_fetch_assoc($variety_query_1)) {
						$head_variety_id_1 = $head_variety_result_1['id'];
						$head_variety_class_query_1 = mysqli_query($conn, "SELECT sc.id FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.extension_id='$report_extension' && s.type='$stock_type' && s.variety='$head_variety_id_1' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
						echo "<td class='align-middle text-center' colspan='".mysqli_num_rows($head_variety_class_query_1)."'><b>".$head_variety_result_1['variety']."</b></td>";
					}
				}

				?>
				<td rowspan="2" class="align-middle text-center"><b>Total</b></td>
			</tr>
			<tr>
				<?php

				$variety_query_2 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN supply AS su INNER JOIN extensions AS e ON sv.id=s.variety && s.id=su.parent_id && e.id=su.receiver_detail WHERE e.district!='$user_district' && s.type='$stock_type' && s.activity_session='$activity_session' && sv.active_status='1' && s.active_status='1' && su.active_status='1' && e.active_status='1' && sv.delete_status='0' && s.delete_status='0' && su.delete_status='0' && e.delete_status='0' GROUP BY s.variety");

				if(mysqli_num_rows($variety_query_2) > 0) {
					while($head_variety_result_2 = mysqli_fetch_assoc($variety_query_2)) {
						$head_variety_id_2 = $head_variety_result_2['id'];
						$head_variety_class_query_2 = mysqli_query($conn, "SELECT sc.class_name FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.extension_id='$report_extension' && s.type='$stock_type' && s.variety='$head_variety_id_2' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
						if(mysqli_num_rows($head_variety_class_query_2) > 0) {
							while($head_variety_class_result_2 = mysqli_fetch_assoc($head_variety_class_query_2)) {
								echo "<td class='align-middle text-center'><b>".$head_variety_class_result_2['class_name']."</b></td>";
							}
						}
					}
				}

				?>
			</tr>
		</thead>
		<tbody>
			<?php

			$total_table_qty = 0;
			$total_table_class_qty = array();
			$district_query = mysqli_query($conn, "SELECT c.id, c.name FROM configurations AS c INNER JOIN extensions AS e INNER JOIN supply AS s ON c.id=e.district && e.id=s.receiver_detail WHERE c.type='district' && e.district!='$user_district' && c.active_status='1' && e.active_status='1' && s.active_status='1' && c.delete_status='0' && e.delete_status='0' && s.delete_status='0' GROUP BY c.id");

			if(mysqli_num_rows($district_query) > 0) {
				$i = 1;
				while($district_result = mysqli_fetch_assoc($district_query)) {
					$district_id = $district_result['id'];
					$total_row_qty = 0;
					echo "<tr>";
					echo "<td class='align-middle text-center'>".$i++."</td>";
					echo "<td class='align-middle text-center'>".$district_result['name']."</td>";
					$variety_query_3 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN supply AS su INNER JOIN extensions AS e ON sv.id=s.variety && s.id=su.parent_id && e.id=su.receiver_detail WHERE e.district!='$user_district' && s.type='$stock_type' && s.activity_session='$activity_session' && sv.active_status='1' && s.active_status='1' && su.active_status='1' && e.active_status='1' && sv.delete_status='0' && s.delete_status='0' && su.delete_status='0' && e.delete_status='0' GROUP BY s.variety");

					if(mysqli_num_rows($variety_query_3) > 0) {
						$j = 0;
						while($head_variety_result_3 = mysqli_fetch_assoc($variety_query_3)) {
							$head_variety_id_3 = $head_variety_result_3['id'];
							$head_variety_class_query_3 = mysqli_query($conn, "SELECT sc.id, sc.class_name FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.extension_id='$report_extension' && s.type='$stock_type' && s.activity_session='$activity_session' && s.variety='$head_variety_id_3' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
							if(mysqli_num_rows($head_variety_class_query_3) > 0) {
								while($head_variety_class_result_3 = mysqli_fetch_assoc($head_variety_class_query_3)) {
									$k = 0;
									$head_variety_class_id = $head_variety_class_result_3['id'];
									$total_cell_qty = 0;
									$supply_query = mysqli_query($conn, "SELECT su.stock_qty FROM supply AS su INNER JOIN stocks AS s INNER JOIN extensions AS e ON su.parent_id=s.id && su.receiver_detail=e.id WHERE su.extension_id='$report_extension' && s.type='$stock_type' && s.activity_session='$activity_session' && s.variety='$head_variety_id_3' && s.class='$head_variety_class_id' && su.receive_source='other_extension' && e.district='$district_id'");
									if(mysqli_num_rows($supply_query) > 0) {
										while($supply_result = mysqli_fetch_assoc($supply_query)) {
											$total_cell_qty += $supply_result['stock_qty'];
										}
										echo "<td class='align-middle text-center'>".$total_cell_qty."</td>";
									} else {
										echo "<td class='align-middle text-center'>0</td>";
									}
									$total_row_qty += $total_cell_qty;
									if(empty($total_table_class_qty[$j])) {
										$total_table_class_qty[$j] = $total_cell_qty;
									} else {
										$total_table_class_qty[$j] += $total_cell_qty;
									}
									$j++;
									$k++;
								}
							}
						}
					}
					echo "<td class='align-middle text-center'>".$total_row_qty."</td>";
					echo "</tr>";
					$total_table_qty += $total_row_qty;
				}
			}

			?>
			<tr>
				<td colspan="2" class="align-middle text-center"><b>Total</b></td>
				<?php

				foreach($total_table_class_qty as $total_table_class_value) {
					echo "<td class='align-middle text-center'><b>".$total_table_class_value."</b></td>";
				}

				?>
				<td class="align-middle text-center"><b><?= $total_table_qty; ?></b></td>
			</tr>
			<tr>
				<td colspan="2" class="align-middle text-center"><b>Total in Monds</b></td>
				<?php

				foreach($total_table_class_qty as $total_table_class_value) {
					echo "<td class='align-middle text-center'><b>".($total_table_class_value / 50)."</b></td>";
				}

				?>
				<td class="align-middle text-center"><b><?= ($total_table_qty / 50); ?></b></td>
			</tr>
		</tbody>
	</table>
</div>