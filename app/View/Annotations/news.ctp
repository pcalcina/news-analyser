
<div class="annotations form">
	<h2>Anotações da notícia</h2>
	<table>
		<thead>
			<tr>
				<th>Nome</th> 
				<th>Valor</th>
			</tr>
		</thead>
		<?php foreach ($annotations as $annotation): ?>
			<tr>
				<td> <?php echo h($annotation['tag']['name']); ?> </td>
				<td> <?php echo h($annotation['Annotation']['value']); ?> </td>
		<?php endforeach; ?>
	</table>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Voltar para notícia'), array('controller' => 'news', 'action' => 'annotate', 1)); ?></li>
		<li><?php echo $this->Html->link(__('Nova anotação'), array('action' => 'add')); ?> </li>
	</ul>
</div>
