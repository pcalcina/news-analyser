<?php
App::uses('AppController', 'Controller');

class CommentsController extends AppController {

	public $components = array('Paginator');

	public function index() {
		$this->Comment->recursive = 0;
		$this->set('comments', $this->Paginator->paginate());
	}

	public function view($id = null) {
		if (!$this->Comment->exists($id)) {
			throw new NotFoundException(__('Invalid comment'));
		}
		$options = array('conditions' => array('Comment.' . $this->Comment->primaryKey => $id));
		$this->set('comment', $this->Comment->find('first', $options));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Comment->create();
			if ($this->Comment->save($this->request->data)) {
				$this->Session->setFlash(__('The comment has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The comment could not be saved. Please, try again.'));
			}
		}
	}

	public function edit($id = null) {
		if (!$this->Comment->exists($id)) {
			throw new NotFoundException(__('Invalid comment'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Comment->save($this->request->data)) {
				$this->Session->setFlash(__('The comment has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The comment could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Comment.' . $this->Comment->primaryKey => $id));
			$this->request->data = $this->Comment->find('first', $options);
		}
	}

	public function delete($id = null) {
		$this->Comment->id = $id;
		if (!$this->Comment->exists()) {
			throw new NotFoundException(__('Invalid comment'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Comment->delete()) {
			$this->Session->setFlash(__('The comment has been deleted.'));
		} else {
			$this->Session->setFlash(__('The comment could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
    
    public function saveAjax(){
        $this->layout = "ajax";
        $this->loadModel('User');
        
        $groupDate = array('AnnotationGroup' => array('creation' => date('Y-m-d H:i:s')));
        
        $this->Comment->create();
        $this->Comment->save(
            array('Comment' => 
                array('news_id'   => $this->request->data['news_id'],
                      'text'      => $this->request->data['comment'],
                      'parent_id' => NULL,
                      'user_id'   => $this->Session->read('Auth.User.id'),
                      'timestamp' => date('Y-m-d H:i:s')
                )
            )
        );
        
        $conditions = 
            array('conditions' => 
                array(
                    'Comment.news_id = ' => $this->request->data['news_id']
                )
            );
        $this->set('comments', $this->Comment->find('all', $conditions));
    }
    
}
