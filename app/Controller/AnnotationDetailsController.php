<?php
App::uses('AppController', 'Controller');
/**
 * AnnotationDetails Controller
 *
 * @property AnnotationDetail $AnnotationDetail
 * @property PaginatorComponent $Paginator
 */
class AnnotationDetailsController extends AppController {

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
		$this->AnnotationDetail->recursive = 0;
		$this->set('annotationDetails', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->AnnotationDetail->exists($id)) {
			throw new NotFoundException(__('Invalid annotation detail'));
		}
		$options = array('conditions' => array('AnnotationDetail.' . $this->AnnotationDetail->primaryKey => $id));
		$this->set('annotationDetail', $this->AnnotationDetail->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AnnotationDetail->create();
			if ($this->AnnotationDetail->save($this->request->data)) {
				$this->Session->setFlash(__('The annotation detail has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The annotation detail could not be saved. Please, try again.'));
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
		if (!$this->AnnotationDetail->exists($id)) {
			throw new NotFoundException(__('Invalid annotation detail'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->AnnotationDetail->save($this->request->data)) {
				$this->Session->setFlash(__('The annotation detail has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The annotation detail could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('AnnotationDetail.' . $this->AnnotationDetail->primaryKey => $id));
			$this->request->data = $this->AnnotationDetail->find('first', $options);
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
		$this->AnnotationDetail->id = $id;
		if (!$this->AnnotationDetail->exists()) {
			throw new NotFoundException(__('Invalid annotation detail'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->AnnotationDetail->delete()) {
			$this->Session->setFlash(__('The annotation detail has been deleted.'));
		} else {
			$this->Session->setFlash(__('The annotation detail could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
