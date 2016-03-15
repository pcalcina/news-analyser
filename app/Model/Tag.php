<?php

App::uses('AppModel', 'Model');

class Tag extends AppModel {

    public $useTable = 'tag';
    public $primaryKey = 'tag_id';
    public $displayField = 'name';
    //public $belongsTo = array('Annotation');
    public $actsAs = array('Containable');
    public $hasMany = array('TagDetail');
}
