
<div class="  index">
    <h2><?php echo "Possíveis Eventos"; ?></h2> 
</div> 
<div class="actions">
	<ul>
	    <li><?php echo $this->Html->link(__('< Voltar'), 
    		            $this->request->referer()); ?>
            </li> 
	    <li> <?php echo $this->Html->link(__('List Annotations Groups'), array('controller' => 'annotationGroups', 'action' => 'index')); ?></li>
             
	</ul>
</div>
<table>
    <tr>
        <th>News</th> 
        <th>Chave</th>
        <th>Data</th>
        <th>Cidade</th>
        <th class="actions"> </th>
    </tr>
<?php foreach ($groups['candidateEvents'] as $key => $candidate): ?>
    <tr> 
        <td>
            <?php echo $this->Html->link(__($candidate[0]['news_id']), 
                array('controller' => 'news', 'action' => 'annotate',
                      $candidate[0]['news_id'])); ?>
        </td>  
        <td><?php echo $key ?></td> 
        <td><?php echo $candidate[0]['date'] ?></td> 
        <td><?php echo $candidate[0]['city'] ?></td>  
        <td class="actions"><?php echo $this->Html->link(__('Agrupar'),
                array('controller' => 'annotation_groups', 'action' => 'generate_event',
                      'x' => implode(',', $groups['detailIdByKey'][$key]))); ?>
        </td> 
    </tr>  
     
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