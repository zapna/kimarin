<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<?php include_partial('dashboard_header', array('customer'=> $customer, 'section'=>__('Change Number')) ) ?>
<script type="text/javascript">
jQuery(function(){
    jQuery('#changenumber').validate({
        rules: {
            existingNumber:{
                required: true,
                minlength: 8,
                digits: true
            },
           newNumber:{
                required: true,
                minlength: 8,
                digits: true
            }
        },
        messages: {
            existingNumber:{
                required: "<?php echo __('Please Enter Old Mobile Number')?>",
                minlength: "<?php echo __('Atleast 8 digits are required')?>",
                digits: "<?php echo __('Please Enter only digits')?>"
            },
            newNumber:{
                required: "<?php echo __('Please Enter New Mobile Number')?>",
                minlength: "<?php echo __('Atleast 8 digits are required')?>",
                digits: "<?php echo __('Please Enter only digits')?>"
            }
        }
    });
    jQuery('#changenumber').submit(function(){
        var newnum = jQuery('#newNumber').val();
        var startnum = newnum.substring(0,4);
        if(startnum == "0034"){
            document.getElementById("error").innerHTML="<?php echo __('Enter mobile number without 0034')?>";
            return false;
        }
        var startnum = newnum.substring(0,2);
        if(startnum == "00"){
            document.getElementById("error").innerHTML="<?php echo __('Enter mobile number without 00')?>";
            return false;
        }
        var startnum = newnum.substring(0,1);
        if(startnum == "0"){
            document.getElementById("error").innerHTML="<?php echo __('Enter mobile number without 0')?>";
            return false;
        }
    })
      
});
</script>

<?php if ($sf_user->hasFlash('change_number_message')): ?>
        <div class="alert_bar">
         <?php echo __($sf_user->getFlash('change_number_message')) ?>
        </div>
    <?php endif; ?>
<div class="left-col">    
    <?php include_partial('navigation', array('selected' => 'dashboard', 'customer_id' => $customer->getId())) ?><br />
    
    <div class="split-form">
        <p><?php echo __('You can change your number maximum 2 times in a month.');?></p>
        <form method="post" name="changenumber" id="changenumber" class="split-form-sign-up" action="<?php echo url_for($targetUrl.'customer/changeNumber') ?>" style="padding-left: 0px">
     <h1><?php //echo __('Change Number');?></h1>
    	<ul class="fl col">
            <li>
                <label><?php echo __('Old Mobile Number') ?></label>
                <input type="text" name="existingNumber" style="margin-bottom:0px" value="<?php echo $customer->getMobileNumber();?>" readonly="readonly" />

            </li>
            <li>
                <label><?php echo __('New Mobile Number') ?><br />0034*</label>
                <input type="text" name="newNumber" id="newNumber" style="margin-bottom:0px" />
                <span class="alertmsg" id="error"></span>
            </li>

                <?php  $c = new Criteria();
                $c->add(ProductPeer::ID, 3);
                $product = ProductPeer::doSelectOne($c);  ?>
                   <input type="hidden" name="product" value="<?php echo $product->getID(); ?>" />
             
	          <li class="fr buttonplacement">
                    <?php $button_disable='';
                    if($disable){
                           $button_disable = 'disabled="disabled"';
                    }
                    ?>  
	            <input  class="butonsigninsmall blockbutton" style="padding: 5px 5px 5px 5px; margin-right: 22px !important;" type="submit" <?php echo $button_disable;?> value="<?php echo __('Next')?>" />
	          </li>

	</ul>

</form>
    <div class="clr"></div>
    </div>
</div>    <?php include_partial('sidebar') ?>