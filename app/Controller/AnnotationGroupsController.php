<?php
App::uses('AppController', 'Controller');

class AnnotationGroupsController extends AppController {

    public $components = array('Paginator');
    public $helpers    = array('Js');

	public function index() {
		$this->AnnotationGroup->recursive = 0;
		$this->set('annotationGroups', $this->Paginator->paginate());
	}

	public function view($id = null) {
		if (!$this->AnnotationGroup->exists($id)) {
			throw new NotFoundException(__('Invalid annotation group'));
		}
		$options = array('conditions' => array('AnnotationGroup.' . $this->AnnotationGroup->primaryKey => $id));
		$this->set('annotationGroup', $this->AnnotationGroup->find('first', $options));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->AnnotationGroup->create();
			if ($this->AnnotationGroup->save($this->request->data)) {
				$this->Session->setFlash(__('The annotation group has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The annotation group could not be saved. Please, try again.'));
			}
		}
	}

	public function edit($id = null) {
		if (!$this->AnnotationGroup->exists($id)) {
			throw new NotFoundException(__('Invalid annotation group'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->AnnotationGroup->save($this->request->data)) {
				$this->Session->setFlash(__('The annotation group has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The annotation group could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('AnnotationGroup.' . $this->AnnotationGroup->primaryKey => $id));
			$this->request->data = $this->AnnotationGroup->find('first', $options);
		}
	}

    public function deleteAjax(){
        $this->layout = "ajax";
    	$this->AnnotationGroup->id = $this->request->data['groupId'];

		$this->loadModel('Annotation');
		$this->Annotation->deleteAll(
		    array('Annotation.annotation_group_id' => $this->AnnotationGroup->id));
		    
		if ($this->AnnotationGroup->exists()) {
			$this->AnnotationGroup->delete();
		}
    }
    
	public function delete($id = null) {
		$this->AnnotationGroup->id = $id;
		if (!$this->AnnotationGroup->exists()) {
			throw new NotFoundException(__('Invalid annotation group'));
		}
		$this->request->onlyAllow('post', 'delete');
		$this->loadModel('Annotation');
		$this->Annotation->deleteAll(array('Annotation.annotation_group_id' => $id));
		
		if ($this->AnnotationGroup->delete()) {
			$this->Session->setFlash(__('The annotation group has been deleted.'));
		} else {
			$this->Session->setFlash(__('The annotation group could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
    
    public function groups() {
        $this->AnnotationGroup->recursive = 0;        
		$this->set('groups', $this->AnnotationGroup->aggregate_events());
	}
    
    protected function getTagsDetailById ($tagsDetail) {
        $tagsDetailById = array();
        foreach ($tagsDetail as $tagDetail) {
            $tagsDetailById[$tagDetail['TagDetail']['tag_detail_id']] = $tagDetail;
        }
        return $tagsDetailById;
    }
    
    protected function getTextTypesById ($textTypes) {
        $textTypesById = array();
        foreach ($textTypes as $textType) {
            $textTypesById[$textType['TextType']['text_type_id']] = $textType;
        }
        return $textTypesById;
    }
    
    protected function getTagsTypesById ($tagTypes) {
        $tagsTypesById = array();
        foreach ($tagTypes as $tagType) {
            $tagsTypesById[$tagType['TagType']['tag_type_id']] = $tagType;
        }
        return $tagsTypesById;
    }    
        
    public function aggregate() {
        $this->loadModel('Tag');
        $this->loadModel('TagDetail');
        $this->loadModel('TextType');
        $this->loadModel('TagType');
                
        $groupIds = explode(',', $this->params->named['x']);
        $tags = $this->Tag->find('all', array('contain' => array('TagDetail' ),
                                              'order' => 'tag.name'));
        $tagTypes = $this->TagType->find('all');
        $textTypes = $this->TextType->find('all');                                              
        $tagsById = $this->getTagsById ($tags);                         
        $orderedGroups = $this->orderGroupsByAnnotation($this->getEventGroups($groupIds), $tagsById);        
        $tagsDetail = $this->TagDetail->find('all'); 
        $tagsDetailById = $this->getTagsDetailById ($tagsDetail);
        $tagTypesById = $this->getTagsTypesById ($tagTypes);
        $textTypesById = $this->getTextTypesById ($textTypes);
        $this->set('tags', $tags);
        $this->set('orderedGroups', $orderedGroups);
        $this->set('tagsById', $tagsById);        
        $this->set('tagsDetailById', $tagsDetailById);
        $this->set('tagTypesById', $tagTypesById);
        $this->set('textTypesById', $textTypesById);
    }

    protected function getTagsById ($tags) {
        $tagsById = array();
        foreach ($tags as $tag) {
            $tagsById[$tag['Tag']['tag_id']] = $tag;
        }
        return $tagsById;
    }
    
    protected function orderGroupsByAnnotation($groups, &$tagsById){
        $orderedGroups = array();
        $data = '';
        $city = '';
        foreach($groups as $group){
            foreach($group['Annotation'] as $annotation){
                $tagName = $tagsById[$annotation['tag_id']]['Tag']['name'];
                if($tagName === 'Data'){
                    $data = $annotation['AnnotationDetail'][0]['value'];
                }
                else if($tagName === 'Cidade'){
                    $city = $annotation['AnnotationDetail'][0]['value'];
                }
                else {
                    $orderedGroups[$annotation['tag_id']][] = $annotation;
                }               
            }
        }
        
        return array('orderedGroups' => $orderedGroups,
                     'date' => $data,
                     'city' => $city);
    }
    
    protected function getEventGroups($ids) { 
        $this->loadModel('AnnotationGroup');
        $this->loadModel('Annotation');
        $this->loadModel('AnnotationDetail'); 
   
        return $this->AnnotationGroup->find('all', 
                 array('conditions' => array('annotation_group_id' => $ids),
                    'contain' => array('Annotation' => array('AnnotationDetail'))));
    }
}
