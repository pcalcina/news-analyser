<?php
App::uses('AppController', 'Controller');
/**
 * EventAnnotationDetails Controller
 *
 * @property EventAnnotationDetail $EventAnnotationDetail
 * @property PaginatorComponent $Paginator
 */
class EventAnnotationDetailsController extends AppController {

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
		$this->EventAnnotationDetail->recursive = 0;
		$this->set('eventAnnotationDetails', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->EventAnnotationDetail->exists($id)) {
			throw new NotFoundException(__('Invalid event annotation detail'));
		}
		$options = array('conditions' => array('EventAnnotationDetail.' . $this->EventAnnotationDetail->primaryKey => $id));
		$this->set('eventAnnotationDetail', $this->EventAnnotationDetail->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->EventAnnotationDetail->create();
			if ($this->EventAnnotationDetail->save($this->request->data)) {
				$this->Session->setFlash(__('The event annotation detail has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event annotation detail could not be saved. Please, try again.'));
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
		if (!$this->EventAnnotationDetail->exists($id)) {
			throw new NotFoundException(__('Invalid event annotation detail'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->EventAnnotationDetail->save($this->request->data)) {
				$this->Session->setFlash(__('The event annotation detail has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event annotation detail could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('EventAnnotationDetail.' . $this->EventAnnotationDetail->primaryKey => $id));
			$this->request->data = $this->EventAnnotationDetail->find('first', $options);
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
		$this->EventAnnotationDetail->id = $id;
		if (!$this->EventAnnotationDetail->exists()) {
			throw new NotFoundException(__('Invalid event annotation detail'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->EventAnnotationDetail->delete()) {
			$this->Session->setFlash(__('The event annotation detail has been deleted.'));
		} else {
			$this->Session->setFlash(__('The event annotation detail could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
