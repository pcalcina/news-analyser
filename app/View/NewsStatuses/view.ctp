<div class="newsStatuses view">
<h2><?php echo __('News Status'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($newsStatus['NewsStatus']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($newsStatus['NewsStatus']['description']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit News Status'), array('action' => 'edit', $newsStatus['NewsStatus']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete News Status'), array('action' => 'delete', $newsStatus['NewsStatus']['id']), null, __('Are you sure you want to delete # %s?', $newsStatus['NewsStatus']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List News Statuses'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New News Status'), array('action' => 'add')); ?> </li>
	</ul>
</div>
