<div class="row">
	<h3>Attribute Set List</h3>
	<div class="span12">
		<div class="tab-pane" id="formcontrols">		
		<?php
			$FORM_JS = '  id="attribute-listing" class="form-horizontal" ';
			echo form_open(current_url(), $FORM_JS);
		?>			
				<fieldset>
					<div class="control-group">
						<?php
							if(!count($attr_list)){
								$this->load->view(THEME.'messages/inc-norecords');
							}else{								
								$listhtml = null;								
								foreach($attr_list as $lindex => $ldetail){									
									$option_html = null;
									$sys_attribute = '[System Attribute]';
									if(!$ldetail['is_sys']){
										$sys_attribute = null;
										$option_html = '<span class="pull-right" style="width:15%">
															<a class="btn btn-info" 
																href="cpcatalogue/attributes/edit/'.$ldetail['id'].'">Edit</a>
															<a class="btn btn-info" 
																onclick="return confirm(\'Are you sure you want to delete this?\');" 
																href="cpcatalogue/attributes/delete/'.$ldetail['id'].'">Delete</a>
														</span>';
									}
									$listhtml .= '<li style="width: 100%; float: left; border: 1px solid rgb(170, 170, 170); border-radius: 5px; margin: 5px 0px; padding: 10px 0px 10px 5px;" class="attr-set-list">
										<div class="" style="width:100%">
											<span style="width:50%;float:left;">
												<strong style="font-size:14px;">'.$ldetail['label'].'</strong></span> &#160;&#160;&#160;
											<span style="width:25%;float:left;">'.$sys_attribute.'</span> &#160;&#160;&#160;
											<span style="width:10%;float:left;"><small><i>'.$type_list[$ldetail['type']].'</i></small></div>'
												.$option_html.
											'</li>';
								}
								echo $listhtml;
							}
						?>
					</div> <!-- /control-group -->
					<div class="control-group" style="text-align:center;">
					<?php
						echo $page_links;
					?>
					</div>
				</fieldset>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
