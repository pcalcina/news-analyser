<div class="annotations form">
<?php echo $this->Form->create('Annotation'); ?>
	<fieldset>
		<legend><?php echo __('Edit Annotation'); ?></legend>
	<?php
		echo $this->Form->input('annotation_id');
		echo $this->Form->input('news_id');
		echo $this->Form->input('tag_id');
		echo $this->Form->input('value');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Ver todas as notícias'), array('controller' => 'news', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Annotation.annotation_id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Annotation.annotation_id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Annotations'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Tags'), array('controller' => 'tags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tag'), array('controller' => 'tags', 'action' => 'add')); ?> </li>
	</ul>
</div>
