<?php

require_once "function.php";


$this_month = date('F');

$query = mysqli_query($conn, "SELECT * FROM activity_chart WHERE active_status='1' && delete_status='0' && activity_time='$this_month'");

if(mysqli_num_rows($query) > 0) {
	while($result = mysqli_fetch_assoc($query)) {
		$activity_id = $result['id'];
		$stock_crop = $result['type_id'];
		$activity_type = $result['activity_type'];
		$activity_month = date('n', strtotime($this_month));

		$notification_query = mysqli_query($conn, "SELECT * FROM notifications WHERE active_status='1' && delete_status='0' && activity_id='$activity_id' && MONTH(date_created)='$activity_month'");
		if(mysqli_num_rows($notification_query) == 0) {
			if($activity_type == 0) {
				$activity_notifications = json_decode(setting('activity_notifications'), true);

				$content = str_replace('{{stock_crop}}', stock_crop($stock_crop), $activity_notifications['activity_type_'.$activity_type]);

				$notify_query = mysqli_query($conn, "INSERT INTO notifications(notify_circle_id, activity_id, content, notify_time, time_created) VALUES('0', '$activity_id', '$content', '$time_created', '$time_created')");
			}
		}
	}
}


$array = array(
	'activity_type_0' => 'Dear Procurement Officer please be ready for Procuring the stock of {{stock_crop}} in this month. Make your finance strong and ready the packing bags',
	'activity_type_1' => 'Dear Procurement Officer please be ready for Fumigate the stock of {{stock_crop}} in this month. Make your finance strong and ready the fumigation equipments',
	'activity_type_2' => 'Dear Procurement Officer please be ready for Cleaning the stock of {{stock_crop}} in this month. Make your finance strong and ready the cleaning equipments',
	'activity_type_3' => 'Dear Procurement Officer please be ready for Supply of the stock of {{stock_crop}} in this month.',
);


?>