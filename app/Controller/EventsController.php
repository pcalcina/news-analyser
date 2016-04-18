<?php
App::uses('AppController', 'Controller');
/**
 * Events Controller
 *
 * @property Event $Event
 * @property PaginatorComponent $Paginator
 */
class EventsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Event->recursive = 0;
		$this->set('events', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Event->exists($id)) {
			throw new NotFoundException(__('Invalid event'));
		}
		$options = array('conditions' => array('Event.' . $this->Event->primaryKey => $id));
		$this->set('event', $this->Event->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
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

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */ 
        
        
	public function saveAjax(){
            debug("porque");
            $this->layout = "ajax"; 
            $this->loadModel('Event');
            $this->loadModel('EventAnnotation');
            $this->loadModel('EventAnnotationDetail'); 
  
        
            if(!empty($this->request->data['event'])){
                
                foreach($this->request->data['event'] as $group){
                    //debug($group['event_id']);
		    /*if(empty($group['event_id'])){
                            $groupDate = array('AnnotationGroup' => array('creation' => date('Y-m-d H:i:s')));
		            $this->AnnotationGroup->create();
		            if($this->AnnotationGroup->save($groupDate)){
           		        $groupId = $this->AnnotationGroup->id;
		            }
		    }
		    else{
		            $groupId = $group['group_id'];
		    }
		        
		    $annotationGroupInfo = 
		            array('AnnotationGroup' => 
       		                array('annotation_group_id' => $groupId,
       		                      'news_id' => $this->request->data['news_id']));		        
		        
                    if(!empty($group['event_id'])){
                        $annotationGroupInfo['AnnotationGroup']['event_id'] = $group['event_id'];
                    }
	            
   		    $this->AnnotationGroup->save($annotationGroupInfo);
		        
		    if(!empty($group['annotations'])){
		        foreach($group['annotations'] as $annotation){
                            
		            $annotation['annotation_group_id'] = $groupId; 
			    if(empty($annotation['annotation_id'])){
                                unset($annotation['annotation_id']);
                                $this->Annotation->create();
                                $this->Annotation->save(array('Annotation' => $annotation));
                                $annotationId = $this->Annotation->id;
                            }
                            else
                            {
                                $this->Annotation->save(array('Annotation' => $annotation));
                                $annotationId = $annotation['annotation_id'];
                            }
                            
                            
                            if(!empty($annotation['annotationsDetail'])){
                                foreach($annotation['annotationsDetail'] as $annotationDetail){ 
                                    
                                    $annotationDetail['annotation_id'] = $annotationId; 
                                    if(empty($annotationDetail['annotation_detail_id'])){
                                         unset($annotationDetail['annotation_detail_id']);
                                         $this->AnnotationDetail->create(); 
                                    } 
                                    $this->AnnotationDetail->save(array('AnnotationDetail' => $annotationDetail));       
                                } 
                            }
                        }
                    }*/
                }
            }
 
            ///Acomodar esto    
            /*$conditions = array('conditions' => array('AnnotationGroup.news_id' => $this->request->data['news_id']),                   
                    'contain' => array('Annotation' => array('AnnotationDetail')));
            $annotationGroups = $this->AnnotationGroup->find('all', $conditions);
            $this->set('eventGroups', $annotationGroups); */
            
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
	}}
