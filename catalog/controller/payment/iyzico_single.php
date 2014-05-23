<?php

class ControllerPaymentIyzicoSingle extends Controller {

    public $postFieldsArray;
    public $apiURL;
    public $transactionMode;

    protected function index() {
        
        
        
        $this->language->load('payment/iyzico_single');

        $this->data['code'] = $this->language->get('code');
        $this->data['text_credit_card'] = $this->language->get('text_credit_card');
        $this->data['text_start_date'] = $this->language->get('text_start_date');
        $this->data['text_issue'] = $this->language->get('text_issue');
        $this->data['text_wait'] = $this->language->get('text_wait');


        $this->data['button_confirm'] = $this->language->get('button_confirm');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/iyzico_single.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/iyzico_single.tpl';
        } else {
            $this->template = 'default/template/payment/iyzico_single.tpl';
        }
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);           
        if ($this->config->get('iyzico_single_test')) {
            $merchantApiId = $this->config->get('iyzico_single_api_id_test');
            $merchantSecretKey = $this->config->get('iyzico_single_secret_key_test');          
        } else {
            $merchantApiId = $this->config->get('iyzico_single_api_id_live');
            $merchantSecretKey = $this->config->get('iyzico_single_secret_key_live');
        }
        $identificationTransId = uniqid("opencart_MBCR");
        $this->data['mode'] = $this->config->get('iyzico_single_test');
        $paymentType = 'CC';
        $presAmount = $order_info['total'] * $order_info['currency_value'];
        $presCurrency = $order_info['currency_code'];
        $presUsage = $this->language->get('orderId_text') . " " . $order_info['order_id'];
        $this->data['presAmount'] = $presAmount;
        $this->data['presCurrency'] = $presCurrency;
        if (function_exists('curl_version')) {
            
            #generate token for payment
            $paymentTokenResp = $this->__generateToken($merchantApiId, $merchantSecretKey, $identificationTransId, $paymentType, $presAmount, $presCurrency, $presUsage, $order_info);                                
            $paymentTokenResp = json_decode($paymentTokenResp);
                        
            #get related api urls.
            $this->load->model('setting/iyzico_single');            
            $this->data['payonJsPath'] = $this->model_setting_iyzico_single->getPayonJSURL($this->transactionMode);            
            #get site url.
            $this->data['siteUrl'] = $this->model_setting_iyzico_single->getSiteUrl();
            
            //next step on generated token
            if (is_object($paymentTokenResp)) {                
                if (property_exists($paymentTokenResp, 'transaction')) {                    
                    $this->data['access_token'] = $paymentTokenResp->transaction->token;
                    $this->data['error'] = '';
                } else if (property_exists($paymentTokenResp, 'errorMessage')) {                    
                    $this->data['error'] = $paymentTokenResp->errorMessage;
                    $this->data['access_token'] = '';
                }else {
                    if($paymentTokenResp->response->error_code == 'invalid_currency'){
                        $this->data['error'] = $this->language->get("invalid_currecy_format");
                        $this->data['access_token'] = '';
                    }else{
                        $this->data['error'] = $this->language->get("Error_message_generateToken");
                        $this->data['access_token'] = '';
                    }                    
                }
            } else {
                $this->data['error'] = $this->language->get("Error_message_generateToken");
                $this->data['access_token'] = '';
            }
        } else {
            $this->data['error'] = $this->language->get("Error_message_curl");
            $this->data['access_token'] = '';
        }

        $this->render();
    }

    private function __generateToken($merchantApiId, $merchantSecretKey, $identificationTransId, $paymentType, $presAmount, $presCurrency, $presUsage, $order_info) {              
        if (!($this->config->get('iyzico_single_test'))) {
            $this->transactionMode = 'live';         
        } else {
            $this->transactionMode = 'test';         
        }
        $this->load->model('setting/iyzico_single');
        $this->postFieldsArray = array(
            'api_id' => urlencode(trim($merchantApiId)),
            'secret' => urlencode(trim($merchantSecretKey)),            
            'mode' => urlencode($this->transactionMode),
            'external_id' => $identificationTransId,
            'type' => urlencode($paymentType),                        
            'amount' => $this->model_setting_iyzico_single->amountConverter($presAmount),
            'currency' => urlencode($presCurrency),
            'return_url' =>  $this->model_setting_iyzico_single->getSiteUrl().'index.php?route=payment/iyzico_single/send',
            'presentation_usage' => urlencode($presUsage),
            'email' => urlencode($order_info['email']),            
            'first_name' => urlencode($order_info['payment_firstname']),
            'last_name' => urlencode($order_info['payment_lastname']),            
            'company_name' => urlencode($order_info['payment_company']),
            'shipping_address_street' => urlencode($order_info['shipping_address_1'] . "," . $order_info['shipping_address_2']),
            'shipping_address_zip' => urldecode($order_info['shipping_postcode']),
            'shipping_address_city' => urlencode($order_info['shipping_city']),
            'shipping_address_state' => urlencode($order_info['shipping_zone']),
            'shipping_address_country' => urlencode($order_info['shipping_country']),
            
            'billing_address_street' => urlencode($order_info['payment_address_1'] . "," . $order_info['payment_address_2']),
            'billing_address_zip' => urlencode($order_info['payment_postcode']),
            'billing_address_city' => urlencode($order_info['payment_city']),
            'billing_address_state' => urlencode($order_info['payment_zone']),
            'billing_address_country' => urlencode($order_info['payment_country']),
            
            'contact_phone' => urlencode($order_info['telephone']),
            'contact_mobile' => urlencode($order_info['telephone']),
            'contact_ip' => $order_info['ip'],          
        );                              
        $response = $this->model_setting_iyzico_single->curlCall($this->postFieldsArray,'tokenGeneration'); 		
        return $response;
    }
    
    public function send() {       
        $response = html_entity_decode($this->request->post['json']);
        $jsonResp = json_decode($response, true);        
        $json = array();
        $respMsg = '';        
        if($jsonResp['response']['state'] == 'failed'){			  
            $this->session->data['error'] = $jsonResp;
            //$this->log->write('iyzico payment failed: ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
            $url = $this->url->link('payment/iyzico_single/error');
            $this->redirect($url);
        }else if ($jsonResp['transaction']['processing']['result'] == "ACK") {
            $message = '';
        
            if (isset($jsonResp['transaction']['identification']['transactionid'])) {
                $message .= 'TRANSACTIONID: ' . $jsonResp['transaction']['identification']['transactionid'] . "\n";
            }
        
            $this->load->model('checkout/order');                                
            $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));            
            $this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('iyzico_single_order_status_id'), $message, false);
        
            $url = $this->url->link('checkout/success');
            $this->redirect($url);
        } else if ($jsonResp['transaction']['processing']['result'] == "NOK") {
            $respMsg = $jsonResp['transaction']['processing']['return']['message'];
            $json['error'] = $respMsg;
            $this->session->data['error'] = $jsonResp;
            //$this->log->write('iyzico payment failed: ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
            $url = $this->url->link('payment/iyzico_single/error');
            $this->redirect($url);
        } else if ($jsonResp['errorMessage']) {
            $json['error'] = $jsonResp['errorMessage'];
            $this->session->data['error'] = $jsonResp['errorMessage'];
            //$this->log->write('iyzico payment failed: ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
            $url = $this->url->link('payment/iyzico_single/error');
            $this->redirect($url);
        }
    }

   
    public function error() {
        if ($this->session->data['error']) {
            $error = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $url = $this->url->link('common/home');
            $this->redirect($url);
        }
        
        $this->language->load('payment/iyzico_single');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
            'separator' => false
        );

        if (isset($this->request->get['route'])) {
            $data = $this->request->get;

            unset($data['_route_']);

            $route = $data['route'];

            unset($data['route']);

            $url = '';

            if ($data) {
                $url = '&' . urldecode(http_build_query($data, '', '&'));
            }

            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $connection = 'SSL';
            } else {
                $connection = 'NONSSL';
            }

            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link($route, $url, $connection),
                'separator' => $this->language->get('text_separator')
            );
        }
                
        if($error['response']['state'] == 'failed'){
            if($error['response']['error_code'] == 'cc_transaction_reject'){
                $this->data['heading_title'] = "Payment error...";
                $this->data['text_error'] = $error['response']['error_message'];
            }
        }        
        $this->data['button_continue'] = $this->language->get('button_continue');

        $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');

        $this->data['continue'] = $this->url->link('checkout/checkout');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
        } else {
            $this->template = 'default/template/error/not_found.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }

}

?>