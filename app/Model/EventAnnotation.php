<?php
App::uses('AppModel', 'Model');
/**
 * EventAnnotation Model
 *
 */
class EventAnnotation extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'event_annotation';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'event_annotation_id';

        
        public $actsAs = array('Containable');
        public $belongsTo = array('Event');
        public $hasMany = array('EventAnnotationDetail');
    
        public $hasOne = array(
         'tag' => array(
            'className' => 'Tag',
            'foreignKey' => false,
            'conditions' => 'tag.tag_id = EventAnnotation.tag_id',
            'fields' => '',
            'order' => ''
         )
       );    
}
