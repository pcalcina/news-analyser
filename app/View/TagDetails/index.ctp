<div class="tagDetails index">
	<h2><?php echo __('Tag Details'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('tag_detail_id'); ?></th>
			<th><?php echo $this->Paginator->sort('annotation_id'); ?></th>
			<th><?php echo $this->Paginator->sort('tag_type_id'); ?></th>
			<th><?php echo $this->Paginator->sort('title'); ?></th>
			<th><?php echo $this->Paginator->sort('val'); ?></th>
			<th><?php echo $this->Paginator->sort('text_type_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($tagDetails as $tagDetail): ?>
	<tr>
		<td><?php echo h($tagDetail['TagDetail']['tag_detail_id']); ?>&nbsp;</td>
		<td><?php echo h($tagDetail['TagDetail']['annotation_id']); ?>&nbsp;</td>
		<td><?php echo h($tagDetail['TagDetail']['tag_type_id']); ?>&nbsp;</td>
		<td><?php echo h($tagDetail['TagDetail']['title']); ?>&nbsp;</td>
		<td><?php echo h($tagDetail['TagDetail']['val']); ?>&nbsp;</td>
		<td><?php echo h($tagDetail['TagDetail']['text_type_id']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $tagDetail['TagDetail']['tag_detail_id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $tagDetail['TagDetail']['tag_detail_id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $tagDetail['TagDetail']['tag_detail_id']), null, __('Are you sure you want to delete # %s?', $tagDetail['TagDetail']['tag_detail_id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Tag Detail'), array('action' => 'add')); ?></li>
	</ul>
</div>
