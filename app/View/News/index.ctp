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
   
   $('.datepicker').datepicker({
       format: "dd-mm-yyyy",
       todayBtn: "linked",
       orientation: "bottom right",
       dateFormat: 'yy-mm-dd',
       autoclose: true,
       changeMonth: true,
       changeYear: true,
       minDate: '2013-01-01',
       maxDate: '2014-12-01',
       todayHighlight: true,
       regional:$.datepicker.regional['pt_BR']
    });
    
    URL_ANNOTATE = "<?php echo $this->Html->url(array('controller' => 'news', 'action' => 'annotate')); ?>";
    
    $("#btnGoToNews").click(function(){
        window.location = URL_ANNOTATE + '/' + $("#txtIdNews").val();
    });
 
    $('#select-keywords').chosen({width:'100%', placeholder:'Escolha as palavras-chave'});

});




function confirmNews(newsId){
	$.ajax({
        type: "POST",
        url: "<?php echo Router::url(array('controller' => 'news', 
                'action' => 'confirmNews')); ?>",
        data: {newsId:newsId},
        success: function(){
            $('#news-row-' + newsId + ' .status-unconfirmed' ).empty()
                .append('Confirmado').removeClass('actions').addClass('status-confirmed');
        }
    });
}
</script>

<div class="xactions">
<table>
<tr >
<td style='vertical-align:middle !important;'><h2><?php echo __('Notícias'); ?></h2></td>
<td style='vertical-align:middle !important;' 
    class='actions'><?php echo $this->Html->link(__(' Crawler '), array('controller' => 'news', 'action' => 'crawler')); ?></td>
<td style='vertical-align:middle !important;'
    class='actions'><?php echo $this->Html->link(__('Notícias Candidatas'), array('controller' => 'news', 'action' => 'news_candidatas')); ?></td>
<td style='vertical-align:middle !important;' 
    class='actions'><?php echo $this->Html->link(__('Identificar eventos'), array('controller' => 'annotation_groups', 'action' => 'possible_groups')); ?></td>
<td style='vertical-align:middle !important;' 
    class='actions'><?php echo $this->Html->link(__('Lista de eventos'), array('controller' => 'events', 'action' => 'index')); ?></td>

<!--td style='vertical-align:middle !important;'
    class='actions'><? php echo $this->Html->link(__('Gerenciar tags'), array('controller' => 'tags', 'action' => 'index')); ?></td-->
<td style='vertical-align:middle !important;'>
    <input id="txtIdNews" placeholder="   ID da notícia"></td>
<td style='vertical-align:middle !important;'>
    <input value="Ir para a notícia >" id='btnGoToNews' type='button'></td> 
<td style='vertical-align:middle !important;'
    class='actions'>
    <a id='link_mostrar_filtro' href='javascript:' 
       onclick='$("#filtro").toggle(); $(this).toggle();$("#link_ocultar_filtro").toggle()'
       style="display:<?php echo ($show_filter)? 'none': 'block'; ?>">
        Mostrar filtro</a>
    	
    <a id='link_ocultar_filtro'  href='javascript:' 
    onclick='$("#filtro").toggle(); $("#link_mostrar_filtro").toggle(); $(this).toggle()'
    style="display:<?php echo ($show_filter)? 'block': 'none'; ?>"
;>
    Ocultar filtro</a>
</td>
</tr>
</table>
</div>

<div id='filtro' style='display:<?php echo ($show_filter)? 'block': 'none'; ?>'>
    
    <?php echo $this->Form->create(false, 
        array('type' => 'get', 
              'action' => 'filter',
              'style' => 'width: 100%; margin-right:0px')); ?>
    <fieldset style='padding:0px; margin-bottom:0px'>
    <span>
    <table style='font-size:10pt'>
    <tr>

    <td style='width:250px'>
        <?php echo $this->Form->input('keywords', 
            array('default' => $filters['keywords'], 
                  'options' => $keywords,
                  'multiple' => 'true',
                  'id' => 'select-keywords')) ?>
    
    </td>
    
    <td>
        <?php echo $this->Form->input('status', 
            array('default' => $filters['status'], 'options' => $statuses)) ?>
    </td>
    <td>        
        <?php echo $this->Form->input('source', 
            array('default' => $filters['source'], 'options' => $sources)) ?>
    </td>
    <td>
        <?php echo $this->Form->input('start_date', 
            array('type' => 'text', 
                  'class' => 'form-control datepicker',
                  'default' => $filters['start_date'], 
                  'label' => 'Data inicial',
                  'style'=>'width:100px')) ?>
    </td>
    <td>
        <?php echo $this->Form->input('end_date', 
            array('type' => 'text', 
                  'class' => 'form-control datepicker',
                  'default' => $filters['end_date'], 
                  'label' => 'Data final',
                  'style'=>'width:100px')) ?>
    </td>
    <td>
	    <?php echo $this->Form->end(__('Filtrar')); ?>
    </td>
    <td>
        <?php echo $this->Form->create(false, 
            array('type' => 'get', 'action' => 'index')); ?>
        <?php echo $this->Form->end(__('Limpar')); ?>
    </td>
    </tr>
    </table>
    </span>
    </fieldset>
</div>

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
	    <th><?php echo $this->Paginator->sort('NewsStatus.description', 'Status'); ?></th>
	    <th><?php echo $this->Paginator->sort('title', 'Título'); ?></th>
	    <th><?php echo $this->Paginator->sort('date', 'Data'); ?></th>
	    <th><?php echo $this->Paginator->sort('Source.name', 'Fonte'); ?></th>
	    <th class="actions"><?php echo __('', ''); ?></th>
	</tr>
	
	<?php foreach ($news_list as $news): ?>
	<tr id="news-row-<?php echo h($news['News']['news_id']);?>">
	  <td><?php echo h($news['News']['news_id']); ?>&nbsp;</td>

    <td><?php echo $news['NewsStatus']['description'];?></td>

	
		<td><a href="<?php echo $this->Html->url(array('action' => 'annotate', 
		    $news['News']['news_id'])); ?>">
		    <?php echo h($news['News']['title']); ?>&nbsp; </a></td>

		<td><?php echo h($news['News']['date']); ?>&nbsp;</td>

		<td><?php echo h($news['Source']['name']); ?> </td>
		
		<td class="actions">
			<?php echo $this->Form->postLink(
			$this->Html->image('trash.png',
                            array('width'=>'10px'),
                            array("alt" => __('Delete'), "title" => __('Delete')))
			, array('action' => 'delete', $news['News']['news_id'])
			, array('escape' => false)
			); ?>
		</td>
	</tr>
<?php endforeach; ?>

	</table>

<span>
<p>
    <?php
	echo $this->Paginator->counter(array(
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


