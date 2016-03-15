<div class="tagDetails form">
<?php echo $this->Form->create('TagDetail'); ?>
	<fieldset>
		<legend><?php echo __('Add Tag Detail'); ?></legend>
	<?php
		echo $this->Form->input('annotation_id');
		echo $this->Form->input('tag_type_id');
		echo $this->Form->input('title');
		echo $this->Form->input('val');
		echo $this->Form->input('text_type_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Tag Details'), array('action' => 'index')); ?></li>
	</ul>
</div>
