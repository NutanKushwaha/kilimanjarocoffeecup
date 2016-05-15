<!-- <div class="yt-header-middle2">
    <div class="container">
        <div class="row">
            <div class="yt-header-middle2-left col-lg-3 col-md-3 col-sm-2 col-xs-12"></div>
            <div class="yt-header-middle2-right col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <div class="head-searchbox">
                    <div class="sm-serachbox-pro" id="sm_serachbox_pro">
                        <div class="sm-searbox-content">
                            <form method="get" action="<?php echo createUrl('catalogue/search'); ?>" id="search_mini_form">
                                <div class="form-search">
                                    <select id="cat" name="cat">
                                        <option value="">All Categories</option>
                                        <?php
                                        $ci = &get_instance();
                                        $ci->load->model('catalogue/Categorymodel');
                                        $searchCats = $ci->Categorymodel->categoriesSearchTree(0);
                                        echo($searchCats);
                                        ?>
                                        
                                       
                                    </select>
                                    <div class="jqTransformInputWrapper" style="width: 310px;">
                                        <div class="jqTransformInputInner"><div>
                                                <input type="text" placeholder="Enter keywords to search..." class="input-text jqtranformdone jqTransformInput" name="q" id="search" size="30" autocomplete="off" style="width: 300px;">
                                            </div>
                                        </div>
                                    </div>
                                    <button class="button form-button" title="Search" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>        
            </div>
        </div>
    </div>
</div>

 -->