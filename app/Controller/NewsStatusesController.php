<?php
App::uses('AppController', 'Controller');
/**
 * NewsStatuses Controller
 *
 * @property NewsStatus $NewsStatus
 * @property PaginatorComponent $Paginator
 */
class NewsStatusesController extends AppController {

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
		$this->NewsStatus->recursive = 0;
		$this->set('newsStatuses', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->NewsStatus->exists($id)) {
			throw new NotFoundException(__('Invalid news status'));
		}
		$options = array('conditions' => array('NewsStatus.' . $this->NewsStatus->primaryKey => $id));
		$this->set('newsStatus', $this->NewsStatus->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->NewsStatus->create();
			if ($this->NewsStatus->save($this->request->data)) {
				$this->Session->setFlash(__('The news status has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The news status could not be saved. Please, try again.'));
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
		if (!$this->NewsStatus->exists($id)) {
			throw new NotFoundException(__('Invalid news status'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->NewsStatus->save($this->request->data)) {
				$this->Session->setFlash(__('The news status has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The news status could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('NewsStatus.' . $this->NewsStatus->primaryKey => $id));
			$this->request->data = $this->NewsStatus->find('first', $options);
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
		$this->NewsStatus->id = $id;
		if (!$this->NewsStatus->exists()) {
			throw new NotFoundException(__('Invalid news status'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->NewsStatus->delete()) {
			$this->Session->setFlash(__('The news status has been deleted.'));
		} else {
			$this->Session->setFlash(__('The news status could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
