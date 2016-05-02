<?php
App::uses('AppModel', 'Model', 'TagDetail');

class Event extends AppModel {
	public $useTable = 'event';
	public $primaryKey = 'event_id';
	public $displayField = 'name';
    public $actsAs = array('Containable');
    public $hasMany = array('EventAnnotation');

    public function exportAsTable(){
        $events = array();
        $q = "SELECT event_annotation.event_id, event_annotation_detail.tag_detail_id, 
                  event_annotation.event_annotation_id, event_annotation_detail.value 
              FROM event_annotation_detail 
              LEFT JOIN event_annotation ON 
                  event_annotation.event_annotation_id = event_annotation_detail.event_annotation_id";
        $raw = $this->query($q);
        
        foreach($raw as $detail){
            $eventId = $detail['event_annotation']['event_id'];
            $tagDetailId   = $detail['event_annotation_detail']['tag_detail_id'];
            $annotationValue = $detail['event_annotation_detail']['value'];
            $events[$eventId][$tagDetailId][] = $annotationValue;
        }
        return $events;
    }
}
