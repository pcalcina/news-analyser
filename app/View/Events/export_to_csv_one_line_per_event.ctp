Evento§<?php echo implode('§', array_values($tagsDetailById)); ?>

<?php foreach($events as $eventId => $event): ?>
    <?php echo $eventId; ?> <?php foreach($tagsDetailById as $id => $name): ?> §<?php if(!empty($event[$id])): ?> <?php echo trim(implode('//', $event[$id])); ?> <?php endif; ?> <?php endforeach; ?> 
<?php endforeach; ?> 
