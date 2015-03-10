<div class="users form">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Cadastrar usuário'); ?></legend>
        <?php echo $this->Form->input('username', array('label' => 'Usuário'));
        echo $this->Form->input('password', array('label' => 'Senha'));
        echo $this->Form->input('role', array(
            'options' => array('codificador' => 'Codificador'),
            'label' => 'Role'
        ));
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Cadastrar')); ?>
</div>
