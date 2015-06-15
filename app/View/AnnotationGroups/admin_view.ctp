<div class="annotationGroups view">
<h2><?php echo __('Annotation Group'); ?></h2>
	<dl>
		<dt><?php echo __('Annotation Group Id'); ?></dt>
		<dd>
			<?php echo h($annotationGroup['AnnotationGroup']['annotation_group_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Creation'); ?></dt>
		<dd>
			<?php echo h($annotationGroup['AnnotationGroup']['creation']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Event Id'); ?></dt>
		<dd>
			<?php echo h($annotationGroup['AnnotationGroup']['event_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('News Id'); ?></dt>
		<dd>
			<?php echo h($annotationGroup['AnnotationGroup']['news_id']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Annotation Group'), array('action' => 'edit', $annotationGroup['AnnotationGroup']['annotation_group_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Annotation Group'), array('action' => 'delete', $annotationGroup['AnnotationGroup']['annotation_group_id']), null, __('Are you sure you want to delete # %s?', $annotationGroup['AnnotationGroup']['annotation_group_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Annotation Groups'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Annotation Group'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Annotations'), array('controller' => 'annotations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Annotation'), array('controller' => 'annotations', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Annotations'); ?></h3>
	<?php if (!empty($annotationGroup['Annotation'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Annotation Id'); ?></th>
		<th><?php echo __('News Id'); ?></th>
		<th><?php echo __('Tag Id'); ?></th>
		<th><?php echo __('Value'); ?></th>
		<th><?php echo __('Annotation Group Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($annotationGroup['Annotation'] as $annotation): ?>
		<tr>
			<td><?php echo $annotation['annotation_id']; ?></td>
			<td><?php echo $annotation['news_id']; ?></td>
			<td><?php echo $annotation['tag_id']; ?></td>
			<td><?php echo $annotation['value']; ?></td>
			<td><?php echo $annotation['annotation_group_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'annotations', 'action' => 'view', $annotation['annotation_id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'annotations', 'action' => 'edit', $annotation['annotation_id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'annotations', 'action' => 'delete', $annotation['annotation_id']), null, __('Are you sure you want to delete # %s?', $annotation['annotation_id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Annotation'), array('controller' => 'annotations', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
