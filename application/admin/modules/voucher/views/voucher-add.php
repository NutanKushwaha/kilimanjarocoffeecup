<script type="text/javascript" src="<?php echo base_url('js/jquery-datetimepicker/jquery-ui.js') ?>"></script>

<script type="text/javascript" src="<?php echo base_url('js/jquery-datetimepicker/jquery-ui-timepicker-addon.js') ?>"></script>

<link href="<?php echo base_url('js/jquery-datetimepicker/date-style.css') ?>" rel="stylesheet" type="text/css">

<link href="<?php echo base_url() ?>/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />

<header class="panel-heading">

    <div class="row">

        <div class="col-sm-1">

            <i class="fa fa-cart-plus fa-2x"></i>

        </div>

        <div class="col-sm-10">

            <h3 style="margin: 0; ">Add voucher</h3>

        </div>

        <div class="col-sm-1" style="text-align: right">

            <a href="<?php echo base_url('voucher/index/') ?>">

				<h3 style="cursor: pointer; margin: 0; color: #000">

					<i class="fa fa-plus-square" title="Manage Voucher"></i>

				</h3>

			</a>

        </div>

    </div>

</header>

<?php

	$attr = [];

	$attr[ 'id' ] 	= 'addvoucherform';

	$attr[ 'name' ] = 'addvoucherform';

	echo form_open('voucher/add/', $attr);

?>    

	<table id="voucher_add"  class="table table-bordered table-striped">

		<tbody>

			<tr>

				<td>Voucher code <span class="error">*</span><br/>

					<small>Only Alpha-Numeric, Non-editable</small>

				</td>

				<td><input 	name="code" type="text" class="form-control"

							id="code" 	value="<?php echo set_value('code'); ?>" 

							maxlength="10"  

							size="40" 

							onchange="$( this ).val( $(this).val().toUpperCase() );" 

					/></td>

			</tr>

			<tr>

				<td>Voucher description <span class="error">*</span></td>

				<td>

					<input 	name="description" 	type="text" class="form-control" 

							id="description" 	value="<?php echo set_value('description'); ?>" 

							maxlength="100" 

							size="100" />

				</td>

			</tr>			

			<tr>

				<td>Voucher action on <span class="error">*</span></td>

				<td><?php echo form_dropdown('vstyle', $voucher_type, set_value('vstyle'), 

							'id ="vstyle" class="form-control" '); ?></td>

			</tr>



			<tr id="value">

				<td>Voucher amount <span class="error"> *</span></td>

				<td><input 	name="amount" type="number" step="0.25" 

							class="form-control nummorezero allownumericwithdecimal" 

							min="0" 

							id="amount" 

							value="<?php echo set_value('amount', 0); ?>" size="40" /></td>

			</tr>

			<tr id="value">

				<td>Voucher active <span class="error"> *</span></td>

				<td><?php

						$opt =[];

						$opt[ '1' ] = 'Active';

						$opt[ '0' ] = 'De-Active';

						echo form_dropdown( 'active', $opt, set_value('active', 1), ' style="width:200px" class="form-control"' );

					?>

				</td>

			</tr>			

			<tr>

				<td>Active From <span class="error"> *</span></td>

				<td><input 	name="active_from" 

							id="active_from" 

							type="text" 

							class="form-control cidate" 

							readonly="readonly" value="<?php echo set_value('active_from'); ?>" size="40" /></td>

			</tr>

			<tr>

				<td>Active To <span class="error"> *</span></td>

				<td><input 	name="active_to" id="active_to" type="text" 

							class="form-control cidate" readonly="readonly" 

							id="active_to" 

							value="<?php echo set_value('active_to'); ?>" size="40" /></td>

			</tr>

			<tr>

				<td> Use time <span class="error"> *</span></td>

				<td>

					<input 	name="use_time" type="number" 

							class="form-control nummorezero allownumericwithdecimal" 

							id="use_time" 

							min="1" 

							value="<?php echo set_value('use_time',1); ?>" size="40" />

				</td>

			</tr>

			<tr>

				<td> User style <span class="error"> *</span><br/><small>Non-editable</small> </td>

				<td>

					<?php

						$user_style = set_value('user_style');

					?>

					<label class="btn btn-default">

						<input 	type="radio" name="user_style" id="user_style_1" 

								value="A" <?= $user_style == 'A'?'checked':''  ?> /> All

					</label> 						

					<label class="btn btn-default">

						<input type="radio" name="user_style" id="user_style_2" value="N" <?= $user_style == 'N'?'checked':''  ?> /> Any

					</label> 

					<label class="btn btn-default">

						<input type="radio" name="user_style" id="user_style_3" value="S" <?= $user_style == 'S'?'checked':''  ?>/> Specific

					</label>					

				</td>

			</tr>			

			<tr>

				<td>Specific customers

					<br/><small>Non-editable<br/><b>Effective only if specific is selected as user style</b></small>

				</td>

				<td>

					<?php

						$opt = [];

						$opt[ 'id' ] = 'customer';

						$opt[ 'style' ] = 'width:250px;';

						$opt[ 'class' ] = 'form-control';

						echo form_multiselect('customer[]', $customers, '', $opt );

					?>

				</td>

			</tr>

			<tr>

				<td>Minimum Order Value ($) <span class="error"> *</span></td>

				<td><input 	name="min_order_value"  

							type="number" step="0.25" 

							min="0" 

							class="form-control nummorezero allownumericwithdecimal" 

							id="minimum_order_value" 

							value="<?php echo set_value('min_order_value', 0); ?>" size="40" /></td>

			</tr>			

		</tbody>

		<tfoot>

			<tr>

				<td></td>

				<td>

					<input type="submit" name="button" id="button" class="btn btn-primary" value="Submit">

					<span class="pull-right">

						Fields marked with <span class="error">*</span> are required.

					</span>

				</td>

			</tr>

		</tfoot>			

    </table>

<?php 

	echo form_close();

?>

<script type="text/javascript">

    $(document).ready(function () {

        $('#active_from').datetimepicker({

            dateFormat: 'yy-mm-dd',

            onSelect: function (date) {

                var maxDate = $('#from_date').datepicker('getDate');

                $("#to_date").datetimepicker("change", {minDate: maxDate});

            }

        });

        $('#active_to').datetimepicker({

            dateFormat: 'yy-mm-dd',

        });

        $( '.nummorezero' ).on( 'change', function(){

			/*

			var curObj = $( this );

			if( curObj.val() < 0 ){

				curObj.val( 0 );

			}

			*/

		});

		$('#code').on('keypress', function (event) {

			var regex = new RegExp("^[a-zA-Z0-9]+$");

			var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

			if (!regex.test(key)) {

			   event.preventDefault();

			   return false;

			}

		});

		$(".allownumericwithdecimal").on("keypress keyup blur",function (event) {

				//this.value = this.value.replace(/[^0-9\.]/g,'');

				$(this).val($(this).val().replace(/[^0-9\.]/g,''));

				if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {

				  event.preventDefault();

				}

		  });

    })

</script>

