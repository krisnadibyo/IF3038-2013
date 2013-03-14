<div id="footer"></div>

<?php if (isset($isDashboard)):
    vh_render('pages.dialog');
endif; ?>


<?php foreach($footerScripts as $script): ?>
<script type="text/javascript" src="<?php echo vh_slink($script); ?>"></script>
<?php endforeach; ?>

<!-- END BODY -->
</body>
</html>
