<?php

App::uses('AppModel', 'Model');

class Annotation extends AppModel {

    public $useTable = 'annotation';
    public $primaryKey = 'annotation_id';
    public $actsAs = array('Containable');
    public $belongsTo = array('AnnotationGroup');
    public $hasMany = array('AnnotationDetail');
//    public $hasMany = array(
//        'TagDetail' => array(
//            'className' => 'TagDetail',
//            'conditions' => array(
//                'annotation_id = TagDetail.annotation_id'),
//            'foreignKey' => false
//        ),
//    );
    public $hasOne = array(
        'tag' => array(
            'className' => 'Tag',
            'foreignKey' => false,
            'conditions' => 'tag.tag_id = Annotation.tag_id',
            'fields' => '',
            'order' => ''
        )
    );

}
