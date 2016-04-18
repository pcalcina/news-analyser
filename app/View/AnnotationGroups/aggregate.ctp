<?php

$this->Html->script('jquery-2.1.1.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('select2.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery.qtip.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery.textHighlighter.js',array('inline'=>false)); ?>
<?php $this->Html->script('jquery-ui-1.10.4.custom.min.js',array('inline'=>false)); ?>
<?php $this->Html->script('datepicker-pt-BR.js',array('inline'=>false)); ?>
<?php echo $this->Html->css('jquery-ui-1.10.4.custom.css'); ?>
<?php echo $this->Html->css('select2.css'); ?>
<?php echo $this->Html->css('jquery.qtip.min.css'); ?>

<script>
    var attributes = {}; 
    var TAGS = <?php echo json_encode($tags); ?>;
    var TagsTypesById = <?php echo json_encode($tagTypesById); ?>;
    var TextTypesById = <?php echo json_encode($textTypesById); ?>;    
    var TAG_NAMES = {};
    for (var i = 0; i < TAGS.length; i++) {
        TAG_NAMES[TAGS[i].Tag.tag_id] = TAGS[i].Tag.name;
    }
    var radio_count_tag_detail = 1;   
    
    $(document).ready(function () {
        addDatePicker($('.datepicker')); 
        //addDatePicker($('.datepicker'));  
        fillEvent();
        
        //fillEvent(event);
        //fillEventGroups(savedEventGroups); 
        //setInterval(function () {
            //saveEventGroups();
        //}, 60000); 
        
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
    
    function fillEvent(){
        var container = $('#event-group-container-original');
        container.removeAttr('id');
        container.addClass('event-group-container');
        
        $(TAGS).each(function (i, tag) {  
            addInputPropertyVacios({
                table: $('.event-group-annotations', container),
                selectedTag: tag.Tag.tag_id,
                emptyProperty: true
                        //,selectedTagName: tag.Tag.name
            }); 
        }); 
    }
             
    function addInputPropertyVacios(options) {
        
        options = normalizeInputPropertyOptions(options);
        var row;
        var rowClass = "TAG-" + options.selectedTag;
        row = $("<tr>").addClass(rowClass);
        row.data('selectedTag', options.selectedTag);
        row.data('validValue', false);
        var tdLabel = createTdLabel(options); 
        var tdValue = createTdValue(options);
        var tdChange = createTdChange(options);
        row.append(tdLabel)
                     .append(tdValue)  
                     .append(tdChange);
        options.table.append(row);
        options.text = '';
        
    }
    
    function createTdLabel(options) {
        var tdLabel = $("<td>").addClass('label').css('font-size', '10pt');
        tdLabel.append(TAG_NAMES[options.selectedTag]);
        return tdLabel;
    }

    function createTdValue(options) { 
        var annotation;
        var tdValue = $("<td>").addClass('value')
                .css('padding', '0px')
                .css('vertical-align', 'middle');
        var btnChangeValue = $('<a>')
                .append('[++]')
                .prop('title', 'Clique para editar')
                .attr('href', 'javascript:')
                .click(function ()
                {
                     
                    options.emptyProperty = false;
                    addInputPropertyNew(annotation,options);//Vacio
                });
        tdValue.append('NA&nbsp;');
        tdValue.append(btnChangeValue);
        return tdValue;
    }

    function createTdChange(options) {
        var tdChange = $("<td>")
                .addClass("change")
                .addClass('actions')
                .css('font-size', '10pt')
                .css('padding', '6px 0px 0px 6px');

        return tdChange;
    }
    
    function addInputPropertyNew(annotation,options) { 
        options = normalizeInputPropertyOptions(options);
 
        var row;
        var rowClass = "TAG-" + options.selectedTag;

        row = options.table.find('>tr.' + rowClass).first();
            
        if (row.data('validValue')) {
                //Cuando quiero crear un nuevo anotation de un mismo tipo 2da a mas
                options.forceAdd = false;
                var newRow = row.clone();
                
                newRow.find('>td.label')
                        .html('<b>' + TAG_NAMES[options.selectedTag] + '</b>');
                newRow.find('.btnRemove').remove();
                newRow.data('validValue', true);
                newRow.data('selectedTag', row.data('selectedTag'));
                newRow.removeClass(rowClass);
                newRow.find('>td.value').empty().append(
                         
                        createInputPropertyNew(annotation,options.text, options.selectedTag,options.EventannotationId));

                if (row.data('clones').length == 0) {
                    newRow.insertAfter(row);
                }
                else {
                    newRow.insertAfter(row.data('clones').slice(-1)[0]);
                }

                row.data('clones').push(newRow);

                //if (options.annotationId) {
                if(annotation){
                    newRow.data('EventannotationId', annotation.annotation_id);
                }
                /*else
                {
                    newRow.data('annotationId', "newAnnotation");
                }*/
                 
        } 
        else {
            //Crear un anotation 1ravez
            
            row.data('validValue', true);
            row.data('clones', []);
               
            var btnRemove = createBtnRemoveInputProperty(options);
            console.log("Criando btnRemove");
            btnRemove.addClass('btnRemove');
            btnRemove.click(function () {  
                if (row.data('clones').length === 0) {
                    row.find('.label').replaceWith(createTdLabel(options)); 
                    row.find('.value').replaceWith(createTdValue(options));
                    row.find('.change').replaceWith(createTdChange(options));
                    row.data('validValue', false);
                    console.log("rem1");
                    removeAnnotation(row);
                }
                else {
                    var oldRow = row.data('clones').pop();
                    console.log("rem2");
                    removeAnnotation(oldRow);
                    oldRow.remove();
                }
            });
      
            var tdValue = createInputPropertyNew(annotation,options.text, options.selectedTag,options.EventannotationId);
            console.log(tdValue);
            addTagRowToTableNew(options,tdValue,btnRemove,row,options.table); 
 
            //if (options.annotationId) {
            if(annotation){
                    //row.data('annotationId', options.annotationId);
                    row.data('EventannotationId', annotation.event_annotation_id);
                    options.EventannotationId = null;
            }
            /*else
            {
                row.data('annotationId', "newAnnotation");
            }*/
            
        }
 
        options.text = '';
    }
     
    function createInputPropertyNew(annotation, text, selectedTag, selectedAnnotation) { 
        var table = $('<table>').css("font-size", "8pt");
         
        if(annotation)
        {
             
            $(annotation.AnnotationDetail).each(function (i, annotationDetail) {
            
                var currentTagDetail = TagDetail[annotationDetail.tag_detail_id];
                var currentTagDetail2 = currentTagDetail.TagDetail;
                var value = annotationDetail.value;
                //var nameClass = annotationDetail.annotation_detail_id + "-" + currentTagDetail.TagDetail.tag_type_id;
                var tr = createInputPropertyDetailNew(currentTagDetail2,value,annotation.annotation_id, annotationDetail.annotation_detail_id); 
                tr.data('annotation_detail_id',annotationDetail.annotation_detail_id);
                table.append(tr); 
            });  
        }
        else
        { 
                
            for (var i = 0; i < TAGS.length; i++) {
 
                if(TAGS[i].Tag.tag_id==selectedTag)
                {  
                    var table = $('<table>').css("font-size", "8pt"); 
                    $(TAGS[i].TagDetail).each(function (i, currentTagDetail) { 
                        var tr = createInputPropertyDetailNew(currentTagDetail,currentTagDetail.default_val,"AnnID","AnnDetailID");
                        table.append(tr); 
                    }); 
                    break;
                } 
            }  
        }
        radio_count_tag_detail++;
        return table;
    }
    
     
    function get_name_radio(){
         return "Tag_Detail_" + radio_count_tag_detail;
         
    }
    
    function removeAnnotation(row) {
        var EventannotationId = row.data('EventannotationId');

        if (EventannotationId) {
            $.ajax({
                type: "POST",
                url: URL_REMOVE_ANNOTATION,
                data: {id: EventannotationId},
                success: function (annotations) {
                }
            });
        }
    }
    
    function createBtnRemoveInputProperty(options) {
        var btnRemove = $('<a>')
                .append('<b>-</b>')
                .click(function () {
                    if (options.highlights) {
                        try {
                            $('#texto-principal').getHighlighter()
                                    .removeHighlights(options.highlights);
                        }
                        catch (e) {

                        } 
                    }
                });

        return btnRemove;
    }
    
    function createInputPropertyDetailNew(currentTagDetail,value,EventannotationId,annotationDetailID)
     {
         var currentTagType = TagsTypesById[currentTagDetail.tag_type_id];
         var tr; 
         switch (currentTagType.TagType.name) {
            case "TextBox":
                        var typeText =TextTypesById[currentTagDetail.text_type_id].TextType.name;
                        tr = createInputTextBox(value,typeText);
                        break;
            case "CheckBox":
                        tr = createInputCheckBox(currentTagDetail.title, value);
                        break;
            case "RadioBox": 
                        tr = createInputRadioBox(currentTagDetail.title, value, get_name_radio(), annotationDetailID);
                        break;
            case "Labelled TextBox":
                        var typeText =TextTypesById[currentTagDetail.text_type_id].TextType.name;
                        tr = createInputLabelledTextBox(currentTagDetail.title,value, typeText);
                        break;    
            default: 
                        createInputTextBox(value,"Text");
                        break;
         }
        //tr.data('annotation_detail_id',annotationDetailID);   
        tr.data('tag_detail_id',currentTagDetail.tag_detail_id);
         return tr;

     }
    function addTagRowToTableNew(options, tdValue, btnRemove, row, table) {
        var ann;
        var btnAddTag = $('<a>')
                .append('[+]')
                .prop('title', 'Clique para adicionar otra tag')
                .attr('href', 'javascript:')
                .click(function () {
                    options.emptyProperty = false;
                    options.forceAdd = true;
                    addInputPropertyNew(ann,options);//vacio
                });

        var tdLabel = row.find('>td.label');
        addBold(tdLabel);
        tdLabel.append('&nbsp;')
                .append(btnAddTag);

        row.find('>td.value').empty().append(tdValue);
        row.find('>td.change').empty().append(btnRemove);
    }
    
    function normalizeInputPropertyOptions(options) {
        options = typeof (options) == "undefined" ? {} : options;
        options.text = typeof (options.text) == "undefined" ?
                "" : options.text;
        options.highlights = typeof (options.highlights) == "undefined" ?
                null : options.highlights;
        options.selectedTag = typeof (options.selectedTag) == "undefined" ?
                null : options.selectedTag;
        options.annotationId = typeof (options.EventannotationId) == "undefined" ?
                null : options.EventannotationId;
        options.table = typeof (options.table) == "undefined" ?
                null : options.table;
        options.emptyProperty = typeof (options.emptyProperty) == "undefined" ?
                false : options.emptyProperty;

        return options;
    }
    
    function addNumberOnlyRestriction(input){
        input.on('keyup', function(){           
            var v = this.value;
            if($.isNumeric(v) === false){
                this.value = this.value.slice(0,-1);
            }
        });
    }
    
    
        function  createInputTextBox(value, typeText )//Ejemplo typeText Number, Text, etc
    {
        var inputTextBox = $('<input>').val(value);
        switch(typeText)
        {
            case "Number":
                addNumberOnlyRestriction(inputTextBox);
                break;
            case "Date": 
                inputTextBox.addClass('datepicker');
                addDatePicker(inputTextBox); 
                inputTextBox.datepicker({defaultDate: <?php echo date("Y-m-d")?>});
                break;
            default:
                //addNumberOnlyRestriction(inputTextBox);
                break; 
        }
 
        var tr = $('<tr>').append($('<td>').prop('colspan', '2').append(inputTextBox)); 
        return  tr;
    }
    
    function  createInputCheckBox(title, value ){
 
        var labelAssociation = title;
        var cbAssociation = $('<input>').prop('type', 'checkbox');
        
        if (value=="true") { 
            cbAssociation.prop('checked', true); 
        }
  
        var tr = $('<tr>')
                .append($('<td>').append(cbAssociation))
                .append($('<td>').append(labelAssociation)); 
 
        return tr;
    }
    
    function createInputRadioBox(title, value, nameRadio, annotationDetailId ){
 
        var radioName = nameRadio; 
        console.log(nameRadio);
        var radioBox = $('<input>')
                .prop('type', 'radio') 
                .prop('name', radioName) 
                .val(false);
 
        if(value === "true") {
            radioBox.prop('checked', true);
        }
        else{
            radioBox.prop('checked', false);
        }
        
        
        var tr = $('<tr>')
                .append($('<td>').append(radioBox).css('width', '10px'))
                .append($('<td>').append(title).css('vertical-align', 'middle'));        
 
        return tr;
    }
    
    function createInputLabelledTextBox(title, value, typeText){
 
        var inputTextBox = $('<input>').css('min-width', '20px').val(value);
        
        switch(typeText)
        {
            case "Number":
                addNumberOnlyRestriction(inputTextBox);
                break;
            case "Date": 
                inputTextBox.addClass('datepicker');
                addDatePicker(inputTextBox);
                inputTextBox.datepicker({defaultDate: new Date(NEWS_DATE)});
                break;
            default:
                //addNumberOnlyRestriction(inputTextBox);
                break; 
        }
   
        var tr = $('<tr>')
                .append($('<td>').append(title))
                .append($('<td>').addClass('inner-td').append(inputTextBox));
        
        return tr;
    }
    
    function addBold(element) {
        var currentLabel = element.html();
        element.html('<b>' + currentLabel + '</b>');
    }
    
     function getValueAnnotationsDetails(type, input)
    {
        var value;
        switch (type) {
            case "text":  
                value = $(input).val();
                break;
            case "checkbox":   
                value = $(input).is(':checked');
                break;
            case "radio": 
                value = $(input).is(':checked');
                console.log(value);
                break;  
            default:  
                value = $(input).val(); 
                break;
        } 
        return value;
    }
    
    function getEventAnnotationsDetail(trs)
    {   
        var annotationsDetail = [];
        
        trs.each(function(i,tr){ 
            var input = $.find('input', tr); 
            annotationsDetail.push({
                event_annotation_detail_id: $(tr).data('annotation_detail_id'),  
                tag_detail_id: $(tr).data('tag_detail_id') 
                //value : getValueAnnotationsDetails( $(input).prop('type'), input)
            }); 
            console.log(getValueAnnotationsDetails( $(input).prop('type'), input));
        });
        return annotationsDetail;
    }
    
    function getEventAnnotations(container) {
        var annotations = [];
        
        container.find('>tr').each(function (i, row) {  
            if ($(row).data('validValue')) { 
                annotations.push({
                    event_annotation_id: $(row).data('EventannotationId'), 
                    tag_id: $(row).data('selectedTag') 
                    //annotationsDetail: getAnnotationsDetail($(row).find('table>tbody>tr')) 
                }); 
                getEventAnnotationsDetail($(row).find('table>tbody>tr'))
            }
        }); 
         
        return annotations;
    }
    
    function saveEvent() {
        //$('#message-saving').show();
        var groups = [];

        //$('.event-group-container').each(function (i, container) {
        console.log("Evento");
        //console.log($('.event-group-container').find('.event-group-id').val());
        groups.push({  
                event_id: $('.event-group-container').find('.event-group-id').val(),
                name : "Provicionalmente", 
                eventAnnotations: getEventAnnotations($('.event-group-container').find('.event-group-annotations'))  
        }); 
        
         
        //console.log($('.event-group-container').find('.event-group-select').select2('val'));
        //}); 
     
        /*var highlights = $('#texto-principal').getHighlighter().serializeHighlights();

        $.post(
            URL_SAVE_ANNOTATIONS,
            {groups: groups, news_id: NEWS_ID, highlights: highlights},
            function (remoteGroups) {
                $('.event-group-container').remove();
                $('#message-saving').hide();  
                fillEventGroups(remoteGroups);
            },
            'json'      
        ); */ 
    } 

</script>


<div class="news">
  <table style="width:100%">
    <tr>
      <td style="width:55%;">
        <table>
          <?php foreach ($orderedGroups as $tagId => $annotations): ?>
          <tr>
            <td>
              <b> <?php echo $tagsById[$tagId]['Tag']['name']; ?> </b>
              <table>
              <tr>
                <?php foreach ($annotations[0]['AnnotationDetail'] as $annotationDetail): ?>
                  <td> <b><?php echo $tagsDetailById[$annotationDetail['tag_detail_id']]['TagDetail']['title']; ?> </b></td>
                <?php endforeach; ?>
              </tr>
              <?php foreach ($annotations as $annotation): ?>
                <tr>
                <?php foreach ($annotation['AnnotationDetail'] as $annotationDetail): ?>
                  <td> <?php if( $annotationDetail['value'] === 'false'): ?> 
                         <?php echo "&#9744;"; ?>
                       <?php elseif($annotationDetail['value'] === 'true'): ?>
                         <?php echo "<b>&#9745;</b>"; ?>
                       <?php else: ?>
                         <?php echo $annotationDetail['value']; ?>
                       <?php endif; ?>
                  </td>
                <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
              </table>
            </td>
          </tr>
          <?php endforeach; ?>
        </table>
      </td>
      <td id='event' style='vertical-align:top; text-align: center'>
        <div id='event-group-container-original' >
        <input type='hidden' value='' class='event-group-id'>
	    <table style="width:100%">
        <tbody class='event-group-annotations'></tbody>
        </table>
        </div>
        <span class='actions' style='text-align:center; padding-bottom:12px'> 
	    &nbsp;
        <a href='javascript:saveEvent();'> Salvar </a> 
        </span>
      </td>
   </tr>
  </table>
</div>
<div id="message-saving" style='display:none'>Salvando evento ...</div>
