<div class="events index">
	<h2><?php echo __('Notitcas Candidatas'); ?></h2>
	 
</div>
<div class="actions">
	<ul>
	    <li><?php echo $this->Html->link(__('< Voltar'), $this->request->referer()); ?>  </li>
            <li><?php echo $this->Html->link(__('< Todas as notícias'), array('controller'=>'News', 'action'=>'index')); ?> </li> 
            <li><?php echo $this->Html->link(__('Crawler'), array('controller' => 'News', 'action' => 'crawler')); ?></li>
            <br> 
    	</ul>
</div>
