<header class="panel-heading">
    <div class="row">
        <div class="col-sm-6">
            <h3 style="margin: 0">Enquiry</h3>
        </div>
        <div class="col-sm-6" style="text-align: right">
            <h3 onclick="openAddEnquiry();" style="cursor: pointer; margin: 0;color:#000;"><i class="fa fa-plus-square" title="Add New Enquiry"></i></h3>
        </div>
    </div>
</header>
<div class="nav-tabs-custom ">
    <ul class="nav nav-tabs">
        <li class="<?= $tab_1 ?>"><a href="#tab_1" data-toggle="tab">Dashboard</a></li>
        <li class="<?= $tab_2 ?>"><a href="#tab_2" data-toggle="tab">Add</a></li>        
    </ul>
    <div class="tab-content">
        <div class="tab-pane <?= $tab_1 ?>" id="tab_1">
            <div class="col-sm-12 padding-0">
                <div class="col-sm-3 surveyRep">
                    Total Enquiries <?= $userAllEnqCount['ttl']; ?>
                </div>
                <div class="col-sm-3 surveyRep" style="border-left: 1px solid gainsboro; border-right: 1px solid gainsboro;">
                    CLosed <?= $userClosedEnqCount['ttl']; ?>
                </div>
                <div class="col-sm-3 surveyRep">
                    In Progress <?= $userActiveEnqCount['ttl']; ?>
                </div>
                <div class="col-sm-3 surveyRep">
                    Complete Ratio <?= round(($userClosedEnqCount['ttl'] / (!$userAllEnqCount['ttl']?'1':$userAllEnqCount['ttl'])) * 100, 2) ?>%
                </div>
            </div>
            <div class="col-sm-12 padding-20" >
                <div id="enquiryGraph" style="min-height: 300px; max-width: 100%; margin: 0 auto">
                    
                </div>
            </div>
            <div class="row col-md-12">
                <form name="enq-filter" action="enquiry/" method="POST">
                    <div class="row">
                        <div class="col-md-5">
                            <label for="filter-enq-status">Select Type</label>
                            <?php
                            $options = array('0' => 'Today', '1' => 'ALL', '2' => 'Closed');
                            $name = 'filter-enq-status';
                            $current = $enq_filter_status;
                            $js = ' class="form-control" id="filter-enq-status"';
                            echo form_dropdown($name, $options, $current, $js);
                            ?>                 
                        </div>
                        <div class="col-md-5">
                            <label for="filter-enq-date">Select Range</label>
                            <input type="text" class="form-control date-range-picker" name="filter-enq-date" readonly value="<?= $enq_filter_date; ?>" />
                        </div>
                        <div class="col-md-2">
                            <label>Action</label>
                            <input type="submit" class="btn btn-primary" value="Filter" />
                        </div>           
                    </div>
                </form>
            </div>
            <div class="row col-md-12">
                <label>Enquiry List</label>
            </div>
            <div class="row col-md-12 ">
                <ul class="list-group">
                    <?php
                    $list = null;
                    foreach ($availEnquiryList as $key => $value) {
                        $list .= '<li class="list-group-item">   <div class="row" style="margin:2px 0;vertical-asign:middle">
                                        <div class="col-md-1"><i style="padding:2px;"
                                        class="btn-'.($value['active']?$value['old']?'danger fa fa-tag':'primary fa fa-tag':'primary fa fa-lock').'"></i></div>
                                        <div class="col-md-3">' . $value['first_name'] . ' ' . $value['last_name'] . '</div>
                                        <div class="col-md-7">' . $value['enquiry'] . '</div>
                                        <button type="button" 
                                                onclick="window.location.assign(\'' . createUrl('enquiry/view/' . $value['id']) . '\')"
                                                class="col-md-1 btn btn-primary" 
                                                data-id="' . $value['id'] . '">View</button>
                                        </div>
                                </li>';
                    }
                    echo $list;
                    ?>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="tab-pane <?= $tab_2 ?>" id="tab_2">
            <div class="col-sm-12">
                <div class="row">
                    <?php 
                        if(!isset($enq_filter_status)){
                            $this->load->view(THEME . 'messages/inc-messages');    
                        } 
                    ?>
                </div>
                <form method="post" action="enquiry/" name="">
                    <div class="form-group">
                        <label class="control-label">First name <span class="error">*</span></label>
                        <input id="fname" class="form-control" maxlength="100" name="first_name" required="required" type="text" value="" /></div>
                    <div class="form-group"><label class="control-label">Last name <span class="error">*</span></label>
                        <input id="lname" class="form-control" maxlength="100" name="last_name" required="required" type="text" value="" /></div>
                    <div class="form-group"><label class="control-label">Telephone number <span class="error">*</span></label>
                        <input id="tele" class="form-control" maxlength="100" name="tel_number" required="required" type="text" value="" /></div>
                    <div class="form-group"><label class="control-label">Email address <span class="error">*</span></label>
                        <input id="email" class="form-control" maxlength="100" name="email_addr" required="required" type="text" value="" /></div>
                    <div class="form-group"><label class="control-label">Post code <span class="error">*</span></label>
                        <input id="pcode" class="form-control" maxlength="100" name="post_code" required="required" type="text" value="" /></div>
                    <div class="form-group"><label class="control-label">Reason for enquiry</label>            
                        <?php
                        $optionArr = array();
                        $selected = array();
                        $extra = ' id="reason" class="col-sm-9 form-control"';
                        foreach ($enquiryList as $key => $keyVal) {
                            $optionArr[$keyVal['id']] = $keyVal['desc'];
                        }
                        echo form_dropdown('enq_reason', $optionArr, $selected, $extra);
                        ?>
                    </div>
                    <div class="form-group"><label class="control-label">Enquiry <span class="error">*</span></label>
                        <textarea id="contents" class="form-control" maxlength="100" name="enquiry"></textarea></div>
                    <div class="form-group"><label class="control-label">Receive news and updates</label>
                        <input maxlength="100" name="receive_update_news" type="checkbox" value="1" /></div>
                    <div class="form-group"><label class="control-label">How did you hear about us</label>
                        <input id="hear" class="form-control" maxlength="100" name="how_reach" type="text" value="" /></div>
                    <div class="form-group">
                        <p style="text-align: center;"><input id="button" class="btn btn-primary" name="button" type="submit" value="Submit" /></p>
                        <p style="text-align: center;">Fields marked with <span class="error">*</span> are required.</p>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
        </div>        
    </div>
</div>
<script src="js/flotchart/jquery.flot.min.js" type="text/javascript"></script>
<script src="js/flotchart/jquery.flot.resize.min.js" type="text/javascript"></script>
<script src="js/flotchart/jquery.flot.pie.min.js" type="text/javascript"></script>
<script src="js/flotchart/jquery.flot.categories.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
<?php
foreach ($grapArray as $key => $keyVal) {
    ?>
            $.plot("#<?= $keyVal['name'] ?>",
    <?= getJSarrayFromPHPArray($keyVal['data'], 'LabelDataColor'); ?>
            , {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        innerRadius: 0.5,
                        label: {
                            show: true,
                            radius: 2 / 3,
                            formatter: labelFormatter,
                            threshold: 0.1
                        }

                    }
                },
                legend: {
                    show: false
                }
            });
    <?php
}
?>
    });
    /*
     * Custom Label formatter
     * ----------------------
     */
    function labelFormatter(label, series) {
        return "<div style='font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;'>"
                + label
                + "<br/>"
                + Math.round(series.percent) + "%</div>";
    }
</script>
<?php $this->load->view('headers/enquiry-form'); ?>
