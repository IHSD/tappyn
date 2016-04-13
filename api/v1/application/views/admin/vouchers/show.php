<?php defined("BASEPATH") or exit('No direct script access allowed');

?>

<div class='row'>
    <div class='col-sm-10 col-sm-offset-1 content'>
        <div class='inner-content'>
            <div class='col-sm-12'>
                <?php $this->load->view('templates/notification', array(
            'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
            'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
            )); ?>
        </div>
        <h3><?php echo strtoupper($voucher->code); ?></h3>
        <hr>
        <h4>Uses</h4>
        <div class='col-sm-4'>
            <?php if(empty($voucher->uses)): ?>
                <div class='alert alert-warning'>
                    This voucher has not been used yet
                </div>
            <?php else: ?>
                <table class='table table-condensed'>
                    <tr>
                        <th>Time</th>
                        <th>Company</th>
                        <th>Contest ID</th>
                    </tr>
                    <?php foreach($voucher->uses as $use): ?>
                        <tr>
                            <td><?php echo date('D M d', $use->created_at); ?></td>
                            <td><?php echo $use->company->profile->name; ?></td>
                            <td>
                                <a href="<?php echo base_url().'admin/contests/show/'.$use->contest_id; ?>"><?php echo $use->contest_id; ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
