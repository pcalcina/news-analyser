
<?php echo implode(',', array_values($tagsDetailById)); ?>

id
<?php foreach($tagsDetailById as $id => $name): ?>
   ,<?php echo $name; ?>
<?php endforeach; ?>
\n

<?php foreach($events as $eventId => $event): ?>    
    <?php foreach(range(0, $maxElements[$eventId]) as $i): ?>
    <tr>
        <td> <?php echo $eventId; ?> </td>
        <?php foreach($tagsDetailById as $id => $name): ?>
            <td>
            <?php if(!empty($event[$id]) && !empty($event[$id][$i])): ?>
                <?php echo $event[$id][$i]; ?>
            <?php endif; ?>
            </td>
        <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
<?php endforeach; ?>

</table>
