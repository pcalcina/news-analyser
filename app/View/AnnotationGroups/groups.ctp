<table>
    <tr>
		<th>News</th>
        <th>AnnotationGroup</th>
        <th>Data</th>
        <th>Cidade</th>
    </tr>
<?php foreach ($groups['candidateEvents'] as $key => $candidate): ?>

    <?php foreach ($candidate as $group): ?>
    <tr>        
        <td> 
            <?php echo $this->Html->link(__($group['news_id']),
                array('controller' => 'news', 'action' => 'annotate',
                      $group['news_id'])); ?>
        </td>
        
        <td> 
            <?php echo $this->Html->link(__($group['annotation_group_id']),
                array('controller' => 'annotation_groups', 'action' => 'view',
                      $group['annotation_group_id'])); ?>
        </td>

        <td> 
            <?php echo $group['date']; ?>
        </td>

        <td> 
            <?php echo $group['city']; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr><th colspan="4"><center>
        <?php echo $this->Html->link(__('Agrupar'),
                array('controller' => 'annotation_groups', 'action' => 'aggregate',
                      'x' => implode(',', $groups['detailIdByKey'][$key]))); ?>
        </center></th></tr>
    <tr><td colspan="4"><center>&nbsp;</center></td></tr>    
<?php endforeach; ?>
</table>

<br/>
<br/>

<h2>Casos anômalos</h2>
<table>
    <tr>
		<th>News</th>
        <th>AnnotationGroup</th>
        <th>Value</th>
    </tr>

<?php foreach ($groups['inconsistentGroups'] as $group): ?>
<tr>        
    <td> 
        <?php echo $this->Html->link(__($group['news_id']),
            array('controller' => 'news', 'action' => 'annotate',
                  $group['news_id'])); ?>
    </td>
    
    <td> 
        <?php echo $this->Html->link(__($group['annotation_group_id']),
            array('controller' => 'annotation_groups', 'action' => 'view',
                  $group['annotation_group_id'])); ?>
    </td>

    <td> 
        <?php echo $group['value']; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
