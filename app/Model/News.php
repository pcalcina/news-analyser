<?php

App::uses('AppModel', 'Model', 'Tag', 'Source');

class News extends AppModel {

    public $primaryKey = 'news_id';
    public $displayField = 'title';
    public $hasOne = array(
        'Source' => array(
            'className' => 'Source',
            'conditions' => array('News.source_id = Source.source_id'),
            'order' => '',
            'foreignKey' => false
        ),
        'NewsStatus' => array(
            'className' => 'NewsStatus',
            'conditions' => array('News.news_status_id = NewsStatus.id'),
            'order' => '',
            'foreignKey' => false
        )
    );
}
