<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'classes/smtp/Exception.php';
require 'classes/smtp/PHPMailer.php';
require 'classes/smtp/SMTP.php';

// $mail = new PHPMailer(true);

class SMTP {

    private $smtp;

    // $this->SMTPAuth = TRUE;
    public function __construct($hostname, $username, $password, $port = 465, $SMTPSecure = 'ssl') {
        $this->smtp = new PHPMailer(true);
        // $mail->SMTPDebug  = 1;  
        $this->smtp->SMTPAuth   = TRUE;
        $this->smtp->SMTPSecure = $SMTPSecure;
        $this->smtp->Port       = $port;
        $this->smtp->Host       = $hostname;
        $this->smtp->Username   = $username;
        $this->smtp->Password   = $password;
        $this->smtp->setFrom($username);
        $this->smtp->isSMTP();
    }

    public function clearAllRecipients() {
        $this->smtp->clearAllRecipients();
    }

    public function addReplyAddress($reply_address) {
        $this->smtp->addReplyTo($reply_address);
    }

    public function addCCAddress($cc_address) {
        $cc_address = explode(',', $cc_address);
        foreach($cc_address as $address) {
            $this->smtp->addCC($address);
        }
    }

    public function addBCCAddress($bcc_address) {
        $bcc_address = explode(',', $bcc_address);
        foreach($bcc_address as $address) {
            $this->smtp->addBCC($address);
        }
    }

    public function addAttachments($attachments) {
        $attachments = explode(',', $attachments);
        //Attachments
        for($i = 0; $i < sizeof($attachments); $i++) {
            $pathinfo = pathinfo($attachments[$i]);
            // $this->smtp->addAttachment('http://localhost/email-marketing/dashboard/'.trim($attachments[$i]), $pathinfo['basename']);
            $this->smtp->addStringAttachment(file_get_contents(trim($attachments[$i])), $pathinfo['basename']);
        }
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    }

    public function addSubject($subject) {
        $this->smtp->Subject = trim($subject);
    }

    public function addMessage($message) {
        $this->smtp->MsgHTML($message);
    }

    public function addToAddress($to) {
        $this->smtp->AddAddress(trim($to));
    }

    public function sendNormalEmail($priority_status = 2, $type = 'html') {
        if($type == 'html') {
            $this->smtp->IsHTML(true);
        }

        if($priority_status == 1) {
            // For most clients expecting the Priority header:
            // 1 = High, 2 = Medium, 3 = Low
            $this->smtp->Priority = 1;
            // MS Outlook custom header
            // May set to "Urgent" or "Highest" rather than "High"
            $this->smtp->AddCustomHeader("X-MSMail-Priority: High");
            // Not sure if Priority will also set the Importance header:
            $this->smtp->AddCustomHeader("Importance: High");
        }

        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        
        if($this->smtp->Send()) {
            return true;
        } else {
            return false;
        }
    }

    public function send_Email_Without_Attachments_And_CC_And_BCC_Address($to, $subject, $message, $priority_status = 2, $type = 'html') {
        if($type == 'html') {
            $this->smtp->IsHTML(true);
        }

        if($priority_status == 1) {
            // For most clients expecting the Priority header:
            // 1 = High, 2 = Medium, 3 = Low
            $this->smtp->Priority = 1;
            // MS Outlook custom header
            // May set to "Urgent" or "Highest" rather than "High"
            $this->smtp->AddCustomHeader("X-MSMail-Priority: High");
            // Not sure if Priority will also set the Importance header:
            $this->smtp->AddCustomHeader("Importance: High");
        }

        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        $this->smtp->AddAddress(trim($to));
        $this->smtp->Subject = trim($subject);
        $this->smtp->MsgHTML($message);
        if($this->smtp->Send()) {
            return true;
        } else {
            return false;
        }
    }

    public function send_Email_Without_Attachments_And_CC_And_With_BCC_Address($to, $subject, $message, $bcc_address, $priority_status = 2, $type = 'html') {
        if($type == 'html') {
            $this->smtp->IsHTML(true);
        }

        if($priority_status == 1) {
            // For most clients expecting the Priority header:
            // 1 = High, 2 = Medium, 3 = Low
            $this->smtp->Priority = 1;
            // MS Outlook custom header
            // May set to "Urgent" or "Highest" rather than "High"
            $this->smtp->AddCustomHeader("X-MSMail-Priority: High");
            // Not sure if Priority will also set the Importance header:
            $this->smtp->AddCustomHeader("Importance: High");
        }

        // $mail->addReplyTo('info@example.com', 'Information');
        $bcc_address = explode($bcc_address, ',');
        foreach($bcc_address as $address) {
            $mail->addBCC(trim($address));
        }
        
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        $this->smtp->AddAddress(trim($to));
        $this->smtp->Subject = trim($subject);
        $this->smtp->MsgHTML($message);
        if($this->smtp->Send()) {
            return true;
        } else {
            return false;
        }
    }

    public function send_Email_Without_Attachments_And_With_CC_And_Without_BCC_Address($to, $subject, $message, $cc_address, $priority_status = 2, $type = 'html') {
        if($type == 'html') {
            $this->smtp->IsHTML(true);
        }

        if($priority_status == 1) {
            // For most clients expecting the Priority header:
            // 1 = High, 2 = Medium, 3 = Low
            $this->smtp->Priority = 1;
            // MS Outlook custom header
            // May set to "Urgent" or "Highest" rather than "High"
            $this->smtp->AddCustomHeader("X-MSMail-Priority: High");
            // Not sure if Priority will also set the Importance header:
            $this->smtp->AddCustomHeader("Importance: High");
        }

        // $mail->addReplyTo('info@example.com', 'Information');
        $cc_address = explode($cc_address, ',');
        foreach($cc_address as $address) {
            $mail->addCC(trim($address));
        }
        
        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        $this->smtp->AddAddress(trim($to));
        $this->smtp->Subject = trim($subject);
        $this->smtp->MsgHTML($message);
        if($this->smtp->Send()) {
            return true;
        } else {
            return false;
        }
    }

    public function send_Email_Without_Attachments_And_With_CC_And_BCC_Address($to, $subject, $message, $cc_address, $bcc_address, $priority_status = 2, $type = 'html') {
        if($type == 'html') {
            $this->smtp->IsHTML(true);
        }

        if($priority_status == 1) {
            // For most clients expecting the Priority header:
            // 1 = High, 2 = Medium, 3 = Low
            $this->smtp->Priority = 1;
            // MS Outlook custom header
            // May set to "Urgent" or "Highest" rather than "High"
            $this->smtp->AddCustomHeader("X-MSMail-Priority: High");
            // Not sure if Priority will also set the Importance header:
            $this->smtp->AddCustomHeader("Importance: High");
        }

        // $mail->addReplyTo('info@example.com', 'Information');
        $cc_address = explode($cc_address, ',');
        foreach($cc_address as $address) {
            $mail->addCC(trim($address));
        }

        $bcc_address = explode($bcc_address, ',');
        foreach($bcc_address as $address) {
            $mail->addBCC(trim($address));
        }
        
        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        $this->smtp->AddAddress(trim($to));
        $this->smtp->Subject = trim($subject);
        $this->smtp->MsgHTML($message);
        if($this->smtp->Send()) {
            return true;
        } else {
            return false;
        }
    }

    public function send_Email_With_Attachments_And_Without_CC_And_BCC_Address($to, $subject, $message, $attachments, $priority_status = 2, $type = 'html') {
        $attachments = explode($attachments, ',');
        if($type == 'html') {
            $this->smtp->IsHTML(true);
        }

        if($priority_status == 1) {
            // For most clients expecting the Priority header:
            // 1 = High, 2 = Medium, 3 = Low
            $this->smtp->Priority = 1;
            // MS Outlook custom header
            // May set to "Urgent" or "Highest" rather than "High"
            $this->smtp->AddCustomHeader("X-MSMail-Priority: High");
            // Not sure if Priority will also set the Importance header:
            $this->smtp->AddCustomHeader("Importance: High");
        }

        // $mail->addReplyTo('info@example.com', 'Information');
        
        //Attachments
        for($i = 0; $i < sizeof($attachments); $i++) {
            $mail->addAttachment(trim($attachments[$i]));
        }
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        $this->smtp->AddAddress(trim($to));
        $this->smtp->Subject = trim($subject);
        $this->smtp->MsgHTML($message);
        if($this->smtp->Send()) {
            return true;
        } else {
            return false;
        }
    }

    public function send_Email_With_Attachments_And_Without_CC_And_With_BCC_Address($to, $subject, $message, $attachments, $bcc_address, $priority_status = 2, $type = 'html') {
        $attachments = explode($attachments, ',');
        if($type == 'html') {
            $this->smtp->IsHTML(true);
        }

        if($priority_status == 1) {
            // For most clients expecting the Priority header:
            // 1 = High, 2 = Medium, 3 = Low
            $this->smtp->Priority = 1;
            // MS Outlook custom header
            // May set to "Urgent" or "Highest" rather than "High"
            $this->smtp->AddCustomHeader("X-MSMail-Priority: High");
            // Not sure if Priority will also set the Importance header:
            $this->smtp->AddCustomHeader("Importance: High");
        }

        // $mail->addReplyTo('info@example.com', 'Information');
        $bcc_address = explode($bcc_address, ',');
        foreach($bcc_address as $address) {
            $mail->addBCC(trim($address));
        }
        
        //Attachments
        for($i = 0; $i < sizeof($attachments); $i++) {
            $mail->addAttachment(trim($attachments[$i]));
        }
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        $this->smtp->AddAddress(trim($to));
        $this->smtp->Subject = trim($subject);
        $this->smtp->MsgHTML($message);
        if($this->smtp->Send()) {
            return true;
        } else {
            return false;
        }
    }

    public function send_Email_With_Attachments_And_CC_And_Without_BCC_Address($to, $subject, $message, $attachments, $cc_address, $priority_status = 2, $type = 'html') {
        $attachments = explode($attachments, ',');
        if($type == 'html') {
            $this->smtp->IsHTML(true);
        }

        if($priority_status == 1) {
            // For most clients expecting the Priority header:
            // 1 = High, 2 = Medium, 3 = Low
            $this->smtp->Priority = 1;
            // MS Outlook custom header
            // May set to "Urgent" or "Highest" rather than "High"
            $this->smtp->AddCustomHeader("X-MSMail-Priority: High");
            // Not sure if Priority will also set the Importance header:
            $this->smtp->AddCustomHeader("Importance: High");
        }

        // $mail->addReplyTo('info@example.com', 'Information');
        $cc_address = explode($cc_address, ',');
        foreach($cc_address as $address) {
            $mail->addCC(trim($address));
        }
        
        //Attachments
        for($i = 0; $i < sizeof($attachments); $i++) {
            $mail->addAttachment(trim($attachments[$i]));
        }
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        $this->smtp->AddAddress(trim($to));
        $this->smtp->Subject = trim($subject);
        $this->smtp->MsgHTML($message);
        if($this->smtp->Send()) {
            return true;
        } else {
            return false;
        }
    }

    public function send_Email_With_Attachments_And_CC_And_BCC_Address($to, $subject, $message, $attachments, $cc_address, $bcc_address, $priority_status = 2, $type = 'html') {
        $attachments = explode($attachments, ',');
        if($type == 'html') {
            $this->smtp->IsHTML(true);
        }

        if($priority_status == 1) {
            // For most clients expecting the Priority header:
            // 1 = High, 2 = Medium, 3 = Low
            $this->smtp->Priority = 1;
            // MS Outlook custom header
            // May set to "Urgent" or "Highest" rather than "High"
            $this->smtp->AddCustomHeader("X-MSMail-Priority: High");
            // Not sure if Priority will also set the Importance header:
            $this->smtp->AddCustomHeader("Importance: High");
        }

        // $mail->addReplyTo('info@example.com', 'Information');
        $cc_address = explode($cc_address, ',');
        foreach($cc_address as $address) {
            $mail->addCC(trim($address));
        }

        $bcc_address = explode($bcc_address, ',');
        foreach($bcc_address as $address) {
            $mail->addBCC(trim($address));
        }
        
        //Attachments
        for($i = 0; $i < sizeof($attachments); $i++) {
            $mail->addAttachment(trim($attachments[$i]));
        }
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        $this->smtp->AddAddress(trim($to));
        $this->smtp->Subject = trim($subject);
        $this->smtp->MsgHTML($message);
        if($this->smtp->Send()) {
            return true;
        } else {
            return false;
        }
    }

}