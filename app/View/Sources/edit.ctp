<div class="sources form">
<?php echo $this->Form->create('Source'); ?>
	<fieldset>
		<legend><?php echo __('Edit Source'); ?></legend>
	<?php
		echo $this->Form->input('source_id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Source.source_id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Source.source_id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Sources'), array('action' => 'index')); ?></li>
	</ul>
</div>
