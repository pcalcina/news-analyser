<div class="textTypes form">
<?php echo $this->Form->create('TextType'); ?>
	<fieldset>
		<legend><?php echo __('Edit Text Type'); ?></legend>
	<?php
		echo $this->Form->input('text_type_id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('TextType.text_type_id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('TextType.text_type_id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Text Types'), array('action' => 'index')); ?></li>
	</ul>
</div>
