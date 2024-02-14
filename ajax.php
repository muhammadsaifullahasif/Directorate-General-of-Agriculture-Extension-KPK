<?php

require_once("function.php");


if(isset($_POST['action']) && $_POST['action'] == 'display_circles') {

	$output = '<option value="">Select AO Circle</option>';

	if(is_admin()) {
		$district_id = $user_district;
	} else {
		$district_id = validate($_POST['district_id']);
	}

	$query = mysqli_query($conn, "SELECT * FROM circles WHERE district='$district_id'");
	if(mysqli_num_rows($query) > 0) {
		while($result = mysqli_fetch_assoc($query)) {
			$output .= "<option value='".$result['id']."'>".$result['name']."</option>";
		}
	}

	echo $output;

}

if(isset($_POST['action']) && $_POST['action'] == 'display_out_district_circles') {

	$output = '<option value="">Select AO Circle</option>';

	$district_id = validate($_POST['district_id']);

	$query = mysqli_query($conn, "SELECT * FROM circles WHERE district='$district_id'");
	if(mysqli_num_rows($query) > 0) {
		while($result = mysqli_fetch_assoc($query)) {
			$output .= "<option value='".$result['id']."'>".$result['name']."</option>";
		}
	}

	echo $output;

}

if(isset($_POST['action']) && $_POST['action'] == 'get_farmer_info') {
	$output = '';

	$farmer_cnic = validate($_POST['farmer_cnic']);

	if($farmer_cnic != '') {
		$query = mysqli_query($conn, "SELECT farmer_cnic, farmer_name, farmer_mobile_number, farmer_address FROM farmers WHERE farmer_cnic='$farmer_cnic' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			echo json_encode(array_merge(array('status' => 'success'), $result));
		} else {
			echo json_encode(array('status' => 'error1'));
		}
	} else {
		echo json_encode(array('status' => 'error2'));
	}

}

if(isset($_POST['action']) && $_POST['action'] == 'get_smp_record') {

	$output = '';

	$farmer_cnic = validate($_POST['farmer_cnic']);
	$stock_source = validate($_POST['stock_source']);
	if($stock_source == 'from_farmer') {
		$stock_source = 'to_farmer';
	}

	if($farmer_cnic != '') {
		// $query = mysqli_query($conn, "SELECT s.id, s.parent_id FROM supply AS s INNER JOIN fscrd_report AS f ON s.id=f.stock_id WHERE (s.receive_source='to_farmer' || s.receive_source='others') && s.receiver_detail='$farmer_cnic' && f.report_status='1' && f.report_type='1' && s.active_status='1' && s.delete_status='0'");
		$query = mysqli_query($conn, "SELECT s.id, s.parent_id FROM supply AS s INNER JOIN fscrd_report AS f ON s.id=f.stock_id WHERE s.receive_source='$stock_source' && s.receiver_detail='$farmer_cnic' && f.report_status='1' && f.report_type='1' && s.active_status='1' && s.delete_status='0'");
		$output .= "<option value=''>Select SMP Record</option>";
		if(mysqli_num_rows($query) > 0) {
			while($result = mysqli_fetch_assoc($query)) {
				$output .= "<option value='".$result['id']."'>".stock_crop(stock_detail($result['parent_id'], 'crop')).' - '.stock_variety(stock_detail($result['parent_id'], 'variety'))."</option>";
			}
		}
	}

	echo $output;

}

if(isset($_POST['action']) && $_POST['action'] == 'get_smp_stock_crop') {

	$output = '';

	$smp_id = validate($_POST['smp_id']);
	$farmer_cnic = validate($_POST['farmer_cnic']);

	if($smp_id != '') {
		$query = mysqli_query($conn, "SELECT * FROM supply WHERE (receive_source='to_farmer' || receive_source='others') && receiver_detail='$farmer_cnic' && id='$smp_id' && active_status='1' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			$stock_crop_id = stock_detail($result['parent_id'], 'crop');
			if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stock_crop WHERE id='$stock_crop_id' && active_status='1' && delete_status='0'")) == 1) {
				$output .= "<option value='".$stock_crop_id."'>".stock_crop($stock_crop_id)."</option>";
			}
		}
	}

	echo $output;

}

if(isset($_POST['action']) && $_POST['action'] == 'get_smp_stock_variety') {

	$output = '';

	$smp_id = validate($_POST['smp_id']);
	$farmer_cnic = validate($_POST['farmer_cnic']);

	if($smp_id != '') {
		$query = mysqli_query($conn, "SELECT * FROM supply WHERE (receive_source='to_farmer' || receive_source='others') && receiver_detail='$farmer_cnic' && id='$smp_id' && active_status='1' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			$stock_crop_id = stock_detail($result['parent_id'], 'crop');
			$stock_variety_id = stock_detail($result['parent_id'], 'variety');
			if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stock_variety WHERE id='$stock_variety_id' && stock_crop_id='$stock_crop_id' && active_status='1' && delete_status='0'")) == 1) {
				$output .= "<option value='".$stock_variety_id."'>".stock_variety($stock_variety_id)."</option>";
			}
		}
	}

	echo $output;

}

if(isset($_POST['action']) && $_POST['action'] == 'get_available_stock_qty') {
	$output = '';

	$stock_id = validate($_POST['stock_id']);

	if($stock_id != '') {
		$query = mysqli_query($conn, "SELECT stock_qty FROM stocks WHERE id='$stock_id' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			echo json_encode(array_merge(array('status' => 'success'), $result));
		} else {
			echo json_encode(array('status' => 'error1'));
		}
	} else {
		echo json_encode(array('status' => 'error2'));
	}

}

if(isset($_POST['action']) && $_POST['action'] == 'check_lot_number') {

	$lot_number = validate($_POST['lot_number']);

	if($lot_number != '') {

		if(isset($_POST['id']) && $_POST['id'] != '' && $_POST['id'] != 0) {

			$id = validate($_POST['id']);

			if(mysqli_num_rows(mysqli_query($conn, "SELECT lot_number FROM stocks WHERE lot_number='$lot_number' && id!='$id' && delete_status='0'")) == 0) {
				echo 0;
			} else {
				echo 1;
			}

		} else {
			if(mysqli_num_rows(mysqli_query($conn, "SELECT lot_number FROM stocks WHERE lot_number='$lot_number' && delete_status='0'")) == 0) {
				echo 0;
			} else {
				echo 1;
			}
		}

	}

}

if(isset($_POST['action']) && $_POST['action'] == 'display_stock_variety') {

	$stock_crop = validate($_POST['stock_crop']);
	$output = '';

	if($stock_crop != '' && $stock_crop != 0) {

		$query = mysqli_query($conn, "SELECT * FROM stock_variety WHERE stock_crop_id='$stock_crop' && active_status='1' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			$output .= "<option value=''>Select Variety</option>";
			while($result = mysqli_fetch_assoc($query)) {
				$output .= "<option value='".$result['id']."'>".$result['variety']."</option>";
			}
		}

	}

	echo $output;

}

if(isset($_POST['action']) && $_POST['action'] == 'display_stock_price') {

	$key = validate($_POST['key']);
	$stock_crop = validate($_POST['stock_crop']);
	$stock_class = validate($_POST['stock_class']);

	if($key != '' && $stock_crop != '' && $stock_class != '') {

		$query = mysqli_query($conn, "SELECT $key FROM stock_price WHERE stock_crop_id='$stock_crop' && stock_class_id='$stock_class' && active_status='1' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			echo $result[$key];
		} else {
			echo 0;
		}

	} else {
		echo 0;
	}

}

if(isset($_POST['action']) && $_POST['action'] == 'total_unseen_notifications') {

	$user_id = validate($_POST['user_id']);
	$i = 0;
	if(is_manager()) {
		$sql = "SELECT id, content, notify_time FROM notifications WHERE ( (notify_user_id='0' || notify_user_id='$user_id') || (notify_circle_id='0' || notify_circle_id='$circle_id') ) && active_status='1' && delete_status='0'";
	} else {
		$sql = "SELECT id, content, notify_time FROM notifications WHERE (notify_user_id='0' || notify_user_id='$user_id') && active_status='1' && delete_status='0'";
	}
	$query = mysqli_query($conn, $sql);
	if(mysqli_num_rows($query) > 0) {
		while($result = mysqli_fetch_assoc($query)) {
			$notification_id = $result['id'];
			$seen_status = mysqli_query($conn, "SELECT * FROM notification_seen WHERE notify_id='$notification_id' && user_id='$user_id' && active_status='1' && delete_status='0'");
			if(mysqli_num_rows($seen_status) == 0) {
				$i++;
			}
		}
	}

	echo $i;
}

if(isset($_POST['action']) && $_POST['action'] == 'display_notifications') {

	$user_id = validate($_POST['user_id']);
	if(isset($_POST['limit']) && $_POST['limit'] != '' && $_POST['limit'] != 0) {
		$limit = validate($_POST['limit']);
		if(is_manager()) {
			$query = mysqli_query($conn, "SELECT id, content, notify_time FROM notifications WHERE ( (notify_user_id='0' || notify_user_id='$user_id') || (notify_circle_id='0' || notify_circle_id='$circle_id') ) && active_status='1' && delete_status='0' LIMIT $limit ORDER BY notify_time DESC");
		} else {
			$query = mysqli_query($conn, "SELECT id, content, notify_time FROM notifications WHERE (notify_user_id='0' || notify_user_id='$user_id') && active_status='1' && delete_status='0' LIMIT $limit ORDER BY notify_time DESC");
		}
	} else {
		if(is_manager()) {
			$query = mysqli_query($conn, "SELECT id, content, notify_time FROM notifications WHERE ( (notify_user_id='0' || notify_user_id='$user_id') || (notify_circle_id='0' || notify_circle_id='$circle_id') ) && active_status='1' && delete_status='0' ORDER BY notify_time DESC");
		} else {
			$query = mysqli_query($conn, "SELECT id, content, notify_time FROM notifications WHERE (notify_user_id='0' || notify_user_id='$user_id') && active_status='1' && delete_status='0' ORDER BY notify_time DESC");
		}
	}
	$output = '';
	if(mysqli_num_rows($query) > 0) {
		while($result = mysqli_fetch_assoc($query)) {
			$notification_id = $result['id'];
			$seen_status = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM notification_seen WHERE notify_id='$notification_id' && user_id='$user_id' && active_status='1' && delete_status='0'"));
			$output .= '<div class="dropdown-divider"></div>';
			$output .= '<div class="dropdown-item '.($seen_status == 0 ? 'bg-light' : 'pb-4').'">';
				$output .= '<p style="overflow-wrap: break-word;">'.$result['content'].'</p>';
				$output .= '<div>';
					$output .= '<span class="float-right text-muted text-sm">'.date('d-M-y H:i A', $result['notify_time']).'</span>';
					if($seen_status == 0) {
						$output .= '<button class="btn btn-link btn-sm seen_notification" data-id="'.$notification_id.'">Mark As Read</button>';
					}
				$output .= '</div>';
			$output .= '</div>';
		}
	} else {
		$output .= 'No Notification Found';
	}

	echo $output;

}

if(isset($_POST['action']) && $_POST['action'] == 'mark_notification_seen') {

	$id = validate($_POST['id']);
	$user_id = validate($_POST['user_id']);

	if($id != '' && $user_id != 0) {
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM notification_seen WHERE notify_id='$id' && user_id='$user_id' && active_status='1' && delete_status='0'")) == 0) {
			$query = mysqli_query($conn, "INSERT INTO notification_seen(notify_id, user_id, time_created) VALUES('$id', '$user_id', '$time_created')");
			if($query) {
				echo 3;
			} else {
				echo 2;
			}
		} else {
			echo 1;
		}
	} else {
		echo 0;
	}

}

if(isset($_POST['action']) && $_POST['action'] == 'display_notification_seen_users') {
	$id = validate($_POST['id']);
	$output = '';
	if($id != '' && $id != 0) {
		$query = mysqli_query($conn, "SELECT * FROM notification_seen WHERE notify_id='$id' && active_status='1' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			$i = 1;
			while($result = mysqli_fetch_assoc($query)) {
				$output .= "<tr>";
					$output .= "<td>".$i."</td>";
					$output .= "<td>".user_display_name($result['user_id'])."</td>";
					$output .= "<td>".date('d-F-Y h:i:s A', $result['time_created'])."</td>";
				$output .= "</tr>";
				$i++;
			}
		} else {
			$output .= "<tr><td colspan='3' class='text-center'>No User Found</td></tr>";
		}
	} else {
		$output .= "<tr><td colspan='3' class='text-center'>No User Found</td></tr>";
	}
	echo $output;
}

if(isset($_POST['action']) && $_POST['action'] == 'display_circle_stock') {

	$stock_circle = validate($_POST['stock_circle']);
	if(isset($_POST['stock_id']) && !empty($_POST['stock_id'])) {
		$stock_id = validate($_POST['stock_id']);
	} else {
		$stock_id = '';
	}
	$output = '<option value="">Select Stock</option>';

	if(!empty($stock_circle) && $stock_circle != 0) {

		$query = mysqli_query($conn, "SELECT * FROM stock_transactions WHERE delete_status='0' && active_status!='2' && circle_id='$stock_circle'");

		if(mysqli_num_rows($query) > 0) {
			while($result = mysqli_fetch_assoc($query)) {
				if($result['id'] == $stock_id) {
					$stock_selected = 'selected';
				} else {
					$stock_selected = '';
				}
				if($result['stock_status'] == 0) {
					$stock_class = 'text-danger';
					$stock_status = 'Uncleaned';
				} else if($result['stock_status'] == 2) {
					$stock_class = 'text-success';
					$stock_status = 'Cleaned';
				} else if($result['stock_status'] == 3) {
					$stock_class = 'text-primary';
					$stock_status = 'Under Fumigation';
				}
				$output .= '<option '.$stock_selected.' class="'.$stock_class.'" data-qty="'.$result['stock_qty'].'" data-price="'.stock_price(stock_detail($result['stock_id'], 'crop'), stock_detail($result['stock_id'], 'class'), 'sale_price').'" value="'.$result['id'].'">'.stock_detail($result['stock_id'], 'lot_number').' - '.stock_crop(stock_detail($result['stock_id'], 'crop')).' - '.stock_variety(stock_detail($result['stock_id'], 'variety')).' - '.stock_class(stock_detail($result['stock_id'], 'class')).' - '.$result['stock_qty'].' (Mun) - '.$stock_status.'</option>';
			}
		}

	}

	echo $output;

}

if(isset($_POST['action']) && $_POST['action'] == 'display_filter_activity_season') {

	$stock_crop_id = validate($_POST['stock_crop']);
	$output = '<option value="">Select Activity Season</option>';

	if($stock_crop_id != '' && $stock_crop_id != 0) {
		
		$query = mysqli_query($conn, "SELECT * FROM stock_activity_season WHERE stock_crop_id='$stock_crop_id' && active_status='1' && delete_status='0' ORDER BY time_created DESC");
		if(mysqli_num_rows($query) > 0) {
			while($result = mysqli_fetch_assoc($query)) {
				$output .= "<option value='".$result['id']."'>".$result['season_title']."</option>";
			}
		}

	}

	echo $output;

}

if(isset($_POST['action']) && $_POST['action'] == 'display_report_activity_season') {

	$stock_crop_id = validate($_POST['stock_crop']);
	$output = '<option value="">Select Activity Season</option>';
	$output .= "<option value='-1'>All Season</option>";

	if($stock_crop_id != '' && $stock_crop_id != 0) {
		
		$query = mysqli_query($conn, "SELECT * FROM stock_activity_season WHERE stock_crop_id='$stock_crop_id' && active_status='1' && delete_status='0' ORDER BY time_created DESC");
		if(mysqli_num_rows($query) > 0) {
			while($result = mysqli_fetch_assoc($query)) {
				$output .= "<option value='".$result['id']."'>".$result['season_title']."</option>";
			}
		}

	}

	echo $output;

}

if(isset($_POST['action']) && $_POST['action'] == 'display_report_format') {

	$report_type = validate($_POST['report_type']);
	$output = '<option value="">Select Report Format</option>';

	if($report_type == 'district_wise_procurement_report' || $report_type == 'district_wise_cleaning_report' || $report_type == 'district_wise_supply_report' || $report_type == 'circle_wise_procurement_report' || $report_type == 'circle_wise_cleaning_report' || $report_type == 'circle_wise_supply_report') {
		$output .= "<option value='variety_wise'>Variety Wise</option>";
		$output .= "<option value='variety_and_class_wise'>Variety and Class Wise</option>";
	}

	echo $output;

}

?>