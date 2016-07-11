<?php
$this->Html->script('jquery-2.1.1.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('select2.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery.qtip.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery.textHighlighter.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery-ui-1.10.4.custom.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('datepicker-pt-BR.js',array('inline'=>false)); ?>
<?php $this->Html->script('tablesorter2.js',array('inline'=>false)); ?>
<?php $this->Html->script('tablesorter.widgets.js',array('inline'=>false)); ?>

<?php $this->Html->script('moment.js',array('inline'=>false)); ?>
<?php $this->Html->script('bootstrap-sortable.js',array('inline'=>false)); ?>


<?php echo $this->Html->css('jquery-ui-1.10.4.custom.css'); ?>
<?php echo $this->Html->css('select2.css'); ?>
<?php echo $this->Html->css('tablesorter.css'); ?>
<?php echo $this->Html->css('jquery.qtip.min.css'); ?>

<script>
    //var eventId = < ?php echo json_encode($eventId); ?>;               
    //var URL_REMOVE_ANNOTATION = '< ?php echo Router::url(array('controller' => 'eventAnnotations', 'action'     => 'deleteAjax')); ?>'; 
          
          
    $(document).ready(function () {
        addDatePicker($('.datepicker')); 
        addDate();
        //$(".annotation-group").tablesorter();  
        
    });
     function addDatePicker(element) {
        element.datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            orientation: "bottom right",
            dateFormat: 'yy-mm-dd',
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            minDate: '2012-01-01',
            maxDate: '2014-12-01',
            defaultDate: <?php echo date("Y-m-d")?>,
            todayHighlight: true,
            regional: $.datepicker.regional['pt_BR']
        });
    }
    
    function addNumberOnlyRestriction(input){
        input.on('keyup', function(){           
            var v = this.value;
            if($.isNumeric(v) === false){
                this.value = this.value.slice(0,-1);
            }
        });
    }
    
    function addBold(element) {
        var currentLabel = element.html();
        element.html('<b>' + currentLabel + '</b>');
    }
    
    function addDate(){
         
        var inputTextBox = $('<input>').val(); 
        //$('#data1').append(inputTextBox);
        console.log($('#data1'));
        
        //inputTextBox.addClass('datepicker');
        //addDatePicker(inputTextBox); 
        //inputTextBox.datepicker({defaultDate: < ?php echo date("Y-m-d")?>});
        //return inputTextBox;  
    }
    
    function crawlear(){
      var data1 = $('#data1');
      var data2 = $('#data2');
      
    }

</script> 
<div class="actions">
    <ul>
        <li><?php echo $this->Html->link(__('< Voltar'), 
    		            $this->request->referer()); ?>
        </li>
	    <li style='vertical-align:middle !important;'  ><?php echo $this->Html->link(__('< Todas as notícias'), array('controller' => 'news', 'action' => 'index')); ?>
        </li>
        <li style='vertical-align:middle !important;'  ><?php echo $this->Html->link(__('Noticias Candidatas'), array('controller' => 'news', 'action' => 'news_candidatas')); ?>
            </li> 
    </ul>
</div>  
<div class = ' index'><h2> <?php echo "Crawler"; ?></h2>
    <table style="width:100%">
        <tr>
            <td style="width:45%">
                <table>
                    <tbody>
                    <tr>
                        <td style="font-size: 10pt;" class="label"><b>Data Inicio</b></td>
                        <td style="padding: 0px; vertical-align: middle;" class="value"><table style="font-size: 8pt;"><tbody><tr><td colspan="2"><div id="date1"></div></td></tr></tbody></table></td>
                    </tr>
                    <tr>
                        <td style="font-size: 10pt;" class="label"><b>Data Fin</b>&nbsp;</td>
                        <td style="padding: 0px; vertical-align: middle;" class="value"><table style="font-size: 8pt;"><tbody><tr><td colspan="2"><input class='datapicker' id='date2'></td></tr></tbody></table></td>
                    </tr>
                    <tr>
                        <td style="font-size: 10pt;" class="label"><b>Origen</b>&nbsp;</td>
                        <td style="padding: 0px; vertical-align: middle;" class="value">
                            <table style="font-size: 8pt;">
                                <tbody>
                                    <tr>
                                        <td><input type="checkbox"></td><td>Folha Sao PAaulo</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            
            <td style="width:45%">
                <table>
                    <tbody>
                        <tr>
                            <td style="font-size: 10pt;" class="label"><b>Palavras Chave</b>&nbsp;</td> 
                            <td style="padding: 0px; vertical-align: middle;" class="value"><input></td>
                        </tr>
                    </tbody>
                </table>
                <span class='actions' style='text-align:right; padding-bottom:5px'> 
                &nbsp;
                <a href='javascript:crawlear();'> Buscar </a> 
                </span>
            </td>    
        </tr>
    </table>
 </div>
<div id="message-loading" style='display:none'>Loading ...</div>
