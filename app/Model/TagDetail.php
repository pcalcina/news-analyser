<?php
App::uses('AppModel', 'Model');
class TagDetail extends AppModel {
    public $useTable = 'tag_detail';
    public $primaryKey = 'tag_detail_id';
    public $displayField = 'title';
    public $actsAs = array('Containable');

    public function getTagsDetailById () {
        $tagsDetail = $this->find('all');
        $tagsDetailById = array();
        foreach ($tagsDetail as $tagDetail) {
            $id   = $tagDetail['TagDetail']['tag_detail_id'];
            $name = $tagDetail['TagDetail']['name'];
            $tagsDetailById[$id] = $name;
        }
        return $tagsDetailById;
    }
}
