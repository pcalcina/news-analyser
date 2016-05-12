<?php $this->Html->script('jquery-2.1.1.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('select2.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery.qtip.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery.textHighlighter.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery-ui-1.10.4.custom.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('datepicker-pt-BR.js',array('inline'=>false)); ?>
<?php echo $this->Html->css('jquery-ui-1.10.4.custom.css'); ?>
<?php echo $this->Html->css('select2.css'); ?>
<?php echo $this->Html->css('jquery.qtip.min.css'); ?>

<script>
    var groupIds = <?php echo json_encode($groupIds); ?>;
    var groups = <?php echo json_encode($groups); ?>;
    var tamGroupIds = groupIds.length;
    var TAGS = <?php echo json_encode($tags); ?>;
    var cantGroup_Column1 = Math.ceil(tamGroupIds/2);
    var cantGroup_Column = [Math.ceil(tamGroupIds/2) , Math.floor(tamGroupIds/2) ]; 
    
    var URL_CREATE_EVENT = '<?php echo Router::url(
                                array('controller' => 'events', 
                                      'action'     => 'createEventAjax')); ?>';
    var city = <?php echo $city; ?>;;
    var date = <?php echo $date; ?>;;
    $(document).ready(function () { 
        //fillGroups(groups); 
    });
 
    function createEvent() { 
        
        var groupIds = [];
        $('.groups-container').each(function(i,container){ 
            if($(container).find('.incluir').is(':checked') == true)
            {
                groupIds.push ($(container).find('.event-group-id').val()); 
            } 
        }); 
        var name =  $('.nameEvent').val();
        if(groupIds.length > 0)
        {
            $.post(
            URL_CREATE_EVENT,
            {groupIds: groupIds, name: name},
            function (link) {  
               location.replace(link);  
            },
            'text'      
            );   
        }
        else
        {
            //No se puede crear evento porque no se elegio ningun annotation groups 
            alert("Elegir annotations groups para gerar evento");
            //$('#message-saving').show();
        }
        
    } 
    
</script> 

<!--?php foreach ( $group['Annotation'] as $indexAnotations => $anotations): ?>
    < ?php echo $anotations[$indexAnotations]; ?>
    <!--?php if($annotations[$indexAnotations]['tag_id']==2): ?> 
         <!--?php $city= $annotations[$indexAnotations]['AnnotationDetail'][0]['value']; ?>   
    <!--- ?php endif; ? - -> 
< ?php endforeach; ?-->  

<div class="  index">
    <h2><?php echo "Gerar Eventos"; ?></h2> 
    <h4><?php echo "Nome do Evento: "; ?></h4>  <input type="text" class='nameEvent' name="nameEvent" style="width: 400px; " value='<?php echo $city . "_" .$date?>'> 
</div> 
<div class="actions">
	<ul>
	    <li><?php echo $this->Html->link(__('< Voltar'), 
    		            $this->request->referer()); ?>
            </li> 
	    <li> <a href='javascript:createEvent();'> Criar Evento </a></li>
             
	</ul>
</div>

 

<div>
    <table style="width:100%">
        <tr> 
          <?php foreach ( $groups as $index => $group): ?>
            <td>        
            <div id='event-group-container-original' class ='groups-container'>
                <input type='hidden' value='<?php echo $group['AnnotationGroup']['annotation_group_id'];?>' class='event-group-id'> 
                <table>
                    <tr>
                       
                      <td style='text-align:right;vertical-alightment:center;width:30px'>
                          <div style='text-align:left;vertical-alightment:center;width:80px'> 
                               
                               <?php echo $this->Html->link(__($group['AnnotationGroup']['annotation_group_id']),
                                    array('controller' => 'news', 'action' => 'annotate',
                                        $group['AnnotationGroup']['news_id'])); ?>
                              
                          </div>
                      </td>
                      <td style='text-align:right;vertical-alightment:center;width:30px'>
                        <div style='text-align:left;vertical-alightment:center;width:80px'   > <input type="checkbox"  class='incluir'  > Incluir </div>
                      </td>
                    </tr>
                </table>
                <table style="width:100%">
                    <tbody class='event-group-annotations'>
                      <?php foreach ( $group['Annotation'] as $indexAnotations => $anotations): ?>
                         <?php foreach ( $anotations['AnnotationDetail'] as $indexAnnotationDetail => $annotationDetail): ?> 
                            <tr>
                                <td> <?php echo $tagsDetailById[$annotationDetail['tag_detail_id']]['TagDetail']['name']; ?> </td>
                                <td> <?php echo $annotationDetail['value'] ; ?> </td>
                            </tr>
                         <?php endforeach; ?>   
                      <?php endforeach; ?>
                        
                      <!--?php foreach ( $group['Annotation'] as $indexAnotations => $anotations): ?> 
                        <tr class="TAG-< ?php echo $anotations['tag_id']; ?>">
                            <td class="label" style="font-size: 10pt;"><b>< ?php echo $tags[$anotations['tag_id']]['Tag']['name']; ?></b>&nbsp;</td>
                            <td class="value" style="padding: 0px; vertical-align: middle;">
                                <table style="font-size: 8pt;">
                                    <tbody>
                                        < ?php foreach ( $anotations['AnnotationDetail'] as $indexAnnotationDetail => $annotationDetail): ?> 
                                        < ?php echo $annotationDetail['tag_detail_id'] ; ?>
                                        < ?php echo $annotationDetail['value'] ; ?> 
                                        < ?php endforeach; ?> 
                                    </tbody>
                                </table> 
                            </td> 
                        </tr>
                      < ?php endforeach; ?-->
                    </tbody>
                </table>    
                 </div>
                </td>    
                <?php if (($index+1) % 2 == 0):?> 
                    </tr><tr> 
                <?php endif; ?>   
          <?php endforeach; ?> 
    </tr>
  </table>
</div>

 