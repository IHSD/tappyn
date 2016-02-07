<div class='twitter-outer-wrapper'>
    <div class='twitter-header center-content'>
        <img height='auto' width='60%' src="<?php echo base_url().'public/img/twitter-header.png' ?>">
    </div>
    <div class='twitter-form-wrapper'>
        <div class='twitter-logo'>
            <img height='35' width='35' src="<?php echo base_url().'public/img/twitter.png'; ?>">
        </div>
        <div class='twitter-input-wrapper'>
            <div class='form-row'>
               <?php echo form_textarea(array('name' => 'text','value' => '','placeholder' => ($contest->objective == 'app_installs' ? "Use wit & humor to capture what makes this app unique" : ($contest->objective == 'website_clicks' ? "Use wit & humor to capture what makes this business unique" : ($contest->objective == 'engagement' ? "Create compelling content this business could supply" : "Create a captivating tweet"))), 'type' => 'text'));?>
            </div>
            <a href='#' class='twitter-button'></a>
        </div>
    </div>
</div>
<style>
.twitter-outer-wrapper {
    border: 2px solid #0084B4;
    padding: 10px;
}
.twitter-form-wrapper {
    background-color: #E5F2F7;
    border: 1px solid #BFE0EC;
    color: #0084B4;
    border-radius: 5px;
    padding: 10px;
}
.twitter-input-wrapper {

}
.twitter-logo {
    float:left;
}
.twitter-header {
    margin-top:5px;
    margin-bottom: 10px;
}
.just-now {
    font-size:0.7em
}
</style>
