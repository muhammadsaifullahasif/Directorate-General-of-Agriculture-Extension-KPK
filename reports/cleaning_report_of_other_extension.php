<div class="table-responsive">
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<td colspan="13" class="text-center">
					<h4><b>VARIETY WISE SEED CLEANING REPORT AT <?php echo extension_name($extension_id).' FROM '.extension_name($report_extension).' '.activity_session_title($activity_session); ?></b></h4>
					<span>Weight in <?= $stock_weight; ?></span>
				</td>
			</tr>
			<tr>
				<td rowspan="2" class="align-middle text-center"><b>S.No</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Name of variety</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Processed QTY</b></td>
				<td colspan="4" class="align-middle text-center"><b>Class</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Small Grain</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Gundy</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Straw</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Dust</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Other</b></td>
				<td rowspan="2" class="align-middle text-center"><b>% Age</b></td>
			</tr>
			<tr>
				<?php

				$stock_class_query = mysqli_query($conn, "SELECT * FROM stock_class WHERE active_status='1' && delete_status='0'");
				if(mysqli_num_rows($stock_class_query) > 0) {
					while($stock_class_result = mysqli_fetch_assoc($stock_class_query)) {
						echo "<td class='align-middle text-center'><b>".$stock_class_result['class_name']."</b></td>";
					}
				}

				?>
			</tr>
		</thead>
		<tbody>
			
			<?php

			$total_table_processing_qty = 0;
			$total_table_class_qty = array();
			$total_table_grade_1 = 0;
			$total_table_small_grains = 0;
			$total_table_gundi = 0;
			$total_table_straw = 0;
			$total_table_dust = 0;
			$total_table_other = 0;

			// $report_variety_query = mysqli_query($conn, "SELECT id, variety FROM stock_variety WHERE stock_type_id='$stock_type' && active_status='1' && delete_status='0'");
			$report_variety_query = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN stock_cleaning AS sc ON sv.id=s.variety && s.id=sc.parent_id WHERE s.stock_source='other_extension' && s.supplier_info='$report_extension' && s.extension_id='$extension_id' && s.type='$stock_type' && s.activity_session='$activity_session' && sv.stock_type_id='$stock_type' && sv.active_status='1' && s.active_status='1' && sc.active_status='1' && sv.delete_status='0' && s.delete_status='0' && sc.delete_status='0' GROUP BY s.variety");
			if(mysqli_num_rows($report_variety_query) > 0) {
				$i = 1;
				while($report_variety_result = mysqli_fetch_assoc($report_variety_query)) {
					$total_row_processing_qty = 0;
					$total_row_class_qty = 0;
					$total_row_small_grains = 0;
					$total_row_gundi = 0;
					$total_row_straw = 0;
					$total_row_dust = 0;
					$total_row_other = 0;
					?>
					<tr>
						<td class="align-middle text-center"><?= $i++; ?></td>
						<td class="align-middle text-center"><?= $report_variety_result['variety']; ?></td>
					<?php
					$report_variety_id = $report_variety_result['id'];
					$report_stock_query = mysqli_query($conn, "SELECT s.class, sc.id, SUM(sc.processing_qty) AS processing_qty FROM stocks AS s INNER JOIN stock_cleaning AS sc ON s.id=sc.parent_id WHERE s.stock_source='other_extension' && s.supplier_info='$report_extension' && s.extension_id='$extension_id' && s.activity_session='$activity_session' && s.type='$stock_type' && s.variety='$report_variety_id' && s.active_status='1' && sc.active_status='1' && s.delete_status='0' && sc.delete_status='0'");
					$report_stock_result = mysqli_fetch_assoc($report_stock_query);

					echo "<td class='align-middle text-center'>";
					if($report_stock_result['processing_qty'] != '') {
						$total_row_processing_qty = $report_stock_result['processing_qty'];
						$total_table_processing_qty += $total_row_processing_qty;
						echo $total_row_processing_qty;
					} else {
						echo 0;
					}
					echo "</td>";

					$report_class_query = mysqli_query($conn, "SELECT id FROM stock_class WHERE active_status='1' && delete_status='0'");
					if(mysqli_num_rows($report_class_query) > 0) {
						while($report_class_result = mysqli_fetch_assoc($report_class_query)) {
							$report_class_id = $report_class_result['id'];
							$report_class_stock_query = mysqli_query($conn, "SELECT sc.id FROM stocks AS s INNER JOIN stock_cleaning AS sc ON s.id=sc.parent_id WHERE s.stock_source='other_extension' && s.supplier_info='$report_extension' && s.extension_id='$extension_id' && s.activity_session='$activity_session' && s.type='$stock_type' && s.variety='$report_variety_id' && s.class='$report_class_id' && s.active_status='1' && sc.active_status='1' && s.delete_status='0' && sc.delete_status='0'");
							$total_cell_class_qty = 0;
							echo "<td class='align-middle text-center'>";
							if(mysqli_num_rows($report_class_stock_query) > 0) {
								while($report_class_stock_result = mysqli_fetch_assoc($report_class_stock_query)) {
									$total_cell_class_qty += stock_cleaning_meta($report_class_stock_result['id'], 'grade_1');
									$total_row_small_grains += stock_cleaning_meta($report_class_stock_result['id'], 'small_grains');
									$total_row_gundi += stock_cleaning_meta($report_class_stock_result['id'], 'gundi');
									$total_row_straw += stock_cleaning_meta($report_class_stock_result['id'], 'straw');
									$total_row_dust += stock_cleaning_meta($report_class_stock_result['id'], 'dust');
									$total_row_other += stock_cleaning_meta($report_class_stock_result['id'], 'other');
								}
								$total_row_class_qty += $total_cell_class_qty;
								echo $total_cell_class_qty;
							} else {
								echo "0";
							}
							echo "</td>";
						}
					}

					echo "<td class='align-middle text-center'>".$total_row_small_grains."</td>";
					echo "<td class='align-middle text-center'>".$total_row_gundi."</td>";
					echo "<td class='align-middle text-center'>".$total_row_straw."</td>";
					echo "<td class='align-middle text-center'>".$total_row_dust."</td>";
					echo "<td class='align-middle text-center'>".$total_row_other."</td>";
					echo "<td class='align-middle text-center'>";
					if($total_row_processing_qty != 0) {
						echo floor(($total_row_class_qty * 100) / $total_row_processing_qty);
					} else {
						echo 0;
					}
					echo "</td>";
					
					?>
					</tr>
					<?php
					$total_table_small_grains += $total_row_small_grains;
					$total_table_gundi += $total_row_gundi;
					$total_table_straw += $total_row_straw;
					$total_table_dust += $total_row_dust;
					$total_table_other += $total_row_other;
				}
			}

			?>

			<?php

			// $total_variety_query = mysqli_query($conn, "SELECT id FROM stock_variety WHERE stock_type_id='$stock_type' && active_status='1' && delete_status='0'");
			$total_variety_query = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN stock_cleaning AS sc ON sv.id=s.variety && s.id=sc.parent_id WHERE s.stock_source='other_extension' && s.supplier_info='$report_extension' && s.extension_id='$extension_id' && s.type='$stock_type' && s.activity_session='$activity_session' && sv.stock_type_id='$stock_type' && sv.active_status='1' && s.active_status='1' && sc.active_status='1' && sv.delete_status='0' && s.delete_status='0' && sc.delete_status='0' GROUP BY s.variety");
			if(mysqli_num_rows($total_variety_query) > 0) {
				?>
				<tr>
					<td colspan="2" class="align-middle text-center"><b>Total in KGs</b></td>
					<td class="align-middle text-center"><b><?= $total_table_processing_qty; ?></b></td>
				<?php
				while($total_variety_result = mysqli_fetch_assoc($total_variety_query)) {
					$total_variety_id = $total_variety_result['id'];

					$total_class_query = mysqli_query($conn, "SELECT id FROM stock_class WHERE active_status='1' && delete_status='0'");
					if(mysqli_num_rows($total_class_query) > 0) {
						while($total_class_result = mysqli_fetch_assoc($total_class_query)) {
							$total_class_id = $total_class_result['id'];
							$total_class_stock_query = mysqli_query($conn, "SELECT sc.id FROM stocks AS s INNER JOIN stock_cleaning AS sc ON s.id=sc.parent_id WHERE s.stock_source='other_extension' && s.supplier_info='$report_extension' && s.extension_id='$extension_id' && s.activity_session='$activity_session' && s.type='$stock_type' && s.variety='$total_variety_id' && s.class='$total_class_id' && s.active_status='1' && sc.active_status='1' && s.delete_status='0' && sc.delete_status='0'");
							if(mysqli_num_rows($total_class_stock_query) > 0) {
								$total_class_qty = 0;
								while($total_class_stock_result = mysqli_fetch_assoc($total_class_stock_query)) {
									if(empty($total_table_class_qty[$total_class_result['id']])) {
										$total_table_class_qty[$total_class_result['id']] = stock_cleaning_meta($total_class_stock_result['id'], 'grade_1');
									} else {
										$total_table_class_qty[$total_class_result['id']] += stock_cleaning_meta($total_class_stock_result['id'], 'grade_1');
									}
								}
							} else {
								if(empty($total_table_class_qty[$total_class_result['id']])) {
									$total_table_class_qty[$total_class_result['id']] = 0;
								} else {
									$total_table_class_qty[$total_class_result['id']] += 0;
								}
							}
						}
					}

				}

				foreach($total_table_class_qty as $total_table_class_value) {
					echo "<td class='align-middle text-center'><b>";
					echo $total_table_class_value;
					echo "</b></td>";
					$total_table_grade_1 += $total_table_class_value;
				}
				?>
					<td class="align-middle text-center"><b><?= $total_table_small_grains ?></b></td>
					<td class="align-middle text-center"><b><?= $total_table_gundi ?></b></td>
					<td class="align-middle text-center"><b><?= $total_table_straw ?></b></td>
					<td class="align-middle text-center"><b><?= $total_table_dust ?></b></td>
					<td class="align-middle text-center"><b><?= $total_table_other ?></b></td>
					<td class="align-middle text-center"><b>
					<?php
					if($total_table_processing_qty != 0) {
						echo floor(($total_table_grade_1 * 100) / $total_table_processing_qty);
					} else {
						echo 0;
					}
					?>
					</b></td>
				</tr>
				<tr>
					<td colspan="2" class="align-middle text-center"><b>Total in Monds</td>
					<td class="align-middle text-center"><b><?= ($total_table_processing_qty / 50); ?></td>
					<?php

					foreach($total_table_class_qty as $total_table_class_value) {
						echo "<td class='align-middle text-center'><b>".($total_table_class_value / 50)."</b></td>";
					}

					?>
					<td class="align-middle text-center"><b><?= ($total_table_small_grains / 50); ?></b></td>
					<td class="align-middle text-center"><b><?= ($total_table_gundi / 50); ?></b></td>
					<td class="align-middle text-center"><b><?= ($total_table_straw / 50); ?></b></td>
					<td class="align-middle text-center"><b><?= ($total_table_dust / 50); ?></b></td>
					<td class="align-middle text-center"><b><?= ($total_table_other / 50); ?></b></td>
					<td class="align-middle text-center"><b>
						<?php

						if($total_table_processing_qty != 0) {
							echo floor(($total_table_grade_1 * 100) / $total_table_processing_qty);
						} else {
							echo 0;
						}

						?>
					</b></td>
				</tr>
				<?php
			}

			?>

		</tbody>
	</table>
</div>