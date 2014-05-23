<?php
/**
 * This is settings class for iyzico Single plugin.
 *
 */
class ModelSettingIyzicoSingle extends Model{

    public $_server = "live";

    public function index(){}

    /**
     * This function returns url for generating tokens.
     * @param string $server
     * @return string $webServiceUrl
     */
    private function getApiUrlForGenerateToken(){

        $webServiceUrl = 'https://api.iyzico.com/v1/create';

        return $webServiceUrl;
    }

    /**
     * This function calls curl call for admin panel.
     * @param string $apiUrl
     * @param array $postFieldsArray
     * @return mixed
     */
    public function curlCall($postFieldsArray,$operation,$apiUrl = null) {

        $apiUrl = ($operation == 'tokenGeneration') ? $this->getApiUrlForGenerateToken() : $apiUrl;
        if($apiUrl == null){
            return "error. url not given.";
        }

        if (!empty($postFieldsArray)) {
            //url-ify the data for the POST
            $fields_string = '';
            foreach ($postFieldsArray as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            $fields_string = rtrim($fields_string, '&');
        }

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, count($apiUrl));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        //execute post
        $jsonResponse = curl_exec($ch);
        $err = curl_errno ( $ch );
        $errmsg = curl_error ( $ch );
        $header = curl_getinfo ( $ch );
        $httpCode = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );

        //close connection
        curl_close($ch);

        $header ['errno'] = $err;
        $header ['errmsg'] = $errmsg;
        return $jsonResponse;
    }

    /**
     * This function returns all required url for iyzico payment.
     * @param type $mode
     * @param type $installment
     * @return type
     */
    public function getPayonJSURL($mode,$installment = false){

        $payonJsPath = 'https://www.iyzico.com/frontend/widget/v1/widget.js?language=tr&mode='.$mode;

        if($installment == true){
            $payonJsPath .= '&installment=true';
        }

        return $payonJsPath;

    }

    /**
     * This function returns site url.
     */
    public function getSiteUrl(){
        if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443){
            $siteUrl =  HTTPS_SERVER;
        } else {
            $siteUrl =  HTTP_SERVER;
        }
        return $siteUrl;
    }

    /**
     * Convert amount for payment as per webservice.
     * @param float $amount
     * @param integer $precision
     * @return number
     */
    public function amountConverter($amount, $precision = 2) {
     $amount = round($amount,2);
     $num_arr = explode('.', $amount);
     $final_amount = 0;
        if (!empty($num_arr[1]) && strlen($num_arr[1]) > 2) {
            $ext_num = (float) substr($amount, 0, strlen($num_arr[0]) + 3);
            $final_num = (float) $ext_num + 0.01;
        } else {
            $final_num = $amount;
        }
        if (is_null($precision)) {
            $final_amount =  $final_num;
        } else {
            $final_amount =  sprintf("%.{$precision}f", $final_num);
        }
        $formattedAmount = str_replace(',', '.', $final_amount);
        return (float) $formattedAmount * 100;
    }
}

?>