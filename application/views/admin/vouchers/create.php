<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
        <div class='row'>
            <div class='col-sm-8 col-sm-offset-2 content'>
                <div class='inner-content'>
                    <div class='col-sm-12'>
                        <?php $this->load->view('templates/notification', array(
                    'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
                    'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
                    )); ?>
                </div>
                <h3>Create Voucher</h3>
                    <div class='col-sm-12'>
                        <form class='form' method='post' action="<?php echo base_url().'admin/vouchers/create'; ?>">
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label>Code</label>
                                    <input class='form-control' type='text' name='code' placeholder='Unique Discount Code'>
                                </div>
                                <div class='form-group'>
                                    <label>Discount</label>
                                    <select class='form-control' name='discount_type'>
                                        <option value='amount'>Flat Amount</option>
                                        <option value='percentage'>Percentage</option>
                                    </select>
                                </div>
                                <div class='form-group'>
                                    <label>Discount Value</label>
                                    <input type='text' class='form-control' name='value'>
                                </div>
                                <div class='form-group'>
                                    <label>Expiration Type</label>
                                    <select class='form-control' name='expiration' id='expiration_type'>
                                        <option value='time_length'>Time Range</option>
                                        <option value='uses'>Usage Limit</option>
                                    </select>
                                <div class='form-group' id='datepicker-container'>
                                    <label>Date Range</label>
                                    <div class="input-daterange input-group" id="datepicker">
                                        <input type="text" class="input-sm form-control" name="start_time" />
                                        <span class="input-group-addon">to</span>
                                        <input type="text" class="input-sm form-control" name="stop_time" />
                                    </div>
                                </div>
                                <div class='form-group' id='usage_limit_container' style='display:none'>
                                    <label>Usage Limit</label>
                                    <input type='number' class='form-control' name='usage_limit' value='10'>
                                </div>
                            </div>
                            <button class='btn btn-primary' type='submit'>Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<script>
    var date_config = {
        format: 'yyyy-mm-dd'
    };
    $('#datepicker-container .input-daterange').datepicker(date_config);
    $('#expiration_type').change(function(e) {
        if($('#expiration_type option:selected').val() == 'time_length')
        {
            $('#datepicker-container').show();
            $("#usage_limit_container").hide();
        } else {
            $('#datepicker-container').hide();
            $("#usage_limit_container").show();
        }
    })
</script>
<?php $this->load->view('templates/footer'); ?>
