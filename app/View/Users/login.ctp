<div class='actions'>
	<ul>
		<li>
		<?php echo $this->Html->link(__('Cadastrar usuário'), 
		    array('controller' => 'users', 'action' => 'add')); ?></li>
    </ul>

</div>
<div class="users form">
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend>
            <?php echo __('Entrar'); ?>
        </legend>
        <?php echo $this->Form->input('username', array('label' => 'Usuário')); ?>
        <?php echo $this->Form->input('password', array('label' => 'Senha')); ?>
    </fieldset>
<?php echo $this->Form->end(__('Login')); ?>
</div>
