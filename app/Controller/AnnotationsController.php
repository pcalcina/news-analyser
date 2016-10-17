<?php
App::uses('AppController', 'Controller');
/**
 * Annotations Controller
 *
 * @property Annotation $Annotation
 * @property PaginatorComponent $Paginator
 */
class AnnotationsController extends AppController {
 
    public function index() {
        $this->loadModel('Tag');
        $this->loadModel('TagDetail');
        $this->Annotation->recursive = 0;
        $tags = $this->Tag->find('list', array('fields'=>array('tag_id','name')));
        $tagDetailsRaw = $this->TagDetail->find('all');
        $tagDetails = array();
        
        foreach($tagDetailsRaw as $tagDetail){
            $tagId = $tagDetail['TagDetail']['tag_id'];
            $name = $tags[$tagId];
            $title = $tagDetail['TagDetail']['title'];
            if(!empty($title)){
                $name .= " ($title)";
            }
            $tagDetails[$tagDetail['TagDetail']['tag_detail_id']] = $name;
        }
        $this->set('tag_details', $tagDetails);
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
    
    public function filterAjax() {
        $this->layout = "ajax";
        $this->loadModel('AnnotationDetail');
        $this->request->onlyAllow('get');
        $results = array();
        $tagDetailId = $this->params['url']['tagDetailId'];
        $showReviewed = $this->params['url']['showReviewed'] === 'true'? 1 : 0;        
        
        $resultsRaw = $this->AnnotationDetail->find('all', 
            array('conditions' => array('AnnotationDetail.tag_detail_id' => $tagDetailId, 
                                        'reviewed' => array(0, $showReviewed))));
        foreach($resultsRaw as $r){                    
            $url = Router::url([
                'controller' => 'News',
                'action' => 'annotate',
                $r['Annotation']['news_id']
            ]);
            $results[] = array($r['AnnotationDetail']['annotation_detail_id'],
                               $url,
                               $r['AnnotationDetail']['reviewed'],
                               $r['AnnotationDetail']['value']);
        }
        $this->set('results', $results);
	}
    
    public function replaceAjax(){
        $this->layout = "ajax";
        $this->loadModel('AnnotationDetail');
        $annotationDetails = $this->request->data['annotationDetails'];
        $replaceText = $this->request->data['replaceText'];
        $this->AnnotationDetail->updateAll(
            array('value' => "'{$replaceText}'", 'reviewed' => 1),
            array('annotation_detail_id' => $annotationDetails)
        );
        $this->set('response', array('success' => 'true'));
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
