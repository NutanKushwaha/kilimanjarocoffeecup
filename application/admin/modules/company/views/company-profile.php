<style>
    .company-prof-address-block .col-lg-6:nth-child(1n) {
        padding-left: 0px;
        padding-right: 15px;
    }
    .company-prof-address-block .col-lg-6:nth-child(n+2) {
        padding-left: 15px;
        padding-right: 0;
    }
    .table-company-profile-detail{
        font-size: 12.5px;
    }
    .table-company-profile-detail tr, .table-company-profile-detail td {
        border: 1px solid #cecece;
    }
    .table-company-profile-detail td:nth-child(odd){
        background: #eee none repeat scroll 0 0;
        width: 40%;
    }
    .user-compy-contact-info .table-company-profile-detail td:nth-child(2n+1) {
        width: 19%;
    }
    .profile-section{
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }
    .profile-section h3 {
        font-size: 20px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
</style>
<?php
    /*
    * ['comp_detail'] = [
                        'details',
                        'addresses',
                        'employees'
                    ]
    */    
    extract($comp_detail);
?>
<link href="<?php echo base_url('/css/datatables/dataTables.bootstrap.css') ?>" rel="stylesheet" type="text/css" />
<header class="panel-heading rightbar-header-section">
    <div class="row">
        <div class="col-sm-9 padding-0">
            <h3 style="margin: 0; color: #333;">  <i class="fa fa-university"></i> Organization Profile <span style=""><small style="font-size:12px;font-style:italic;">(SAP data)</small></span> </h3> 
            <!--<small style="float:left;color:black"><i>(SAP data)</i></small>-->
        </div>
        <div class="col-sm-3">
            <a class="btn btn-primary pull-right" type="button" data-toggle="tooltip" 
				href="<?php echo base_url('dashboard') ?>" data-original-title="" title=""><i class="fa fa-share"></i> Back</a>
        </div>
    </div>
</header>
<div class="col-lg-12 padding-0" style="">
    <div class="internal-bottom-container profile-middle-container">
        <div class="col-lg-12 user-profile-info profile-section">
            <div class="col-lg-12 padding-0">
                <h3>Details</h3>
            </div>
            <div class="col-lg-12 padding-0">
                <div class="col-lg-6 pad-left0">
                    
                    <!-- Start new Table  -->
                    <table class="table table-company-profile-detail">
                        <tbody>
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td> <?= $details['name']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Company Code:</strong></td>
                                <td><?= $details['company_code']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Contact Person:</strong></td>
                                <td><?= $details['contact_person']; ?></td>
                            </tr>
                            
                            
                        </tbody>
                    </table>                                       
                </div>
                <div class="col-lg-6 pad-right0">
                    
                    
                    <table class="table table-company-profile-detail">
                        <tbody>
                            <tr>
                                <td> <strong> Login account status:</strong></td>
                                <td> 
                                    <?php 
                                        $attr = '  	class="btn btn-danger" 
													style="font-size:12px; float:right; 
															color:white; 
															padding-bottom:5px;" 
													id="send_account_activate_email"; ';
                                    echo $details['account_active'] ? 'Active' : 'Deactive '.anchor('company/activate_email/'.$details[ 'id' ], 
																									'<i class="fa fa-envelope-o" style="margin-right: 2px;"></i> Send activation email', 
																									$attr); 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong> Email:</strong></td>
                                <td><?= $details['email_address']; ?></td>
                            </tr>
                            <tr>
                                <td><strong> Phone1: </strong></td>
                                <td><?= $details['phone1']; ?></td>
                            </tr>
                            <tr>
                                <td><strong> Phone2 </strong></td>
                                <td><?= $details['phone2']; ?> </td>
                            </tr>
                            <tr>
								<?php
									$hidden = $attr = [];
									$attr[ 'id' ] 	= 'company-logo';
									$attr[ 'name' ] = 'company-logo';
									$hidden[ 'company_id' ] = $details[ 'id' ];
									echo form_open_multipart('company/logo_add', $attr, $hidden);
								?>
                                
                                <td>
									<div style="width:100%">
											<?php
												//echo form_upload('image');																								
											?>											
									</div>
									<div style="width:100%">
										<div  style="width:60%; float:left;">
											<small> Allowed format gif|jpg|png</small>
										</div>
										<div  style="width:40%; float:right; margin-top:4px;">
										<?php
											echo form_submit('upload_logo', 'Upload', ' style="pull-right"  class="btn btn-primary"');
										?>
										</div>
									</div>
                                </td>
								<?php
									echo form_close();
								?>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>  
            </div>          
        </div>
        
		
        <div class="col-lg-12 user-addr-info profile-section">
            <div class="col-lg-12 padding-0">
                <h3>Address</h3>
            </div>
            <div class="col-lg-12 padding-0 company-prof-address-block">
            <?php  foreach($addresses as $address) { ?>
                <div class="col-lg-6">
                      <table class="table table-company-profile-detail">
                        <tbody>
                            <tr>
                                <td><strong>Address Name:</strong></td>
                                <td> <?= $address['address_name']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Street:</strong></td>
                                <td><?= $address['street']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Zip code:</strong></td>
                                <td><?= $address['zip_code']; ?></td>
                            </tr>
                            <tr>
                                <td><strong> City:</strong></td>
                                <td><?= $address['city']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>County:</strong></td>
                                <td><?= $address['county']; ?></td>
                            </tr>
                                                        
                        </tbody>
                    </table>             
                </div>
            <?php  } ?>
            </div>
        </div>

        <div class="col-lg-12 user-compy-contact-info profile-section">        
            <div class="col-lg-12 padding-0">
                <h3>Contact Person</h3>
            </div>
            <div class="col-lg-12 padding-0">
            <?php  foreach($employees as $employe) { ?>
                <div class="col-lg-12 padding-0" style="">
                    
                    
                      <table class="table table-company-profile-detail">
                        <tbody>
                            <tr>
                                <td><strong> Name:</strong></td>
                                <td> <?= $employe['title'].$employe['name']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Full name:</strong></td>
                                <td><?= $employe['first_name'].$employe['last_name']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Position:</strong></td>
                                <td><?= $employe['position']; ?></td>
                            </tr>
                            <tr>
                                <td><strong> Address:</strong></td>
                                <td><?= $employe['address']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Mobile:</strong></td>
                                <td><?= $employe['mobile']; ?></td>
                            </tr>
                            
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?= $employe['email']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Fax:</strong></td>
                                <td><?= $employe['fax']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Phone 1:</strong></td>
                                <td><?= $employe['phone1']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Phone 2:</strong></td>
                                <td><?= $employe['phone2']; ?></td>
                            </tr>

                            
                        </tbody>
                    </table>
                              
                </div>
          
                    <?php  } ?>
                
            </div>
        </div>        
    </div>
</div>
<link href="<?php echo base_url('/js/colorpicker/bootstrap-colorpicker.css') ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('/js/colorpicker/bootstrap-colorpicker.js') ?>"></script>
<script>									
	$(function(){
		$('#menu-base-color').colorpicker({
			color:'<?= $details['theme_menu_base'] ?>',
			format: 'hex'
		});
		$('#menu-hover-color').colorpicker({
			color:'<?= $details['theme_menu_hover'] ?>',
          format: 'hex'
        });		
	});
</script>
