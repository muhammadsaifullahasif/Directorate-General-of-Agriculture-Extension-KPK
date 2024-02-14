<?php

require_once('config.php');

date_default_timezone_set("Asia/Karachi");

function validate($text) {
	global $conn;
	return mysqli_real_escape_string($conn, trim(strip_tags($text)));
}

function setting($meta_key) {
	global $conn;
	$query = mysqli_query($conn, "SELECT * FROM setting WHERE meta_key='$meta_key'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['meta_value'];
	} else {
		return '';
	}
}

$allow_application = json_decode(setting('allow_application'), true);

function is_login() {
	global $conn;
	if(isset($_SESSION['agriculture_user_login'])) {
		$user_login = $_SESSION['agriculture_user_login'];
		$query = mysqli_query($conn, "SELECT * FROM users WHERE user_login='$user_login' && active_status='1' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function user_id($user_login) {
	global $conn;
	$query = mysqli_query($conn, "SELECT id FROM users WHERE user_login='$user_login'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['id'];
	} else {
		return 0;
	}
}

if(!is_login()) {
	header('location: login.php');
} else {
	$user_login = $_SESSION['agriculture_user_login'];
	$user_id = user_id($user_login);
}

function is_super_admin() {
	global $conn;

	if(isset($_SESSION['agriculture_user_login'])) {
		$user_login = $_SESSION['agriculture_user_login'];
		if(mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE user_login='$user_login' && active_status='1' && delete_status='0' && role='0' && type='0'")) == 1) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}

}

function is_admin() {
	global $conn;

	if(isset($_SESSION['agriculture_user_login'])) {
		$user_login = $_SESSION['agriculture_user_login'];
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE user_login='$user_login' && active_status='1' && delete_status='0' && role='0' && type='1'")) == 1) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}

}

function is_manager() {
	global $conn;

	if(isset($_SESSION['agriculture_user_login'])) {
		$user_login = $_SESSION['agriculture_user_login'];
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE user_login='$user_login' && active_status='1' && delete_status='0' && role='1' && type='0'")) == 1) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}

}

function is_storekeeper() {
	global $conn;

	if(isset($_SESSION['agriculture_user_login'])) {
		$user_login = $_SESSION['agriculture_user_login'];
		if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE user_login='$user_login' && active_status='1' && delete_status='0' && role='1' && type='1'")) == 1) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}

}

function user_detail($user_id, $key) {
	global $conn;

	$query = mysqli_query($conn, "SELECT $key FROM users WHERE id='$user_id' && delete_status='0'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result[$key];
	} else {
		return '';
	}
}

if(is_admin() || is_manager() || is_storekeeper()) {
	$user_district = user_detail($user_id, 'district');
	$circle_id = user_detail($user_id, 'circle_id');
}

function user_role($role, $type) {
	if($role == 0 && $type == 0) {
		return 'Director Seed KPK'; // Super Administrator
	} else if($role == 0 && $type == 1) {
		return 'District Director'; // Administrator
	} else if($role == 1 && $type == 0) {
		return 'Procurement Officer'; // Circle Manager
	} else if($role == 1 && $type == 1) {
		return 'Store Keeper'; // Store Keeper
	}
}

function user_display_name($user_id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT display_name FROM users WHERE id='$user_id'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['display_name'];
	} else {
		return '';
	}
}

function user_profile_image($user_id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT meta_value FROM user_meta WHERE user_id='$user_id' && meta_key='profile_image'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		$profile_image = json_decode($result['meta_value'], true);
		if($profile_image['image_path'] != '') {
			return $profile_image['image_path'];
		} else {
			return 'dist/img/avatar.png';
		}
	} else {
		return 'dist/img/avatar.png';
	}
}

function user_meta($user_id, $meta_key) {
	global $conn;
	$query = mysqli_query($conn, "SELECT meta_value FROM user_meta WHERE user_id='$user_id' && meta_key='$meta_key'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['meta_value'];
	} else {
		return '';
	}
}

function circle_meta($circle_id, $meta_key) {
	global $conn;
	$query = mysqli_query($conn, "SELECT meta_value FROM circle_meta WHERE circle_id='$circle_id' && meta_key='$meta_key'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['meta_value'];
	} else {
		return '';
	}
}

function circle_manager($circle_id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT display_name FROM users WHERE circle_id='$circle_id'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['display_name'];
	} else {
		return '';
	}
}

function circle_name($circle_id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT name FROM circles WHERE id='$circle_id' && delete_status='0'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['name'];
	} else {
		return '';
	}
}

function farmer_info($id, $key, $id_type = 'cnic') {
	global $conn;

	if($id_type == 'cnic') {
		$query = mysqli_query($conn, "SELECT $key FROM farmers WHERE farmer_cnic='$id' && delete_status='0'");
	} else if($id_type == 'id') {
		$query = mysqli_query($conn, "SELECT $key FROM farmers WHERE id='$id' && delete_status='0'");
	}
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result[$key];
	} else {
		return '';
	}
}

function fscrd_comment($fscrd_report_id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT report_comment FROM fscrd_report WHERE id='$fscrd_report_id'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['report_comment'];
	} else {
		return '';
	}
}

function fscrd_status($fscrd_report_id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT report_status FROM fscrd_report WHERE id='$fscrd_report_id'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['report_status'];
	} else {
		return '';
	}
}

function stock_meta($stock_id, $meta_key) {
	global $conn;
	$query = mysqli_query($conn, "SELECT meta_value FROM stock_meta WHERE stock_id='$stock_id' && meta_key='$meta_key'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['meta_value'];
	} else {
		return '';
	}
}

function stock_detail($stock_id, $key) {
	global $conn;
	$query = mysqli_query($conn, "SELECT $key FROM stocks WHERE id='$stock_id' && delete_status='0'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result[$key];
	} else {
		return '';
	}
}

function stock_transaction_detail($stock_id, $key, $id = 'id', $circle_id = '', $stock_status = '', $active_status = '') {
	global $conn;
	if($id === 'id') {
		$query = mysqli_query($conn, "SELECT $key FROM stock_transactions WHERE id='$stock_id' && delete_status='0'");
	} else {
		$query = mysqli_query($conn, "SELECT $key FROM stock_transactions WHERE circle_id='$circle_id' && stock_id='$stock_id' && stock_status='$stock_status' && active_status='$active_status' && delete_status='0'");
	}
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result[$key];
	} else {
		return '';
	}
}

function stock_price($crop_id, $class_id, $key) {
	global $conn;
	$query = mysqli_query($conn, "SELECT $key FROM stock_price WHERE stock_crop_id='$crop_id' && stock_class_id='$class_id' && delete_status='0'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result[$key];
	} else {
		return '0';
	}
}

function supply_meta($supply_id, $meta_key) {
	global $conn;
	$query = mysqli_query($conn, "SELECT meta_value FROM supply_meta WHERE supply_id='$supply_id' && meta_key='$meta_key'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['meta_value'];
		// return "SELECT meta_value FROM supply_meta WHERE supply_id='$supply_id' && meta_key='$meta_key'";
	} else {
		return '';
	}
}

function stock_cleaning_meta($stock_cleaning_id, $meta_key) {
	global $conn;
	$query = mysqli_query($conn, "SELECT meta_value FROM stock_cleaning_meta WHERE stock_cleaning_id='$stock_cleaning_id' && meta_key='$meta_key'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['meta_value'];
	} else {
		return '';
	}
}

function stock_fumigation_meta($stock_fumigation_id, $meta_key) {
	global $conn;
	$query = mysqli_query($conn, "SELECT meta_value FROM stock_fumigation_meta WHERE stock_fumigation_id='$stock_fumigation_id' && meta_key='$meta_key'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['meta_value'];
	} else {
		return '';
	}
}

function stock_crop($id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT crop FROM stock_crop WHERE id='$id' && delete_status='0'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['crop'];
	} else {
		return '';
	}
}

function stock_class($id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT class_name FROM stock_class WHERE id='$id' && delete_status='0'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['class_name'];
	} else {
		return '';
	}
}

function stock_variety($id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT variety FROM stock_variety WHERE id='$id' && delete_status='0'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['variety'];
	} else {
		return '';
	}
}

function finance_amount($circle_id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT amount FROM finance WHERE circle_id='$circle_id' && active_status='1' && delete_status='0'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['amount'];
	} else {
		return '';
	}
}

function finance_id($circle_id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT id FROM finance WHERE circle_id='$circle_id' && active_status='1' && delete_status='0'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['id'];
	} else {
		return '';
	}
}

function transaction_detail($ref_id, $trans_flow, $type, $key, $table = 'transaction_meta') {
	global $conn;

	$query = mysqli_query($conn, "SELECT * FROM transactions WHERE ref_id='$ref_id' && trans_flow='$trans_flow' && type='$type'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		if($table == 'transactions') {
			return $result[$key];
		} else {
			$transaction_id = $result['id'];
			$meta_query = mysqli_query($conn, "SELECT meta_value FROM transaction_meta WHERE meta_key='$key' && transaction_id='$transaction_id'");
			if(mysqli_num_rows($meta_query) > 0) {
				$meta_result = mysqli_fetch_assoc($meta_query);
				return $meta_result['meta_value'];
			} else {
				return '';
			}
		}
	} else {
		return '';
	}
}

function transaction_meta($transaction_id, $meta_key) {
	global $conn;
	$query = mysqli_query($conn, "SELECT meta_value FROM transaction_meta WHERE transaction_id='$transaction_id' && meta_key='$meta_key'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['meta_value'];
	} else {
		return '';
	}
}

function transaction_type($type) {

	if($type == 0) {
		return 'Procure Stock';
	} else if($type == 1) {
		return 'Fumigate Stock';
	} else if($type == 2) {
		return 'Clean Stock';
	} else if($type == 3) {
		return 'Supply Stock Price';
	} else if($type == 4) {
		return 'Supply Cost';
	} else if($type == 5) {
		return 'Receive Stock Price';
	} else if($type == 6) {
		return 'Receive Cost';
	} else if($type == 7) {
		return 'Other';
	} else if($type == 8) {
		return 'Allot Budget';
	}

}

function district_name($district_id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT name FROM configurations WHERE id='$district_id' && type='district' && delete_status='0'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['name'];
	} else {
		return '';
	}
}

function activity($type) {
	if($type == 0) {
		return 'Procure Stock';
	} else if($type == 1) {
		return 'Fumigate Stock';
	} else if($type == 2) {
		return 'Clean Stock';
	} else if($type == 3) {
		return 'Supply Stock';
	}
}

function activity_id($id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT crop_id, activity_type FROM activity_chart WHERE id='$id' && delete_status='0'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		if($result['activity_type'] == 0) {
			return 'Procure Stock of '.stock_crop($result['crop_id']);
		} else if($result['activity_type'] == 1) {
			return 'Fumigate Stock of '.stock_crop($result['crop_id']);
		} else if($result['activity_type'] == 2) {
			return 'Clean stock of '.stock_crop($result['crop_id']);
		} else if($result['activity_type'] == 3) {
			return 'Supply stock of '.stock_crop($result['crop_id']);
		}
	} else {
		return '';
	}
}

function activity_season_title($activity_season_id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT season_title FROM stock_activity_season WHERE id='$activity_season_id'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		if($result['season_title'] != '') {
			return $result['season_title'];
		} else {
			return '';
		}
	} else {
		return '';
	}
}

function current_activity_season_id($stock_crop) {
	global $conn;
	$query = mysqli_query($conn, "SELECT id FROM stock_activity_season WHERE stock_crop_id='$stock_crop' && season_status='1' && active_status='1' && delete_status='0'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['id'];
	} else {
		return 0;
	}
}

function current_activity_season_title($stock_crop) {
	global $conn;
	$query = mysqli_query($conn, "SELECT season_title FROM stock_activity_season WHERE stock_crop_id='$stock_crop' && season_status='1' && active_status='1' && delete_status='0'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		if($result['season_title'] != '') {
			return $result['season_title'];
		} else {
			return '';
		}
	} else {
		return '';
	}
}

function update_notifications() {
	global $conn, $time_created;

	$query = mysqli_query($conn, "UPDATE notifications SET active_status='1' WHERE notify_time<='$time_created'");
	if($query) {
		return true;
	} else {
		return false;
	}
}

update_notifications();

function backup_type($type) {
	if($type == 0)
		return 'Complete Backup';
	else if($type == 1)
		return 'Stock Backup';
	else if($type == 2)
		return 'Fumigataion Backup';
	else if($type == 3)
		return 'Cleaning Backup';
	else if($type == 4)
		return 'Supply Backup';
}

function backup_method($method) {
	if($method == 'file')
		return 'File';
	else if($method == 'email')
		return 'Email';
}

function select_circle($district = '', $selected_id = '', $id = '') {

	global $conn;
	$output = '';
	$sql = "SELECT * FROM circles WHERE active_status='1' && delete_status='0'";

	if(!empty($district)) {
		$sql .= " && district='$city' ";
	}
	if(is_manager() || is_storekeeper()) {
		global $circle_id;
		$sql .= " && id!='$circle_id' ";
	}

	$sql .= " ORDER BY name ASC ";
	$query = mysqli_query($conn, $sql);
	if(mysqli_num_rows($query) > 0) {
		while($result = mysqli_fetch_assoc($query)) {
			if(!empty($selected_id) && $selected_id == $result['id']) {
				$circle_selected = 'selected';
			} else {
				$circle_selected = '';
			}
			$output .= "<option ".$circle_selected." value='".$result['id']."'>".$result['name']."</option>";
		}
	}

	return $output;

}

?>