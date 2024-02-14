<div class="table-responsive">
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<td colspan="15" class="align-middle text-center">
					<h4><b>DISTRICT WISE CLEANING REPORT OF <?php echo stock_type($stock_type); ?> STOCK AT <?php echo extension_name($report_extension).' '.activity_session_title($activity_session); ?></b></h4>
					<span>Weight in <?= $stock_weight; ?></span>
				</td>
			</tr>
			<tr>
				<td rowspan="2" class="align-middle text-center"><b>Sr.No</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Name of District</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Processed QTY</b></td>
				<td colspan="4" class="align-middle text-center"><b>Class</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Small Grain</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Gundi</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Straw</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Dust</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Other</b></td>
				<td rowspan="2" class="align-middle text-center"><b>% Age</b></td>
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
			<?php

			$total_table_processing_qty = 0;
			$total_table_class_qty = array();
			$total_table_small_grains = 0;
			$total_table_gundi = 0;
			$total_table_straw = 0;
			$total_table_dust = 0;
			$total_table_other = 0;
			$district_query_1 = mysqli_query($conn, "SELECT c.id, c.name FROM configurations AS c INNER JOIN extensions AS e INNER JOIN stocks AS s ON c.id=e.district && e.id=s.supplier_info WHERE c.type='district' && e.district!='$user_district' && s.stock_source='other_extension' && s.type='$stock_type' && s.activity_session='$activity_session' && c.active_status='1' && e.active_status='1' && s.active_status='1' && c.delete_status='0' && e.delete_status='0' && s.delete_status='0' GROUP BY c.name");
			if(mysqli_num_rows($district_query_1) > 0) {
				$i = 1;
				while($district_result_1 = mysqli_fetch_assoc($district_query_1)) {
					$district_id_1 = $district_result_1['id'];
					$total_row_processing_qty = 0;
					$total_row_class_qty = 0;
					$total_row_small_grains = 0;
					$total_row_gundi = 0;
					$total_row_straw = 0;
					$total_row_dust = 0;
					$total_row_other = 0;
					echo "<tr>";
					echo "<td class='align-middle text-center'>".$i++."</td>";
					echo "<td class='align-middle text-center'>".$district_result_1['name']."</td>";
					/*$stock_query_1 = mysqli_query($conn, "SELECT SUM(sc.processing_qty) AS processing_qty FROM stock_cleaning AS sc INNER JOIN stocks AS s INNER JOIN extensions AS e ON sc.parent_id=s.id && s.supplier_info=e.id WHERE s.stock_source='other_extension' && e.district='$district_id_1' && s.extension_id='$report_extension' && sc.active_status='1' && s.active_status='1' && e.active_status='1' && sc.delete_status='0' && s.delete_status='0' && e.delete_status='0' GROUP BY e.district");
					if(mysqli_num_rows($stock_query_1) > 0) {
						$stock_result_1 = mysqli_fetch_assoc($stock_query_1);
						echo "<td>".$stock_result_1['processing_qty']."</td>";
					} else {
						echo "<td>0</td>";
					}*/
					$stock_query_1 = mysqli_query($conn, "SELECT sc.id, sc.processing_qty FROM stock_cleaning AS sc INNER JOIN stocks AS s INNER JOIN extensions AS e ON sc.parent_id=s.id && s.supplier_info=e.id WHERE s.stock_source='other_extension' && e.district='$district_id_1' && s.extension_id='$report_extension' && s.type='$stock_type' && s.activity_session='$activity_session' && sc.active_status='1' && s.active_status='1' && e.active_status='1' && sc.delete_status='0' && s.delete_status='0' && e.delete_status='0'");
					if(mysqli_num_rows($stock_query_1) > 0) {
						while($stock_result_1 = mysqli_fetch_assoc($stock_query_1)) {
							$total_row_processing_qty += $stock_result_1['processing_qty'];
							$total_row_small_grains += stock_cleaning_meta($stock_result_1['id'], 'small_grains');
							$total_row_gundi += stock_cleaning_meta($stock_result_1['id'], 'gundi');
							$total_row_straw += stock_cleaning_meta($stock_result_1['id'], 'straw');
							$total_row_dust += stock_cleaning_meta($stock_result_1['id'], 'dust');
							$total_row_other += stock_cleaning_meta($stock_result_1['id'], 'other');
						}
						echo "<td class='align-middle text-center'>".$total_row_processing_qty."</td>";
					} else {
						echo "<td class='align-middle text-center'>0</td>";
					}
					$stock_class_query_2 = mysqli_query($conn, "SELECT * FROM stock_class WHERE active_status='1' && delete_status='0'");
					if(mysqli_num_rows($stock_class_query_2) > 0) {
						$j = 0;
						while($stock_class_result_2 = mysqli_fetch_assoc($stock_class_query_2)) {
							$stock_class_2 = $stock_class_result_2['id'];
							$stock_query_2 = mysqli_query($conn, "SELECT sc.id FROM stock_cleaning AS sc INNER JOIN stocks AS s INNER JOIN extensions AS e ON sc.parent_id=s.id && s.supplier_info=e.id WHERE s.class='$stock_class_2' && s.type='$stock_type' && s.activity_session='$activity_session' && s.stock_source='other_extension' && e.district='$district_id_1' && s.extension_id='$report_extension' && sc.active_status='1' && s.active_status='1' && e.active_status='1' && sc.delete_status='0' && s.delete_status='0' && e.delete_status='0'");
							$total_cell_class_qty = 0;
							if(mysqli_num_rows($stock_query_2) > 0) {
								while($stock_result_2 = mysqli_fetch_assoc($stock_query_2)) {
									$total_cell_class_qty += stock_cleaning_meta($stock_result_2['id'], 'grade_1');
								}
							}
							echo "<td class='align-middle text-center'>".$total_cell_class_qty."</td>";
							$total_row_class_qty += $total_cell_class_qty;
							if(empty($total_table_class_qty[$j])) {
								$total_table_class_qty[$j] = $total_cell_class_qty;
							} else {
								$total_table_class_qty[$j] += $total_cell_class_qty;
							}
							$j++;
						}
					}
					echo "<td class='align-middle text-center'>".$total_row_small_grains."</td>";
					echo "<td class='align-middle text-center'>".$total_row_gundi."</td>";
					echo "<td class='align-middle text-center'>".$total_row_straw."</td>";
					echo "<td class='align-middle text-center'>".$total_row_dust."</td>";
					echo "<td class='align-middle text-center'>".$total_row_other."</td>";
					if($total_row_processing_qty != 0) {
						echo "<td class='align-middle text-center'>".round((($total_row_class_qty * 100) / $total_row_processing_qty), 2)."</td>";
					} else {
						echo "<td class='align-middle text-center'>0</td>";
					}

					$total_table_processing_qty += $total_row_processing_qty;
					$total_table_small_grains += $total_row_small_grains;
					$total_table_gundi += $total_row_gundi;
					$total_table_straw += $total_row_straw;
					$total_table_dust += $total_row_dust;
					$total_table_other += $total_row_other;
					echo "</tr>";
				}
			}

			?>
			<tr>
				<td colspan="2" class="align-middle text-center"><b>Total in KGs</b></td>
				<td class="align-middle text-center"><b><?php echo $total_table_processing_qty; ?></b></td>
				<?php
				$total_table_qty = 0;
				foreach($total_table_class_qty as $total_table_class_value) {
					echo "<td class='align-middle text-center'><b>".$total_table_class_value."</b></td>";
					$total_table_qty += $total_table_class_value;
				}

				echo "<td class='align-middle text-center'><b>".$total_table_small_grains."</b></td>";
				echo "<td class='align-middle text-center'><b>".$total_table_gundi."</b></td>";
				echo "<td class='align-middle text-center'><b>".$total_table_straw."</b></td>";
				echo "<td class='align-middle text-center'><b>".$total_table_dust."</b></td>";
				echo "<td class='align-middle text-center'><b>".$total_table_other."</b></td>";
				if($total_table_processing_qty != 0) {
					echo "<td class='align-middle text-center'><b>".round(($total_table_qty * 100 / $total_table_processing_qty), 2)."</b></td>";
				} else {
					echo "<td class='align-middle text-center'><b>0</b></td>";
				}

				?>
			</tr>
			<tr>
				<td class="align-middle text-center" colspan="2"><b>Total in Monds</b></td>
				<td class="align-middle text-center"><b><?php echo $total_table_processing_qty / 50; ?></b></td>
				<?php
				$total_table_qty = 0;
				foreach($total_table_class_qty as $total_table_class_value) {
					echo "<td class='align-middle text-center'><b>".($total_table_class_value / 50)."</b></td>";
					$total_table_qty += $total_table_class_value;
				}

				echo "<td class='align-middle text-center'><b>".($total_table_small_grains / 50)."</b></td>";
				echo "<td class='align-middle text-center'><b>".($total_table_gundi / 50)."</b></td>";
				echo "<td class='align-middle text-center'><b>".($total_table_straw / 50)."</b></td>";
				echo "<td class='align-middle text-center'><b>".($total_table_dust / 50)."</b></td>";
				echo "<td class='align-middle text-center'><b>".($total_table_other / 50)."</b></td>";
				if($total_table_processing_qty != 0) {
					echo "<td class='align-middle text-center'><b>".round(($total_table_qty * 100 / $total_table_processing_qty), 2)."</b></td>";
				} else {
					echo "<td class='align-middle text-center'><b>0</b></td>";
				}

				?>
			</tr>
		</tbody>
	</table>
</div>