var EventAggregator = function(){
    
    this.test = function(){
        alert("XXX");
    }
    
    var createEventGroup = function(group) {
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
                    table: $('.event-group-annotations', container)});
            }
        } 
    }
}
