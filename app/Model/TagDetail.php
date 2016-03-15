<?php
App::uses('AppModel', 'Model');
/**
 * TagDetail Model
 *
 */
class TagDetail extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
    public $useTable = 'tag_detail';

/**
 * Primary key field
 *
 * @var string
 */
    public $primaryKey = 'tag_detail_id';

/**
 * Display field
 *
 * @var string
 */
    public $displayField = 'title';
    public $actsAs = array('Containable');
    //public $belongsTo = array('Tag');
    //public $belongsTo = array('AnnotationDetail');
 
}
