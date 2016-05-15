<div class="col-lg-3 col-md-3 col-sm-0 col-xs-12" id="yt_header_left">
    <div class="yt-menu">
        <div id="btn_categories">
            <span><i class="fa fa-list"></i> Categories</span>
        </div>
        <div class="yt-menu-content">
            <div data-sam="" id="sm_megamenu_menu" class="sm_megamenu_wrapper_vertical_menu sambar">
                <div class="sambar-inner">
                    <a href="#sm_megamenu_menu" data-sapi="collapse" class="btn-sambar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>	
                    <!--<ul data-jsapi="on" class="sm-megamenu-hover sm_megamenu_menu sm_megamenu_menu_black">-->
                        <?php
                        $this->load->model('catalogue/Categorymodel');
                        $catsPr = $this->Categorymodel->categoriesTree(0);
                        echo $catsPr;
                        ?>
                        <?php ///if ($categories['num_rows'] > 0) { ?>
                            <?php /* //foreach ($categories['result'] as $cproduct) { ?>
                                <li class=" other-toggle sm_megamenu_lv1 sm_megamenu_nodrop" >
                                    <a id="sm_megamenu_180" href="<?php echo createUrl('catalogue/index/') . $cproduct['category_alias'] ?>" class="sm_megamenu_head sm_megamenu_nodrop ">
                                        <span class="sm_megamenu_icon sm_megamenu_nodesc">		
                                            <span class="sm_megamenu_title"><?php echo $cproduct['category'] ?></span>
                                        </span>
                                    </a>
                                </li>
                                <?php
                                */
                            //}
                        //}
                        ?>
                    <!--</ul>-->
                    <div class="clearfix"></div>
                </div>
            </div>
            <!--End Module-->
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
		$i = 0;
		$('.sm_megamenu_wrapper_vertical_menu .sm_megamenu_menu').append('<div class="more-wrap"><span class="more-view">More Categories</span></div>');
		$('.sm_megamenu_wrapper_vertical_menu .sm_megamenu_menu > li.sm_megamenu_lv1').each(function(){
			$i ++; 
			if($i>14){ 
				$(this).css('display', 'none');
			}			
		});
		$('.sm_megamenu_wrapper_vertical_menu .sm_megamenu_menu .more-wrap > .more-view').click(function(){
			if($(this).hasClass('open')){
				$i=0;
				$('.sm_megamenu_wrapper_vertical_menu .sm_megamenu_menu > li.sm_megamenu_lv1').each(function(){
					$i ++;
					if($i>14){
						$(this).slideUp(200);
                        //$(this).addClass('disappear');
                        //$(this).removeClass('appear');
					}
				});
				$(this).removeClass('open');
				$('.more-wrap').removeClass('active-i');
				$(this).text('More Categories');
			}else{
                            
				$i=0;
				$('.sm_megamenu_wrapper_vertical_menu .sm_megamenu_menu > li.sm_megamenu_lv1').each(function(){
					$i ++;
					if($i>14){
						$(this).slideDown(200);
                        // $(this).addClass('appear');
                         //$(this).removeClass('disappear');
					}
				});
				$(this).addClass('open');
				$('.more-wrap').addClass('active-i');
				$(this).text('Close Menu');
			}
		});
		
		/*fix show button more*/
		
		var nhd2 = $('.sm_megamenu_wrapper_vertical_menu .sm_megamenu_menu > li.sm_megamenu_lv1').length;

		if(nhd2 < 14){
			$('.sm_megamenu_wrapper_vertical_menu .more-wrap').css('display', 'none');
		} else{
			$('.sm_megamenu_wrapper_vertical_menu .more-wrap').css('display', 'block');
		}
	})
    </script>