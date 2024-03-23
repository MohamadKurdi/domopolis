</div>
<div id="footer" style="padding:10px;">
    <div id="version" style="text-align:center; ">
        Engine v<?php echo $oc_version; ?> |
        Framework v<?php echo $framework_version; ?> |
        <?php if ($last_commit) { ?>Last commit <?php echo $last_commit; ?> | <?php }?>
        Still making ecommerce better 😊
    </div>
</div>

<?php if ($this->user->isLogged()) { ?>
<?php require_once($this->checkTemplate(dirname(__FILE__) , 'common/footer_assets')); ?>
<?php } ?>

</body>
</html>