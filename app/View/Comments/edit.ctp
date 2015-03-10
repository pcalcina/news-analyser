<div class="comments form">
<?php echo $this->Form->create('Comment'); ?>
	<fieldset>
		<legend><?php echo __('Edit Comment'); ?></legend>
	<?php
		echo $this->Form->input('news_id');
		echo $this->Form->input('comment_id');
		echo $this->Form->input('text');
		echo $this->Form->input('parent_id');
		echo $this->Form->input('user_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Comment.comment_id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Comment.comment_id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Comments'), array('action' => 'index')); ?></li>
	</ul>
</div>
