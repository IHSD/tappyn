<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
  // This identifies your website in the createToken call below
  Stripe.setPublishableKey("<?php echo $publishable_key; ?>");
  jQuery(function($) {
      $('#payment-form').submit(function(event) {
        var $form = $(this);

        // Disable the submit button to prevent repeated clicks
        $form.find('button').prop('disabled', true);

        Stripe.card.createToken($form, stripeResponseHandler);

        // Prevent the form from submitting with the default action
        return false;
      });

      function stripeResponseHandler(status, response) {
      var $form = $('#payment-form');

      if (response.error) {
        // Show the errors on the form
        $form.find('.payment-errors').text(response.error.message);
        $form.find('button').prop('disabled', false);
      } else {
        // response contains id and card, which contains additional card details
        var token = response.id;
        // Insert the token into the form so it gets submitted to the server
        $form.append($('<input type="hidden" name="stripeToken" />').val(token));
        // and submit
        $form.get(0).submit();
      }
    };
    });
</script>
<?php $this->load->view('templates/notification', array(
'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
)); ?>

<?php if(!empty($account->external_accounts->data)): ?>
    <?php foreach($account->external_accounts->data as $source): ?>
        <?php echo form_open('accounts/remove_method'); ?>
        <?php echo form_input(array('name' => 'source_id', 'type' => 'hidden', 'value' => $source->id)); ?>
        <?php echo form_submit('remove', "Remove Method"); ?>
        <?php echo form_close(); ?>
    <?php endforeach; ?>
<?php endif; ?>
<section class='innerpage'>
	<div class='small-12 medium-6 div-center'>
	<form action="" method="POST" id="payment-form">
	<span class="payment-errors"></span>
     <div class='small-12 columns'> 
    <div class='form-row'>
      		<label><span>Account Type</span></label>
      		<?php echo form_dropdown('account_type', array(
          'card' => "Debit Card",
          'bank_account' => "Bank Account"
      )); ?>
    </div>
    </div>
    <div class='small-12 columns'> 
	  <div class="form-row">
	    <label>
	      <span>Card Number</span>
	      <input type="text" size="20" data-stripe="number"/>
	    </label>
	  </div>
    </div>
    <div class='small-4 columns'> 
	  <div class="form-row">
	    <label>
	      <span>CVC</span>
	      <input type="text" size="4" data-stripe="cvc"/>
	    </label>
	  </div>
    </div>
  	<div class='small-4 columns'>
    
      <div class="form-row">
       <label><span>Month Exp</span></label>
  	      <select  data-stripe="exp-month">
             <option value='01'>January</option>
             <option value='02'>February</option>
             <option value='03'>March</option>
             <option value='04'>April</option>
             <option value='05'>May</option>
             <option value='06'>June</option>
             <option value='07'>July</option>
             <option value='08'>August</option>
             <option value='09'>September</option>
             <option value='10'>October</option>             
            <option value='11'>November</option>
              <option value='12'>December</option>  	
          </select>
      </div>
    </div>
    <div class='small-4 columns'>
     <div class='form-row'>
        <label><span>Year Exp</span></label>
  	    <select  data-stripe="exp-year">
             <option value='2016'>2016</option>
             <option value='2017'>2017</option>
             <option value='2018'>2018</option>
             <option value='2019'>2019</option>
             <option value='2020'>2020</option>
             <option value='2021'>2021</option>
             <option value='2022'>2022</option>
             <option value='2023'>2023</option>
             <option value='2024'>2024</option>
             <option value='2025'>2025</option>             
             <option value='2026'>2026</option>
             <option value='2027'>2027</option>
        </select>
  	  </div>
    </div>
	  <input type='hidden' data-stripe='currency' value='usd'/>
	  <div class='form-row text-center'><button class='btn' type="submit">Submit Payment</button></div>
	</form>
	</div>
</section>




<?php $this->load->view('templates/footer'); ?>
