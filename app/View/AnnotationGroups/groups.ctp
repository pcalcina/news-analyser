<div class="annotationGroups">
	<h2><?php echo __('Grupos de anotações'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('annotation_group_id'); ?></th>
			<th><?php echo $this->Paginator->sort('creation'); ?></th>
			<th><?php echo $this->Paginator->sort('event_id'); ?></th>
			<th><?php echo $this->Paginator->sort('news_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($annotationGroups as $annotationGroup): ?>
	<tr>
		<td><?php echo h($annotationGroup['AnnotationGroup']['annotation_group_id']); ?>&nbsp;</td>
		<td><?php echo h($annotationGroup['AnnotationGroup']['creation']); ?>&nbsp;</td>
		<td><?php echo h($annotationGroup['AnnotationGroup']['event_id']); ?>&nbsp;</td>
		<td><?php echo h($annotationGroup['AnnotationGroup']['news_id']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $annotationGroup['AnnotationGroup']['annotation_group_id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $annotationGroup['AnnotationGroup']['annotation_group_id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $annotationGroup['AnnotationGroup']['annotation_group_id']), null, __('Are you sure you want to delete # %s?', $annotationGroup['AnnotationGroup']['annotation_group_id'])); ?>
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

<!--
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Annotation Group'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Annotations'), array('controller' => 'annotations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Annotation'), array('controller' => 'annotations', 'action' => 'add')); ?> </li>
	</ul>
</div>
-->
