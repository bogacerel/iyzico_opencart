<?php

class ControllerPaymentIyzicoSingle extends Controller {

    private $error = array();
    public $iyzicoSiteUrl = 'https://www.iyzico.com/';

    /**
     * This function is called when admin side iyzico payment module is edited.
     */
    public function index() {
        $this->language->load('payment/iyzico_single');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (isset($this->request->get['data'])) {
            $paramData = json_decode(base64_decode($this->request->get['data']));
            if ($paramData == 'Token Expired') {
                $this->session->data['errorMsg'] = $this->language->get('Token is expired. Please try again.');
            } else {
                if (!empty($paramData->merchant_id) && !empty($paramData->token)) {

                    $url = $this->iyzicoSiteUrl . "merchantregistration-rest";

                    $data = "PURPOSE=KEY_FETCH" .
                            "&TOKEN_ID=" . $paramData->token .
                            "&MERCHANT_ID=" . $paramData->merchant_id;

                    $ch = curl_init();

                    //set the url, number of POST vars, POST data
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    curl_setopt($ch, CURLOPT_POST, 3);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

                    //execute post
                    $response = curl_exec($ch);

                    $resultJson = json_decode($response);
                    $iyzicoTestKeys = json_decode($resultJson->testKeys, true);
                    $iyzicoLiveKeys = json_decode($resultJson->liveKeys, true);
                    $this->data['response'] = TRUE;
                }
            }
        }


        //save form data
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('iyzico_single', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['link_title'] = $this->language->get('text_link');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_yes'] = $this->language->get('entry_mode_test');
        $this->data['text_no'] = $this->language->get('entry_mode_live');
        $this->data['text_authorization'] = $this->language->get('text_authorization');

        //
        $this->data['entry_api_id_test'] = $this->language->get('entry_api_id_test');
        $this->data['entry_secret_key_test'] = $this->language->get('entry_secret_key_test');
        $this->data['entry_api_id_live'] = $this->language->get('entry_api_id_live');
        $this->data['entry_secret_key_live'] = $this->language->get('entry_secret_key_live');

        $this->data['entry_test'] = $this->language->get('entry_test');
        $this->data['entry_order_status'] = $this->language->get('entry_order_status');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->session->data['errorMsg'])) {
            $this->data['errorMsg'] = $this->language->get($this->session->data['errorMsg']);
            $this->session->data['errorMsg'] = '';
        } else {
            $this->data['errorMsg'] = '';
        }
        if (isset($resultJson->message)) {
            $this->data['message'] = $this->language->get($resultJson->message);
        } else {
            $this->data['message'] = '';
        }
        $this->data['hasError'] = isset($resultJson->hasError) ? $resultJson->hasError : false;
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['api_id_test'])) {
            $this->data['error_api_id_test'] = $this->error['api_id_test'];
        } else {
            $this->data['error_api_id_test'] = '';
        }

        if (isset($this->error['api_id_live'])) {
            $this->data['error_api_id_live'] = $this->error['api_id_live'];
        } else {
            $this->data['error_api_id_live'] = '';
        }

        if (isset($this->error['secret_key_test'])) {
            $this->data['error_secret_key_test'] = $this->error['secret_key_test'];
        } else {
            $this->data['error_secret_key_test'] = '';
        }

        if (isset($this->error['secret_key_live'])) {
            $this->data['error_secret_key_live'] = $this->error['secret_key_live'];
        } else {
            $this->data['error_secret_key_live'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/iyzico_single', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('payment/iyzico_single', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['iyzico_single_api_id_test'])) {
            $this->data['iyzico_single_api_id_test'] = $this->request->post['iyzico_single_api_id_test'];
        } else if (!empty($iyzicoTestKeys['login'])) {
            $this->data['iyzico_single_api_id_test'] = $iyzicoTestKeys['login'];
        } else {
            $this->data['iyzico_single_api_id_test'] = $this->config->get('iyzico_single_api_id_test');
        }

        if (isset($this->request->post['iyzico_single_api_id_live'])) {
            $this->data['iyzico_single_api_id_live'] = $this->request->post['iyzico_single_api_id_live'];
        } else if (!empty($iyzicoLiveKeys['login'])) {
            $this->data['iyzico_single_api_id_live'] = $iyzicoLiveKeys['login'];
        } else {
            $this->data['iyzico_single_api_id_live'] = $this->config->get('iyzico_single_api_id_live');
        }

        if (isset($this->request->post['iyzico_single_secret_key_test'])) {
            $this->data['iyzico_single_secret_key_test'] = $this->request->post['iyzico_single_secret_key_test'];
        } else if (!empty($iyzicoTestKeys['password'])) {
            $this->data['iyzico_single_secret_key_test'] = $iyzicoTestKeys['password'];
        } else {
            $this->data['iyzico_single_secret_key_test'] = $this->config->get('iyzico_single_secret_key_test');
        }

        if (isset($this->request->post['iyzico_single_secret_key_live'])) {
            $this->data['iyzico_single_secret_key_live'] = $this->request->post['iyzico_single_secret_key_live'];
        } else if (!empty($iyzicoLiveKeys['password'])) {
            $this->data['iyzico_single_secret_key_live'] = $iyzicoLiveKeys['password'];
        } else {
            $this->data['iyzico_single_secret_key_live'] = $this->config->get('iyzico_single_secret_key_live');
        }


        if (isset($this->request->post['iyzico_single_test'])) {
            $this->data['iyzico_single_test'] = $this->request->post['iyzico_single_test'];
        } else {
            $mode = $this->config->get('iyzico_single_test');
            if (isset($mode) && $mode == 0) {
                $this->data['iyzico_single_test'] = 0;
            } else {
                $this->data['iyzico_single_test'] = 1;
            }
        }

        if (isset($this->request->post['iyzico_single_method'])) {
            $this->data['iyzico_single_transaction'] = $this->request->post['iyzico_single_transaction'];
        } else {
            $this->data['iyzico_single_transaction'] = $this->config->get('iyzico_single_transaction');
        }


        if (isset($this->request->post['iyzico_single_order_status_id'])) {
            $this->data['iyzico_single_order_status_id'] = $this->request->post['iyzico_single_order_status_id'];
        } else {
            $this->data['iyzico_single_order_status_id'] = $this->config->get('iyzico_single_order_status_id');
        }

        $this->load->model('localisation/order_status');

        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


        if (isset($this->request->post['iyzico_single_status'])) {
            $this->data['iyzico_single_status'] = $this->request->post['iyzico_single_status'];
        } else {
            $this->data['iyzico_single_status'] = $this->config->get('iyzico_single_status');
        }

        if (isset($this->request->post['iyzico_single_sort_order'])) {
            $this->data['iyzico_single_sort_order'] = $this->request->post['iyzico_single_sort_order'];
        } else {
            $this->data['iyzico_single_sort_order'] = $this->config->get('iyzico_single_sort_order');
        }

//         $this->data['redirectUrl'] = $this->url->link('payment/iyzico/redirectToRegister', 'token=' . $this->session->data['token'], 'SSL');
        $this->template = 'payment/iyzico_single.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        //show registration link, if keys are not defined.
//         $izicoUnameTest = $this->config->get('iyzico_api_id_test');
//         $izicoUnameLive = $this->config->get('iyzico_api_id_live');
//         $izicoSenderTest = $this->config->get('iyzico_secret_key_test');
//         $izicoSenderLive = $this->config->get('iyzico_secret_key_live');

//         $this->data['displayLink'] = false;
//         if (!empty($izicoUnameTest) && !empty($izicoSenderTest) && !empty($izicoPasswordTest) && !empty($izicoChannelTest)) {
//             $this->data['displayLink'] = true;
//         }

        $this->response->setOutput($this->render());
    }


    public function install(){
    }

    public function uninstall() {
    }




    protected function validate() {
        if (!$this->user->hasPermission('modify', 'payment/iyzico_single')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (!$this->request->post['iyzico_single_api_id_test']) {
            $this->error['api_id_test'] = $this->language->get('error_api_id_test');
        }
        if (!$this->request->post['iyzico_single_secret_key_test']) {
            $this->error['secret_key_test'] = $this->language->get('error_secret_key_test');
        }

        if (!$this->request->post['iyzico_single_api_id_live']) {
            $this->error['api_id_live'] = $this->language->get('error_api_id_live');
        }

        if (!$this->request->post['iyzico_single_secret_key_live']) {
            $this->error['secret_key_live'] = $this->language->get('error_secret_key_live');
        }



        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function redirectToRegister() {

        //This function will redirect to register action in Iyzico with some post data.
        $url = $this->iyzicoSiteUrl . "merchantregistration-rest";

        $data = "REGISTRATION.BUSINESS_ID=OPENCART" .
                "&REGISTRATION.PARTNER_ID=100100100100" .
                "&PURPOSE=CHECK_PARTNER" .
                "&INFRASTRUCTURE.USED=bumbumleri" .
                "&YOUR_WEBSITE=website@example.com" .
                "&ABOUT_BUSINESS=myBusiness" .
                "&AVERAGE_PAYMENT=averagePayment" .
                "&BUSINESS_TYPE=businessType" .
                "&LEGAL_NAME=legalName" .
                "&TAX_ID=12534" .
                "&COMMERCIAL_REGISTER_NAME=Vprajapati" .
                "&COMMERCIAL_REGISTER_NUMBER=5151" .
                "&BUSINESS_START_DATE=test" .
                "&BUSINESS_ADDRESS_STREET=anandnagar" .
                "&BUSINESS_ADDRESS_CITY=palanpur" .
                "&BUSINESS_ADDRESS_STATE=Gujarat" .
                "&BUSINESS_ADDRESS_ZIP=12354" .
                "&COMPANY_REP_FIRST_NAME=Vijay" .
                "&COMPANY_REP_LAST_NAME=Prajapati" .
                "&COMPANY_REP_DOB=27.10.1988" .
                "&COMPANY_REP_SOCIAL_SECURITY_NUMBER=test" .
                "&CC_STATEMENT_BUSINESS_NAME=est" .
                "&CC_STATEMENT_PHONE=setes" .
                "&BANK_NAME=set" .
                "&BANK_BRANCH_NAME=set" .
                "&BANK_TOTAL_BRANCHES=set" .
                "&BANK_ACC_NO=test" .
                "&SWIFT=test" .
                "&IBAN=setes" .
                "&MERCHANT.EMAIL_ADDRESS=" . $this->config->get('config_email');

        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        //execute post
        $response = curl_exec($ch);

        $resultJson = json_decode($response);

        if (empty($resultJson)) {
            $this->session->data['errorMsg'] = 'Invalid call.';
            $this->redirect($this->url->link('payment/iyzico/index', 'token=' . $this->session->data['token'], 'SSL'));
        } else {
            $this->session->data['errorMsg'] = $resultJson->message;
            if (!empty($resultJson->redirectUrl)) {
                $argArr = array();
                $argArr['email'] = $this->config->get('config_email');
                $argArr['redirect'] = $this->url->link('payment/iyzico/index', 'token=' . $this->session->data['token'], 'SSL');
                $redUrl = json_encode($argArr);
                $queryString = base64_encode($redUrl);
                $redirectUrl = $resultJson->redirectUrl . '/queryString/' . $queryString;
                $this->redirect($redirectUrl);
            } else {
                $this->session->data['errorMsg'] = $resultJson->message;
                $this->redirect($this->url->link('payment/iyzico/index', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }
    }

}

?>