Evento§<?php echo implode('§', array_values($tagsDetailById)); ?> 
<?php foreach($events as $eventId => $event): ?><?php $i=0?> 
    <?php foreach(range(1, $maxElementsTags[$eventId]) as $d): ?> <?php echo $eventId; ?>
        <?php foreach($tagsDetailById as $id => $name): ?>  <?php if(!empty($event[$id]) && !empty($event[$id][$i])): ?> §<?php echo $event[$id][$i]; ?> <?php else: ?> §<?php echo "  "; ?> <?php endif; ?> <?php endforeach; ?><?php $i=$i+1?> 
    <?php endforeach; ?>
<?php endforeach; ?>
