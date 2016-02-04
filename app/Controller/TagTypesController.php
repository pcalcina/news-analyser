<?php
App::uses('AppController', 'Controller');
/**
 * TagTypes Controller
 *
 * @property TagType $TagType
 * @property PaginatorComponent $Paginator
 */
class TagTypesController extends AppController {

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
		$this->TagType->recursive = 0;
		$this->set('tagTypes', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->TagType->exists($id)) {
			throw new NotFoundException(__('Invalid tag type'));
		}
		$options = array('conditions' => array('TagType.' . $this->TagType->primaryKey => $id));
		$this->set('tagType', $this->TagType->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->TagType->create();
			if ($this->TagType->save($this->request->data)) {
				$this->Session->setFlash(__('The tag type has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tag type could not be saved. Please, try again.'));
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
		if (!$this->TagType->exists($id)) {
			throw new NotFoundException(__('Invalid tag type'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->TagType->save($this->request->data)) {
				$this->Session->setFlash(__('The tag type has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tag type could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('TagType.' . $this->TagType->primaryKey => $id));
			$this->request->data = $this->TagType->find('first', $options);
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
		$this->TagType->id = $id;
		if (!$this->TagType->exists()) {
			throw new NotFoundException(__('Invalid tag type'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->TagType->delete()) {
			$this->Session->setFlash(__('The tag type has been deleted.'));
		} else {
			$this->Session->setFlash(__('The tag type could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
