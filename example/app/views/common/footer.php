
</div>

<div class="row footer-row">
    <?php
    $version = SK\Digidoc\Digidoc::version();
    $filename = "dds-hashcode-$version.tar.gz";
    $updated = file_exists($filename) ? date("d.m.Y", filemtime("$filename")) : "N/A";
    ?>
    <p><a href="<?php echo $filename ?>">Source code</a> (updated <?php echo $updated ?>). Hashcode library
        version <?php echo $version ?>.</p>
</div>
</div>

</body>
</html>