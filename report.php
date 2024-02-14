<?php

if($report_type == 'cleaning_report_of_current_circle') {

?>
<table class="table table-bordered table-sm">
	<thead>
		<tr>
			<td colspan="13" class="text-center">
				<h3><b>VARIETY WISE SEED CLEANING REPORT <?php echo circle_name($report_circle).' '.activity_season_title($activity_season); ?></b></h3>
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

		$report_variety_query = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN stock_cleaning AS sc ON sv.id=s.variety && s.id=sc.parent_id WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && s.activity_season='$activity_season' && sv.stock_crop_id='$stock_crop' && sv.active_status='1' && s.active_status='1' && sc.active_status='1' && sv.delete_status='0' && s.delete_status='0' && sc.delete_status='0' GROUP BY s.variety");
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
				$report_stock_query = mysqli_query($conn, "SELECT s.class, sc.id, SUM(sc.processing_qty) AS processing_qty FROM stocks AS s INNER JOIN stock_cleaning AS sc ON s.id=sc.parent_id WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && s.activity_season='$activity_season' && s.variety='$report_variety_id' && s.active_status='1' && sc.active_status='1' && s.delete_status='0' && sc.delete_status='0'");
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
						$report_class_stock_query = mysqli_query($conn, "SELECT sc.id FROM stocks AS s INNER JOIN stock_cleaning AS sc ON s.id=sc.parent_id WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && s.activity_season='$activity_season' && s.variety='$report_variety_id' && s.class='$report_class_id' && s.active_status='1' && sc.active_status='1' && s.delete_status='0' && sc.delete_status='0'");
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
		<tr>
			<td colspan="2" class="align-middle text-center"><b>Total in KGs</b></td>
			<td class="align-middle text-center"><b><?= $total_table_processing_qty; ?></b></td>
			<?php

			$total_variety_query = mysqli_query($conn, "SELECT id FROM stock_variety WHERE stock_crop_id='$stock_crop' && active_status='1' && delete_status='0'");
			if(mysqli_num_rows($total_variety_query) > 0) {
				while($total_variety_result = mysqli_fetch_assoc($total_variety_query)) {
					$total_variety_id = $total_variety_result['id'];

					$total_class_query = mysqli_query($conn, "SELECT id FROM stock_class WHERE active_status='1' && delete_status='0'");
					if(mysqli_num_rows($total_class_query) > 0) {
						while($total_class_result = mysqli_fetch_assoc($total_class_query)) {
							$total_class_id = $total_class_result['id'];
							$total_class_stock_query = mysqli_query($conn, "SELECT sc.id FROM stocks AS s INNER JOIN stock_cleaning AS sc ON s.id=sc.parent_id WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && s.activity_season='$activity_season' && s.variety='$total_variety_id' && s.class='$total_class_id' && s.active_status='1' && sc.active_status='1' && s.delete_status='0' && sc.delete_status='0'");
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
			<td colspan="2" class="align-middle text-center"><b>Total in Monds</b></td>
			<td class="align-middle text-center"><b><?= ($total_table_processing_qty / 50); ?></b></td>
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

	</tbody>
</table>
<?php

} else if($report_type == 'cleaning_report_of_other_circle') {

?>
<div class="table-responsive">
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<td colspan="13" class="text-center">
					<h4><b>VARIETY WISE SEED CLEANING REPORT AT <?php echo circle_name($circle_id).' FROM '.circle_name($report_circle).' '.activity_season_title($activity_season); ?></b></h4>
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

			$report_variety_query = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN stock_cleaning AS sc ON sv.id=s.variety && s.id=sc.parent_id WHERE s.stock_source='other_circle' && s.supplier_info='$report_circle' && s.circle_id='$circle_id' && s.crop='$stock_crop' && s.activity_season='$activity_season' && sv.stock_crop_id='$stock_crop' && sv.delete_status='0' && s.delete_status='0' && sc.delete_status='0' GROUP BY s.variety");
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
					$report_stock_query = mysqli_query($conn, "SELECT s.class, sc.id, SUM(sc.processing_qty) AS processing_qty FROM stocks AS s INNER JOIN stock_cleaning AS sc ON s.id=sc.parent_id WHERE s.stock_source='other_circle' && s.supplier_info='$report_circle' && s.circle_id='$circle_id' && s.activity_season='$activity_season' && s.crop='$stock_crop' && s.variety='$report_variety_id' && s.active_status='1' && sc.active_status='1' && s.delete_status='0' && sc.delete_status='0'");
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
							$report_class_stock_query = mysqli_query($conn, "SELECT sc.id FROM stocks AS s INNER JOIN stock_cleaning AS sc ON s.id=sc.parent_id WHERE s.stock_source='other_circle' && s.supplier_info='$report_circle' && s.circle_id='$circle_id' && s.activity_season='$activity_season' && s.crop='$stock_crop' && s.variety='$report_variety_id' && s.class='$report_class_id' && s.active_status='1' && sc.active_status='1' && s.delete_status='0' && sc.delete_status='0'");
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

			$total_variety_query = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN stock_cleaning AS sc ON sv.id=s.variety && s.id=sc.parent_id WHERE s.stock_source='other_circle' && s.supplier_info='$report_circle' && s.circle_id='$circle_id' && s.crop='$stock_crop' && s.activity_season='$activity_season' && sv.stock_crop_id='$stock_crop' && sv.active_status='1' && s.active_status='1' && sc.active_status='1' && sv.delete_status='0' && s.delete_status='0' && sc.delete_status='0' GROUP BY s.variety");
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
							$total_class_stock_query = mysqli_query($conn, "SELECT sc.id FROM stocks AS s INNER JOIN stock_cleaning AS sc ON s.id=sc.parent_id WHERE s.stock_source='other_circle' && s.supplier_info='$report_circle' && s.circle_id='$circle_id' && s.activity_season='$activity_season' && s.crop='$stock_crop' && s.variety='$total_variety_id' && s.class='$total_class_id' && s.active_status='1' && sc.active_status='1' && s.delete_status='0' && sc.delete_status='0'");
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
<?php

} else if($report_type == 'variety_wise_cleaning_report_at_current_circle') {

?>
<div class="table-responsive">
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<td colspan="15" class="align-middle text-center">
					<h4><b>VARIETY WISE CLEANING REPORT OF <?php echo stock_crop($stock_crop); ?> STOCK AT <?php echo circle_name($report_circle).' '.activity_season_title($activity_season); ?></b></h4>
					<span>Weight in <?= $stock_weight; ?></span>
				</td>
			</tr>
			<tr>
				<td rowspan="2" class="align-middle text-center"><b>Sr.No</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Name of Variety</b></td>
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
			$variety_query_1 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN stock_cleaning AS sc ON sv.id=s.variety && s.id=sc.parent_id WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && sv.stock_crop_id='$stock_crop' && s.activity_season='$activity_season' GROUP BY s.variety");
			if(mysqli_num_rows($variety_query_1) > 0) {
				$i = 1;
				while($variety_result_1 = mysqli_fetch_assoc($variety_query_1)) {
					$variety_id_1 = $variety_result_1['id'];
					$total_row_processing_qty = 0;
					$total_row_class_qty = 0;
					$total_row_small_grains = 0;
					$total_row_gundi = 0;
					$total_row_straw = 0;
					$total_row_dust = 0;
					$total_row_other = 0;
					echo "<tr>";
					echo "<td class='align-middle text-center'>".$i++."</td>";
					echo "<td class='align-middle text-center'>".$variety_result_1['variety']."</td>";
					$stock_cleaning_query_1 = mysqli_query($conn, "SELECT sc.id, SUM(sc.processing_qty) AS processing_qty FROM stock_cleaning AS sc INNER JOIN stocks AS s ON sc.parent_id=s.id && s.circle_id='$report_circle' && s.crop='$stock_crop' && s.variety='$variety_id_1' && s.activity_season='$activity_season' GROUP BY s.variety");
					if(mysqli_num_rows($stock_cleaning_query_1) > 0) {
						$stock_cleaning_result_1 = mysqli_fetch_assoc($stock_cleaning_query_1);
						echo "<td class='align-middle text-center'>".$stock_cleaning_result_1['processing_qty']."</td>";
						$total_row_processing_qty += $stock_cleaning_result_1['processing_qty'];
					} else {
						echo "<td class='align-middle text-center'>0</td>";
					}

					$stock_class_query_2 = mysqli_query($conn, "SELECT * FROM stock_class WHERE active_status='1' && delete_status='0'");
					if(mysqli_num_rows($stock_class_query_2) > 0) {
						$j = 0;
						while($stock_class_result_2 = mysqli_fetch_assoc($stock_class_query_2)) {
							$stock_class_2 = $stock_class_result_2['id'];
							$stock_cleaning_query_2 = mysqli_query($conn, "SELECT sc.id FROM stock_cleaning AS sc INNER JOIN stocks AS s ON sc.parent_id=s.id && s.circle_id='$report_circle' && s.crop='$stock_crop' && s.variety='$variety_id_1' && s.class='$stock_class_2' && s.activity_season='$activity_season' GROUP BY s.variety");
							$total_cell_class_qty = 0;
							if(mysqli_num_rows($stock_cleaning_query_2) > 0) {
								while($stock_cleaning_result_2 = mysqli_fetch_assoc($stock_cleaning_query_2)) {
									$total_cell_class_qty += stock_cleaning_meta($stock_cleaning_result_2['id'], 'grade_1');
									$total_row_small_grains += stock_cleaning_meta($stock_cleaning_result_2['id'], 'small_grains');
									$total_row_gundi += stock_cleaning_meta($stock_cleaning_result_2['id'], 'gundi');
									$total_row_straw += stock_cleaning_meta($stock_cleaning_result_2['id'], 'straw');
									$total_row_dust += stock_cleaning_meta($stock_cleaning_result_2['id'], 'dust');
									$total_row_other += stock_cleaning_meta($stock_cleaning_result_2['id'], 'other');
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
						echo "<td class='align-middle text-center'>".round(($total_row_class_qty * 100 /$total_row_processing_qty), 2)."</td>";
					} else {
						echo "<td class='align-middle text-center'>0</td>";
					}

					$total_table_processing_qty += $total_row_processing_qty;
					$total_table_small_grains += $total_row_small_grains;
					$total_table_gundi += $total_row_gundi;
					$total_table_straw += $total_row_straw;
					$total_table_dust += $total_row_dust;
					$total_table_other += $total_row_other;
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
<?php

} else if($report_type == 'district_wise_cleaning_report') {

?>
<div class="table-responsive">
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<td colspan="15" class="align-middle text-center">
					<h4><b>DISTRICT WISE CLEANING REPORT OF <?php echo stock_crop($stock_crop); ?> STOCK AT <?php echo circle_name($report_circle).' '.activity_season_title($activity_season); ?></b></h4>
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
			$district_query_1 = mysqli_query($conn, "SELECT c.id, c.name FROM configurations AS c INNER JOIN circles AS e INNER JOIN stocks AS s ON c.id=e.district && e.id=s.supplier_info WHERE c.type='district' && e.district!='$user_district' && s.stock_source='other_circle' && s.crop='$stock_crop' && s.activity_season='$activity_season' && c.active_status='1' && e.active_status='1' && s.active_status='1' && c.delete_status='0' && e.delete_status='0' && s.delete_status='0' GROUP BY c.name");
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
					/*$stock_query_1 = mysqli_query($conn, "SELECT SUM(sc.processing_qty) AS processing_qty FROM stock_cleaning AS sc INNER JOIN stocks AS s INNER JOIN circles AS e ON sc.parent_id=s.id && s.supplier_info=e.id WHERE s.stock_source='other_circle' && e.district='$district_id_1' && s.circle_id='$report_circle' && sc.active_status='1' && s.active_status='1' && e.active_status='1' && sc.delete_status='0' && s.delete_status='0' && e.delete_status='0' GROUP BY e.district");
					if(mysqli_num_rows($stock_query_1) > 0) {
						$stock_result_1 = mysqli_fetch_assoc($stock_query_1);
						echo "<td>".$stock_result_1['processing_qty']."</td>";
					} else {
						echo "<td>0</td>";
					}*/
					$stock_query_1 = mysqli_query($conn, "SELECT sc.id, sc.processing_qty FROM stock_cleaning AS sc INNER JOIN stocks AS s INNER JOIN circles AS e ON sc.parent_id=s.id && s.supplier_info=e.id WHERE s.stock_source='other_circle' && e.district='$district_id_1' && s.circle_id='$report_circle' && s.crop='$stock_crop' && s.activity_season='$activity_season' && sc.active_status='1' && s.active_status='1' && e.active_status='1' && sc.delete_status='0' && s.delete_status='0' && e.delete_status='0'");
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
							$stock_query_2 = mysqli_query($conn, "SELECT sc.id FROM stock_cleaning AS sc INNER JOIN stocks AS s INNER JOIN circles AS e ON sc.parent_id=s.id && s.supplier_info=e.id WHERE s.class='$stock_class_2' && s.crop='$stock_crop' && s.activity_season='$activity_season' && s.stock_source='other_circle' && e.district='$district_id_1' && s.circle_id='$report_circle' && sc.active_status='1' && s.active_status='1' && e.active_status='1' && sc.delete_status='0' && s.delete_status='0' && e.delete_status='0'");
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
<?php

} else if($report_type == 'variety_wise_and_class_wise_stock_at_current_circle') {

?>
<div class="table-responsive">
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<td colspan="15" class="align-middle text-center">
					<h4><b>TOTAL VARIETY WISE AND CLASS WISE <?php echo stock_crop($stock_crop); ?> STOCK AT <?php echo circle_name($report_circle).' '.activity_season_title($activity_season); ?></b></h4>
					<span>Weight in <?= $stock_weight; ?></span>
				</td>
			</tr>
			<tr>
				<td rowspan="2" class="align-middle text-center"><b>S.No</b></td>
				<?php

				$variety_query_1 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s ON sv.id=s.variety WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && sv.stock_crop_id='$stock_crop' && s.activity_season='$activity_season' GROUP BY s.variety");
				if(mysqli_num_rows($variety_query_1) > 0) {
					while($variety_result_1 = mysqli_fetch_assoc($variety_query_1)) {
						$variety_id_1 = $variety_result_1['id'];
						$variety_class_query_1 = mysqli_query($conn, "SELECT sc.id FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && s.activity_season='$activity_season' && s.variety='$variety_id_1' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
						echo "<td colspan='".mysqli_num_rows($variety_class_query_1)."' class='align-middle text-center'>".$variety_result_1['variety']."</td>";
					}
				}

				?>
				<td rowspan="2" class="align-middle text-center"><b>Total</b></td>
			</tr>
			<tr>
				<?php

				$variety_query_2 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s ON sv.id=s.variety WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && sv.stock_crop_id='$stock_crop' && s.activity_season='$activity_season' GROUP BY s.variety");

				if(mysqli_num_rows($variety_query_2) > 0) {
					while($variety_result_2 = mysqli_fetch_assoc($variety_query_2)) {
						$variety_id_2 = $variety_result_2['id'];
						$variety_class_query_2 = mysqli_query($conn, "SELECT sc.class_name FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.circle_id='$report_circle' && s.activity_season='$activity_season' && s.crop='$stock_crop' && s.variety='$variety_id_2' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
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

			$variety_query_3 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s ON sv.id=s.variety WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && sv.stock_crop_id='$stock_crop' && s.activity_season='$activity_season' GROUP BY s.variety");

			$total_row_qty = 0;
			if(mysqli_num_rows($variety_query_3) > 0) {
				while($variety_result_3 = mysqli_fetch_assoc($variety_query_3)) {
					$variety_id_3 = $variety_result_3['id'];
					$variety_class_query_3 = mysqli_query($conn, "SELECT sc.id, sc.class_name FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.circle_id='$report_circle' && s.activity_season='$activity_season' && s.crop='$stock_crop' && s.variety='$variety_id_3' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
					if(mysqli_num_rows($variety_class_query_3) > 0) {
						while($variety_class_result_3 = mysqli_fetch_assoc($variety_class_query_3)) {
							$variety_class_id_3 = $variety_class_result_3['id'];
							$stock_query = mysqli_query($conn, "SELECT SUM(st.stock_qty) AS stock_qty FROM stock_transactions AS st INNER JOIN stocks AS s ON st.stock_id=s.id WHERE s.activity_season='$activity_season' && s.variety='$variety_id_3' && s.class='$variety_class_id_3' && s.active_status='1' && st.active_status!='0' && s.delete_status='0' && st.delete_status='0' GROUP BY st.stock_id");
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
<?php

} else if($report_type == 'current_circle_to_other_district') {

?>
<div class="table-responsive">
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<td colspan="100" class="align-middle text-center">
					<h4><b><?= stock_crop($stock_crop); ?> OUT DISTRICT ISSUE FROM <?= circle_name($circle_id); ?></b></h4>
					<span>Weight in <?= $stock_weight; ?></span>
				</td>
			</tr>
			<tr>
				<td rowspan="2" class="align-middle text-center"><b>S.No</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Name of District</b></td>
				<?php

				$variety_query_1 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN supply AS su INNER JOIN circles AS e ON sv.id=s.variety && s.id=su.parent_id && e.id=su.receiver_detail WHERE e.district!='$user_district' && s.crop='$stock_crop' && s.activity_season='$activity_season' && sv.active_status='1' && s.active_status='1' && su.active_status='1' && e.active_status='1' && sv.delete_status='0' && s.delete_status='0' && su.delete_status='0' && e.delete_status='0' GROUP BY s.variety");

				if(mysqli_num_rows($variety_query_1) > 0) {
					while($head_variety_result_1 = mysqli_fetch_assoc($variety_query_1)) {
						$head_variety_id_1 = $head_variety_result_1['id'];
						$head_variety_class_query_1 = mysqli_query($conn, "SELECT sc.id FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && s.variety='$head_variety_id_1' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
						echo "<td class='align-middle text-center' colspan='".mysqli_num_rows($head_variety_class_query_1)."'><b>".$head_variety_result_1['variety']."</b></td>";
					}
				}

				?>
				<td rowspan="2" class="align-middle text-center"><b>Total</b></td>
			</tr>
			<tr>
				<?php

				$variety_query_2 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN supply AS su INNER JOIN circles AS e ON sv.id=s.variety && s.id=su.parent_id && e.id=su.receiver_detail WHERE e.district!='$user_district' && s.crop='$stock_crop' && s.activity_season='$activity_season' && sv.active_status='1' && s.active_status='1' && su.active_status='1' && e.active_status='1' && sv.delete_status='0' && s.delete_status='0' && su.delete_status='0' && e.delete_status='0' GROUP BY s.variety");

				if(mysqli_num_rows($variety_query_2) > 0) {
					while($head_variety_result_2 = mysqli_fetch_assoc($variety_query_2)) {
						$head_variety_id_2 = $head_variety_result_2['id'];
						$head_variety_class_query_2 = mysqli_query($conn, "SELECT sc.class_name FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && s.variety='$head_variety_id_2' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
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
			$district_query = mysqli_query($conn, "SELECT c.id, c.name FROM configurations AS c INNER JOIN circles AS e INNER JOIN supply AS s ON c.id=e.district && e.id=s.receiver_detail WHERE c.type='district' && e.district!='$user_district' && c.active_status='1' && e.active_status='1' && s.active_status='1' && c.delete_status='0' && e.delete_status='0' && s.delete_status='0' GROUP BY c.id");

			if(mysqli_num_rows($district_query) > 0) {
				$i = 1;
				while($district_result = mysqli_fetch_assoc($district_query)) {
					$district_id = $district_result['id'];
					$total_row_qty = 0;
					echo "<tr>";
					echo "<td class='align-middle text-center'>".$i++."</td>";
					echo "<td class='align-middle text-center'>".$district_result['name']."</td>";
					$variety_query_3 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN supply AS su INNER JOIN circles AS e ON sv.id=s.variety && s.id=su.parent_id && e.id=su.receiver_detail WHERE e.district!='$user_district' && s.crop='$stock_crop' && s.activity_season='$activity_season' && sv.active_status='1' && s.active_status='1' && su.active_status='1' && e.active_status='1' && sv.delete_status='0' && s.delete_status='0' && su.delete_status='0' && e.delete_status='0' GROUP BY s.variety");

					if(mysqli_num_rows($variety_query_3) > 0) {
						$j = 0;
						while($head_variety_result_3 = mysqli_fetch_assoc($variety_query_3)) {
							$head_variety_id_3 = $head_variety_result_3['id'];
							$head_variety_class_query_3 = mysqli_query($conn, "SELECT sc.id, sc.class_name FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && s.activity_season='$activity_season' && s.variety='$head_variety_id_3' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
							if(mysqli_num_rows($head_variety_class_query_3) > 0) {
								while($head_variety_class_result_3 = mysqli_fetch_assoc($head_variety_class_query_3)) {
									$k = 0;
									$head_variety_class_id = $head_variety_class_result_3['id'];
									$total_cell_qty = 0;
									$supply_query = mysqli_query($conn, "SELECT su.stock_qty FROM supply AS su INNER JOIN stocks AS s INNER JOIN circles AS e ON su.parent_id=s.id && su.receiver_detail=e.id WHERE su.circle_id='$report_circle' && s.crop='$stock_crop' && s.activity_season='$activity_season' && s.variety='$head_variety_id_3' && s.class='$head_variety_class_id' && su.receive_source='other_circle' && e.district='$district_id'");
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
<?php

} else if($report_type == 'current_circle_to_within_district') {

?>
<div class="table-responsive">
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<td colspan="100" class="align-middle text-center">
					<h4><b><?= stock_crop($stock_crop); ?> WITH IN DISTRICT ISSUE FROM <?= circle_name($circle_id); ?></b></h4>
					<span>Weight in <?= $stock_weight; ?></span>
				</td>
			</tr>
			<tr>
				<td rowspan="2" class="align-middle text-center"><b>S.No</b></td>
				<td rowspan="2" class="align-middle text-center"><b>Name of circle</b></td>
				<?php

				$variety_query_1 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN supply AS su INNER JOIN circles AS e ON sv.id=s.variety && s.id=su.parent_id && e.id=su.receiver_detail WHERE e.district='$user_district' && s.crop='$stock_crop' && s.activity_season='$activity_season' && sv.active_status='1' && s.active_status='1' && su.active_status='1' && e.active_status='1' && sv.delete_status='0' && s.delete_status='0' && su.delete_status='0' && e.delete_status='0' GROUP BY s.variety");

				if(mysqli_num_rows($variety_query_1) > 0) {
					while($head_variety_result_1 = mysqli_fetch_assoc($variety_query_1)) {
						$head_variety_id_1 = $head_variety_result_1['id'];
						$head_variety_class_query_1 = mysqli_query($conn, "SELECT sc.id FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && s.activity_season='$activity_season' && s.variety='$head_variety_id_1' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
						echo "<td class='align-middle text-center' colspan='".mysqli_num_rows($head_variety_class_query_1)."'><b>".$head_variety_result_1['variety']."</b></td>";
					}
				}

				?>
				<td rowspan="2" class="align-middle text-center"><b>Total</b></td>
			</tr>
			<tr>
				<?php

				$variety_query_2 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN supply AS su INNER JOIN circles AS e ON sv.id=s.variety && s.id=su.parent_id && e.id=su.receiver_detail WHERE e.district='$user_district' && s.crop='$stock_crop' && s.activity_season='$activity_season' && sv.active_status='1' && s.active_status='1' && su.active_status='1' && e.active_status='1' && sv.delete_status='0' && s.delete_status='0' && su.delete_status='0' && e.delete_status='0' GROUP BY s.variety");

				if(mysqli_num_rows($variety_query_2) > 0) {
					while($head_variety_result_2 = mysqli_fetch_assoc($variety_query_2)) {
						$head_variety_id_2 = $head_variety_result_2['id'];
						$head_variety_class_query_2 = mysqli_query($conn, "SELECT sc.class_name FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && s.activity_season='$activity_season' && s.variety='$head_variety_id_2' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
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
			$district_query = mysqli_query($conn, "SELECT c.id, s.receiver_detail FROM configurations AS c INNER JOIN circles AS e INNER JOIN supply AS s ON c.id=e.district && e.id=s.receiver_detail WHERE c.type='district' && e.district='$user_district' && c.active_status='1' && e.active_status='1' && s.active_status='1' && c.delete_status='0' && e.delete_status='0' && s.delete_status='0' GROUP BY c.id");

			if(mysqli_num_rows($district_query) > 0) {
				$i = 1;
				while($district_result = mysqli_fetch_assoc($district_query)) {
					$district_id = $district_result['id'];
					$total_row_qty = 0;
					echo "<tr>";
					echo "<td class='align-middle text-center'>".$i++."</td>";
					// echo "<td class='align-middle text-center'>".$district_result['name']."</td>";
					echo "<td class='align-middle text-center'>".circle_name($district_result['receiver_detail'])."</td>";
					$variety_query_3 = mysqli_query($conn, "SELECT sv.id, sv.variety FROM stock_variety AS sv INNER JOIN stocks AS s INNER JOIN supply AS su INNER JOIN circles AS e ON sv.id=s.variety && s.id=su.parent_id && e.id=su.receiver_detail WHERE e.district='$user_district' && s.crop='$stock_crop' && s.activity_season='$activity_season' && sv.active_status='1' && s.active_status='1' && su.active_status='1' && e.active_status='1' && sv.delete_status='0' && s.delete_status='0' && su.delete_status='0' && e.delete_status='0' GROUP BY s.variety");

					if(mysqli_num_rows($variety_query_3) > 0) {
						$j = 0;
						while($head_variety_result_3 = mysqli_fetch_assoc($variety_query_3)) {
							$head_variety_id_3 = $head_variety_result_3['id'];
							$head_variety_class_query_3 = mysqli_query($conn, "SELECT sc.id, sc.class_name FROM stock_class AS sc LEFT JOIN stocks AS s ON sc.id=s.class WHERE s.circle_id='$report_circle' && s.crop='$stock_crop' && s.activity_season='$activity_season' && s.variety='$head_variety_id_3' && sc.active_status='1' && s.active_status='1' && sc.delete_status='0' && s.delete_status='0' GROUP BY s.class");
							if(mysqli_num_rows($head_variety_class_query_3) > 0) {
								while($head_variety_class_result_3 = mysqli_fetch_assoc($head_variety_class_query_3)) {
									$k = 0;
									$head_variety_class_id = $head_variety_class_result_3['id'];
									$total_cell_qty = 0;
									$supply_query = mysqli_query($conn, "SELECT su.stock_qty FROM supply AS su INNER JOIN stocks AS s INNER JOIN circles AS e ON su.parent_id=s.id && su.receiver_detail=e.id WHERE su.circle_id='$report_circle' && s.crop='$stock_crop' && s.activity_season='$activity_season' && s.variety='$head_variety_id_3' && s.class='$head_variety_class_id' && su.receive_source='other_circle' && e.district='$district_id'");
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
<?php

} else if($report_type == 'grand_total_at_current_circle') {

?>
<div class="table-responsive">
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<td colspan="15" class="align-middle text-center">
					<h4><b>GRAND TOTAL OF <?php echo stock_crop($stock_crop); ?> STOCK AT <?php echo circle_name($report_circle).' '.activity_season_title($activity_season); ?></b></h4>
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
						$stock_query_1 = mysqli_query($conn, "SELECT SUM(st.stock_qty) as stock_qty FROM stocks AS s INNER JOIN stock_transactions AS st ON s.id=st.stock_id WHERE st.circle_id='$report_circle' && st.class='$stock_class_id_2' && s.crop='$stock_crop' && s.activity_season='$activity_season' && s.active_status='1' && st.active_status!='0' && s.delete_status='0' && st.delete_status='0' GROUP BY st.class");
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
						$stock_query_1 = mysqli_query($conn, "SELECT SUM(st.stock_qty) as stock_qty FROM stocks AS s INNER JOIN stock_transactions AS st ON s.id=st.stock_id WHERE s.circle_id='$report_circle' && st.class='$stock_class_id_2' && s.crop='$stock_crop' && s.activity_season='$activity_season' && s.active_status='1' && st.active_status='1' && s.delete_status='0' && st.delete_status='0' GROUP BY s.class");
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
<?php

}




?>