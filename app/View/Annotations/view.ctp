<div class="annotations view">
<h2><?php echo __('Annotation'); ?></h2>
	<dl>
		<dt><?php echo __('Annotation Id'); ?></dt>
		<dd>
			<?php echo h($annotation['Annotation']['annotation_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('News Id'); ?></dt>
		<dd>
			<?php echo h($annotation['Annotation']['news_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tag Id'); ?></dt>
		<dd>
			<?php echo h($annotation['Annotation']['tag_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Value'); ?></dt>
		<dd>
			<?php echo h($annotation['Annotation']['value']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Annotation'), array('action' => 'edit', $annotation['Annotation']['annotation_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Annotation'), array('action' => 'delete', $annotation['Annotation']['annotation_id']), null, __('Are you sure you want to delete # %s?', $annotation['Annotation']['annotation_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Annotations'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Annotation'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tags'), array('controller' => 'tags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tag'), array('controller' => 'tags', 'action' => 'add')); ?> </li>
	</ul>
</div>
	<div class="related">
		<h3><?php echo __('Related Tags'); ?></h3>
	<?php if (!empty($annotation['tag'])): ?>
		<dl>
			<dt><?php echo __('Tag Id'); ?></dt>
		<dd>
	<?php echo $annotation['tag']['tag_id']; ?>
&nbsp;</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
	<?php echo $annotation['tag']['name']; ?>
&nbsp;</dd>
		</dl>
	<?php endif; ?>
		<div class="actions">
			<ul>
				<li><?php echo $this->Html->link(__('Edit Tag'), array('controller' => 'tags', 'action' => 'edit', $annotation['tag']['tag_id'])); ?></li>
			</ul>
		</div>
	</div>
	
