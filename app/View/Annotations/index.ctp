<div class="annotations index">
	<h2><?php echo __('Annotations'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('annotation_id'); ?></th>
			<th><?php echo $this->Paginator->sort('news_id'); ?></th>
			<th><?php echo $this->Paginator->sort('tag_id'); ?></th>
			<th><?php echo $this->Paginator->sort('value'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($annotations as $annotation): ?>
	<tr>
		<td><?php echo h($annotation['Annotation']['annotation_id']); ?>&nbsp;</td>
		<td><?php echo h($annotation['Annotation']['news_id']); ?>&nbsp;</td>
		<td><?php echo h($annotation['Annotation']['tag_id']); ?>&nbsp;</td>
		<td><?php echo h($annotation['Annotation']['value']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $annotation['Annotation']['annotation_id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $annotation['Annotation']['annotation_id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $annotation['Annotation']['annotation_id']), null, __('Are you sure you want to delete # %s?', $annotation['Annotation']['annotation_id'])); ?>
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
		<li><?php echo $this->Html->link(__('Ver todas as notícias'), array('controller' => 'news', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Annotation'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Tags'), array('controller' => 'tags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tag'), array('controller' => 'tags', 'action' => 'add')); ?> </li>
	</ul>
</div>
