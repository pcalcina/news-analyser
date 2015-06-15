<div class="annotationGroups form">
<?php echo $this->Form->create('AnnotationGroup'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Annotation Group'); ?></legend>
	<?php
		echo $this->Form->input('annotation_group_id');
		echo $this->Form->input('creation');
		echo $this->Form->input('event_id');
		echo $this->Form->input('news_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('AnnotationGroup.annotation_group_id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('AnnotationGroup.annotation_group_id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Annotation Groups'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Annotations'), array('controller' => 'annotations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Annotation'), array('controller' => 'annotations', 'action' => 'add')); ?> </li>
	</ul>
</div>
