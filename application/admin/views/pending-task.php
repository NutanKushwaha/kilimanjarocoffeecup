<?php $this->load->helper('html'); ?>
<br/>
<a href="<?= site_url(); ?>"><h5>Home</h5></a>
<h3> Pending task </h3>
<?php
	$attributes = [
                    'class' => 'boldlist',
                    'id'    => 'mylist'
        		];
	echo ul($list, $attributes);
?>