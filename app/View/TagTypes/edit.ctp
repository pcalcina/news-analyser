<div class="tagTypes form">
<?php echo $this->Form->create('TagType'); ?>
	<fieldset>
		<legend><?php echo __('Edit Tag Type'); ?></legend>
	<?php
		echo $this->Form->input('tag_type_id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('TagType.tag_type_id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('TagType.tag_type_id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Tag Types'), array('action' => 'index')); ?></li>
	</ul>
</div>
