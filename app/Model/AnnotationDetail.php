<?php
App::uses('AppModel', 'Model');
/**
 * AnnotationDetail Model
 *
 */
class AnnotationDetail extends AppModel {
 
	public $useTable = 'annotation_detail';
	public $primaryKey = 'annotation_detail_id';
        public $displayField = 'value';
        public $actsAs = array('Containable');
        public $belongsTo = array('Annotation');
    //public $hasMany = array('AnnotationDetail');

}
