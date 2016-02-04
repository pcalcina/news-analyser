<div class="tagTypes view">
<h2><?php echo __('Tag Type'); ?></h2>
	<dl>
		<dt><?php echo __('Tag Type Id'); ?></dt>
		<dd>
			<?php echo h($tagType['TagType']['tag_type_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($tagType['TagType']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($tagType['TagType']['description']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Tag Type'), array('action' => 'edit', $tagType['TagType']['tag_type_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Tag Type'), array('action' => 'delete', $tagType['TagType']['tag_type_id']), null, __('Are you sure you want to delete # %s?', $tagType['TagType']['tag_type_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tag Types'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tag Type'), array('action' => 'add')); ?> </li>
	</ul>
</div>
