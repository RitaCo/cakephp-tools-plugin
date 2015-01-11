<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>
<div class="container">
    <div class="<?= h($class) ?> zoomIn"><?= h($message) ?></div>
  
</div>