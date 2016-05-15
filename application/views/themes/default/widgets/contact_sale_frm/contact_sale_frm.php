<div class="form_contact">
	<h2>Let's Get Started...</h2>
	<form action="contact/index/" method="post" enctype="multipart/form-data" name="contact_form" id="contact_form">
		<input name="options" type="hidden" value="<?php echo $widget_settings['enq_id'];?>" />
		<input name="last_name" type="hidden" value="" />
		<input name="title" type="hidden" value="" />
		<input name="postcode" type="hidden" value="" />
		<ul>
			<li>
				<label>Name </label>
				<input name="first_name" type="text" class="field full " id="first_name" />
			</li>
			<li>
				<label>Company </label>
				<input name="company" type="text" class="field full " id="company" />
			</li>
			<li>
				<label>Address </label>
				<input name="address" type="text" class="field full " id="address" />
			</li>
			<li>
				<label>Address2 </label>
				<input name="address_2" type="text" class="field full " id="address_2" />
			</li>
			<li>
				<label>City </label>
				<input name="city" type="text" class="field left_half " id="city" />
			</li>
			<li>
				<label class="nowidth">State </label>
				<input name="state" type="text" class="field right_half " id="state" />
			</li>
			<div style="clear:both"></div>
			<li>
				<label>Email </label>
				<input name="email" type="text" class="field full" id="email" />
			</li>
			<li>
				<label>Phone </label>
				<input name="phone" type="text" class="field full" id="phone"  />
			</li>
			<li>
				<label>Comments </label>
				<textarea name="message" rows="5" style="width: 218px" class="field" id="message"></textarea>
			</li>
			<li style="float:right; padding-top:8px">
				<input type="image" src="images/submit-btn2.png" />
			</li>
		</ul>
		<div style="clear:both"></div>
	</form>
</div>