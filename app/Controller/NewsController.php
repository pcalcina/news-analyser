<?php

App::uses('AppController', 'Controller');

define("STATUS_SEM_REVISAO", "1");
define("STATUS_SEM_CODIFICAR", "2");
define("STATUS_CODIFICADA", "3");
define("STATUS_COM_EVENTO", "4");
define("STATUS_ELIMINADA", "5");
define("STATUS_SEM_FILTRAR", "6");

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
        $raw_statuses = $this->NewsStatus->find('all', 
            array('conditions' => array('NewsStatus.id !=' => STATUS_ELIMINADA, 'id !=' => STATUS_SEM_FILTRAR)));
        $statuses = array(null => $labelForNull);

        foreach ($raw_statuses as $status) {
            $statuses[$status['NewsStatus']['id']] = $status['NewsStatus']['description'];
        }
        
        return $statuses;
    }
    
    public function crawler(){
    
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
        $conditions = array('NewsStatus.id !=' => STATUS_ELIMINADA, 'id !=' => STATUS_SEM_FILTRAR);
        $this->set('news_list', $this->Paginator->paginate($conditions));
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
        } 
        catch (Exception $exception) {
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

    public function news_candidatas(){
        $this->News->recursive = 0;
        $conditions = array('NewsStatus.id ==' => STATUS_SEM_FILTRAR);
        $this->set('news_list', $this->Paginator->paginate($conditions));
    }
    
    function format_date_for_url($date) {
        $splitted_date = array_reverse(explode('-', $date));
        return implode('%2F', $splitted_date);
    }
    
    public function start_crawler(){
        $this->layout = "ajax";
        $initial_date = $this->format_date_for_url($this->request->data['startDate']);
        $final_date = $this->format_date_for_url($this->request->data['endDate']);
        $keywords = implode(' ', $this->request->data['keywords']);
        $url = "https://localhost:9080/crawl.json";
        $pid = $this->crawler_exec($keywords, $initial_date, $final_date);
        $this->set('crawler_id', $pid);
    }
    
    private function crawler_exec($keywords, $initial_date, $final_date){
        $crawler_dir = "/home/pablo/Programming/news-crawler";
        $crawler_name = "folha-spider";
        $command = "cd {$crawler_dir} ; scrapy crawl {$crawler_name} " .
                   " -a keywords={$keywords} " . 
                   " -a initial_date={$initial_date} " . 
                   " -a final_date={$final_date}";
//        $last_line = system($command, $retval);
//        debug($last_line);
//        debug($retval);
//        exit;
        $process = new Process($command);
        return $process->getPid();
    }
    
    public function crawler_status($crawler_id){
        $this->layout = "ajax";    
        $process = new Process();
        $process->setPid($crawler_id);
        $this->set("running", $process->status());
    }
    
    private function crawler_request($url, $keywords, $initial_date, $final_date){
        $fields = array('request' => array('url' => $url, 
                     'meta' => array('keywords' => $keywords, 'initial_date' => $initial_date, 'final_date' => $final_date)), 
                     'spider_name' => 'folha-spider');
        $postvars = http_build_query($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        $result = curl_exec($ch);
        curl_close($ch);
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
            
        } 
        else {
            $this->loadModel('Tag');
            $this->loadModel('Event');
            $this->loadModel('Comment');
            $this->loadModel('TagType');
            $this->loadModel('TextType');
            $this->loadModel('TagDetail'); 
            
            $optionsNews = array('conditions' => array('News.' . $this->News->primaryKey => $id));
            $events = $this->Event->find('all', array('order' => 'event.name'));
            $tags = $this->Tag->find('all', 
                 array('contain' => array('TagDetail' ),'order' => 'tag.name'));
            
            $commentsConditions = array('conditions' => array('Comment.news_id = ' => $id));
       
            $tagTypes = $this->TagType->find('all');
            $textTypes = $this->TextType->find('all');
            $tagsDetail = $this->TagDetail->find('all'); 
            
            $tagsById = $this->getTagsById ($tags);
            $tagsDetailById = $this->getTagsDetailById ($tagsDetail);
            $tagTypesById = $this->getTagsTypesById ($tagTypes);
            $textTypesById = $this->getTextTypesById ($textTypes);
              
            $this->set('tags', $tags);
            $this->set('events', $events);
            $news = $this->News->find('first', $optionsNews);
            $this->set('news', $news);
            $this->set('statuses', $this->load_statuses('-'));
            $this->set('saved_event_groups', $this->getEventGroups($id));
            $this->set('comments', $this->Comment->find('all', $commentsConditions));
            $this->set('tagsById', $tagsById);
            $this->set('tagTypes', $tagTypes);
            $this->set('tagTypesById', $tagTypesById);
            $this->set('textTypesById', $textTypesById);
            $this->set('tagsDetailById', $tagsDetailById);
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
    
    public function acceptNews($id=null) {  
        if(!empty($id)){
            $this->loadModel('News');
            /*debug($id);
            exit(-1);*/
            $newInfo = array('News' => array('news_id' => $id, 'news_status_id' => STATUS_SEM_REVISAO));
            $this->News->save($newInfo);
            return $this->redirect(array('action' => 'news_candidatas'));  
        }
    }
}

class Process {
    private $pid;
    private $command;

    public function __construct($cl=false){
        if ($cl != false){
            $this->command = $cl;
            $this->runCom();
        }
    }
    private function runCom(){
        $command = $this->command.' > /dev/null 2>&1 & echo $!';
        exec($command, $op);
        $this->pid = (int)$op[0];
    }

    public function setPid($pid){
        $this->pid = $pid;
    }

    public function getPid(){
        return $this->pid;
    }

    public function status(){
        $command = 'ps -p '.$this->pid;
        exec($command,$op);
        if (!isset($op[1]))return false;
        else return true;
    }

    public function start(){
        if ($this->command != '')$this->runCom();
        else return true;
    }

    public function stop(){
        $command = 'kill '.$this->pid;
        exec($command);
        if ($this->status() == false)return true;
        else return false;
    }
}
