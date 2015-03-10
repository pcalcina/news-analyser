<?php $this->Html->script('jquery-2.1.1.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery-ui-1.10.4.custom.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('datepicker-pt-BR.js',array('inline'=>false)); ?>
<?php echo $this->Html->css('jquery-ui-1.10.4.custom.css'); ?>
<script>
$(document).ready(function(){
    $('#EventDate, #EventPlace, #EventOther').keyup(function(){
        updateName();
    });
    
    $('#EventDate').datepicker({
       format: "dd-mm-yyyy",
       todayBtn: "linked",
       orientation: "bottom right",
       dateFormat: 'yy-mm-dd',
       autoclose: true,
       changeMonth: true,
       changeYear: true,
       minDate: '2012-01-01',
       maxDate: '2014-12-01',
       todayHighlight: true,
       regional:$.datepicker.regional['pt-BR']
    });
});

function updateName(){
    var name = $('#EventDate').val() + ' ' + 
               $('#EventPlace').val() + ' ' + 
               $('#EventOther').val();
    $('#EventName').val(name);
}
</script>
<div class="events">

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('< Todos os eventos'), 
		    array('action' => 'index')); ?></li>
	</ul>
</div>
<br/>

<?php echo $this->Form->create('Event', array('id' => 'frm_create_event')); ?>
    <h3> &nbsp;&nbsp;&nbsp;Atualizar evento </h3> 
	<fieldset>
	<table>
    	<tr>
        	<td style='width:15%'>
                <?php echo $this->Form->input('event_id'); ?>
            	<?php echo $this->Form->input('date',  
            	    array('type' => 'text', 'label' => 'Data')); ?></td>

        	<td style='width:40%'><?php echo $this->Form->input('place', 
            	    array('type' => 'text', 'label' => 'Local')); ?></td>

        	<td colspan='2' style='width:45%'><?php echo $this->Form->input('other', 
                    array('type' => 'text', 'label' => 'Outras informações')); ?></td>
        </tr>
        <tr>
            <td colspan='3' style='width:80%'><?php echo $this->Form->input('name',  
                    array('type' => 'text', 
                          'readonly', 'readonly', 
                          'label' => 'Nome (gerado automáticamente)')); ?></td>
            <td><?php echo $this->Form->end(__('Atualizar evento')); ?></td>
        </tr>
    </table>
	</fieldset>
</div>

