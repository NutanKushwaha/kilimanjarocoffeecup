<header class="panel-heading">
    <div class="row">
        <div class="col-sm-9 pad-left0">            
            <a href="cpcatalogue/attributeset/"><h3 style="margin: 0; color: #222;"> <i class="fa fa-arrow-left" title="Attributes"></i> Attribute Set Add-from</h3> </a>            
        </div>        
        <div class="col-sm-3">
            
        </div>
    </div>
</header>
<div class="tableWrapper mar-top10">	
	<?php
		$FORM_JS = ' id="add-attr-set" class="form-horizontal" ';
	  echo form_open(current_url(), $FORM_JS);
	?>
		<fieldset>
			<div class="control-group">
				<label class="control-label form-text-custom-size" for="set_name">Attribute set Name</label>
				<div class="controls">
					<input type="text" required class="span6 form-control" id="set_name" name="set_name">
                                        <p class="help-block text-orange">Attribute set name should be unique</p>
				</div> <!-- /controls -->
			</div> <!-- /control-group -->

			<div class="control-group">
				<label class="control-label form-text-custom-size" for="source_name" name="source_name">Source Attributeset</label>
				<div class="controls">
				<?php
					$js = 'class="form-control" id="source_name" ';
					echo form_dropdown('source_name', $set_list, '', $js);
				?>
				</div> <!-- /controls -->
			</div>
                        
                        <br/>
			<div class="control-group">
				<button type="submit" class="btn btn-primary">Save</button> 
                                <button class="btn btn-default" type="reset">Reset</button>
			</div> <!-- /form-actions -->					
		</fieldset>
	<?php echo form_close(); ?>
</div>

