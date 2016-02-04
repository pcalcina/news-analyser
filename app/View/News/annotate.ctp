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
    var tagTypes = <?php echo json_encode($tag_types); ?>;
    var TAGS = <?php echo json_encode($tags); ?>;
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
    var ACTORS = <?php echo json_encode($actors); ?>;
    var CITIES = <?php echo json_encode($cities); ?>;

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

        setInterval(function () {
            saveEventGroups();
        }, 60000);

        $('#texto-principal img').load(function () {
            $(this).css('width', '100%').css('height', 'auto');
        });

        activateCommentButton();

        showComments(COMMENTS);

        console.log("<parsing>");
        if (SERIALIZED_HIGHLIGHTS) {
            $('#texto-principal').getHighlighter()
                    .deserializeHighlights(SERIALIZED_HIGHLIGHTS);
        }
        console.log("</parsing>");
    });

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

    function addTagRowToTable(options, tdValue, btnRemove, row, table) {
        var btnAddTag = $('<a>')
                .append('[+]')
                .prop('title', 'Clique para adicionar otra tag')
                .attr('href', 'javascript:')
                .click(function () {
                    options.emptyProperty = false;
                    options.forceAdd = true;
                    addInputProperty(options);
                });

        var tdLabel = row.find('>td.label');
        addBold(tdLabel);
        tdLabel.append('&nbsp;')
                .append(btnAddTag);

        row.find('>td.value').empty().append(tdValue);
        row.find('>td.change').empty().append(btnRemove);
    }

    function addSelectedTagToCurrentRow(options) {
        var selectedTag = $("#options-tags").select2("data");
        options.selectedTag = selectedTag.id;
        console.log("Options: ");
        console.log(options);
        options.table = $('.event-group-container-selected .event-group-annotations ');
        addInputProperty(options);
        $("#addTagRow").dialog("close");
    }

    function addTagRow(options) {
        $("#options-tags").select2(
                {
                    dropdownAutoWidth: true,
                    minimumResultsForSearch: -1
                }
        );


        $("#addTagRow").dialog({
            dialogClass: "no-close",
            title: "Selecione a tag",
            buttons: [
                {
                    text: "OK",
                    click: function () {
                        addSelectedTagToCurrentRow(options);
                    }
                }
            ],
            position: {my: "center top", at: "center top"}
        });

        $("#options-tags").select2('open');

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

    function createTdLabel(options) {
        var tdLabel = $("<td>").addClass('label').css('font-size', '10pt');
        tdLabel.append(TAG_NAMES[options.selectedTag]);
        return tdLabel;
    }

    function createTdValue(options) {
        var tdValue = $("<td>").addClass('value')
                .css('padding', '0px')
                .css('vertical-align', 'middle');
        var btnChangeValue = $('<a>')
                .append('[+]')
                .prop('title', 'Clique para editar')
                .attr('href', 'javascript:')
                .click(function ()
                {
                    options.emptyProperty = false;
                    addInputProperty(options);
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

    function addInputProperty(options) {
        options = normalizeInputPropertyOptions(options);
        var row;
        var rowClass = "TAG-" + options.selectedTag;

        if (options.emptyProperty) {
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
        }
        else {
            row = options.table.find('>tr.' + rowClass).first();

            console.log(options);

            if (row.data('validValue')) {
                options.forceAdd = false;
                var newRow = row.clone();
                newRow.find('>td.label')
                        .html('<b>' + TAG_NAMES[options.selectedTag] + '</b>');
                newRow.find('.btnRemove').remove();
                newRow.data('validValue', true);
                newRow.data('selectedTag', row.data('selectedTag'));
                newRow.removeClass(rowClass);
                newRow.find('>td.value').empty().append(
                        createInputProperty(options.text, options.selectedTag));

                if (row.data('clones').length == 0) {
                    newRow.insertAfter(row);
                }
                else {
                    newRow.insertAfter(row.data('clones').slice(-1)[0]);
                }

                row.data('clones').push(newRow);

                if (options.annotationId) {
                    newRow.data('annotationId', options.annotationId);
                }
            }
            else {
                row.data('validValue', true);
                row.data('clones', []);

                var btnRemove = createBtnRemoveInputProperty(options);
                btnRemove.addClass('btnRemove');
                btnRemove.click(function () {


                    if (row.data('clones').length == 0) {
                        row.find('.label').replaceWith(createTdLabel(options));
                        row.find('.value').replaceWith(createTdValue(options));
                        row.find('.change').replaceWith(createTdChange(options));
                        row.data('validValue', false);
                        removeAnnotation(row);
                    }
                    else {
                        var oldRow = row.data('clones').pop();
                        removeAnnotation(oldRow);
                        oldRow.remove();
                    }
                });
                addTagRowToTable(options,
                        createInputProperty(options.text, options.selectedTag),
                        btnRemove,
                        row,
                        options.table);

                if (options.annotationId) {
                    row.data('annotationId', options.annotationId);
                    options.annotationId = null;
                }
            }
        }

        options.text = '';
    }

    function createInputProperty(text, selectedTag) {
        var input;

        switch (tagTypes[selectedTag]) {
            case 'NUMERO_MANIFESTANTES':
                input = createInputNumberOfManifestants(text);
                break;

            case 'CONFLITO_MANIFESTANTES':
                input = createInputConflictType(text);
                break;

            case 'DATA':
                input = createInputDate(text);
                break;

            case 'ACTION':
                input = createInputAction(text);
                break;

            case 'CASUALTIES':
                input = createInputCasualties(text);
                break;

            case 'ATORES':
                input = createInputAtores(text);
                break;

            default:
                if (selectedTag == 2) { // Medida emergencial!
                    input = createStandardInputProperty(
                            text, {autocomplete: CITIES});
                }
                else {
                    input = createStandardInputProperty(text);
                }
                break;
        }

        return input;
    }

    function createStandardInputProperty(text, options) {
        var inputProperty = $("<input>");
        inputProperty.css("width", "100%");
        inputProperty.css("font-size", "10pt")
        inputProperty.val(text);
        inputProperty.addClass('annotation-value');
        inputProperty.focus();

        if (options && options.autocomplete) {
            inputProperty.autocomplete({source: options.autocomplete});
        }
        return inputProperty;
    }

    function parseJSONOrEmptyObject(strToParse) {
        var object = {};

        if (strToParse) {
            try {
                object = $.parseJSON(strToParse);
            }
            catch (e) {
            }
        }

        return object;
    }

    function createInputNumberOfManifestants(data) {
        var options = parseJSONOrEmptyObject(data);
        var table = $('<table>').css("font-size", "8pt");
        var labelPolice = 'Polícia';
        var labelNewsAgency = 'Imprensa';
        var labelManifestants = 'Manifestantes';

        var inputPolice =
                $('<input>').css('min-width', '20px').addClass('annotation-quantity-police');
        var inputNewsAgency =
                $('<input>').css('min-width', '20px').addClass('annotation-quantity-news-agency');
        var inputManifestants =
                $('<input>').css('min-width', '20px').addClass('annotation-quantity-manifestants');
        if (options.policia) {
            inputPolice.val(options.policia);
        }

        if (options.imprensa) {
            inputNewsAgency.val(options.imprensa);
        }

        if (options.manifestantes) {
            inputManifestants.val(options.manifestantes);
        }

        table.append($('<tr>')
                .append($('<td>')
                        .append(labelPolice))
                .append($('<td>').addClass('inner-td').append(inputPolice)));

        table.append($('<tr>')
                .append($('<td>')
                        .append(labelNewsAgency))
                .append($('<td>').addClass('inner-td').append(inputNewsAgency)));

        table.append($('<tr>')
                .append($('<td>')
                        .append(labelManifestants))
                .append($('<td>').addClass('inner-td').append(inputManifestants)));

        return table;
    }

    function createInputAction(data) {
        var options = parseJSONOrEmptyObject(data);
        var table = $('<table>').css("font-size", "8pt");
        var labelAction = 'Ação';
        var labelObject = 'Objeto';
        var inputObject = $('<input>').css('min-width', '80px').css('width', '100%')
                .addClass('annotation-action-object');
        var inputAction = $('<input>').css('min-width', '80px')
                .addClass('annotation-action-action');

        if (options.object) {
            inputObject.val(options.object);
            inputObject.focus();
        }

        if (options.action) {
            inputAction.val(options.action);
        }

        table.append($('<tr>')
                .append($('<td style="width:10px">')
                        .append(labelAction))
                .append($('<td>').addClass('inner-td').append(inputObject)));

        table.append($('<tr>')
                .append($('<td>')
                        .append(labelObject))
                .append($('<td>').addClass('inner-td').append(inputAction)));

        return table;
    }

    function createInputDate(date) {
        var input = createStandardInputProperty(date);
        input.addClass('datepicker');
        addDatePicker(input);
        input.datepicker({defaultDate: new Date(NEWS_DATE)});
        input.focus();
        return input;
    }

    function createInputConflictType(data) {
        var options = parseJSONOrEmptyObject(data);
        var labelManMan = 'Entre Manifestantes';
        var labelManPol = 'Manifestantes x Polícia';
        var labelManJor = 'Manifestantes x Jornalistas';
        var labelManCid = 'Manifestantes x Cidadãos';
        var labelPolJor = 'Polícia x Jornalistas';
        var labelPolCid = 'Polícia x Cidadãos';
        var labelNoConflict = 'Não houve conflito';

        var cbManMan = $('<input>').prop('type', 'checkbox').addClass('annotation-man-man');
        var cbManPol = $('<input>').prop('type', 'checkbox').addClass('annotation-man-pol');
        var cbManJor = $('<input>').prop('type', 'checkbox').addClass('annotation-man-jor');
        var cbManCid = $('<input>').prop('type', 'checkbox').addClass('annotation-man-cid');
        var cbPolJor = $('<input>').prop('type', 'checkbox').addClass('annotation-pol-jor');
        var cbPolCid = $('<input>').prop('type', 'checkbox').addClass('annotation-pol-cid');
        var cbNoConflict = $('<input>').prop('type', 'checkbox').addClass('annotation-no-conflict');

        if (options['man-man']) {
            cbManMan.prop('checked', true);
        }
        if (options['man-pol']) {
            cbManPol.prop('checked', true);
        }
        if (options['man-jor']) {
            cbManJor.prop('checked', true);
        }
        if (options['man-cid']) {
            cbManCid.prop('checked', true);
        }
        if (options['pol-jor']) {
            cbPolJor.prop('checked', true);
        }
        if (options['pol-cid']) {
            cbPolCid.prop('checked', true);
        }
        if (options['no-conflict']) {
            cbNoConflict.prop('checked', true);
        }

        var table = $('<table>').css("font-size", "8pt");

        table.append($('<tr>')
                .append($('<td>').append(cbNoConflict))
                .append($('<td>').append(labelNoConflict)));

        table.append($('<tr>')
                .append($('<td>').append(cbManMan))
                .append($('<td>').append(labelManMan)));

        table.append($('<tr>')
                .append($('<td>').append(cbManPol))
                .append($('<td>').append(labelManPol)));

        table.append($('<tr>')
                .append($('<td>').append(cbManJor))
                .append($('<td>').append(labelManJor)));

        table.append($('<tr>')
                .append($('<td>').append(cbManCid))
                .append($('<td>').append(labelManCid)));

        table.append($('<tr>')
                .append($('<td>').append(cbPolJor))
                .append($('<td>').append(labelPolJor)));

        table.append($('<tr>')
                .append($('<td>').append(cbPolCid))
                .append($('<td>').append(labelPolCid)));


        return table;
    }

    function createInputCasualties(data) {
        var options = parseJSONOrEmptyObject(data);
        var labelCasualties = 'Houve mortos?';
        var cbCasualties = $('<input>').prop('type', 'checkbox').addClass('annotation-casualties');
        var txtInjured = $('<input>').addClass('annotation-injured');

        if (options['casualties']) {
            cbCasualties.prop('checked', true);
        }

        if (options['injured']) {
            txtInjured.val(options.injured);
            txtInjured.focus();
        }

        var table = $('<table>').css("font-size", "8pt");

        table.append($('<tr>')
                .append($('<td>').prop('colspan', '2').append(txtInjured)));

        table.append($('<tr>')
                .append($('<td>').append(cbCasualties))
                .append($('<td>').append(labelCasualties)));

        return table;
    }

//FIXME: Urgent: create a generic framework for editing this!
    function createInputAtores(data) {
        var options = parseJSONOrEmptyObject(data);
        var labelAssociation = 'É associação';
        var cbAssociation = $('<input>').prop('type', 'checkbox').addClass('annotation-association');
        var txtActors = $('<input>').addClass('annotation-actors');

        txtActors.autocomplete({
            source: ACTORS
        });

        if (options['association']) {
            cbAssociation.prop('checked', true);
        }

        if (options['actors']) {
            txtActors.val(options.actors);
            txtActors.focus();
        }

        var table = $('<table>').css("font-size", "8pt");

        table.append($('<tr>')
                .append($('<td>').prop('colspan', '2').append(txtActors)));

        table.append($('<tr>')
                .append($('<td>').append(cbAssociation))
                .append($('<td>').append(labelAssociation)));

        return table;
    }

    function getSelectedText() {
        if (window.getSelection) {
            return window.getSelection().toString();
        }
        else if (document.getSelection) {
            return document.getSelection();
        }
        else if (document.selection) {
            return document.selection.createRange().text;
        }
    }

    function getAnnotationValue(row, selectedTag) {
        var value;

        switch (tagTypes[selectedTag]) {
            case 'NUMERO_MANIFESTANTES':
                value = JSON.stringify({
                    'policia': $('.annotation-quantity-police', row).val(),
                    'imprensa': $('.annotation-quantity-news-agency', row).val(),
                    'manifestantes': $('.annotation-quantity-manifestants', row).val()
                });
                break;

            case 'CONFLITO_MANIFESTANTES':
                value = JSON.stringify({
                    'man-man': $('.annotation-man-man', row).is(':checked'),
                    'man-pol': $('.annotation-man-pol', row).is(':checked'),
                    'man-jor': $('.annotation-man-jor', row).is(':checked'),
                    'man-cid': $('.annotation-man-cid', row).is(':checked'),
                    'pol-jor': $('.annotation-pol-jor', row).is(':checked'),
                    'pol-cid': $('.annotation-pol-cid', row).is(':checked'),
                    'no-conflict': $('.annotation-no-conflict', row).is(':checked')
                });
                break;

            case 'ACTION':
                value = JSON.stringify({
                    'object': $('.annotation-action-object', row).val(),
                    'action': $('.annotation-action-action', row).val()
                });
                break;

            case 'CASUALTIES':
                value = JSON.stringify({
                    'injured': $('.annotation-injured', row).val(),
                    'casualties': $('.annotation-casualties', row).is(':checked'),
                });
                break;

            case 'ATORES':
                value = JSON.stringify({
                    'actors': $('.annotation-actors', row).val(),
                    'association': $('.annotation-association', row).is(':checked'),
                });
                break;

            default:
                value = $('.annotation-value', row).val();
                break;
        }

        return value;
    }

    function getAnnotations(container) {
        var annotations = [];
        container.find('>tr').each(function (i, row) {

            if ($(row).data('validValue')) {
                annotations.push({
                    news_id: NEWS_ID,
                    tag_id: $(row).data('selectedTag'),
                    value: getAnnotationValue(row, $(row).data('selectedTag')),
                    annotation_id: $(row).data('annotationId')
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
                annotations: getAnnotations(
                        $(container).find('.event-group-annotations'))
            });
        });

        var highlights = $('#texto-principal').getHighlighter().serializeHighlights();

        console.log(groups);

        $.post(
                URL_SAVE_ANNOTATIONS,
                {groups: groups,
                    news_id: NEWS_ID,
                    highlights: highlights},
        function (groups) {
            $('.event-group-container').remove();
            $('#message-saving').hide();
            fillEventGroups(groups);
        },
                'json'
                );
    }

    function fillEventGroups(groups) {
        for (var i in groups) {
            createEventGroup(groups[i]);
        }
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
                .append(TRASH_IMAGE)
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
            addInputProperty({
                table: $('.event-group-annotations', container),
                selectedTag: tag.Tag.tag_id,
                emptyProperty: true
                        //,selectedTagName: tag.Tag.name
            });
        });

        container.click();

        if (group) {
            $('.event-group-select', container)
                    .select2('val', group.AnnotationGroup.event_id);
            container.find('.event-group-id')
                    .val(group.AnnotationGroup.annotation_group_id);

            for (var i in group.Annotation) {
                var annotation = group.Annotation[i];

                addInputProperty({text: annotation.value,
                    selectedTag: annotation.tag_id,
                    annotationId: annotation.annotation_id,
                    table: $('.event-group-annotations', container)});
            }
        }
    }

    function changeStatus(status) {
        $.ajax({
            type: "POST",
            url: URL_CHANGE_STATUS,
            data: {news_status_id: status,
                news_id: NEWS_ID},
            success: function (response) {
                if (response.success) {
                    $('#current_status_description')
                            .html(response.news_status.description);

                    if (response.news_status.next_status_id) {
                        $('#next_statuses').val(response.news_status.next_status_id);
                    }
                    else {
                        $('#next_statuses').val('');
                    }
                }
                else {
                    alert('Error cambiando status, tente mais tarde');
                }
            },
            dataType: 'json'
        });
    }

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
</script>

<div class="news">

    <table style="width:100%">
        <tr>
            <td style="width:55%;">
                &nbsp; &nbsp; &nbsp;
                <span class='actions' style='padding-bottom:12px;'>
            <?php if ($this->request->referer() != '/'): ?>
                <?php echo $this->Html->link(__('< Voltar à lista'), 
    		            $this->request->referer()); ?>
		    <?php else: ?>
                <?php echo $this->Html->link(__('< Voltar à lista'), 
    		            array('controller'=>'News', 'action'=>'index')); ?>
    		<?php endif; ?>

                    &nbsp;

            <?php echo $this->Html->link(__('Gerenciar eventos'), 
		            array('controller' => 'events', 'action' => 'index')); ?> 
                    &nbsp;


            <?php echo $this->Html->link(__('Ver tutorial'), 
                array('controller' => 'tags', 'action' => 'description')); ?>
                </span>


                <div id="texto-principal" 
                     style="border: 2px solid; border-radius:10px; margin: 10px; padding:10px">
                    <div>
                        <p><span style="width:100%; text-align:center">
                                <b>Selecione um trecho de texto para anotá-lo</b>
                            </span>
                        </p>
                        Fonte: <b> <?php echo $news['Source']['name']; ?> </b> &nbsp;&nbsp;|&nbsp;&nbsp;
                        Tags:  <b> <?php echo $news['News']['keywords']; ?> </b> <br/><br/>
                    </div>
                    <h3> <?php echo h($news['News']['title']) ?>
                        <small>(<?php echo $news['News']['date']; ?>)</small></h3>

                    <div><?php echo $news['News']['content']; ?></div>
                </div>

                <span>
                    <div style='background-color:lightgray !important;'>
                        <table class='change-status'>
                            <tr>
                                <td>Status atual:</td>
                                <td>Marcar como: </td>

                                <td rowspan=2>
                                    <input type='button' value='Marcar'
                                           onclick="changeStatus($('#next_statuses').val());">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b id='current_status_description'> 
                            <?php echo $news['NewsStatus']['description']; ?> 
                                    </b>
                                </td>

                                <td>
		            <?php echo $this->Form->input('next_status', 
                       array('default' => $news['NewsStatus']['next_status_id'], 
                             'options' => $statuses,
                             'id' => 'next_statuses',
                             'label' => '')) ?>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div style='padding:10px;'>
                        <center>
                            Fluxo de Codificação: <br/>
                            <b> 1.Suja &nbsp; &#8594; &nbsp; 
                                2.Limpa &nbsp; &#8594; &nbsp; 
                                3.Codificada &nbsp; &#8594; &nbsp; 
                                4.Validada </b>
                        </center>
                    </div>

                    <hr>
                    <span>
                        &nbsp;&nbsp;&nbsp;<label><h4>
                                <span id='comment-counter'>0</span> 
                                Comentários</h4></label>
                        <table id="comment-list" style="padding:10"></table>
                        <textarea id="txtComment" placeholder="Digite seu comentário"></textarea>
                        <button id='btnComment'>Comentar</button>
                    </span>

                </span>
            </td>

            <td id='event-groups' style="width:*; vertical-align:top; text-align: center">
                <span class='actions' style='text-align:center; padding-bottom:12px'>
                    <a href='javascript:createEventGroup();'> + Evento</a> 
                    &nbsp;
                    <a href='javascript:saveEventGroups();'> Salvar </a> 
                </span>

                <div id='event-group-container-original' style='display:none'>

                    <input type='hidden' value='' class='event-group-id'>
                    <!--
                                    <span class='actions' style='text-align:center;'>
                    -->

                    <!-- <a class='event-group-add-annotation' href="javascript:">+ Anotação</a>  -->

                    <!--
                                    </span>
                    -->

                    <table>
                        <tr>
                            <td style='text-align:center;width:30px'>
                                <div class='event-group-highlight-color' 
                                     style='background:yellow; width:30px; position:absolute;'> 
                                    &nbsp; &nbsp; &nbsp;</div>
                            </td>

                            <td style='text-align:center;vertical-alightment:center;width:*'>
                                <select class='event-group-select'>
                                    <option value=''></option>
                                <?php foreach ($events as $event): ?>
                                    <option value="<?php echo h($event['Event']['event_id']); ?>">
                                        <?php echo h($event['Event']['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                                </select>
                            </td>

                            <td class='actions' style='text-align:center;vertical-alightment:center;width:10px'>
                                <span>
                                    <a class='event-group-remove-event' href="javascript:"></a></td>
                            </span>
                        </tr>
                    </table>

                    <table style="width:100%">
                        <tbody class='event-group-annotations'></tbody>
                    </table>

                </div>
            </td>	
        </tr>
    </table>
</div>
<div id="message-saving" style='display:none'>Salvando alterações ...</div>
<div id="addTagRow" style=display:none;><span>
        <select class="options-tags" id="options-tags" style="width:100%">
    <?php foreach ($tags as $tag): ?>
            <option value="<?php echo h($tag['Tag']['tag_id']); ?>">
	    <?php echo h($tag['Tag']['name']); ?></option>
    <?php endforeach; ?>
        </select>
    </span>
</div>
