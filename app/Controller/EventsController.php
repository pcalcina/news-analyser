<?php
App::uses('AppController', 'Controller');
class EventsController extends AppController {

	public $components = array('Paginator');

	public function index() {
		$this->Event->recursive = 0;
		$this->set('events', $this->Paginator->paginate());
	}

	public function view($id = null) {
		if (!$this->Event->exists($id)) {
			throw new NotFoundException(__('Invalid event'));
		}
		$options = array('conditions' => array('Event.' . $this->Event->primaryKey => $id));
		$this->set('event', $this->Event->find('first', $options));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Event->create();
			if ($this->Event->save($this->request->data)) {
				$this->Session->setFlash(__('The event has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event could not be saved. Please, try again.'));
			}
		}
	}
 
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function edit($id = null) {
        $this->loadModel('Tag');
        $this->loadModel('TagDetail');
        $this->loadModel('TextType');
        $this->loadModel('TagType');
        $this->loadModel('AnnotationGroup');
        
        $eventId = $id;
        $groupIds = $this->AnnotationGroup->find('all', 
                 array('conditions' => array('AnnotationGroup.event_id' => $eventId), 
                     'contain' => array('Annotation' => array('AnnotationDetail')) ));  
        $tags = $this->Tag->find('all', array('contain' => array('TagDetail' ),
                                              'order' => 'tag.name')); 
        $tagsDetail = $this->TagDetail->find('all');  
        $tagsDetailById = $this->getTagsDetailById ($tagsDetail);
        $textTypes = $this->TextType->find('all');       
        $textTypesById = $this->getTextTypesById ($textTypes); 
        $tagTypes = $this->TagType->find('all');
        $tagTypesById = $this->getTagsTypesById ($tagTypes);
        $tagsById = $this->getTagsById ($tags);                    
        $orderedGroups = $this->orderGroupsByAnnotation($groupIds, $tagsById);
        $saved_event = $this->getEvent($eventId); 
        
        $this->set('eventId', $eventId);
        $this->set('groupIds', $groupIds); 
        $this->set('tags', $tags);
        $this->set('tagsDetailById', $tagsDetailById);
        $this->set('textTypesById', $textTypesById); 
        $this->set('tagTypesById', $tagTypesById);
        $this->set('orderedGroups', $orderedGroups);
        $this->set('tagsById', $tagsById);        
        $this->set('saved_event', $saved_event);
        $this->set('nameEvent', $saved_event[0]['Event']['name']);
        
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
    
    protected function getTagsById ($tags) {
        $tagsById = array();
        foreach ($tags as $tag) {
            $tagsById[$tag['Tag']['tag_id']] = $tag;
        }
        return $tagsById;
    }
    
     
    
    protected function getEventId ($groupIds)
    {
        $AnnotationGroups= $this->AnnotationGroup->find('all', array('conditions' => array('annotation_group_id' => $groupIds)));
        $EventsIds = array();
        $count = 0;
        foreach ($AnnotationGroups as $annotationGroup) {
            //$EventsIds[$annotationGroup['AnnotationGroup']['annotation_group_id']]=$annotationGroup['AnnotationGroup']['event_id']; 
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
   
    
     
    
    protected function getTagsDetailById ($tagsDetail) {
        $tagsDetailById = array();
        foreach ($tagsDetail as $tagDetail) {
            $tagsDetailById[$tagDetail['TagDetail']['tag_detail_id']] = $tagDetail;
        }
        return $tagsDetailById;
    }
    
    protected function getTagsTypesById ($tagTypes) {
        $tagsTypesById = array();
        foreach ($tagTypes as $tagType) {
            $tagsTypesById[$tagType['TagType']['tag_type_id']] = $tagType;
        }
        return $tagsTypesById;
    } 
    
    protected function getTextTypesById ($textTypes) {
        $textTypesById = array();
        foreach ($textTypes as $textType) {
            $textTypesById[$textType['TextType']['text_type_id']] = $textType;
        }
        return $textTypesById;
    }
    
    protected function getEvent($id) { 
        $this->loadModel('Event');
        $this->loadModel('EventAnnotation');
        $this->loadModel('EventAnnotationDetail'); 
   
        return $this->Event->find('all', 
                 array('conditions' => array('Event.event_id' => $id),                   
                    'contain' => array('EventAnnotation' => array('EventAnnotationDetail'))));
    }
    
    //Create event 
    public function createEventAjax(){  
        $this->layout = "ajax";  
        $this->loadModel('AnnotationGroup');
        $this->loadModel('Event');
         
        $this->Event->create(); 
        $EventInfo =  array('Event' =>  array( 'name' => $this->request->data['name']));
        $this->Event->save($EventInfo);
        $eventId = $this->Event->id; 
        foreach($this->request->data['groupIds'] as $groupId){ 
            $annotationGroupInfo = array('AnnotationGroup' => array('annotation_group_id' => $groupId, 'event_id' => $eventId ));
            $this->AnnotationGroup->save($annotationGroupInfo);
        } 
        /*$link = Router::connect(
            'URL',
            array('controller' => 'events', 'action' => 'index')
        ); */ 
        
        $link = Router::url(array(
            'controller' => 'events',
            'action' => 'edit',  
            $eventId
        ));
        
        //debug($link);
        $this->set('link', $link);   
    }
    
 
    public function saveAjax(){ 
        $this->layout = "ajax"; 
        $this->loadModel('AnnotationGroup');
        $this->loadModel('Event');
        $this->loadModel('EventAnnotation');
        $this->loadModel('EventAnnotationDetail'); 
        
        
        if(!empty($this->request->data['event'])){
            foreach($this->request->data['event'] as $group){
                //if(empty($group['event_id'])){ }
                $eventId = $group['event_id']; 
                $EventInfo =  array('Event' =>  array('event_id' =>$eventId,'name' => $group['name']));
                $this->Event->save($EventInfo);
                
                if(!empty($group['eventAnnotations'])){
                    foreach($group['eventAnnotations'] as $eventannotation){
                        $eventannotation['event_id'] = $eventId;  
                        if(empty($eventannotation['event_annotation_id'])){
                            unset($eventannotation['event_annotation_id']);
                            $this->EventAnnotation->create();
                            $this->EventAnnotation->save(array('EventAnnotation' => $eventannotation));
                            $eventannotationId = $this->EventAnnotation->id;
                        }
                        else {
                            $this->EventAnnotation->save(array('EventAnnotation' => $eventannotation));
                            $eventannotationId = $eventannotation['event_annotation_id'];
                        } 
                        if(!empty($eventannotation['eventAnnotationsDetail'])){
                            foreach($eventannotation['eventAnnotationsDetail'] as $eventAnnotationDetail){ 
                                $eventAnnotationDetail['event_annotation_id'] = $eventannotationId; 
                                if(empty($eventAnnotationDetail['event_annotation_detail_id'])){
                                    unset($eventAnnotationDetail['event_annotation_detail_id']);
                                    $this->EventAnnotationDetail->create(); 
                                } 
                                $this->EventAnnotationDetail->save(array('EventAnnotationDetail' => $eventAnnotationDetail));  
                            } 
                        }  
                    }
                } 
            }
        } 
        $conditions = array('conditions' => array('Event.event_id' => $eventId),                   
                'contain' => array('EventAnnotation' => array('EventAnnotationDetail'))); 
        $events = $this->Event->find('all', $conditions); 
        $this->set('event', $events);  
    }
    
    public function export_to_csv() {
        //$this->header(“Content-Type: application/vnd.ms-excel; charset=UTF-8″); 
        $this->response->download("eventos.csv");
        $this->layout = "ajax"; 
        $this->loadModel('TagDetail');
        
        $tagsDetailById = $this->TagDetail->getTagsDetailById(); 
        $events = $this->Event->exportAsTable();  
        
        $maxElementsTags = array();
        $ElementsEventTag = array();
        foreach($events as $eventId => $event){ 
            $ElementsEventTag[$eventId] = array(); 
            $ElementsEventTag[$eventId]['TagsElements'] = array();
            foreach($event as $tagId => $tag){
                $ElementsEventTag[$eventId]['TagsElements'][$tagId ] = count($tag); 
            } 
            $maxElementsTags[$eventId]=max($ElementsEventTag[$eventId]['TagsElements']);
        } 
        $this->set('events', $events);
        $this->set('tagsDetailById', $tagsDetailById); 
        $this->set('maxElementsTags', $maxElementsTags);
    }    
      
	public function delete($id = nul) {
            $this->Session->setFlash(__('A eliminação de eventos está suspensa por enquanto. Será implementada numa versão futura. Desculpe os trastornos'));
        }
}        
