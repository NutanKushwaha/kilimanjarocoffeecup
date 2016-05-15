<div class="row">
	<h3>Attribute Set List</h3>
	<div class="span12">
		<div class="tab-pane" id="formcontrols">			
		<?php
			$FORM_JS = '  id="edit-profile" class="form-horizontal" ';
			echo form_open(current_url(), $FORM_JS);
		?>
				<fieldset>					
					<div class="control-group">
                                            <ul class="list-unstyled attr-set-list">
						<?php
							if(!count($set_list)){
								$this->load->view(THEME.'messages/inc-norecords');
							}else{
								$listhtml = null;								
								foreach($set_list as $lindex => $ldetail){
									$html_opt = '';
									if(!$ldetail['is_sys']){
										$html_opt = '<span class="pull-right">                                                                                               
															<a class="btn btn-info" href="cpcatalogue/attributes/add/'.$ldetail['id'].'"> <i class="fa fa-plus-square"></i> Add Attributes</a>
															<a class="btn btn-info" href="cpcatalogue/attributes/manage/'.$ldetail['id'].'"> <i class="fa fa-wrench"></i> Manage Attributes</a>
															<a class="btn btn-info" onclick="return confirm(\'Are you sure you want to delete this?\');" 
																href="cpcatalogue/attributeset/delete/'.$ldetail['id'].'"><i class="fa fa-trash"></i> Delete
															</a>
													</span>';
									}
									$listhtml .= '<li style="width:100%;border:1px solid #aaa;"><span class="">'.$ldetail['set_name'].' </span>'.$html_opt.'</li>';
								}
								echo $listhtml;
							}
						?>
                                               
                                                </ul>
                                             
					</div> <!-- /control-group -->					
					<div style="text-align:center;">
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

