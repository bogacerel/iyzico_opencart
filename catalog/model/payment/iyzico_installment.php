<?php 
class ModelPaymentIyzicoInstallment extends Model {
  	public function getMethod($address, $total) { 
		$this->language->load('payment/iyzico_installment');		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('iyzico_installment_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('iyzico_installment_total') > 0 && $this->config->get('iyzico_installment_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('iyzico_installment_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}	
		
		$method_data = array();
                $strlength = "";
                if(strlen($this->language->get('text_title') < 128)){
                    for($i=0;$i <= ( 128 - strlen($this->language->get('text_title')));$i++){
                        $strlength .= " ";
                    }
                }
		if ($status) {  
					$method_data = array( 
                            'code'       => 'iyzico_installment',
                            'title'      => $this->language->get('text_title_in_checkout') . $strlength . "   <style type='text/css'> table.radio tr td:first-child { vertical-align: top; } table.radio label { height:auto; } </style>",
                            'sort_order' => $this->config->get('iyzico_installment_sort_order') 
                    );                
                }
   
    	return $method_data;
  	}
}
?>