<div class="col-sm-12">
    <?php
    	$this->load->view(THEME . 'messages/inc-messages');
    ?>
</div>
<div class="row">
	<h3>Attribute Set List</h3>
	<div class="span12">
		<div class="tab-pane" id="formcontrols">			
			<?php
				$FORM_JS = '  id="add-edit-set" class="form-horizontal"  ';
				echo form_open(current_url($cid), $FORM_JS);
			?>			
				<fieldset>
					<div class="control-group">
						<?php
							if(!count($attr_list)){
								$this->load->view(THEME.'messages/inc-norecords');
							}else{
								$JS = ' class="form-control" ';
								echo form_dropdown('attribute_set_id', $attr_list, $attribute_set_id, $JS);
							}
						?>
					</div>
					<div class="control-group">
						<div class="col-sm-12">		
							<br/>					
					            <input type="submit" class="btn btn-primary preview-add-button btn-fix-width" 
					            value="Submit"/>	
				        </div>
					</div>
				</fieldset>

			<?php echo form_close(); ?>
		</div>
	</div>
</div>