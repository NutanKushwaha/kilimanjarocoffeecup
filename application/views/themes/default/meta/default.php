<title><?php echo $page['browser_title'];?></title>
<?php if($page['meta_description']) { ?>
<meta name="description" content="<?php echo htmlentities($page['meta_description']); ?>" />
<?php } ?>
<?php if($page['meta_keywords']) { ?>
<meta name="keywords" content="<?php echo htmlentities($page['meta_keywords']); ?>" />
<?php } ?>