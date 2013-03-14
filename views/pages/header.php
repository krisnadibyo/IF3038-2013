<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />

    <script type="text/javascript" src="<?php echo vh_link('page/approot_js'); ?>"></script>

<?php foreach($headerScripts as $script): ?>
    <script type="text/javascript" src="<?php echo vh_slink($script); ?>"></script>
<?php endforeach; ?>

    <style type="text/css">@import url(<?php echo vh_slink('css/maincss.css'); ?>);</style>

    <title><?php echo $title; ?></title>
</head>

<body>
<!-- BEGIN BODY -->

<div id="header">
    <div>
        <div id="logo"><a href="<?php echo vh_link(''); ?>">MadToDo!</a></div>
        <ul id="topNav">
            <li><a <?php if (isset($isProfile)): ?>class="current"<?php endif; ?> href="<?php echo vh_link('page/profile'); ?>">Profile</a></li>
            <li><a <?php if (isset($isDashboard)): ?>class="current"<?php endif; ?> href="<?php echo vh_link('page/dashboard'); ?>">Dashboard</a></li>
            <li><a <?php if (isset($isHome)): ?>class="current"<?php endif; ?> href="<?php echo vh_link(''); ?>">Home</a></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>
