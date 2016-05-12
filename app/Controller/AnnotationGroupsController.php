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
        
    public function possible_groups() {
        $this->AnnotationGroup->recursive = 0;   
        //debug($this->AnnotationGroup->grouping_groups());
        $this->set('groups', $this->AnnotationGroup->grouping_groups());
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
    
    protected function getEvent($id) { 
        if(empty($id)) {
             $event = array();
        }
        else {
            $this->loadModel('Event');
            $this->loadModel('EventAnnotation');
            $this->loadModel('EventAnnotationDetail'); 
   
            $event = $this->Event->find('all', array('conditions' => array('Event.event_id' => $id),
                    'contain' => array('EventAnnotation' => array('EventAnnotationDetail'))));
        }
        return $event;
    }    
    
    public function generate_event() {
        $this->loadModel('Tag');
        $this->loadModel('TagDetail'); 
        $this->loadModel('TextType');
        $this->loadModel('TagType');
        $this->loadModel('AnnotationGroup');
        
        $groupIds = explode(',', $this->params->named['x']); 
        $tags = $this->Tag->find('all', array('contain' => array('TagDetail' ),
                                              'order' => 'tag.name'));
        $groups = $this->getOrderedGroups($groupIds);
        foreach ($groups[0]['Annotation'] as $annotation) {
             if($annotation['tag_id']==2)
            {
                $city = $annotation['AnnotationDetail'][0]['value']; 
            }
            if($annotation['tag_id']==4)
            {
                $date = $annotation['AnnotationDetail'][0]['value']; 
            }
        }
        
        $this->set('groupIds', $groupIds);
        $this->set('groups', $groups); 
        $this->set('tagsDetailById', $this->getTagsDetailById ($this->TagDetail->find('all'))); 
        $this->set('tags', $this->getTagsById ($tags));
        $this->set('city', $city);
        $this->set('date', $date);
        
    }
    
    public function aggregate() {
        $this->loadModel('Tag');
        $this->loadModel('TagDetail');
        $this->loadModel('TextType');
        $this->loadModel('TagType');
        $this->loadModel('AnnotationGroup');
                
        $groupIds = explode(',', $this->params->named['x']); 
        $tags = $this->Tag->find('all', array('contain' => array('TagDetail' ),
                                              'order' => 'tag.name'));
 
        $eventId = $this->getEventId ($groupIds);
        
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
        $this->set('groupIds', $groupIds);
        $this->set('eventId', $eventId);
        $this->set('saved_event', $this->getEvent($eventId)); 
        $buff = array();  
    }
    
    protected function getEventId ($groupIds)
    {
        $AnnotationGroups= $this->AnnotationGroup->find('all', array('conditions' => array('annotation_group_id' => $groupIds)));
        $EventsIds = array();
        $count = 0;
        foreach ($AnnotationGroups as $annotationGroup) {
            $EventsIds[$count]=$annotationGroup['AnnotationGroup']['event_id']; 
            $count =$count+1;
        }  
        foreach ($EventsIds as $EventsId) {
            if($EventsId!=$EventsIds[0])
            {
                return null;
            }
        }
        return $EventsIds[0];
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
    
    protected function getOrderedGroups($ids) { 
        $this->loadModel('AnnotationGroup');
        $this->loadModel('Annotation');
        $this->loadModel('AnnotationDetail'); 
   
        return $this->AnnotationGroup->find('all', 
                 array('conditions' => array('annotation_group_id' => $ids),
                    'contain' => array('Annotation' => array('AnnotationDetail', 'order' => 'Annotation.tag_id ASC'))));
    }
}
