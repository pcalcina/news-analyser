<?php $this->Html->script('jquery-2.1.1.min.js',array('inline'=>false)); ?>  
<?php $this->Html->script('tablesorter2.js',array('inline'=>false)); ?>  s
<?php echo $this->Html->css('tablesorter.css'); ?> 

<script>
$(document).ready(function () {
         
        $(".annotation-group").tablesorter();  
        
    });
</script> 

<div class="  index">
    <h2><?php echo "Possíveis Eventos"; ?></h2> 
</div> 
<div class="actions">
	<ul>
	    <li><?php echo $this->Html->link(__('< Voltar'), $this->request->referer()); ?> </li>  
            <li><?php echo $this->Html->link(__('< Todas as notícias'), array('controller'=>'News', 'action'=>'index')); ?> </li> 
            <li> <?php echo $this->Html->link(__('Lista de Eventos'), array('controller' => 'events', 'action' => 'index')); ?></li>
	</ul>
</div>
<table class="annotation-group tablesorter">
    <thead>
    <tr>
        <th>News</th> 
        <th>Name</th>
        <th>Data</th>
        <th>Cidade</th>
        <th class="actions"> </th>
    </tr> 
    </thead>
    <tbody>
<?php foreach ($groups['candidateEvents'] as $key => $candidate): ?>
    <tr> 
        <td>
            <?php echo $this->Html->link(__($candidate[0]['news_id']), 
                array('controller' => 'news', 'action' => 'annotate',
                      $candidate[0]['news_id'])); ?>
        </td>  
        <td><?php echo $candidate[0]['city'] ?> - <?php echo $candidate[0]['date'] ?></td> 
        <td><?php echo $candidate[0]['date'] ?></td> 
        <td><?php echo $candidate[0]['city'] ?></td>  
        <td class="actions"><?php echo $this->Html->link(__('Agrupar'),
                array('controller' => 'annotation_groups', 'action' => 'generate_event',
                      'x' => implode(',', $groups['detailIdByKey'][$key]))); ?>
        </td> 
    </tr>  
     
<?php endforeach; ?>    
    </tbody>
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