<?php
// Text
if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443){
    $_['text_title']           = 'iyzico Single Payment';
} else {
    $_['text_title']           = 'iyzico Single Payment';
}
$_['text_credit_card']     = 'Credit Card Details';
$_['text_start_date']      = '(if available)';
$_['text_issue']           = '(for Maestro and Solo cards only)';
$_['text_wait']            = 'Please wait!';
$_['text_title_in_checkout'] = 'iyzico Single Payment';

// Entry
$_['orderId_text']         = 'orderID';
$_['Error_message_curl']         = 'You have to enable PHP CURL Extention on this server.';
$_['Error_message_generateToken']= 'Some problem in generating payment token.';
$_['Error_message_sender_head']= 'Bir Hata Olustu';
$_['Error_message_sender_text']= 'missing or invalid sender id';

$_['heading_title'] = 'The page you requested cannot be found!';
$_['text_error']    = 'The page you requested cannot be found.';

//error message
$_['invalid_currecy_format']  = 'This currency format is not supported.';

?>