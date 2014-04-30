<?php

$icons = array(
	'error' => 'icon-erroralt'
)

?>

<div class="alert_<?php echo $class?>">
    <p>
        <i class="<?= $icons[$class]?>"></i>
        <?php echo  $message; ?>
    </p>
</div>