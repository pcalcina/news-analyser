<?php
App::uses('AppModel', 'Model');
/**
 * EventAnnotationDetail Model
 *
 */
class EventAnnotationDetail extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'event_annotation_detail';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'event_annotation_detail_id'; 
        public $displayField = 'value';
        public $actsAs = array('Containable');
        public $belongsTo = array('EventAnnotation'); 
}
