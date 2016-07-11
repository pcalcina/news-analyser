<?php $this->Html->script('jquery-2.1.1.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery-ui-1.10.4.custom.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('datepicker-pt-BR.js',array('inline'=>false)); ?>
<?php //$this->Html->script('select2-latest.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('chosen.jquery.min.js', array('inline'=>false)); ?>
<?php echo $this->Html->css('jquery-ui-1.10.4.custom.css'); ?>
<?php //echo $this->Html->css('select2-latest.min.css'); ?>
<?php echo $this->Html->css('chosen.css'); ?>
<script>
$(document).ready(function() {
    URL_EVENT = "<?php echo $this->Html->url(array('controller' => 'events', 'action' => 'edit')); ?>";
    console.log(URL_EVENT);
    $("#btnGoToEvents").click(function(){
        console.log(URL_EVENT);
        window.location = URL_EVENT + '/' + $("#txtIdEvents").val();
         
    });
});
</script>
<div class="events index">
	<h2><?php echo __('Eventos'); ?></h2>
        <table> 
            <tr>
                <td style='vertical-align:middle !important; '>
                    <input id="txtIdEvents" placeholder="   ID do evento"></td>
                <td style='vertical-align:middle !important;'>
                    <input value="Ir para evento>" id='btnGoToEvents' type='button'></td> 
            </tr>
        </table>
      
            
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
	    <li><?php echo $this->Html->link(__('< Voltar'), $this->request->referer()); ?>  </li>
            <li><?php echo $this->Html->link(__('< Todas as notícias'), array('controller'=>'News', 'action'=>'index')); ?> </li> 
            <li><?php echo $this->Html->link(__('Identificar eventos'), array('controller' => 'annotation_groups', 'action' => 'possible_groups')); ?></li>
            <br>    
            <li><?php echo $this->Html->link(__('Exportar para CSV'), 
    		            array('controller'=>'Events', 'action'=>'export_to_csv')); ?>
	    </li>

            <li><?php echo $this->Html->link(__('Exportar para CSV (1 linha)'), 
    		            array('controller'=>'Events', 'action'=>'export_to_csv_one_line_per_event')); ?>
            </li>

    	 
		 	</ul>
</div>
