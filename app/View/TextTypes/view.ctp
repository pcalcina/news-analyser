<div class="textTypes view">
<h2><?php echo __('Text Type'); ?></h2>
	<dl>
		<dt><?php echo __('Text Type Id'); ?></dt>
		<dd>
			<?php echo h($textType['TextType']['text_type_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($textType['TextType']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Text Type'), array('action' => 'edit', $textType['TextType']['text_type_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Text Type'), array('action' => 'delete', $textType['TextType']['text_type_id']), null, __('Are you sure you want to delete # %s?', $textType['TextType']['text_type_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Text Types'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Text Type'), array('action' => 'add')); ?> </li>
	</ul>
</div>
