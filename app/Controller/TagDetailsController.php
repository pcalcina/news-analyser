<?php

App::uses('AppController', 'Controller');

/**
 * TagDetails Controller
 *
 * @property TagDetail $TagDetail
 * @property PaginatorComponent $Paginator
 */
class TagDetailsController extends AppController {

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
        $this->TagDetail->recursive = 0;
        $this->set('tagDetails', $this->Paginator->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->TagDetail->exists($id)) {
            throw new NotFoundException(__('Invalid tag detail'));
        }
        $options = array('conditions' => array('TagDetail.' . $this->TagDetail->primaryKey => $id));
        $this->set('tagDetail', $this->TagDetail->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->TagDetail->create();
            if ($this->TagDetail->save($this->request->data)) {
                return $this->flash(__('The tag detail has been saved.'), array('action' => 'index'));
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
        if (!$this->TagDetail->exists($id)) {
            throw new NotFoundException(__('Invalid tag detail'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->TagDetail->save($this->request->data)) {
                return $this->flash(__('The tag detail has been saved.'), array('action' => 'index'));
            }
        } else {
            $options = array('conditions' => array('TagDetail.' . $this->TagDetail->primaryKey => $id));
            $this->request->data = $this->TagDetail->find('first', $options);
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
        $this->TagDetail->id = $id;
        if (!$this->TagDetail->exists()) {
            throw new NotFoundException(__('Invalid tag detail'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->TagDetail->delete()) {
            return $this->flash(__('The tag detail has been deleted.'), array('action' => 'index'));
        } else {
            return $this->flash(__('The tag detail could not be deleted. Please, try again.'), array('action' => 'index'));
        }
    }

}
