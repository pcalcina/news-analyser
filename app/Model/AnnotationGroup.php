<?php

App::uses('AppModel', 'Model');

class AnnotationGroup extends AppModel {

    public $useTable = 'annotation_group';
    public $primaryKey = 'annotation_group_id';
    public $actsAs = array('Containable');
    public $hasMany = array('Annotation');
    
    public function grouping_groups(){  
        $raw = $this->query("SELECT  
            annotation.annotation_group_id, 
            tag_detail.name,
            annotation.annotation_id,
            annotation_detail.value,
            annotation.news_id
            FROM annotation_detail
            LEFT JOIN annotation ON annotation.annotation_id = annotation_detail.annotation_id
            LEFT JOIN annotation_group ON annotation_group.annotation_group_id = annotation.annotation_group_id
            LEFT JOIN tag_detail ON tag_detail.tag_detail_id = annotation_detail.tag_detail_id
            WHERE annotation_group.event_id IS NULL and  name IN ('cidade.cidade', 'data.data')
            ORDER BY annotation.annotation_group_id, tag_detail.name ASC");
        
        $inconsistentGroups = array();
        $annotationGroups = array();
        $candidateEvents = array();
        $currAnnotation = -1;
        $groupIdByKey = array();
        
        //console.log(count($raw));
        for($i = 0; $i < count($raw) - 1; $i++) { 
            $current = $raw[$i];
            $next = $raw[$i+1];
            if($current['annotation']['annotation_group_id'] != $next['annotation']['annotation_group_id'])
            {
                continue;
            }
            if($current['tag_detail']['name'] == 'cidade.cidade' and $next['tag_detail']['name'] == 'data.data')
            {
                $date = trim($next['annotation_detail']['value']);
                $city = trim($current['annotation_detail']['value']);
                $key = $date . "-" . $city;
                $fields = array(
                    'annotation_group_id' => $current['annotation']['annotation_group_id'],
                    'news_id' => $current['annotation']['news_id'],
                    'date'    => $date,
                    'city'    => $city);
                $candidateEvents[$key][] = $fields;
                $groupIdByKey[$key][]    =    $next['annotation']['annotation_group_id'];
            }
            else{
                #FIXME: Correct this format
                $inconsistentGroups[] = array(
                    'annotation_group_id' => $current['annotation']['annotation_group_id'],
                    'news_id' => $current['annotation']['news_id'],
                    'value'   => $current['annotation_detail']['value'] . ' - ' .
                                 $next['annotation_detail']['value']
                );
            }
            
        } 
        
        arsort($candidateEvents);
        
        return array('candidateEvents'    => $candidateEvents,
                     'inconsistentGroups' => $inconsistentGroups,
                     'detailIdByKey'      => $groupIdByKey);
    }
    
    public function aggregate_events(){        
        $raw = $this->query("SELECT  
            annotation.annotation_group_id, 
            tag_detail.name,
            annotation.annotation_id,
            annotation_detail.value,
            annotation.news_id
            FROM annotation_detail
            LEFT JOIN annotation ON annotation.annotation_id = annotation_detail.annotation_id
            LEFT JOIN tag_detail ON tag_detail.tag_detail_id = annotation_detail.tag_detail_id
            WHERE name IN ('cidade.cidade', 'data.data')
            ORDER BY annotation.annotation_group_id, tag_detail.name ASC");
        
        $inconsistentGroups = array();
        $annotationGroups = array();
        $candidateEvents = array();
        $currAnnotation = -1;
        $groupIdByKey = array();
        
        for($i = 0; $i < count($raw) - 1; $i++) {
            $current = $raw[$i];
            $next = $raw[$i+1];
            
            if($current['annotation']['annotation_group_id'] != 
               $next['annotation']['annotation_group_id'])
            {
                continue;
            }
            if($current['tag_detail']['name'] == 'cidade.cidade' and
               $next['tag_detail']['name'] == 'data.data')
            {
                $date = trim($next['annotation_detail']['value']);
                $city = trim($current['annotation_detail']['value']);
                $key = $date . "-" . $city;
                $fields = array(
                    'annotation_group_id' => $current['annotation']['annotation_group_id'],
                    'news_id' => $current['annotation']['news_id'],
                    'date'    => $date,
                    'city'    => $city);
                $candidateEvents[$key][] = $fields;
                $groupIdByKey[$key][]    =    $next['annotation']['annotation_group_id'];
            }
            else{
                #FIXME: Correct this format
                $inconsistentGroups[] = array(
                    'annotation_group_id' => $current['annotation']['annotation_group_id'],
                    'news_id' => $current['annotation']['news_id'],
                    'value'   => $current['annotation_detail']['value'] . ' - ' .
                                 $next['annotation_detail']['value']
                );
            }
        }
        
        arsort($candidateEvents);
        
        return array('candidateEvents'    => $candidateEvents,
                     'inconsistentGroups' => $inconsistentGroups,
                     'detailIdByKey'      => $groupIdByKey);
    }
}
