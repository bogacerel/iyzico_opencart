<?php

class ControllerPaymentIyzicoInstallment extends Controller {

    private $error = array();

    /**
     * This function is called when admin side iyzico payment module is edited.
     */
    public function index() {
        $this->language->load('payment/iyzico_installment');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');


        //save form data
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('iyzico_installment', $this->request->post);
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
            'href' => $this->url->link('payment/iyzico_installment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('payment/iyzico_installment', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['iyzico_installment_api_id_test'])) {
            $this->data['iyzico_installment_api_id_test'] = $this->request->post['iyzico_installment_api_id_test'];
        } else if (!empty($iyzicoTestKeys['login'])) {
            $this->data['iyzico_installment_api_id_test'] = $iyzicoTestKeys['login'];
        } else {
            $this->data['iyzico_installment_api_id_test'] = $this->config->get('iyzico_installment_api_id_test');
        }

        if (isset($this->request->post['iyzico_installment_api_id_live'])) {
            $this->data['iyzico_installment_api_id_live'] = $this->request->post['iyzico_installment_api_id_live'];
        } else if (!empty($iyzicoLiveKeys['login'])) {
            $this->data['iyzico_installment_api_id_live'] = $iyzicoLiveKeys['login'];
        } else {
            $this->data['iyzico_installment_api_id_live'] = $this->config->get('iyzico_installment_api_id_live');
        }

        if (isset($this->request->post['iyzico_installment_secret_key_test'])) {
            $this->data['iyzico_installment_secret_key_test'] = $this->request->post['iyzico_installment_secret_key_test'];
        } else if (!empty($iyzicoTestKeys['password'])) {
            $this->data['iyzico_installment_secret_key_test'] = $iyzicoTestKeys['password'];
        } else {
            $this->data['iyzico_installment_secret_key_test'] = $this->config->get('iyzico_installment_secret_key_test');
        }

        if (isset($this->request->post['iyzico_installment_secret_key_live'])) {
            $this->data['iyzico_installment_secret_key_live'] = $this->request->post['iyzico_installment_secret_key_live'];
        } else if (!empty($iyzicoLiveKeys['password'])) {
            $this->data['iyzico_installment_secret_key_live'] = $iyzicoLiveKeys['password'];
        } else {
            $this->data['iyzico_installment_secret_key_live'] = $this->config->get('iyzico_installment_secret_key_live');
        }

        if (isset($this->request->post['iyzico_installment_test'])) {
            $this->data['iyzico_installment_test'] = $this->request->post['iyzico_installment_test'];
        } else {
            $mode = $this->config->get('iyzico_installment_test');
            if (isset($mode) && $mode == 0) {
                $this->data['iyzico_installment_test'] = 0;
            } else {
                $this->data['iyzico_installment_test'] = 1;
            }
        }

        if (isset($this->request->post['iyzico_installment_order_status_id'])) {
            $this->data['iyzico_installment_order_status_id'] = $this->request->post['iyzico_installment_order_status_id'];
        } else {
            $this->data['iyzico_installment_order_status_id'] = $this->config->get('iyzico_installment_order_status_id');
        }

        $this->load->model('localisation/order_status');

        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


        if (isset($this->request->post['iyzico_installment_status'])) {
            $this->data['iyzico_installment_status'] = $this->request->post['iyzico_installment_status'];
        } else {
            $this->data['iyzico_installment_status'] = $this->config->get('iyzico_installment_status');
        }

        if (isset($this->request->post['iyzico_installment_sort_order'])) {
            $this->data['iyzico_installment_sort_order'] = $this->request->post['iyzico_installment_sort_order'];
        } else {
            $this->data['iyzico_installment_sort_order'] = $this->config->get('iyzico_installment_sort_order');
        }

        $this->template = 'payment/iyzico_installment.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    public function install(){
    }

    public function uninstall() {
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'payment/iyzico_installment')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['iyzico_installment_api_id_test']) {
            $this->error['api_id_test'] = $this->language->get('error_api_id_test');
        }
        if (!$this->request->post['iyzico_installment_secret_key_test']) {
            $this->error['secret_key_test'] = $this->language->get('error_secret_key_test');
        }

        if (!$this->request->post['iyzico_installment_api_id_live']) {
            $this->error['api_id_live'] = $this->language->get('error_api_id_live');
        }

        if (!$this->request->post['iyzico_installment_secret_key_live']) {
            $this->error['secret_key_live'] = $this->language->get('error_secret_key_live');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

}

?>