<form>
<table>
    <tr>
		<th>News</th>
        <th>AnnotationGroup</th>
        <th>Data</th>
        <th>Cidade</th>
        <th>Selecionar</th>
    </tr>

<?php foreach ($groups['annotationGroups'] as $group): ?>
<tr>        
    <td> 
        <?php echo $this->Html->link(__($group[2]['news_id']),
            array('controller' => 'news', 'action' => 'annotate',
                  $group[2]['news_id'])); ?>
    </td>
    
    <td> 
        <?php echo $this->Html->link(__($group[2]['annotation_group_id']),
            array('controller' => 'annotation_groups', 'action' => 'view',
                  $group[2]['annotation_group_id'])); ?>
    </td>

    <td> 
        <?php echo $group[0]; ?>
    </td>

    <td> 
        <?php echo $group[1]; ?>
    </td>
    
    <td><input type="checkbox"></td>
</tr>
<?php endforeach; ?>
</table>

<input type="submit" value="Agrupar">
</form>


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
        <?php echo $group["value"]; ?>
    </td>
</tr>



<?php endforeach; ?>
</table>
