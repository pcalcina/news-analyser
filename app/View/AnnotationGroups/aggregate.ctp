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
    var tagTypes = <?php echo json_encode($tagTypes); ?>;
    var TAGS = <?php echo json_encode($tags); ?>;
    var TagsById = <?php echo json_encode($tagsById); ?>;
    var TagsTypesById = <?php echo json_encode($tagTypesById); ?>;
    var TextTypesById = <?php echo json_encode($textTypesById); ?>;
    var TagDetail = <?php echo json_encode($tagsDetailById); ?>;
    var radio_count_tag_detail = 1; 
      
    var TAG_NAMES = {};
    var NEWS_ID = '<?php echo $news['News']['news_id'] ?>';
    var USER_ID = '<?php echo $this->Session->read('Auth.User.id') ?>';
    var URL_SAVE_ANNOTATIONS = '<?php echo Router::url(
                                array('controller' => 'annotations', 
                                      'action'     => 'saveAjax')); ?>';
    var URL_REMOVE_ANNOTATION = "<?php echo Router::url(array('controller' => 'annotations', 'action' => 'deleteAjax')); ?>";
    var URL_SAVE_COMMENT = "<?php echo Router::url(array('controller' => 'comments', 'action' => 'saveAjax')); ?>";
    var TRASH_IMAGE = '<?php echo $this->Html->image("trash.png", array("width"=>"10px", "alt" => __("Delete"), "title" => __("Delete"))); ?>';
    var URL_REMOVE_ANNOTATION_GROUP = "<?php echo Router::url(array('controller' => 'annotation_groups', 'action' => 'deleteAjax')); ?>";
    var URL_CHANGE_STATUS = "<?php echo Router::url(array('controller' => 'news', 'action' => 'change_status')); ?>";
    var savedEventGroups = <?php echo json_encode($saved_event_groups); ?>;
    var SERIALIZED_HIGHLIGHTS =
<?php if (!empty($news['News']['highlights'])):?>
    <?php echo $news['News']['highlights'] ?>;
<?php else:?>
    [];
<?php endif;?>
    var COMMENTS = <?php echo json_encode($comments); ?>;
    var NEWS_DATE = '<?php echo $news['News']['date']; ?>';
    var highlightColors = ['#FFFF7B', 'lightgreen', 'lightblue', 'lightpink', 'lightsteelblue', 'lightgray'];

    for (var i = 0; i < TAGS.length; i++) {
        TAG_NAMES[TAGS[i].Tag.tag_id] = TAGS[i].Tag.name;
    }

    $(document).ready(function () {
        addDatePicker($('.datepicker'));

        $('#texto-principal').textHighlighter({
            onAfterHighlight: function (highlights, text) {

                if (text.length > 0) {
                    addTagRow({text: text, highlights: highlights});
                }
            }
        });

        $('.annotation-name').on('change', function () {
            alert($(this).val());
        });
            
        fillEventGroups(savedEventGroups);

        //setInterval(function () {
            //saveEventGroups();
        //}, 60000);

        $('#texto-principal img').load(function () {
            $(this).css('width', '100%').css('height', 'auto');
        });

        activateCommentButton();

        showComments(COMMENTS);

        //console.log("<parsing>");
        if (SERIALIZED_HIGHLIGHTS) {
            $('#texto-principal').getHighlighter()
                    .deserializeHighlights(SERIALIZED_HIGHLIGHTS);
        }
        //console.log("</parsing>");
    });
    
    function showComments(comments) {
        $("#comment-list").empty();
        $('#comment-counter').html(comments.length);
        $(comments).each(function (i, comment) {
            var row = $('<tr>').append('<td>');
            row.append($('<p>').append('&nbsp;<small><i><b>' + comment.user.username + '</b> - ' +
                    comment.Comment.timestamp + '</i></small>'));
            row.append($('<p>').append('&nbsp;&nbsp;&nbsp;' + '<big>' + comment.Comment.text + '</big>'));
            row.append('<hr>');
            $("#comment-list").append(row);
        });
    }     
    function activateCommentButton() {
        $("#btnComment").click(function () {
            $('#message-saving').show();

            $.post(
                    URL_SAVE_COMMENT,
                    {comment: $("#txtComment").val(), news_id: NEWS_ID},
            function (comments) {
                showComments(comments);
                $('#message-saving').hide();
                $("#txtComment").val('');
            },
                    'json'
                    );
        });
    }
    
    function fillEventGroups(groups) {
         
        for (var i in groups) {
            createEventGroup(groups[i]);
        }
    }
    
    function createEventGroup(group) {
        var container = $('#event-group-container-original').clone();
        container.find('.event-group-highlight-color')
                .css('background', highlightColors[$('.event-group-container').size()]);

        container.removeAttr('id');
        container.addClass('event-group-container');
        container.show();
        $('.event-group-select', container).select2({
            placeholder: "Selecione um evento"});

        container.find('.event-group-remove-event')
                .append(TRASH_IMAGE);
        container.find('.event-group-remove-event').click(function () {
            if (confirm('Eliminar evento?')) {
                removeEventGroup(container);
            }
        });

        $('#event-groups').append(container);

        container.on('click', function () {
            $('.event-group-container-selected')
                    .removeClass('event-group-container-selected');
            $(this).addClass('event-group-container-selected');
            var color = container.find('.event-group-highlight-color')
                    .css('background-color');
            $('#texto-principal').getHighlighter().setColor(color);
        });
         
         
        $(TAGS).each(function (i, tag) {
             
            addInputPropertyVacios({
                table: $('.event-group-annotations', container),
                selectedTag: tag.Tag.tag_id,
                emptyProperty: true
                        //,selectedTagName: tag.Tag.name
            });
            
        });
 
        if (group) {
            $('.event-group-select', container)
                    .select2('val', group.AnnotationGroup.event_id);
            container.find('.event-group-id')
                    .val(group.AnnotationGroup.annotation_group_id);

            for (var i in group.Annotation) {
                var annotation = group.Annotation[i];
                addInputPropertyNew(annotation,{text: annotation.value,
                    selectedTag: annotation.tag_id,
                    annotationId: annotation.annotation_detail_id,
                    table: $('.event-group-annotations', container)});//lleno
                /*addInputProperty({text: annotation.value,
                    selectedTag: annotation.tag_id,
                    annotationId: annotation.annotation_id,
                    table: $('.event-group-annotations', container)});*/
                 
            }
        } 
    }
    
    function removeEventGroup(container) {
        var groupId = container.find('.event-group-id').val();

        if (groupId) {
            $.post(
                    URL_REMOVE_ANNOTATION_GROUP,
                    {groupId: groupId},
            function (response) {
            });
        }

        container.remove();
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
    
    function addInputPropertyNew(annotation,options) {
        //console.log(options);
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
                         
                        createInputPropertyNew(annotation,options.text, options.selectedTag,options.annotationId));

                if (row.data('clones').length == 0) {
                    newRow.insertAfter(row);
                }
                else {
                    newRow.insertAfter(row.data('clones').slice(-1)[0]);
                }

                row.data('clones').push(newRow);

                //if (options.annotationId) {
                if(annotation){
                    newRow.data('annotationId', annotation.annotation_id);
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
      
            var tdValue = createInputPropertyNew(annotation,options.text, options.selectedTag,options.annotationId);
            //console.log(tdvalue);            
            addTagRowToTableNew(options,tdValue,btnRemove,row,options.table); 
 
            //if (options.annotationId) {
            if(annotation){
                    //row.data('annotationId', options.annotationId);
                    row.data('annotationId', annotation.annotation_id);
                    options.annotationId = null;
            }
            /*else
            {
                row.data('annotationId', "newAnnotation");
            }*/
            
        }
 
        options.text = '';
    }
     
    function createInputPropertyNew(annotation, text, selectedTag, selectedAnnotation) {
        //console.log(selectedAnnotation);
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
    
    
     function createInputPropertyDetailNew(currentTagDetail,value,annotationID,annotationDetailID)
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
     
    function normalizeInputPropertyOptions(options) {
        options = typeof (options) == "undefined" ? {} : options;
        options.text = typeof (options.text) == "undefined" ?
                "" : options.text;
        options.highlights = typeof (options.highlights) == "undefined" ?
                null : options.highlights;
        options.selectedTag = typeof (options.selectedTag) == "undefined" ?
                null : options.selectedTag;
        options.annotationId = typeof (options.annotationId) == "undefined" ?
                null : options.annotationId;
        options.table = typeof (options.table) == "undefined" ?
                null : options.table;
        options.emptyProperty = typeof (options.emptyProperty) == "undefined" ?
                false : options.emptyProperty;

        return options;
    }
    
    function createTdLabel(options) {
        var tdLabel = $("<td>").addClass('label').css('font-size', '10pt');
        tdLabel.append(TAG_NAMES[options.selectedTag]);
        return tdLabel;
    }

    function createTdValue(options) {
       /* console.log("------");
        console.log(options);
        console.log("------"); */
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
    
    
    function removeAnnotation(row) {
        var annotationId = row.data('annotationId');

        if (annotationId) {
            $.ajax({
                type: "POST",
                url: URL_REMOVE_ANNOTATION,
                data: {id: annotationId},
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
                inputTextBox.datepicker({defaultDate: new Date(NEWS_DATE)});
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
    
    function addNumberOnlyRestriction(input){
        input.on('keyup', function(){           
            var v = this.value;
            if($.isNumeric(v) === false){
                this.value = this.value.slice(0,-1);
            }
        });
    }
 
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
            defaultDate: new Date(NEWS_DATE),
            todayHighlight: true,
            regional: $.datepicker.regional['pt_BR']
        });
    }
  
    function addBold(element) {
        var currentLabel = element.html();
        element.html('<b>' + currentLabel + '</b>');
    }
    
    
    function get_type(thing){
        if(thing===null)return "[object Null]"; // special case
        return Object.prototype.toString.call(thing);
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
    
    function getAnnotationsDetail(trs)
    {   
        var annotationsDetail = [];
        
        trs.each(function(i,tr){ 
            var input = $.find('input', tr); 
            annotationsDetail.push({
                annotation_detail_id: $(tr).data('annotation_detail_id'),  
                tag_detail_id: $(tr).data('tag_detail_id'), 
                value : getValueAnnotationsDetails( $(input).prop('type'), input)
            }); 
            
        });
        return annotationsDetail;
    }
    
    function getAnnotations(container) {
        var annotations = [];
        
        container.find('>tr').each(function (i, row) { 
            if ($(row).data('validValue')) {
                annotations.push({
                    annotation_id: $(row).data('annotationId'),
                    news_id: NEWS_ID,
                    tag_id: $(row).data('selectedTag'), 
                    annotationsDetail: getAnnotationsDetail($(row).find('table>tbody>tr')) 
                });
            }
        });
        
        return annotations;
    }
    
    function saveEventGroups() {
        $('#message-saving').show();
        var groups = [];

        $('.event-group-container').each(function (i, container) {
            groups.push({ 
                event_id: $(container).find('.event-group-select').select2('val'),
                group_id: $(container).find('.event-group-id').val(),
                news_id: NEWS_ID,
                annotations: getAnnotations($(container).find('.event-group-annotations')) 
            }); 
        });
        
        var highlights = $('#texto-principal').getHighlighter().serializeHighlights();

        $.post(
            URL_SAVE_ANNOTATIONS,
            {groups: groups, news_id: NEWS_ID, highlights: highlights},
            function (remoteGroups) {
                $('.event-group-container').remove();
                $('#message-saving').hide(); 
                //console.log(remoteGroups);
                fillEventGroups(remoteGroups);
            },
            'json'      
        );  
    }             
     
</script>

<div class="news">

    <table style="width:100%">
        <tr>
            <td style="width:55%;">
                <table>
                <?php foreach ($annotationGroups as $group): ?>
                    <tr>
                        <td>
                            <?php foreach ($group['Annotation'] as $annotation): ?>
                                <?php foreach ($annotation['AnnotationDetail'] as $detail): ?>
                                    <?php echo $detail['tag_detail_id'] . ' - ' .$detail['value'] . '<br/>' ; ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </table>
            </td>

            <td id='event' style='vertical-align:top; text-align: center'>
                AQUI VA EL EVENTO
            </td>
        </tr>
    </table>
</div>
<div id="message-saving" style='display:none'>Salvando evento ...</div>
