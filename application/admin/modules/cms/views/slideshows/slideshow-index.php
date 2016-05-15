<header class="panel-heading">
    <div class="row">
        <div class="col-sm-9">
            <h3 style="margin: 0;"> <i class="fa fa-picture-o"></i> Manage Slide Show</h3>
        </div>
        <div class="col-sm-3 text-right">
            <a href="cms/slideshow/add" style="color: #fff;" title="Add Slide Show" class="btn btn-primary">
                <h4 style="margin: 0;font-size: 15px;">
                    <i class="fa fa-plus-square">  </i> Add Slideshows 
                </h4>
            </a>
        </div>
    </div>
</header>

<?php $this->load->view(THEME . 'messages/inc-messages'); ?>

<?php
if (count($slideshows) == 0) {
    $this->load->view(THEME.'messages/inc-norecords');
    return;
}
?>

<div class="table">
    <table class="table" >
        <tr>
            <th width="70%">Slide Show Name</th>
            <th width="30%" style="text-align:left;padding-left: 100px;">Action</th>
        </tr>
        <?php
        foreach ($slideshows as $item) {
            $enable_disable_link = 'cms/slideshow/disable/' . $item['slideshow_id'];
            $enable_disable_text = 'Disable';
            if ($item['active'] == 0) {
                $enable_disable_text = 'Enable';
                $enable_disable_link = 'cms/slideshow/enable/' . $item['slideshow_id'];
            }
            ?>
            <tr class="<?php echo alternator('', 'alt'); ?>">
                <td style="text-align:left;"><?php echo $item['slideshow_title']; ?></td>
                <td style="text-align:right;"><a href="cms/slide/index/<?php echo $item['slideshow_id']; ?>">Slides</a> |
                    <a href="<?php echo $enable_disable_link; ?>" onclick="return confirm('Are you sure you want to Enable/Disable this Slideshow?');"><?php echo $enable_disable_text; ?></a> |
                    <a href="cms/slideshow/edit/<?php echo $item['slideshow_id']; ?>">Edit</a> |
                    <a href="cms/slideshow/delete/<?php echo $item['slideshow_id']; ?>" onclick="return confirm('Are you sure you want to delete this Slideshow?');">Delete</a>
                </td>
            <?php } ?>
    </table>
</div>
