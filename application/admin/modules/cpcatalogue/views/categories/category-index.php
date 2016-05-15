<style>
    .categories_container .head-accordion-container-dropdown {
        border-bottom: medium none !important;
        color:#333 !important;
    }
    .categories_container .head-accordion-container-dropdown:last-child {
        border-bottom: 1px solid #aaa !important;
    }
    .categories_container .mlevel-1 > li article {
        border: 1px solid rgb(170, 170, 170);
        margin-bottom: 3px;
        padding-top: 5px;
    }    
    .mlevel-1 span.edit-setting{
        
    }
    .categories_container .head-accordion-container-dropdown .even{
        color: #222 !important;
        border-radius: 5px;
    }
</style>

<header class="panel-heading">
    <div class="row">
        <div class="col-sm-9">
            <a href="cpcatalogue/category/">
            <h3 style="margin: 0;cursor: pointer; margin: 0; color: #000;"> <i class="fa fa-file-text-o" title="Category Listing"></i>  Manage Categories <small style="color:#aaa;font-size: 13px;"><i>Department</i></small></h3></a>
        </div>
        
        <!-- <div class="col-sm-3" style="text-align: right">            
            <a href="cpcatalogue/category/add" class="btn btn-primary">
                <h3 style="cursor: pointer; margin: 0;font-size: 15px;"><i class="fa fa-plus-square" title="New Category"></i> New Category</h3>
            </a>            
        </div> -->
    </div>
</header>
<div class="col-sm-12">
    <?php    	
    	if (count($categories) == 0) {
				$this->load->view(THEME.'messages/inc-norecords');
			echo "</div>";
			return;
		}
    ?>
</div>
<div class="tableWrapper mar-top10">    
    <div class="categories_container">
        <section id="only-one" data-accordion-group>            
            <?php 				
                foreach( $categoryAcord as $catIndex => $catDet ){
            ?>
                    <section style="border:1px solid <?= $catDet['categ_color'] ?>;padding:10px 10px 0;"
                        class="col-md-12 head-accordion-container-dropdown 
                            accordion-level-parent-<?= $catDet['internal_parent'] ?>" data-accordion>
                            <button <?php echo $catDet['catCountForParent']?'data-control':''; ?> 
                                    class="even dropdown-cate" 
                                    style="color: rgb(242, 119, 51); text-align: left; margin-bottom: 10px; width: 80%; padding: 8px 10px;font-size: 14px; font-weight: 600; "><?= $catDet['category_text'].($catDet['catCountForParent'] ? '<span class="pull-right"><i><small>sub categories</small></i></span>' : '' ); ?></button>
                        <span class="attr-set-list pull-right edit-setting" style="margin-top:8px;">
                                <a class="btn btn-info" 
                                    href="cpcatalogue/category/edit/<?= $catDet['category_id'] ?>"> <i class="fa fa-edit"></i> Edit</a>  
                                | <a class="btn btn-info" 
                                        href="cpcatalogue/category/delete/<?= $catDet['category_id'] ?>"><i class="fa fa-trash"></i> Delete </a> 
                            </span>
                            <div data-content>
                                <ul class="mlevel-1">
                                    <li><?= com_inner_accordian_category( $catDet[ 'children' ] ); ?></li>
                                </ul>
                            </div>
                    </section>
            <?php
                }
            ?>
        </section>
    </div>
</div>
<script type="text/javascript">
      $(document).ready(function() {
        $('#only-one [data-accordion]').accordion();

        $('#multiple [data-accordion]').accordion({
          singleOpen: false
        });

        $('#single[data-accordion]').accordion({
          transitionEasing: 'cubic-bezier(0.455, 0.030, 0.515, 0.955)',
          transitionSpeed: 200
        });
      });
    </script>
    
<!--script>
    $(".deleterow").on("click", function(){
        var sections = $('section');
        var $killrow = $('section').find();
        var find = $( "section.accordion-level-parent-1" ).find( sections );
        l(find);
//            $killrow.addClass("danger");
//                $killrow.fadeOut(2000, function(){
//                $(this).remove();
//        });
    });
    function l(v){
        console.log(v);
    }
</script-->
<script>
//    $(".deleterow").on("click", function(){
//       var sections = $('section');
//        var $killrow = $('section').find();
//        var find = $( "section.accordion-level-parent-1" ).find( sections );
//        l(find.prevObject);
//        find.prevObject.addClass("danger");
//          find.prevObject.fadeOut(2000, function(){
//                $(this).remove();
//        });
//        });
//        function l(v){
//        console.log(v);
//    }
    
    
//     $(".deleterow").on("click", function(){
//       var sections = $('section');
//        var $killrow = $('section').find();
//        var find = $( "section.accordion-level-parent-2" ).find( sections );
//        l(find.prevObject);
//        find.prevObject.addClass("danger");
//          find.prevObject.fadeOut(2000, function(){
//                $(this).remove();
//        });
//        });
//        function l(v){
//        console.log(v);
//    }
    
//     $(".deleterow").on("click", function(){
//       var sections = $('section');
//        var $killrow = $('section').find();
//        var find = $( "section.accordion-level-parent-3" ).find( sections );
//        l(find.prevObject);
//        find.prevObject.addClass("danger");
//          find.prevObject.fadeOut(2000, function(){
//                $(this).remove();
//        });
//        });
//        function l(v){
//        console.log(v);
//    }
    
     $(".deleterow").on("click", function(){
     var hideclass = $(this).attr('data-class');    
     console.log(hideclass);
  
        $('.'+hideclass).addClass("danger");
           $('.'+hideclass).fadeOut(2000, function(){
                $('.'+hideclass).remove();
        });
     //       var sections = $('section');
//        var $killrow = $('section').find();
//        var find = $( "section.accordion-level-parent-4" ).find( sections );
//        l(find.prevObject);
//         $('.'+hideclass).addClass("danger");
//           $('.'+hideclass).fadeOut(2000, function(){
//                $(this).remove();
//        });
        });
        function l(v){
        console.log(v);
    }
</script>
