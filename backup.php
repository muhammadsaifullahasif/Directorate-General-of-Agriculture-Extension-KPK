<?php

require_once('function.php');
include_once('Mysqldump/Mysqldump.php');
$dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host='.$server.';dbname='.$db_name, $db_user, $db_pass);
$path = 'backups/'.date('Y').'/'.date('m').'/'.date('d');

if (!file_exists($path)) {
    mkdir($path, 0777, true);
}

$file = $path.'/'.time().'.sql';
// $file = $path.'/'.date('d-F-Y').'-'.time().'.sql';
$dump->start($file);

require_once('smtp/smtp.php');

// $array = array(
	// 'email_address' => 'info@wirecoder.com',
	// 'email_password' => '7S@!fullah',
	// 'email_host' => 'wirecoder.com'
// );

// echo json_encode($array);

// $query = mysqli_query($conn, "SELECT * FROM setting WHERE meta_key='smtp_configuration'");
// if(mysqli_num_rows($query) > 0) {
	// $result = mysqli_fetch_assoc($query);
// }

$SMTP = new SMTP('wirecoder.com', 'info@wirecoder.com', '7S@!fullah');

if(!empty(setting('backup_cc_email'))) {
	$SMTP->addCCAddress(trim(setting('backup_cc_email')));
}

$SMTP->addSubject(trim('Backup of Agriculture Portal '.date('d-F-Y h:i:s A')));

$SMTP->addAttachments(trim('http://localhost/agriculture/'.$file));

$SMTP->addToAddress(trim( setting('admin_email') ));

$SMTP->addMessage(trim('Backup of Agriculture Portal '.date('d-F-Y h:i:s A')));

$SMTP->sendNormalEmail(1);

?>