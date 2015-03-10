<div class="news view">
<h2><?php echo __('News'); ?></h2>
	<dl>
		<dt><?php echo __('News Id'); ?></dt>
		<dd>
			<?php echo h($news['News']['news_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($news['News']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Content'); ?></dt>
		<dd>
			<?php echo h($news['News']['content']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date'); ?></dt>
		<dd>
			<?php echo h($news['News']['date']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit News'), array('action' => 'edit', $news['News']['news_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete News'), array('action' => 'delete', $news['News']['news_id']), null, __('Are you sure you want to delete # %s?', $news['News']['news_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List News'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New News'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Annotations'), array('controller' => 'annotations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Annotations'), array('controller' => 'annotations', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Annotations'); ?></h3>
	<?php if (!empty($news['annotations'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Annotation Id'); ?></th>
		<th><?php echo __('News Id'); ?></th>
		<th><?php echo __('Tag Id'); ?></th>
		<th><?php echo __('Value'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($news['annotations'] as $annotations): ?>
		<tr>
			<td><?php echo $annotations['annotation_id']; ?></td>
			<td><?php echo $annotations['news_id']; ?></td>
			<td><?php echo $annotations['tag_id']; ?></td>
			<td><?php echo $annotations['value']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'annotations', 'action' => 'view', $annotations['annotation_id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'annotations', 'action' => 'edit', $annotations['annotation_id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'annotations', 'action' => 'delete', $annotations['annotation_id']), null, __('Are you sure you want to delete # %s?', $annotations['annotation_id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Annotations'), array('controller' => 'annotations', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
