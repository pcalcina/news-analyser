<?php
App::uses('AppController', 'Controller');
/**
 * TextTypes Controller
 *
 * @property TextType $TextType
 * @property PaginatorComponent $Paginator
 */
class TextTypesController extends AppController {

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
		$this->TextType->recursive = 0;
		$this->set('textTypes', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->TextType->exists($id)) {
			throw new NotFoundException(__('Invalid text type'));
		}
		$options = array('conditions' => array('TextType.' . $this->TextType->primaryKey => $id));
		$this->set('textType', $this->TextType->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->TextType->create();
			if ($this->TextType->save($this->request->data)) {
				$this->Session->setFlash(__('The text type has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The text type could not be saved. Please, try again.'));
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
		if (!$this->TextType->exists($id)) {
			throw new NotFoundException(__('Invalid text type'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->TextType->save($this->request->data)) {
				$this->Session->setFlash(__('The text type has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The text type could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('TextType.' . $this->TextType->primaryKey => $id));
			$this->request->data = $this->TextType->find('first', $options);
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
		$this->TextType->id = $id;
		if (!$this->TextType->exists()) {
			throw new NotFoundException(__('Invalid text type'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->TextType->delete()) {
			$this->Session->setFlash(__('The text type has been deleted.'));
		} else {
			$this->Session->setFlash(__('The text type could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
