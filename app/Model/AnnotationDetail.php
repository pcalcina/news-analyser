<?php

App::uses('AppModel', 'Model');

/**
 * AnnotationDetail Model
 *
 */
class AnnotationDetail extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'annotation_detail';

    /**
     * Primary key field
     *
     * @var string
     */
    public $primaryKey = 'annotation_detail_id';

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'value';
    public $actsAs = array('Containable');
    public $belongsTo = array('Annotation');
    //public $hasMany = array('TagDetail');

}
