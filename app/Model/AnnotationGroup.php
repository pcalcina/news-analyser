<?php
App::uses('AppModel', 'Model');
/**
 * AnnotationGroup Model
 *
 */
class AnnotationGroup extends AppModel {

	public $useTable = 'annotation_group';
	public $primaryKey = 'annotation_group_id';
	public $actsAs = array('Containable');
    public $hasMany = array('Annotation');
    
#	public $hasMany = array(
#	    'Annotation' => array(
#    			'className'     => 'Annotation',
#    			'conditions'    => array(
#    			    'Annotation.annotation_group_id = AnnotationGroup.annotation_group_id'),
#    			'foreignKey'    => false
#    		),
#	);
}
