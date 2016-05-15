<style type="text/css">
    .table-editable {
        position: relative;
        .glyphicon {
            font-size: 20px;
        }
    }

    .table-remove{
      color: #700;
      cursor: pointer;
      
      &:hover {
        color: #f00;
      }
    }

    .table-disable-remove{
      color: #9D476F;
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
    .table-error {
        border : 1px red solid;
    }
    [contentEditable=true]:empty:not(:focus):before{
        content:attr(data-text)
    }

    .tbwrapper {
      text-transform: uppercase;
      background: #ececec;
      color: #555;
      cursor: help;
      font-family: "Gill Sans", Impact, sans-serif;
      /*
      font-size: 20px;
      margin: 100px 75px 10px 75px;
      padding: 15px 20px;
      width: 200px;
      */
      position: relative;
      text-align: center;      
      -webkit-transform: translateZ(0); /* webkit flicker fix */
      -webkit-font-smoothing: antialiased; /* webkit text rendering fix */
    }

    .tbwrapper .tooltip {
      background: #1496bb;
      bottom: 100%;
      color: #fff;
      display: block;
      left: -50px;
      margin-bottom: 15px;
      opacity: 0;
      /* 
        padding: 20px;
        width: 100%;
      */
      pointer-events: none;
      position: absolute;      
      -webkit-transform: translateY(10px);
         -moz-transform: translateY(10px);
          -ms-transform: translateY(10px);
           -o-transform: translateY(10px);
              transform: translateY(10px);
      -webkit-transition: all .25s ease-out;
         -moz-transition: all .25s ease-out;
          -ms-transition: all .25s ease-out;
           -o-transition: all .25s ease-out;
              transition: all .25s ease-out;
      -webkit-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
         -moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
          -ms-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
           -o-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
              box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
    }

    /* This bridges the gap so you can mouse into the tooltip without it disappearing */
    .tbwrapper .tooltip:before {
      bottom: -20px;
      content: " ";
      display: block;
      height: 20px;
      left: 0;
      position: absolute;
      width: 100%;
    }  

    /* CSS Triangles - see Trevor's post */
    .tbwrapper .tooltip:after {
      border-left: solid transparent 10px;
      border-right: solid transparent 10px;
      border-top: solid #1496bb 10px;
      bottom: -10px;
      content: " ";
      height: 0;
      left: 50%;
      margin-left: -13px;
      position: absolute;
      width: 0;
    }
      
    .tbwrapper:hover .tooltip {
      opacity: 1;
      pointer-events: auto;
      -webkit-transform: translateY(0px);
         -moz-transform: translateY(0px);
          -ms-transform: translateY(0px);
           -o-transform: translateY(0px);
              transform: translateY(0px);
    }

    /* IE can just show/hide with no transition */
    .lte8 .tbwrapper .tooltip {
      display: none;
    }

    .lte8 .tbwrapper:hover .tooltip {
      display: block;
    }
</style>
<header class="panel-heading">
    <div class="row">
        <div class="col-sm-9">
            <a href="cpcatalogue/attributeset/"><h3 style="margin: 0; color: #222;"> <i class="fa fa-arrow-left" title="Attributes"></i> Edit Attribute</h3> </a>
        </div>
        <div class="col-sm-3">
            
        </div>
    </div>
</header>
<?php $this->load->view(THEME.'messages/inc-messages'); ?>
<?php
  $FORM_JS = '  name="form" id="filter_frm"  onsubmit="return validateForm()" ';
  echo form_open(current_url($attr_id), $FORM_JS);
?>      
    <div class="form-group">
        <div class="col-sm-12">
            <label>Title</label>
            <input type="text" placeholder="Title" value="<?php echo $edit_det['label']; ?>" 
                name="label" class="form-control" disabled autocomlete="off">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <label>Attribute Type</label>
            <?php 
                $js = ' class="form-control" autocomlete="off" disabled ';
                echo form_dropdown('type', $type_list, $edit_det['type'], $js);
            ?>
        </div>
    </div>
    <?php
      /*
      3 : DROPDOWN
      7 : MULTISELECT
      */
      if(in_array($edit_det['type'], array(3, 7))){
    ?>
    <div class="form-group" id="item-table" style="display:block;">
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
                        <?php
                            if($edit_sub){
                                foreach($edit_sub as $opt){
									$occupied  = false;
									if( isset( $occu_sub[ $opt[ 'id' ] ] ) ){
										$occupied  = true;
									}
                                    $trow = '<span class=" table-remove glyphicon glyphicon-remove"></span>';
                                    if( $occupied ){
                                        $trow = '<span class="table-disable-remove tbwrapper">X<div class="tooltip">occupied!</div></span>';
                                    }
                                echo '<tr>
                                            <td contenteditable="true" data-text="Enter text here" data-id="'.$opt['id'].'">'.
												$opt['option_text'].'</td>
                                            <td>'.$trow.'</td>
                                        </tr>';
                                }
                            }else{
                                echo '<tr>
                                            <td contenteditable="true" data-text="Enter text here" ></td>
                                            <td><span class="table-remove glyphicon glyphicon-remove"></span></td>
                                        </tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
      }
    ?>
    <div class="form-group">
        <div class="col-sm-12">
            <label>Visible</label>
            <?php 
                $js = ' class="form-control" autocomlete="off" ';
                $visible_list = array();
                $visible_list['1'] = 'Yes';
                $visible_list['0'] = 'NO';
                echo form_dropdown('visible', $visible_list, $edit_det['visible'], $js);
            ?>
        </div>
    </div>

  
    <div class="form-group">
        <div class="col-sm-12">
            <label>Required</label>
            <?php 
                $js = ' class="form-control" autocomlete="off" ';
                $reqired_list = array();
                $reqired_list['1'] = 'Yes';
                $reqired_list['0'] = 'NO';
                echo form_dropdown('required', $reqired_list, $edit_det['required'], $js);
            ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <label>Searchable</label>
            <?php 
                $js = ' class="form-control" autocomlete="off" ';
                $search_list = array();
                $search_list['1'] = 'Yes';
                $search_list['0'] = 'NO';
                echo form_dropdown('searchable', $search_list, $edit_det['searchable'], $js);
            ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <label>Is Numeric</label>
            <?php 
                $js = ' class="form-control" autocomlete="off" ';
                $search_list = array();
                $search_list['1'] = 'Yes';
                $search_list['0'] = 'NO';
                echo form_dropdown('is_numeric', $search_list, $edit_det['is_numeric'], $js);
            ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12">
            <label>Is User Related</label>
            <?php 
                $js = ' class="form-control" autocomlete="off" ';
                $search_list = array();
                $search_list['1'] = 'Yes';
                $search_list['0'] = 'NO';
                echo form_dropdown('is_userrelated', $search_list, $edit_det['is_userrelated'], $js);
            ?>
        </div>
    </div>
    
    <br/>
    <div class="form-group">

        <br/><br/>
        <div class="col-sm-12">
            <input type="submit" class="btn btn-primary preview-add-button btn-fix-width" 
            value="<?php echo 'Update'; ?>"/>
        </div>
    </div>
<?php echo form_close(); ?>
<script type="text/javascript">    
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
                                $(this).addClass("has-error");
                                $(this).addClass("table-error");
                                validate = false;                                
                            }else{
                                $(this).removeClass("has-error");
                                $(this).removeClass("table-error");
                            }
                        }
                    });
                }
            });            
        }

        if(!validate){            
            $('#comMsgModalTitle').html('Attribute');
            $('#comMsgModalBody').html('Options must be filled');
            $('#comMsgModal').modal('show');
            return validate;
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
                    h['related_id'] = $td.eq(i).attr('data-id');
                });
                
                data.push(h);
            });
            $('#options').val(JSON.stringify(data));
        }
        return validate;
    }
</script>
