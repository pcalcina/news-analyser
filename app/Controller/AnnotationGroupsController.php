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
        
    public function aggregate() {
        $groupIds = explode(',', $this->params->named['x']);
        
        $this->set('annotationGroups', $this->getEventGroups($groupIds));
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
