<?php $this->Html->script('jquery-2.1.1.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery-ui-1.10.4.custom.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('datepicker-pt-BR.js',array('inline'=>false)); ?>
<?php //$this->Html->script('select2-latest.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('chosen.jquery.min.js', array('inline'=>false)); ?>
<?php echo $this->Html->css('jquery-ui-1.10.4.custom.css'); ?>
<?php //echo $this->Html->css('select2-latest.min.css'); ?>
<?php echo $this->Html->css('chosen.css'); ?>
<style>
.status-confirmed {text-align:centeIDr; background-color:green; color:white};
</style>

<script>
$(document).ready(function() {
    
});

function showContent(news_id){
    $("#news-content-" + news_id).dialog({
        width: '90%',
        height: '600'
    });
}
</script>

<div class="actions">
    <ul>
        <li><?php echo $this->Html->link(__('< Voltar'), $this->request->referer()); ?>  </li>
        <li><?php echo $this->Html->link(__('< Todas as notícias'), array('controller'=>'News', 'action'=>'index')); ?> </li> 
        <li><?php echo $this->Html->link(__('Crawler'), array('controller' => 'News', 'action' => 'crawler')); ?></li>
        <br> 
    </ul>
</div>

<div class="events index">
    <h2><?php echo __('Notícias Candidatas'); ?></h2>
    <span>
        <p>
        <?php echo $this->Paginator->counter(array(
        'format' => __('<b>{:count}</b> resultados. Página {:page} de {:pages}.')
        ));?>	
        </p>
    </span>
    
    <table cellpadding="0" cellspacing="0" style='font-size:10pt'>
	<tr>
	    <th><?php echo $this->Paginator->sort('news_id', 'ID'); ?></th>
	    <th> </th>
	    <th><?php echo $this->Paginator->sort('title', 'Título'); ?></th>
	    <th><?php echo $this->Paginator->sort('date', 'Data'); ?></th>
	    <th class="actions">
                <?php echo __('', ''); ?>
                <?php echo __('', ''); ?></th>
	</tr>
	
	<?php foreach ($news_list as $news): ?>
	<tr id="news-row-<?php echo h($news['News']['news_id']);?>">
            <td><?php echo h($news['News']['news_id']); ?>&nbsp;</td>
            <td><input class="selected-news" type="checkbox" data-news-id="<?php echo h($news['News']['news_id']); ?>"> </td>
            <td><a href="javascript:showContent(<?php echo $news['News']['news_id']?>)">
                <?php echo h($news['News']['title']); ?>&nbsp;</a>
            </td>
            
            <td><?php echo h($news['News']['date']); ?>&nbsp;</td>

            <td class="actions">
                <?php echo $this->Form->postLink(__('Eliminar'), 
                            array('action' => 'delete', $news['News']['news_id']), null, __('Eliminar noticia # %s?', $news['News']['news_id'])); ?>
                       
                <?php echo $this->Form->postLink(__('Aceptar'), array('action' => 'acceptNews',$news['News']['news_id']), null ); ?>
            </td>    
            <td style="display:none">
                <div id="news-content-<?php echo $news['News']['news_id']?>" style="display:none" title="<?php echo $news['News']['title']?>">
                    <div style="float:right">
                    <span class="actions">
                    <?php echo $this->Form->postLink(__('Eliminar'), 
                            array('action' => 'delete', $news['News']['news_id']), null, __('Eliminar noticia # %s?', $news['News']['news_id'])); ?>
                       
                    <?php echo $this->Form->postLink(__('Aceptar'), array('action' => 'acceptNews',$news['News']['news_id']), null ); ?>
                    </span>
                    </div>
                    <br/>
                    <?php echo $news['News']['content']?>
                </div>
                            
            </td>
	</tr>
        <?php endforeach; ?>
    </table>
    <span>
        <p>
        <?php echo $this->Paginator->counter(array(
	'format' => __('<b>{:count}</b> resultados. Página {:page} de {:pages}.')
	));
	?>	
        </p>
        <div class="paging">
            <?php
            echo $this->Paginator->prev('< ' . __('anterior'), array(), null, 
            array('class' => 'prev disabled'));
            echo $this->Paginator->numbers(array('separator' => ''));
            echo $this->Paginator->next(__('seguinte') . ' >', array(), null, 
            array('class' => 'next disabled'));
            ?>
        </div>
    </span>
</div>
