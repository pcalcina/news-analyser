<div class="events index">
	<h2><?php echo __('Eventos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('name', 'Nome'); ?></th>
			<th class="actions"><?php echo __(''); ?></th>
	</tr>
	<?php foreach ($events as $event): ?>
	<tr>
		<td>
		<a href="<?php echo $this->Html->url(array('action' => 'edit', 
		    $event['Event']['event_id'])); ?>">
		    <?php echo h($event['Event']['name']); ?> </a>
		
		
		</td>
		<td class="actions">
			<?php echo $this->Form->postLink(__('Eliminar'), array('action' => 'delete', $event['Event']['event_id']), null, __('Are you sure you want to delete # %s?', $event['Event']['event_id'])); ?>
                        <?php echo $this->Form->postLink(__('Editar'), array('action' => 'edit',$event['Event']['event_id'] ), null ); ?>

                </td>
	</tr>
        <?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página {:page} de {:pages}. Mostrando {:current} resultados de um total de {:count}. ({:start} - {:end})')));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<ul>
	    <li><?php echo $this->Html->link(__('< Voltar'), 
    		            $this->request->referer()); ?>
        </li>

    	<li><?php echo $this->Html->link(__('Exportar para CSV'), 
    		            array('controller'=>'Events', 'action'=>'export_to_csv')); ?>
	    </li>

    	<li><?php echo $this->Html->link(__('< Todas as notícias'), 
    		            array('controller'=>'News', 'action'=>'index')); ?>
	    </li>
		<li><?php echo $this->Html->link(__('Criar evento'), array('action' => 'add')); ?></li>
	</ul>
</div>
