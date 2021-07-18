    </main>
    <?php //require(includes . '_scripts.php');?>
    <footer>
        <div><a href="<?=url?>">Copyright (c) <?=date("Y ") . institute;?>. All rights reserved.</a></div>
        <div id="statusbar">
            Processed in <?=round(microtime(true) - starttime, 4)?> seconds.</div>
        <div><a href="http://www.ananyamultitech.com/">Powered by Sindhugenous Technologies</a></div>
    </footer>
</body>
</html>