<?php $this->Html->script('jquery-2.1.1.min.js', array('inline'=>false)); ?>
<?php $this->Html->script('jquery-ui-1.10.4.custom.min.js', array('inline'=>false)); ?>
<?php $this->Html->script('jquery.dataTables.min.js', array('inline'=>false)); ?>
<?php $this->Html->script('dataTables.jqueryui.min.js', array('inline'=>false)); ?>
<?php $this->Html->script('dataTables.select.min.js', array('inline'=>false)); ?>
<?php $this->Html->script('select2.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('dataTables.buttons.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('dataTables.responsive.min.js',array('inline'=>false)); ?>

<?php echo $this->Html->css('select2.css'); ?>
<?php echo $this->Html->css('jquery.dataTables.min.css'); ?>
<?php echo $this->Html->css('select.dataTables.min.css'); ?>
<?php echo $this->Html->css('jquery-ui.css'); ?>
<?php echo $this->Html->css('buttons.dataTables.min.css'); ?>
<?php echo $this->Html->css('dataTables.jqueryui.min.css'); ?>
<?php echo $this->Html->css('responsive.dataTables.min.css'); ?>

<div class="annotations index">
	<h2><?php echo __('Anotações'); ?></h2>

    <table>
    <tr>
        
    <td><?php echo $this->Form->select('tag_detail', $tag_details, 
                    array(//'empty' => 'Selecione uma variável',
                          'id'    => 'selectTagDetailId')); ?>
    </td>
    <td>
    <?php echo $this->Form->input('show_reviewed', 
                    array('type'=>'checkbox', 
                          'label' => 'Mostrar revisados',
                          'id' => 'show_reviewed',
                          'format' => array('before', 'input', 'between', 'label', 'after', 'error' ))); ?>
    </td>                      
    <td>
    <?php echo $this->Form->button('Atualizar', 
                    array('type'  => 'button',
                          'id'    => 'update-annotations')); ?>
    </td>
    </tr>
    </table> 
                            
    <table>
        <tr>
            <td> <table id="results" style="display:none">
             <thead> 
                        <tr>                            
                            <th>id</th>                            
                            <th>Notícia</th>
                            <th>Revisado</th>                            
                            <th>Valor</th>
                        </tr>
                    </thead>
            </table> </td>
            <td> <span  id="annotation_detail_selection"> </span> </td>
        </tr>
    </table>
</div>

<script>
var URL_FILTER = '<?php echo Router::url(array('controllers' => 'annotation', 
                                               'action'      => 'filterAjax')); ?>';

var URL_REPLACE = '<?php echo Router::url(array('controllers' => 'annotation', 
                                                'action'      => 'replaceAjax')); ?>';                                               
$(document).ready(function(){
    $("#selectTagDetailId").select2({width:'resolve', dropdownAutoWidth : true, placeholder:'Selecione'});
   // $("#selectTagDetailId").on("change", function (e) {
   //     updateAnnotations();
    //});
    
    $("#update-annotations").on("click", function(e){
        updateAnnotations();
    });
});

function updateAnnotations(){
    $.get(URL_FILTER + '?'+ $.param(
            {tagDetailId: $("#selectTagDetailId").select2('val'),
             showReviewed: $("#show_reviewed").is(':checked')}), 
        function(response){
            fillAnnotationDetails(response);
    }, 'json');
}

function fillAnnotationDetails(response){
    $('#results').DataTable().destroy(); 
    $('#results').replaceWith($('#original-results').clone().prop('id', 'results'));   
    $('#results').show();
    $('#results').DataTable({
        dom: 'rlfBitp',
        buttons: ['selectNone',
            {
                extend: 'selected',
                action: function ( e, dt, button, config ) {
                    showReplaceDialog(dt.rows({selected:true}).data());
                }
            }
        ],
        
        data:response, 
        language:{
            "lengthMenu":     "Mostrar _MENU_ notícias",
            "search":         "Filtrar:",
            buttons: {
                'selected':   'Substituir selecionados',
                'selectNone': 'Deselecionar todos',
                'selectAll':  'Selecionar todos'
            }
        },
        responsive: true,
        columnDefs: [
            {
                "targets": [0],
                "width": '50px',
                "visible": true,
                "searchable": false
            },
            {
                "targets": [2],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 1 ],
                "orderable": false,
                "searchable": false,
                "width": '80px',
                "render": function ( data, type, row, meta ) {
                    return '<a href="' + data + '" target="_blank">Ver notícia</a>' + 
                        (row[2] === '1' ? '  &#10004; ' : '');
                }
            }            
        ],
        pageLength: 500,
        lengthMenu: [100, 200, 500, 1000, 1500], 
        select: { style:    'os',
                  selector: 'td',
                  blurable: true},
        
    });
}

function showReplaceDialog(rawData){   
    $("#replace-text").val('');    
    $('#replace-dialog').dialog({
        width: $(window).width()/2,
        height: $(window).height() - 100,
        modal: true,
        buttons: [
            {
                text: "Confirmar substituição",
                icons: {
                    primary: "ui-icon-heart"
                },
                click: function() {
                    $("#replace-text").val();
                    
                    var api = $('#replace-table').DataTable();
                    var annotationDetails = api.columns(0).data()[0];
                    console.log(annotationDetails);
                    var replaceText = $("#replace-text").val();
                    var confirmationText = "Confirma que deseja substituir " + 
                                           annotationDetails.length + " linhas por " + 
                                           replaceText + "?";
                    if(confirm(confirmationText)){
                        $.post(URL_REPLACE, {annotationDetails: annotationDetails,
                                             replaceText: replaceText}, 
                               function(response){
                                    console.log(response);
                                    updateAnnotations();
                                    if(response.success){
                                        alert("Mudanças realizadas com sucesso");
                                    }
                               }, 'json'
                        );
                    }
                    $( this ).dialog( "close" );
                }             
            }
        ]
    });
    
    var replaceData = [];
    $.each(rawData, function(i, j){
        replaceData.push(j);
    });
    $('#replace-table').DataTable().destroy();
    $('#replace-table').DataTable({data: replaceData, dom: 'it', 
                                   columnDefs: [{"targets": [ 1, 2 ], "visible": false}]}); 
}
</script>

<div id="replace-dialog" title="Confirmar substituição" style="display:none">
    <span>
            <label>Substituir por: </label><input id="replace-text">
    </span>    
    <span>
        <table id="replace-table" style="font-size: 0.8em"> 
            <thead> 
                <tr>
                    <th>id</th>
                    <th>Notícia</th>
                    <th>Revisado</th>
                    <th>Valor</th>
                </tr>
            </thead>
        </table>
        <br/><br/>       
    </span>
</div>

<table id="original-results" style="display:none"> 
                    <thead> 
                        <tr>                            
                            <th>id</th>                            
                            <th>Notícia</th>
                            <th>Revisado</th>                            
                            <th>Valor</th>
                        </tr>
                    </thead>
                </table>

<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Ver todas as notícias'), array('controller' => 'news', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Annotation'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Tags'), array('controller' => 'tags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tag'), array('controller' => 'tags', 'action' => 'add')); ?> </li>
	</ul>
</div>
