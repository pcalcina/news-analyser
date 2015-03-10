<div class="newsStatuses form">
<?php echo $this->Form->create('NewsStatus'); ?>
	<fieldset>
		<legend><?php echo __('Edit News Status'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('NewsStatus.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('NewsStatus.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List News Statuses'), array('action' => 'index')); ?></li>
	</ul>
</div>
