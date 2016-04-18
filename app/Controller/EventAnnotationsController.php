<?php
App::uses('AppController', 'Controller');
/**
 * EventAnnotations Controller
 *
 * @property EventAnnotation $EventAnnotation
 * @property PaginatorComponent $Paginator
 */
class EventAnnotationsController extends AppController {

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
		$this->EventAnnotation->recursive = 0;
		$this->set('eventAnnotations', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->EventAnnotation->exists($id)) {
			throw new NotFoundException(__('Invalid event annotation'));
		}
		$options = array('conditions' => array('EventAnnotation.' . $this->EventAnnotation->primaryKey => $id));
		$this->set('eventAnnotation', $this->EventAnnotation->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->EventAnnotation->create();
			if ($this->EventAnnotation->save($this->request->data)) {
				$this->Session->setFlash(__('The event annotation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event annotation could not be saved. Please, try again.'));
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
		if (!$this->EventAnnotation->exists($id)) {
			throw new NotFoundException(__('Invalid event annotation'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->EventAnnotation->save($this->request->data)) {
				$this->Session->setFlash(__('The event annotation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event annotation could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('EventAnnotation.' . $this->EventAnnotation->primaryKey => $id));
			$this->request->data = $this->EventAnnotation->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->EventAnnotation->id = $id;
		if (!$this->EventAnnotation->exists()) {
			throw new NotFoundException(__('Invalid event annotation'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->EventAnnotation->delete()) {
			$this->Session->setFlash(__('The event annotation has been deleted.'));
		} else {
			$this->Session->setFlash(__('The event annotation could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
