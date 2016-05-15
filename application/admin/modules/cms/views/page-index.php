<header class="panel-heading">
    <div class="row">
        <div class="col-sm-9">
            <h3 style="margin: 0;"> <i class="fa fa-file-text-o"></i>  Manage Pages</h3>
        </div>
        <div class="col-sm-3" style="text-align: right">
            <?php if (1 == 1): ?>
            <a href="cms/page/add" class="btn btn-primary"><h4 style="cursor: pointer; margin: 0;font-size: 15px;"><i class="fa fa-plus-square" title="Add New Page"></i> Add Page</h4></a>
            <?php endif; ?>
        </div>
    </div>
</header>
<div class="col-sm-12">
    <?php    
    if (count($pages) == 0) {
        $this->load->view(THEME . 'messages/inc-norecords');
        echo "</div>";
        return;
    }
    ?>
</div>
<div class="tableWrapper mar-top10 subpage-product-page">
    <?php echo $pagetree; ?>
</div>