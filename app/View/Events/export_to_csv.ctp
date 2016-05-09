
<?php echo implode(',', array_values($tagsDetailById)); ?>


id
<?php foreach($tagsDetailById as $id => $name): ?>
   ,<?php echo $name; ?>
<?php endforeach; ?>,
<?php echo "\n"; ?>
<?php foreach($events as $eventId => $event): ?>    
    <?php foreach(range(0, $maxElements[$eventId]) as $i): ?>
        <?php echo $eventId; ?> ,
        <?php foreach($tagsDetailById as $id => $name): ?>
            
            <?php if(!empty($event[$id]) && !empty($event[$id][$i])): ?>
                <?php echo $event[$id][$i]; ?>
            <?php endif; ?>
            ,
        <?php endforeach; ?>
    <?php echo "\n"; ?>
    <?php endforeach; ?>
<?php endforeach; ?>


<br/>
<br/>
<br/>
<br/>
-------------------------

<br/>
<br/>
<br/>
<br/>

<table>
<th>
<td>id</td>
<?php foreach($tagsDetailById as $id => $name): ?>
   <td><?php echo $name; ?></td>
<?php endforeach; ?>
</th>

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
