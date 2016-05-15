<div class="row">
    <?php $this->load->view(THEME . 'messages/inc-messages'); ?>
</div>
<header class="panel-heading">
    <div class="row">
        <div class="col-sm-4">
            <a href="<?= createUrl('enquiry/') ?>"><i class="fa fa-2x fa-arrow-left" title="Back Enquiry" style="color: #fff"></i></a>
        </div>
        <div class="col-sm-4" style="text-align: center">
            <h3 style="margin: 0">Follow Up</h3>
        </div>
    </div>
</header>
<section>
    <div class="row">
        <div>
            <?php
            $this->load->view(THEME . 'messages/inc-messages');
            ?>
        </div>
        <div class="col-sm-12">
            <form method="POST" name="" action="enquiry/addFollow">
                <input type="hidden" name="enq_id" value="<?= $enq_det['id']; ?>" />
                <div class="form-group clearfix">
                    <div class="col-sm-2">
                        <label class="common-label-lay">Name </label>
                    </div>
                    <div class="col-sm-10" >
                        <div class="common-form-lay">
                            <?=
                            $enq_det['first_name'], ' ', $enq_det['last_name'];
                            'class="form-control"'
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <div class="col-sm-6 padding-0">
                        <div class="col-sm-4"> 
                            <label class="common-label-lay">Phone </label>
                        </div>
                        <div class="col-sm-8">
                            <div class="common-form-lay">
                                <?=
                                $enq_det['tel_number'];
                                'class="form-control"'
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 padding-0">
                        <div class="col-sm-4">
                            <label class="common-label-lay">Email  </label>
                        </div>
                        <div class="col-sm-8">
                            <div class="common-form-lay">
                                <?=
                                $enq_det['email_addr'];
                                'class="form-control"'
                                ?>
                            </div>
                        </div>    
                    </div>
                </div>
                <div class="form-group clearfix">
                    <div class="col-sm-2">
                        <label class="common-label-lay">Enquiry </label>
                    </div>
                    <div class="col-sm-10">
                        <div class="common-form-lay">
                            <?=
                            $enq_det['enquiry'];
                            'class="form-control"'
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <div class="col-sm-2">
                        <label class="common-label-lay">Follow up mode</label>
                    </div>
                    <div class="col-sm-10">
                        <select class="form-control" name="follow_way" id="follow_way">
                            <option value="telephone">Telephone</option>
                            <option value="email">Email</option>
                            <option value="visit">Visit</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <div class="col-sm-2">
                        <label class="common-label-lay">Comment</label>
                    </div>
                    <div class="col-sm-10">
                        <textarea class="form-control" required name="description" id='description' ></textarea>       
                    </div>
                </div>
                <div class="form-group clearfix">
                    <div class="col-sm-6 padding-0">
                        <div class="col-sm-4">
                            <label class="common-label-lay">Enquiry Status</label>                   
                        </div>
                        <div class="col-sm-8">
                            <select class="form-control" name="deactive" id="deactive">
                                <option value="">In Process</option>
                                <option value="1">Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="col-sm-4">
                            <label class="common-label-lay">Next Follow Date</label>
                        </div>
                        <div class="col-sm-8 padding-0">
                            <input type="text" value="<?= $todayDate; ?>" readonly required name='next_follow_up' id='next_follow_up' class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <p style="text-align: right;"><input id="button" class="btn btn-primary" name="button" type="submit" value="Submit" /></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-12">
        <h4 style="padding: 5px; background: #0081CA; color:#fff">Follow Up comments</h4>
        <ul class="nav boldlist">
            <?php
            $followList = '<li class="col-md-12 padding-0" style="background: #EBEBEB; padding: 5px 0; font-size: 15px;">  
                                    <div class="col-md-3">
                                        Enquiry Date
                                    </div>
                                    <div class="col-md-6" >
                                        Description
                                    </div>
                                    <div class="col-md-3" >
                                        Follow up Date
                                    </div>                                    
                                </li>
                        ';

            foreach ($enq_follow as $key => $kval) {
                $followList .= '<li class="col-md-12 padding-0">  
                                <div class="col-md-3" >'
                        . date("d-m-Y", strtotime($enq_det['insert_time'])) . '</div>
                                                        <div class="col-md-6" >'
                        . $kval['description'] . '</div>
                                                        <div class="col-md-3" >'
                        . date("d-m-Y", strtotime($kval['next_follow_up'])) . '</div>
                                                    </li>';
            }
            echo $followList;
            ?>
        </ul>
    </div>
</section>
<?php $this->load->view('headers/enquiry-followup'); ?>