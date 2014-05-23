<?php
// Text
if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443){
    $_['text_title']           = 'iyzico Kredi Kartı Tek Çekim';
} else {
    $_['text_title']           = 'iyzico Kredi Kartı Tek Çekim';
}
$_['text_credit_card']     = 'Kredi Kartı Bilgileri';
$_['text_start_date']      = '(varsa)';
$_['text_issue']           = '(Maestro kartları için)';
$_['text_wait']            = 'Lütfen bekleyin!';
$_['text_title_in_checkout'] = 'Kredi Kartı Tek Çekim';

// Entry
$_['orderId_text']         = 'siparis no';
$_['Error_message_curl']         = 'PHP bu sunucuda Genişletme CURL etkinleştirmeniz gerekir.';
$_['Error_message_generateToken']= 'Ödeme simge üreten bir sorun.';
$_['Error_message_sender_head']= 'Bir Hata Olustu';
$_['Error_message_sender_text']= 'Hata: missing or invalid sender id';

$_['heading_title'] = 'Aradığınız sayfa bulunamadı!';
$_['text_error']    = 'Aradığınız sayfa bulunamadı!';
?>