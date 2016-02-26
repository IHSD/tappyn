<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
        <div class='row'>
            <div class='col-sm-10 col-sm-offset-1 content'>
                <h2>Admin</h2>
                <hr>
                <div class='row'>
                    <div class='col-sm-2'>
                        <h3>Users</h3>
                    </div>
                    <div class='col-sm-10'>
                        <div id="chartContainer">FusionCharts XT will load here!</div>
                    </div>
                </div>
                <hr>
                <div class='row'>
                    <div class='col-sm-2'>
                        <h3>Signups</h3>
                    </div>
                    <div class='col-sm-10'>

                    </div>
                </div>
                <hr>
                <div class='row'>
                    <div class='col-sm-2'>
                        <h3>Submissions</h3>
                    </div>
                    <div class='col-sm-10'>

                    </div>
                </div>
                <hr>
                <div class='row'>
                    <div class='col-sm-2'>
                        <h3>Contests</h3>
                    </div>
                    <div class='col-sm-10'>

                    </div>
                </div>
                <hr>
                <div class='row'>
                    <div class='col-sm-2'>
                        <h3>Votes</h3>
                    </div>
                    <div class='col-sm-10'>

                    </div>
                </div>
                <hr>
            </div>
        </div>

<script>
    $(document).ready(function(){
        $.get({
            url : "<?php echo base_url().'admin/home/data' ?>",
            success: function(response){console.log(response);}
        })
        FusionCharts.ready(function(){

          var revenueChart = new FusionCharts({
            "type": "column2d",
            "renderAt": "chartContainer",
            "width": "500",
            "height": "300",
            "dataFormat": "json",
            "dataSource": {
              "chart": {
                  "caption": "Monthly revenue for last year",
                  "subCaption": "Harry's SuperMart",
                  "xAxisName": "Month",
                  "yAxisName": "Revenues (In USD)",
               },
              "data": []
            }
        });

        revenueChart.render();
    })
})
</script>



<?php $this->load->view('templates/footer'); ?>
