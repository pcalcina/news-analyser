<?php
App::uses('AppController', 'Controller');
/**
 * Annotations Controller
 *
 * @property Annotation $Annotation
 * @property PaginatorComponent $Paginator
 */
class AnnotationsController extends AppController {
 
	public $components = array('Paginator');
 
	public function index() {
		$this->Annotation->recursive = 0;
		$this->set('annotations', $this->Paginator->paginate());
	}
 
	public function view($id = null) {
		if (!$this->Annotation->exists($id)) {
			throw new NotFoundException(__('Invalid annotation'));
		}
		$options = array('conditions' => array('Annotation.' . $this->Annotation->primaryKey => $id));
		$this->set('annotation', $this->Annotation->find('first', $options));
	}
 
	public function add() {
		if ($this->request->is('post')) {
			$this->Annotation->create();
			if ($this->Annotation->save($this->request->data)) {
				$this->Session->setFlash(__('The annotation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The annotation could not be saved. Please, try again.'));
			}
		}
	}
 
	public function edit($id = null) {
		if (!$this->Annotation->exists($id)) {
			throw new NotFoundException(__('Invalid annotation'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Annotation->save($this->request->data)) {
				$this->Session->setFlash(__('The annotation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The annotation could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Annotation.' . $this->Annotation->primaryKey => $id));
			$this->request->data = $this->Annotation->find('first', $options);
		}
	}
        public function news($newsId = null){
		$this->set('annotations', $this->loadAnnotationsForNews($newsId));
	}
	
	protected function loadAnnotationsForNews($newsId){
		$options = array('conditions' => array('Annotation.news_id = ' => $newsId));
		return $this->Annotation->find('all', $options);
	}
	
	public function saveAjax(){
            $this->layout = "ajax";
            $this->loadModel('News');
            $this->loadModel('AnnotationGroup');
            $this->loadModel('Annotation');
            $this->loadModel('AnnotationDetail'); 
 
            $this->News->save(array('News' => 
            array('news_id'   => $this->request->data['news_id'],
                  'highlights' => $this->request->data['highlights'],
                  'user_id'    => $this->Session->read('Auth.User.id'))));
        
            if(!empty($this->request->data['groups'])){
                
                foreach($this->request->data['groups'] as $group){
                    
		    if(empty($group['group_id'])){
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
                    }
                }
            }
 
            ///Acomodar esto    
            $conditions = array('conditions' => array('AnnotationGroup.news_id' => $this->request->data['news_id']),                   
                    'contain' => array('Annotation' => array('AnnotationDetail')));
            $annotationGroups = $this->AnnotationGroup->find('all', $conditions);
            $this->set('eventGroups', $annotationGroups); 
            
	}
	
	public function deleteAjax(){
		$this->layout = "ajax";
		$annotationId = $this->request->data['id'];
		$this->Annotation->id = $this->request->data['id'];
                $this->request->onlyAllow('post', 'delete');
                
		if ($this->Annotation->exists()) { 
                     
                    $this->loadModel('AnnotationDetail');
                    $this->AnnotationDetail->deleteAll(array('AnnotationDetail.annotation_id' => $annotationId)); 
		    $this->Annotation->delete(); 
                }
	}
        
	public function delete($id = null) {
		$this->Annotation->id = $id;
		if (!$this->Annotation->exists()) {
			throw new NotFoundException(__('Invalid annotation'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Annotation->delete()) {
			$this->Session->setFlash(__('The annotation has been deleted.'));
		} else {
			$this->Session->setFlash(__('The annotation could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
        
}
