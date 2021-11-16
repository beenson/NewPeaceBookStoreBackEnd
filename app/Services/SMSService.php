<?php
namespace App\Services;

class SMSService {

    public static function generateRandCode() {
        $txt = "QAZWSXEDCRFVGBHNUJMIKLOP1234567890";
        $code = "";
        for($i=0; $i<6; $i++) {
            $code .= $txt[rand(0, strlen($txt)-1)];
        }
    }
    public static function sendVerifyMessage($phone, $code) {
        $msg = "牛脾趾書城會員驗證碼: ".$code."";
        return SMSService::sendMessage($phone, $msg);
    }
    public static function sendMessage($phone, $msg) {
        $sms_acc = env('SMS_ACCOUNT');
        $sms_pwd = env('SMS_PASSWORD');
        $sms_msg = urlencode($msg);
        $return = file_get_contents('http://sms-get.com/api_send.php?method=1&sms_msg='.$sms_msg.'&phone='.$phone.'&username='.$sms_acc.'&password='.$sms_pwd.'');
        return $return;
    }
    public static function getPoint() {
        $sms_acc = env('SMS_ACCOUNT');
        $sms_pwd = env('SMS_PASSWORD');
        $return = file_get_contents('http://sms-get.com/api_query_credit.php?username='.$sms_acc.'&password='.$sms_pwd.'');
        return $return;
    }
}
