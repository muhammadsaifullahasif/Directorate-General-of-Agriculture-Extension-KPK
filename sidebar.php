<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="index3.html" class="brand-link">
		<img src="dist/img/DG_Agriculture_Extension_Icon.png" alt="Directorate of Agriculture Extension" class="brand-image img-circle elevation-3" style="opacity: .8">
		<span class="brand-text font-weight-light">Directorate of Agriculture Extension</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="<?php echo user_profile_image($user_id); ?>" class="img-circle elevation-2" alt="User Image" style="width: 35px; height: 35px; object-fit: cover;">
			</div>
			<div class="info">
				<a href="#" class="d-block"><?php echo user_meta($user_id, 'first_name').' '.user_meta($user_id, 'last_name'); ?></a>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column nav-compact nav-child-indent nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
				<li class="nav-item">
					<a href="index.php" class="nav-link">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>Dashboard</p>
					</a>
				</li>
				<?php
				if(is_super_admin() || is_admin()) {
				?>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-building"></i>
						<p>Circles<i class="fas fa-angle-left right"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="circles.php" class="nav-link">
								<i class="far fa-building nav-icon"></i>
								<p>All Circles</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="circle-new.php" class="nav-link">
								<i class="fas fa-plus-square nav-icon"></i>
								<p>Add Circle</p>
							</a>
						</li>
					</ul>
				</li>
				<?php
				}
				if(is_super_admin() || is_admin() || is_manager()) {
				?>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-users"></i>
						<p>Users<i class="fas fa-angle-left right"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="users.php" class="nav-link">
								<i class="fas fa-users nav-icon"></i>
								<p>All Users</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="user-new.php" class="nav-link">
								<i class="fas fa-plus-square nav-icon"></i>
								<p>Add User</p>
							</a>
						</li>
					</ul>
				</li>
				<?php
				}
				?>
				<li class="nav-header">STOCK</li>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-cubes"></i>
						<p>Procurement<i class="fas fa-angle-left right"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="stock-available.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Available Stock</p>
							</a>
						</li>
						<?php
						if(is_manager() || is_storekeeper()) {
						?>
						<li class="nav-item">
							<a href="stock-new.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Stock</p>
							</a>
						</li>
						<?php
						}
						?>
						<li class="nav-item">
							<a href="stock.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>All Stock</p>
							</a>
						</li>
						<a href="stock-edit.php" class="d-none"></a>
						<a href="stock-detail.php" class="d-none"></a>
					</ul>
				</li>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-shipping-fast"></i>
						<p>SMP Detail<i class="fas fa-angle-left right"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<?php if(is_manager()) { ?>
						<li class="nav-item">
							<a href="stock-supply-new.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>New SMP / Distribution</p>
							</a>
						</li>
						<?php } ?>
						<li class="nav-item">
							<a href="smp-list.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>SMP List</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="smp-accepted-list.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>SMP Accepted List</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="smp-rejected-list.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>SMP Rejected List</p>
							</a>
						</li>
						<a href="supply-edit.php" class="d-none"></a>
					</ul>
				</li>
				<!-- <li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-shipping-fast"></i>
						<p>Supply<i class="fas fa-angle-left right"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="supply.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Supply</p>
							</a>
						</li>
						<?php if(is_super_admin() || is_admin() || is_manager()) { ?>
						<li class="nav-item">
							<a href="stock-supply-new.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Supply</p>
							</a>
						</li>
						<?php } ?>
						<a href="supply-edit.php" class="d-none"></a>
					</ul>
				</li> -->
				<!-- <li class="nav-item">
					<a href="stock-receiving.php" class="nav-link">
						<i class="nav-icon fas fa-dolly"></i>
						<p>Stock Receiving</p>
					</a>
				</li> -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<!-- <i class="nav-icon fas fa-card"></i> -->
						<i class="nav-icon fas fa-clipboard-list"></i>
						<p>Fumigation<i class="fas fa-angle-left right"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="stock-fumigation.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Under Fumigation</p>
							</a>
						</li>
						<?php if(is_manager() || is_storekeeper()) { ?>
						<li class="nav-item">
							<a href="stock-fumigation-new.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Fumigation</p>
							</a>
						</li>
						<?php } ?>
						<li class="nav-item">
							<a href="stock-fumigation-history.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Fumigation History</p>
							</a>
						</li>
						<a href="stock-fumigation-edit.php" class="d-none"></a>
					</ul>
				</li>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-industry"></i>
						<p>Cleaning<i class="fas fa-angle-left right"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="stock-cleaning.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Cleaning Stock</p>
							</a>
						</li>
						<?php if(is_manager() || is_storekeeper()) { ?>
						<li class="nav-item">
							<a href="stock-cleaning-new.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Cleaning</p>
							</a>
						</li>
						<?php } ?>
						<li class="nav-item">
							<a href="stock-cleaning-history.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Cleaning Stock History</p>
							</a>
						</li>
						<a href="stock-cleaning-edit.php" class="d-none"></a>
					</ul>
				</li>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-chart-bar"></i>
						<p>Reports<i class="fas fa-angle-left right"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<?php
						if(is_manager() || is_storekeeper()) {
						?>
						<li class="nav-item">
							<a href="supply-tags-demand.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Supply Tags Demand</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="reports.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Reports</p>
							</a>
						</li>
						<?php
						}
						?>
						<li class="nav-item">
							<a href="standing-crop-report.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Standing Crop Report</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="grain-sampling-report.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Grain Sampling Report</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="clean-sampling-report.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Clean Sampling Report</p>
							</a>
						</li>
						<a href="stock-cleaning-edit.php" class="d-none"></a>
					</ul>
				</li>
				<!-- <li class="nav-item">
					<a href="reports.php" class="nav-link">
						<i class="nav-icon fas fa-chart-bar"></i>
						<p>Reports</p>
					</a>
				</li> -->
				<?php
				if(is_super_admin() || is_admin() || is_manager()) {
				?>
				<!-- <li class="nav-item">
					<a href="account-book.php" class="nav-link">
						<i class="nav-icon fas fa-file-invoice-dollar"></i>
						<p>Account Book</p>
					</a>
				</li> -->
				<?php
				}
				?>
				<li class="nav-header">CONFIGURATIONS</li>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-cogs"></i>
						<p>Configuration<i class="fas fa-angle-left right"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<?php if(is_super_admin()) { ?>
						<li class="nav-item">
							<a href="crops.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Crops</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="variety.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Variety</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="class.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Class</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="districts.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Districts</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="prices.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Prices</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="activity-chart.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Activity Chart</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="activity-season.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Stock Activity Season</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="notifications.php" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Notifications</p>
							</a>
						</li>
						<?php } ?>
					</ul>
				</li>
				<li class="nav-item">
					<a href="change-password.php" class="nav-link">
						<i class="nav-icon fas fa-key"></i>
						<p>Change Password</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="logout.php" class="nav-link">
						<i class="nav-icon fas fa-sign-out-alt"></i>
						<p>Logout</p>
					</a>
				</li>
			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>