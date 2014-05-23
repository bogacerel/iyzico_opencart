<?php 
if($this->data['error']){
    echo '<div style="" class="warning">'.$this->data['error'].'<img class="close" alt="" src="catalog/view/theme/default/image/close.png"></div>';
}else{        	
?>
<h2><?php echo $text_credit_card; ?></h2>
<div class="content" id="payment">
    <form class="iyzico-payment-form" id="<?php echo $this->data['access_token']; ?>" >VISA MASTER</form>    
</div>    
<script>
    /**
     * This is main function called when page loads.
     * step 1: remove allready added iyzico payment JS.
     * step 2: loadScript: will load related JS. and wait till javascript full loads.
     * step 3: loadIyzicoPaymentForm: will call payment form function from loaded javascript file through step 2.
     * @returns {undefined}
     */
    removeAllIyzicoJS(function() {
        loadScript('<?php echo $payonJsPath;?>', function() {
            loadIyzicoPaymentForm();
        });
    });

    /**
     * This function removes already appended javascripts appended to head for iyzico payment.
     * @param {type} callback
     * @returns {undefined} 
     * */
    function removeAllIyzicoJS(callback) {
        $("#iyzico-payon-cnp").remove();
        $("#iyzico-payon-js").remove();
        $("link").each(function() {
            var url = this.href;
            if (url.indexOf("/frontend/widget/v1/") != -1) {
                $(this).remove();
            }
        });
        if (callback && typeof (callback) === "function") {
            callback();
        }
    }

    /**
     * This function adds javascript to head of html and callbacks when it loads completly.
     * @param {string} url
     */
    function loadScript(url, callback) {
        var script = document.createElement("script")
        script.type = "text/javascript";

        if (script.readyState) {  //IE
            script.onreadystatechange = function() {
                if (script.readyState == "loaded" ||
                        script.readyState == "complete") {
                    script.onreadystatechange = null;
                    callback();
                }
            };
        } else {  //Others
            script.onload = function() {
                callback();
            };
        }

        script.src = url;
        document.getElementsByTagName("head")[0].appendChild(script);
    }
</script>
<style>
    .ic-table{
        width: 96%;
    }
    .submitInput{
        width: 99%;
    }
    input[type='text'], input[type='password'], textarea {
		background: none;
		border: 1px solid #CCCCCC;
		padding: 3px;
		margin-left: 0px;
		margin-right: 0px;
    }
</style>
<?php 
}
?>