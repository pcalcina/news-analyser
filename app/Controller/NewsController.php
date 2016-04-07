<?php

App::uses('AppController', 'Controller');

define("STATUS_SEM_REVISAO", "1");
define("STATUS_SEM_CODIFICAR", "2");
define("STATUS_CODIFICADA", "3");
define("STATUS_COM_EVENTO", "4");
define("STATUS_ELIMINADA", "5");

function to_utf8($string) {
// From http://w3.org/International/questions/qa-forms-utf-8.html
    if (preg_match('%^(?:
      [\x09\x0A\x0D\x20-\x7E]            # ASCII
    | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
    | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
    | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
    | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
    | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
    | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
    | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
)*$%xs', $string)) {
        return $string;
    } else {
        return iconv('CP1252', 'UTF-8', $string);
    }
}

function url_to_utf8($url) {
    return to_utf8(rawurldecode($url));
}

class NewsController extends AppController {

    public $components = array('Paginator');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    private function load_statuses($labelForNull) {
        $this->loadModel('NewsStatus');
        $raw_statuses = $this->NewsStatus->find('all', array('conditions' => array('NewsStatus.id !=' => STATUS_ELIMINADA)));
        $statuses = array(null => $labelForNull);

        foreach ($raw_statuses as $status) {
            $statuses[$status['NewsStatus']['id']] = $status['NewsStatus']['description'];
        }

        return $statuses;
    }

    private function load_sources() {
        $this->loadModel('Source');
        $raw_sources = $this->Source->find('all');
        $sources = array(null => 'Todos');

        foreach ($raw_sources as $source) {
            $sources[$source['Source']['source_id']] = $source['Source']['name'];
        }

        return $sources;
    }

    private function load_all_keywords() {
        return array(
            'aborto',
            'baderneiros',
            'depreda%E7%E3o',
            'greve',
            'manifesta%E7%E3o',
            'paralisa%E7%E3o',
            'pro-choice',
            'pro-escolha',
            'pro-vida',
            'protesto',
            'reivindica%E7%E3o',
            'vandalismo');
    }

    private function load_all_keywords_decoded() {
        return array_map('url_to_utf8', $this->load_all_keywords());
    }

    public function basic_index() {
        $this->News->recursive = 0;
        $this->set('statuses', $this->load_statuses('Todos'));
        $this->set('sources', $this->load_sources());
        $this->set('keywords', $this->load_all_keywords_decoded());
    }

    public function index() {
        $this->basic_index();
        $this->set('news_list', $this->Paginator->paginate());
        $this->set('filters', array(
            'status' => '',
            'source' => '',
            'keywords' => '',
            'start_date' => date('2013-01-01'),
            'end_date' => date('2014-12-01')
                )
        );
        $this->set('show_filter', false);
    }

    public function view($id = null) {
        if (!$this->News->exists($id)) {
            throw new NotFoundException(__('Invalid news'));
        }
        $options = array('conditions' =>
            array('News.' . $this->News->primaryKey => $id));
        $this->set('news', $this->News->find('first', $options));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->News->create();
            if ($this->News->save($this->request->data)) {
                $this->Session->setFlash(__('The news has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(
                        __('The news could not be saved. Please, try again.'));
            }
        }
    }

    public function filter() {
        $this->basic_index();
        $this->set('show_filter', true);

        $this->News->recursive = 0;

        $this->Paginator->settings = array('order' => array('News.date' => 'desc'));

        $conditions = array('News.news_status_id != ' => STATUS_ELIMINADA);

        if (!empty($this->request->query['status'])) {
            $conditions['News.news_status_id ='] = $this->request->query['status'];
        }

        if (!empty($this->request->query['keywords'])) {
            $all_keywords = $this->load_all_keywords();
            $c = array();

            foreach ($this->request->query['keywords'] as $k) {
                //FIXME: Suboptimal solution, add FTS (Full text search)
                //       Normalize this table also
                $conditions['AND'][] = array(
                    'OR' => array(
                        array('News.keywords LIKE' => "%,{$all_keywords[$k]},%"),
                        array('News.keywords LIKE' => "%{$all_keywords[$k]},%"),
                        array('News.keywords LIKE' => "%,{$all_keywords[$k]}%"),
                        array('News.keywords LIKE' => "%{$all_keywords[$k]}%")));
            }
        }

        if (!empty($this->request->query['source'])) {
            $conditions['News.source_id ='] = $this->request->query['source'];
        }

        if (isset($this->request->query['start_date'])) {
            $conditions['News.date >='] = $this->request->query['start_date'];
        }

        if (isset($this->request->query['end_date'])) {
            $conditions['News.date <='] = $this->request->query['end_date'];
        }

        try {
            $news_list = $this->Paginator->paginate($conditions);
            $this->set('news_list', $news_list);
        } catch (Exception $exception) {
            unset($this->request->params['named']['page']);
            $this->set('news_list', $this->Paginator->paginate($conditions));
        }

        if (!empty($this->request->query)) {
            $this->set('filters', $this->request->query);
        }
    }

    public function edit($id = null) {
        if (!$this->News->exists($id)) {
            throw new NotFoundException(__('Invalid news'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->News->save($this->request->data)) {
                $this->Session->setFlash(__('The news has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(
                        __('The news could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' =>
                array('News.' . $this->News->primaryKey => $id));
            $this->request->data = $this->News->find('first', $options);
        }
    }


    protected function getTagsById ($tags) {
        $tagsById = array();
        foreach ($tags as $tag) {
            $tagsById[$tag['Tag']['tag_id']] = $tag;
        }
        return $tagsById;
    }
    
    protected function getTagsDetailById ($tagsDetail) {
        $tagsDetailById = array();
        foreach ($tagsDetail as $tagDetail) {
            $tagsDetailById[$tagDetail['TagDetail']['tag_detail_id']] = $tagDetail;
        }
        return $tagsDetailById;
    }
    
    protected function getTagsTypesById ($tagTypes) {
        $tagsTypesById = array();
        foreach ($tagTypes as $tagType) {
            $tagsTypesById[$tagType['TagType']['tag_type_id']] = $tagType;
        }
        return $tagsTypesById;
    }
     
    protected function getTextTypesById ($textTypes) {
        $textTypesById = array();
        foreach ($textTypes as $textType) {
            $textTypesById[$textType['TextType']['text_type_id']] = $textType;
        }
        return $textTypesById;
    }
    
    protected function getEventGroups($id) { 
        $this->loadModel('AnnotationGroup');
        $this->loadModel('Annotation');
        $this->loadModel('AnnotationDetail'); 
   
        return $this->AnnotationGroup->find('all', 
                 array('conditions' => array('AnnotationGroup.news_id' => $id),                   
                    'contain' => array('Annotation' => array('AnnotationDetail'))));
    }

    public function annotate($id = null) {
        if (!$this->News->exists($id)) {
            throw new NotFoundException(__('Invalid news'));
        }
        if ($this->request->is(array('post', 'put'))) {
            
        } else {
            $this->loadModel('Tag');
            
            $this->loadModel('Event');
            $this->loadModel('Comment');
            $this->loadModel('TagType');
            $this->loadModel('TextType');
            $this->loadModel('TagDetail'); 
            
            //$annotations = array('conditions' => array('AnnotationDetail.news_id' => $id));
            $optionsNews = array('conditions' => array('News.' . $this->News->primaryKey => $id));
            $events = $this->Event->find('all', array('order' => 'event.name'));

            //$tags = $this->Tag->find('all', array('order' => 'tag.name'));
    
   
            $tags = $this->Tag->find('all', 
                 array('contain' => array('TagDetail' ),'order' => 'tag.name'));
            
            //$prueba = $this->AnnotationDetail->find('all',array('groupBy' => 'AnnotationDetail.tag_id'));
           
                    
            $commentsConditions = array('conditions' => array('Comment.news_id = ' => $id));
       
            $tagTypes = $this->TagType->find('all');
            $textTypes = $this->TextType->find('all');
            $tagsDetail = $this->TagDetail->find('all'); 
            
            $tagsById = $this->getTagsById ($tags);
            $tagsDetailById = $this->getTagsDetailById ($tagsDetail);
            $tagTypesById = $this->getTagsTypesById ($tagTypes);
            $textTypesById = $this->getTextTypesById ($textTypes);
              
            //$this->set('annotations', $annotations);
            $this->set('tags', $tags);
            $this->set('events', $events);
            //$this->set('tag_types', $this->getTagTypes($tags));
            $news = $this->News->find('first', $optionsNews);
            $this->set('news', $news);
            $this->set('statuses', $this->load_statuses('-'));
            $this->set('saved_event_groups', $this->getEventGroups($id));
            $this->set('comments', $this->Comment->find('all', $commentsConditions));
            //$this->set('actors', $this->load_actors());
            //$this->set('cities', $this->load_cities());
            $this->set('tagsById', $tagsById);
            $this->set('tagTypes', $tagTypes);
            $this->set('tagTypesById', $tagTypesById);
            $this->set('textTypesById', $textTypesById);
            $this->set('tagsDetailById', $tagsDetailById);
 
            
            //debug("........................") ;
            //debug($this->getEventGroups($id)) ;
            //debug("........................") ;
            //debug($prueba);
            //debug("------------------------") ;
            //debug($tagTypesById);
            //debug("........................") ;
            //debug($textTypesById);
            //debug("-------------------------") ;
            //debug($annotattes);
            //debug($tagsDetailById);
        }
    }

    public function delete($id = null) {
        $this->News->id = $id;
        if (!$this->News->exists()) {
            throw new NotFoundException(__('Invalid news'));
        }
        $this->request->onlyAllow('post', 'delete');

        $changeStatus = array('news_status_id' => STATUS_ELIMINADA, 'news_id' => $id);

        if ($this->News->save($changeStatus)) {
            //$this->Session->setFlash(__('Notícia marcada como eliminada.'));
        }

        return $this->redirect($this->referer());
    }

    public function change_status() {
        $this->layout = "ajax";
        $success = false;

        if ($this->News->save($this->request->data)) {
            $success = true;
        }

        $options = array('conditions' =>
            array('News.' . $this->News->primaryKey => $this->News->id));
        $news = $this->News->find('first', $options);

        $this->set('response', array('success' => $success,
            'news_status' => $news['NewsStatus']));
    }

    /*private function load_actors() {
        $this->loadModel('Annotation');
        $actors = array();
        $raw_annotation = $this->Annotation->find('all', array('conditions' => array('Annotation.tag_id ==' => '1',
                'Annotation.value !=' => 'NA'),
            'fields' => array('DISTINCT Annotation.value')));

        foreach ($raw_annotation as $a) {
            if (!empty($a['Annotation']['value'])) {
                if ($a['Annotation']['value'][0] != '{') {
                    $actors[] = trim($a['Annotation']['value']);
                } else {
                    $v = json_decode($a['Annotation']['value']);
                    if (!empty($v->actors)) {
                        $actors[] = trim($v->actors);
                    }
                }
            }
        }
        sort($actors);
        return $actors;
    }*/

   /* private function load_cities() {
        $this->loadModel('Annotation');
        $cities = array();
        $raw_annotation = $this->Annotation->find('all', array('conditions' => array('Annotation.tag_id ==' => '2',
                'Annotation.value !=' => 'NA'),
            'fields' => array('DISTINCT Annotation.value')));

        foreach ($raw_annotation as $a) {
            if (!empty($a['Annotation']['value'])) {
                $cities[] = trim($a['Annotation']['value']);
            }
        }
        sort($cities);
        return $cities;
    }
*/
    /*public function get_actors() {
        $this->layout = "ajax";
        $this->set('actors', $this->load_actors());
    }*/

}
