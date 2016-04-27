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

	public function edit($id = null) {
		if (!$this->Event->exists($id)) {
			throw new NotFoundException(__('Invalid event'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Event->save($this->request->data)) {
				$this->Session->setFlash(__('The event has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Event.' . $this->Event->primaryKey => $id));
			$this->request->data = $this->Event->find('first', $options);
		}
	}

    public function saveAjax(){ 
        $this->layout = "ajax"; 
        $this->loadModel('AnnotationGroup');
        $this->loadModel('Event');
        $this->loadModel('EventAnnotation');
        $this->loadModel('EventAnnotationDetail'); 
        
         
        if(!empty($this->request->data['event'])){
            foreach($this->request->data['event'] as $group){
                if(empty($group['event_id'])){
                    debug("nuevo evento");
                    unset($group['event_id']); 
                    $this->Event->create(); 
                    $EventInfo =  array('Event' =>  array( 'name' => $group['name']));	 
                    $this->Event->save($EventInfo);
                    $eventId = $this->Event->id;
                }
                else{
                    debug("antiguo evento");
                    $eventId = $group['event_id'];
                } 
                         
                if(!empty($this->request->data['groupsIds'])){
                    foreach($this->request->data['groupsIds'] as $groupId){ 
                        $annotationGroupInfo = array('AnnotationGroup' => array('annotation_group_id' => $groupId, 'event_id' => $eventId ));
                        $this->AnnotationGroup->save($annotationGroupInfo);
                    }
                } 
                    
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
        //$this->response->download("eventos.csv");
        $this->layout = "ajax"; 
        $this->loadModel('TagDetail');
        $tagsDetailById = $this->TagDetail->getTagsDetailById();        
        $this->set('tagsDetailById', $tagsDetailById);
        $events = $this->Event->exportAsTable();
        $this->set('events', $events);
        $maxElements = array();
        foreach($events as $eventId => $event){
            $maxElements[$eventId] = count($event);
        }
        $this->set('maxElements', $maxElements);
    }    
      
	public function delete($id = null) {
		$this->Event->id = $id;
		if (!$this->Event->exists()) {
			throw new NotFoundException(__('Invalid event'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Event->delete()) {
			$this->Session->setFlash(__('The event has been deleted.'));
		} else {
			$this->Session->setFlash(__('The event could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
