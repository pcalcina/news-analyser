<div class="tags view">
<h2><?php echo __('Tag'); ?></h2>
	<dl>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($tag['Tag']['type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Example'); ?></dt>
		<dd>
			<?php echo h($tag['Tag']['example']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tag Id'); ?></dt>
		<dd>
			<?php echo h($tag['Tag']['tag_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($tag['Tag']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($tag['Tag']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tag Type Id'); ?></dt>
		<dd>
			<?php echo h($tag['Tag']['tag_type_id']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Tag'), array('action' => 'edit', $tag['Tag']['tag_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Tag'), array('action' => 'delete', $tag['Tag']['tag_id']), null, __('Are you sure you want to delete # %s?', $tag['Tag']['tag_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tags'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tag'), array('action' => 'add')); ?> </li>
	</ul>
</div>
