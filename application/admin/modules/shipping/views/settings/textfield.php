

<input name="<?php echo $key; ?>" type="text" class="textfield form-control form-control-custom-with-tags" 
	id="<?php echo $key; ?>" value="<?php echo set_value("$key", $val); ?>" size="40">
&nbsp;<span class="form-custom-bottom-tags" style="position:relative;top:-15px;">
	<?php if($comment) { ?><?php echo $comment;?><?php } ?></span>



