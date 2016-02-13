<?php

App::uses('AppModel', 'Model', 'Tag', 'Source');

/**
 * News Model
 *
 * @property Annotations $annotations
 */
class News extends AppModel {

    /**
     * Primary key field
     *
     * @var string
     */
    public $primaryKey = 'news_id';

    /**
     * Display field
     *
     * @var string
     */
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

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * hasMany associations
     *
     * @var array
     */
    /* public $hasMany = array(
      'annotations' => array(
      'className' => 'Annotation',
      'foreignKey' => 'news_id',
      'dependent' => false,
      'conditions' => 'annotations.news_id = News.news_id',
      'fields' => '',
      'order' => '',
      'limit' => '',
      'offset' => '',
      'exclusive' => '',
      'finderQuery' => '',
      'counterQuery' => ''
      )
      ); */
}
