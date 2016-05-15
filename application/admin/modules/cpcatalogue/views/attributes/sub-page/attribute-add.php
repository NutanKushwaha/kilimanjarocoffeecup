<style type="text/css">
    .table-editable {
        position: relative;
        .glyphicon {
            font-size: 20px;
        }
    }

    .table-remove {
      color: #700;
      cursor: pointer;
      
      &:hover {
        color: #f00;
      }
    }

    .table-up, .table-down {
      color: #007;
      cursor: pointer;
      
      &:hover {
        color: #00f;
      }
    }

    .table-add {
      color: #070;
      cursor: pointer;
      position: absolute;
      top: 8px;
      right: 0;
      
      &:hover {
        color: #0b0;
      }
    }

    [contentEditable=true]:empty:not(:focus):before{
        content:attr(data-text)
    }

</style>
<header class="panel-heading">
    <div class="row">
        
        <div class="col-sm-9">
            <h3 style="margin: 0;"><i class="fa fa-arrow-left" title="Attributes"></i> Add Attribute</h3>
        </div>
        <div class="col-sm-3 text-right">
            <a href="cpcatalogue/attributeset" class="btn btn-primary"><h3 style="cursor: pointer; margin: 0;font-size: 15px;"><i class="fa fa-share" title="Attributes"></i> Back</h3></a>
        </div>
    </div>
</header>

<?php 
    //$this->load->view('inc-messages');
    /*
        <form  name="form" enctype="multipart/form-data" method="post" action="" 
        onsubmit="return validateForm()">
        echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash());
    */
    $FORM_JS = ' name="form" onsubmit="return validateForm()" ';
    echo form_open(current_url(), $FORM_JS);
?>
    <div class="form-group">
        <div class="col-sm-12">
            <label>Title</label>
            <input type="text" placeholder="Title" name="label" class="form-control" required>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <label>Attribute Type</label>
            <select name="type" class="form-control" autocomlete="off" id="type" required>
                <option value="">-Select Type-</option>
                <?php 
                    $options_html = null;
                    foreach($type_list as $type_index => $type_text){ 
                        $options_html .= '<option value="'.$type_index.'">'.$type_text.'</option>';
                    }
                    echo $options_html ;
                ?>
            </select>                    
        </div>
    </div>
    <div class="form-group" id="item-table" style="display:none;">
        <input type="hidden" name="options" value="" id="options">
        <div class="col-sm-12">
            <div id="table" class="table-editable">
                <span class="table-add glyphicon glyphicon-plus"></span>
                <table class="table">
                    <tbody>
                        <tr class="hide">
                            <td contenteditable="true" data-text="Enter text here"></td>
                            <td>
                              <span class="table-remove glyphicon glyphicon-remove"></span>
                            </td>
                        </tr>
                        <tr>
                            <th width="95%">Item Name <small>Please add the options</small></th>
                            <th width="5%"></th>
                        </tr>
                        <tr>
                            <td contenteditable="true"></td>
                            <td><span class="table-remove glyphicon glyphicon-remove"></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label>Visible</label>
            <select name="visible" class="form-control" required>
                <option value="">Select</option>
                <option value="1">Yes</option>
                <option value="0">No</option>                
            </select>
        </div>
    </div>

  
    <div class="form-group">
        <div class="col-sm-12">
            <label>Required</label>
            <select name="required" class="form-control" required>
                <option value="">Select</option>
                <option value="1">Yes</option>
                <option value="0">No</option>                
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <label>Searchable</label>
            <select name="searchable" class="form-control" required>
                <option value="">Select</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <label>Is Numeric</label>
            <select name="is_numeric" class="form-control" required>
                <option value="">Select</option>
                <option value="1">Yes</option>
                <option value="0">No</option>                
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <label>Is User Related</label>
            <select name="is_userrelated" class="form-control" required>
                <option value="">Select</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
    </div>

    <br/>
    <div class="form-group">
        <br/>
        <div class="col-sm-12">
            <input type="submit" class="btn btn-primary preview-add-button btn-fix-width" 
            value="Submit"/>
        </div>
    </div>
<?php echo form_close(); ?>
<script>
    $(document).ready(function(){
        $('#type').on('change', function(){
            var selected_option   =   $( "#type option:selected" ).text();
            if(selected_option == "DROPDOWN" || selected_option == "MULTISELECT"){
                var trhtml = "<tr class=\"hide\"><td contenteditable=\"true\" data-text=\"Enter text here\"></td><td><span class=\"table-remove glyphicon glyphicon-remove\"></span></td></tr><tr><th width=\"95%\">Item Name</th><th width=\"5%\"></th></tr><tr> <td contenteditable=\"true\" data-text=\"Enter text here\"></td> <td><span class=\"table-remove glyphicon glyphicon-remove\"></span></td> </tr>";
                $('.table > tbody').html(trhtml);
                $('#item-table').css('display','block');
            }else{
                $('#item-table').css('display','none');
            }
        });
    });

    var $TABLE = $('#table');

    $('.table-add').click(function () {
        var $clone = $TABLE.find('tr.hide').clone(true).removeClass('hide table-line');
        $TABLE.find('table').append($clone);
    });

    $('.table-remove').click(function () {
        $(this).parents('tr').detach();
    });

    $('.table-up').click(function () {
      var $row = $(this).parents('tr');
      if ($row.index() === 1) return; // Don't go above the header
      $row.prev().before($row.get(0));
    });

    $('.table-down').click(function () {
      var $row = $(this).parents('tr');
      $row.next().after($row.get(0));
    });

    function validateForm(){
        var validate = true;
        if($("#item-table").is(':visible')){            
            $('.table > tbody  > tr').each(function() {                
                if(!$(this).hasClass( "hide")){
                    $(this).find('td').each(function(){
                        if($(this).attr("contenteditable")){
                            if($(this).text().trim() == ""){                                
                                $('#comMsgModalTitle').html('Attribute');
                                $('#comMsgModalBody').html('Options must be filled');
                                $('#comMsgModal').modal('show');
                                validate = false;
                                return false;
                            }
                        }
                    });
                }
            });            
        }
        // A few jQuery helpers for exporting only
        jQuery.fn.pop = [].pop;
        jQuery.fn.shift = [].shift;
        
        var $rows = $TABLE.find('tr:not(:hidden)');
        var headers = [];
        var data = [];        
        if(validate){
              
            // Get the headers (add special header logic here)
            $($rows.shift()).find('th:not(:empty)').each(function () {
                headers.push($(this).text().toLowerCase());
            });
              
              // Turn all existing rows into a loopable array
            $rows.each(function () {
                var $td = $(this).find('td');
                var h = {};
                
                // Use the headers from earlier to name our hash keys
                headers.forEach(function (header, i) {
                  h['item'] = $td.eq(i).text();   
                });
                
                data.push(h);
            });
            $('#options').val(JSON.stringify(data));
        }
        return validate;
    }
</script>