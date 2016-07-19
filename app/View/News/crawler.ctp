<?php $this->Html->script('jquery-2.1.1.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('select2.full.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery.qtip.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery.textHighlighter.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery-ui-1.10.4.custom.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('datepicker-pt-BR.js',array('inline'=>false)); ?>
<?php $this->Html->script('tablesorter2.js',array('inline'=>false)); ?>
<?php $this->Html->script('tablesorter.widgets.js',array('inline'=>false)); ?>
<?php $this->Html->script('moment.js',array('inline'=>false)); ?>
<?php $this->Html->script('bootstrap-sortable.js',array('inline'=>false)); ?>
<?php echo $this->Html->css('jquery-ui-1.10.4.custom.css'); ?>
<?php echo $this->Html->css('select2-new.css'); ?>
<?php echo $this->Html->css('tablesorter.css'); ?>
<?php echo $this->Html->css('jquery.qtip.min.css'); ?>

<script>
var URL_START_CRAWLING = "<?php echo Router::url(array('controller' => 'news', 'action' => 'start_crawler')); ?>";
var URL_CRAWLER_STATUS = "<?php echo Router::url(array('controller' => 'news', 'action' => 'crawler_status')); ?>";
$(document).ready(function () {
    addDatePicker($('.datepicker')); 
    $("#keywords").select2({
        tags: true,
        placeholder: "Digite as palavras chave",
        tokenSeparators: [',', ' ']
    });
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
        defaultDate: '<?php echo date("Y-m-d")?>',
        todayHighlight: true,
        regional: $.datepicker.regional['pt_BR']
    });
}

function initCrawling(){
    var crawlingButton = $("#init-crawling-button");
    var startDate = $("#data-inicial").val();
    var endDate = $("#data-final").val();
    var keywords = $("#keywords").select2('val');
    var debugString = "Iniciando crawling " + startDate + ", " + endDate + ", " + keywords;
    
    if(startDate && endDate && keywords){
        crawlingButton.prop("disabled", true);
        console.log(debugString);
        $('#processing_crawler').dialog({
            modal:true, 
            closeOnEscape: false,
            //beforeClose: function (event, ui) { return false; },
            dialogClass: "noclose"
        });
        $.post(URL_START_CRAWLING, {startDate:startDate, endDate:endDate, keywords:keywords}, function(crawling_response){
            console.log("Response");
            console.log(crawling_response);
            console.log("id = " + crawling_response.crawler_id)
            var url_status = URL_CRAWLER_STATUS + '/' + crawling_response.crawler_id;
            console.log("url_status = " + url_status);
            
            var timer = setInterval(function(){ 
                $.get(url_status, function(status_response){
                    console.log(status_response);
                    if(!status_response.running){
                        clearInterval(timer);
                        $('#processing_crawler').dialog("close");
                        $('#crawling_finished').dialog();
                    }
                }, 'json');
            }, 5 * 1000);
        }, 'json');

    }
    else if(!startDate){
        alert("Preencher data inicial");
    }
    else if(!endDate){
        alert("Preencher data final");
    }
    else if(!keywords){
        alert("Preencher palavras chave");
    }
}

</script>

<div class="actions">
    <ul>
        <li><?php echo $this->Html->link(__('< Voltar'), 
    		            $this->request->referer()); ?>
        </li>
	<li style='vertical-align:middle !important;'>
	        <?php echo $this->Html->link(__('< Todas as notícias'), 
	              array('controller' => 'news', 'action' => 'index')); ?>
        </li>
        <li style='vertical-align:middle !important;'>
            <?php echo $this->Html->link(__('Notícias Candidatas'), 
                  array('controller' => 'news', 'action' => 'news_candidatas')); ?>
        </li> 
    </ul>
</div>  
<div class = 'index'>
<h2> <?php echo "Crawler"; ?></h2>

<form>
<table style="width:100%">
    <tr>
        <td style="padding: 10px; vertical-align: middle;" class="value">
            <input class='datepicker' id='data-inicial' placeholder="Data inicial">
        </td>
        <td style="padding: 10px; vertical-align: middle;" class="value">
            <input class='datepicker' id='data-final' placeholder="Data final">
        </td>
    </tr>
    <tr>
        <td style="padding: 10px; vertical-align: middle;" colspan="2">
            <select id="keywords" multiple="" style="width:100%"></select>
        </td>
    </tr>
    <tr>
        <td style="padding: 10px; vertical-align: middle;">
            <label>Origem</label>
        </td>
        <td style="padding: 10px; vertical-align: middle;">
            <input type="checkbox" disabled="true" checked="true">
            Folha de São Paulo
        </td>
    </tr>        
     <tr>
        <td style="padding: 10px; vertical-align: middle;" colspan="2">
            <input id="init-crawling-button" type="button" value="Iniciar crawling" 
                   class="submit" onclick="javascript:initCrawling();"></input>
        </td>
    </tr>
</table>
</form>
 </div>
<div id="message-loading"    style='display:none'>Loading ...</div>
<div id="processing_crawler" style='display:none;text-align:center'>
    <span align="center">
    <h2>Crawler em execução</h2>
<!--    <h3>Não recarregue esta página</h3> -->
<br/>
<?php echo $this->Html->link(__('Ver avanço'), 
                  array('controller' => 'news', 'action' => 'news_candidatas')); ?>
<br/>
    <?php echo $this->Html->image("processing.gif", array("width"=>"200px", "alt" => __("Processing"), "title" => __("Processing"))); ?>
    </span>    
</div>

<div id="crawling_finished" style='display:none;text-align:center'>
<span>
<h2> Crawling finalizado </h2>
<?php echo $this->Html->link(__('Revisar notícias candidatas'), 
                  array('controller' => 'news', 'action' => 'news_candidatas')); ?>
</span>
</div>                  
                  
                  
                  
