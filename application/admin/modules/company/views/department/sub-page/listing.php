<div class="row">
	<h4>Kits List</h4>
	<div class="span12">
		<div class="tab-pane" id="formcontrols">
			<form id="edit-profile" class="form-horizontal">
				<fieldset>					
					<div class="control-group">
						<?php
							if(!count($list)){
								$this->load->view(THEME.'messages/inc-norecords');
							}else{						
								$listhtml = null;								
								foreach($list as $lindex => $ldetail){
									$listhtml .= '<li 	class="mar-bot10" style="width: 100%; float: left; 
														border: 1px solid rgb(170, 170, 170); border-radius: 3px; 
														padding: 5px 5px 5px 0px;">
													<span class="pull-left" style="padding: 8px 10px 0px;">'.
														$ldetail['name'].
													'</span>
													<span class="pull-right">
														<a class="btn btn-danger p-x-2" 
															onclick="return confirm(\'Are you sure you want to delete this?\');" 
															href="company/department/delete/'.$ldetail['id'].'"> Delete <i class="fa fa-trash-o"></i>
														</a>
													</span>
													<span class="pull-right" style="padding-right: inherit; backgrund-color:#F27733;">
														<a class="btn btn-info" 	
															style="background-color:#F27733;"
															href="company/department/assignProduct/'.$ldetail['id'].'"> Manage Products
														</a>
													</span>
												</li>';
								}
								echo $listhtml;
							}
						?>
					</div> <!-- /control-group -->					
					<div style="text-align:center">
						<?php
							echo $page_links;
						?>						
					</div>
					</div>
				</fieldset>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

