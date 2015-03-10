<div class="sources view">
<h2><?php echo __('Source'); ?></h2>
	<dl>
		<dt><?php echo __('Source Id'); ?></dt>
		<dd>
			<?php echo h($source['Source']['source_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($source['Source']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Source'), array('action' => 'edit', $source['Source']['source_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Source'), array('action' => 'delete', $source['Source']['source_id']), null, __('Are you sure you want to delete # %s?', $source['Source']['source_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Sources'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Source'), array('action' => 'add')); ?> </li>
	</ul>
</div>
