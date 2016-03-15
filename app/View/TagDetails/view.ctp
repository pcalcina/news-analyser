<div class="tagDetails view">
<h2><?php echo __('Tag Detail'); ?></h2>
	<dl>
		<dt><?php echo __('Tag Detail Id'); ?></dt>
		<dd>
			<?php echo h($tagDetail['TagDetail']['tag_detail_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Annotation Id'); ?></dt>
		<dd>
			<?php echo h($tagDetail['TagDetail']['annotation_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tag Type Id'); ?></dt>
		<dd>
			<?php echo h($tagDetail['TagDetail']['tag_type_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($tagDetail['TagDetail']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Val'); ?></dt>
		<dd>
			<?php echo h($tagDetail['TagDetail']['val']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Text Type Id'); ?></dt>
		<dd>
			<?php echo h($tagDetail['TagDetail']['text_type_id']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Tag Detail'), array('action' => 'edit', $tagDetail['TagDetail']['tag_detail_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Tag Detail'), array('action' => 'delete', $tagDetail['TagDetail']['tag_detail_id']), null, __('Are you sure you want to delete # %s?', $tagDetail['TagDetail']['tag_detail_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Tag Details'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tag Detail'), array('action' => 'add')); ?> </li>
	</ul>
</div>
