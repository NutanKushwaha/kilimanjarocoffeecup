<div style='text-align: center'>
    <?php $this->load->view('inc-messages'); ?>
</div>
<?php // e( $allAddress) ?>
<div class="col-lg-12">
    <div class="columns-w">														
        <div class="columns-w">
            <?php $this->load->view('themes/'.THEME.'/layout/inc-dashboard'); ?>
            <div class="account-create yt-main-right yt-main col-main col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="page-title">
                    <h1>Address Information</h1>
                </div>
                <div class="add_add">
                    <a href="<?php echo createUrl('customer/contact/add_address') ?>">Add Address</a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Address recipient</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Address 1</th>
                            <th>Address 2</th>
                            <th>City</th>
                            <th>County</th>
                            <th>Post code</th>
                            <th>country</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($allAddress['num_rows'] > 0) {
                            foreach ($allAddress['result'] as $address) {
                                ?>
                                <tr>
                                    <td><?php echo $address['uadd_recipient'] ?></td>
                                    <td><?php echo $address['uadd_phone'] ?></td>
                                    <td><?php echo $address['uadd_company'] ?></td>
                                    <td><?php echo $address['uadd_address_01'] ?></td>
                                    <td><?php echo $address['uadd_address_02'] ?></td>
                                    <td><?php echo $address['uadd_city'] ?></td>
                                    <td><?php echo $address['uadd_county'] ?></td>
                                    <td><?php echo $address['uadd_post_code'] ?></td>
                                    <td><?php echo $address['uadd_country'] ?></td>
                                    <td><a href="<?php echo createUrl('customer/contact/edit_address/') . $address['uadd_id'] ?>">Edit</a></td>
                                </tr>
    <?php }
} else { ?>
                            <tr>
                                <td colspan="9">
                                    <h4>
                                        No Address Found.
                                    </h4>
                                </td>
                            </tr>
<?php } ?>
                    </tbody>
                </table>
            </div>
        </div>



    </div>
</div>