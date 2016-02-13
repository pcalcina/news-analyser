<?php

App::uses('AppModel', 'Model');

class Tag extends AppModel {

    public $useTable = 'tag';
    public $primaryKey = 'tag_id';
    public $displayField = 'name';
}
