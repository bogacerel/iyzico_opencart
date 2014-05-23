<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($message) { ?>
    <div class="<?php echo $hasError?'warning':'success'; ?>"><?php echo $message; ?></div>
    <?php } ?>
    <?php if ($errorMsg) { ?>
    <div class="warning"><?php echo $errorMsg; ?></div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button" id="saveKeys"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content">
     <!--       <?php if ($displayLink) { ?>
            <div style="margin-left: 10px;">
                <div class="buttons"><a href="<?php echo $redirectUrl; ?>" class="button"><?php echo $link_title; ?></a></div>
            </div>
            <?php } ?> -->

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_api_id_test; ?></td>
                        <td><input type="text" name="iyzico_single_api_id_test" value="<?php echo $iyzico_single_api_id_test; ?>" />
                            <?php if ($error_api_id_test) { ?>
                            <span class="error"><?php echo $error_api_id_test; ?></span>
                            <?php } ?></td>
                    </tr>                 
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_secret_key_test; ?></td>
                        <td><input type="text" name="iyzico_single_secret_key_test" value="<?php echo $iyzico_single_secret_key_test; ?>" />
                            <?php if ($error_secret_key_test) { ?>
                            <span class="error"><?php echo $error_secret_key_test; ?></span>
                            <?php } ?></td>
                    </tr>
	                 <tr>
	                    <td><span class="required">*</span> <?php echo $entry_api_id_live; ?></td>
	                    <td><input type="text" name="iyzico_single_api_id_live" value="<?php echo $iyzico_single_api_id_live; ?>" />
	                        <?php if ($error_api_id_live) { ?>
	                        <span class="error"><?php echo $error_api_id_live; ?></span>
	                        <?php } ?></td>
	                </tr>                 
	                <tr>
	                    <td><span class="required">*</span> <?php echo $entry_secret_key_live; ?></td>
	                    <td><input type="text" name="iyzico_single_secret_key_live" value="<?php echo $iyzico_single_secret_key_live; ?>" />
	                        <?php if ($error_secret_key_live) { ?>
	                        <span class="error"><?php echo $error_secret_key_live; ?></span>
	                        <?php } ?></td>
	                </tr>

                    
                    <tr>
                        <td><?php echo $entry_test; ?></td>
                        <td><?php if ($iyzico_single_test) { ?>
                            <input type="radio" name="iyzico_single_test" value="1" checked="checked" />
                            <?php echo $text_yes; ?>
                            <input type="radio" name="iyzico_single_test" value="0" />
                            <?php echo $text_no; ?><br>

                            <?php } else { ?>
                            <input type="radio" name="iyzico_single_test" value="1" />
                            <?php echo $text_yes; ?>
                            <input type="radio" name="iyzico_single_test" value="0" checked="checked" />
                            <?php echo $text_no; ?><br>

                            <?php } ?></td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_order_status; ?></td>
                        <td><select name="iyzico_single_order_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $iyzico_single_order_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td><select name="iyzico_single_status">
                                <?php if ($iyzico_single_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input type="text" name="iyzico_single_sort_order" value="<?php echo $iyzico_single_sort_order; ?>" size="1" /></td>
                    </tr>        
                </table>
            </form>
        </div>
    </div>
</div>
<?php echo $footer; ?>


<style type="text/css">

    #form input[type='text'], input[type='password'] {
        width: 250px;
    }

</style>
<script type="text/javascript">
                window.onload = function() {

                    var response = '<?php echo $response ?>';
                    if (response == 1) {
                        var el = document.getElementById('saveKeys');
                        el.click();
                    }
                };
</script>