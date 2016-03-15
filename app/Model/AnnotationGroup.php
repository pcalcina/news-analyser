<?php

App::uses('AppModel', 'Model');

/**
 * AnnotationGroup Model
 *
 */
function sort_by_date($e1, $e2) {

    $comparison = strtotime($e1['annotated_date']) - strtotime($e2['annotated_date']);

    if ($comparison == 0) {
        if (!empty($e1['Cidade']) && !empty($e2['Cidade'])) {
            $comparison = strcmp($e1['Cidade'][0], $e2['Cidade'][0]);
        } else {
            $comparison = -10000;
        }
    }
    return $comparison;
}

class AnnotationGroup extends AppModel {

    public $useTable = 'annotation_group';
    public $primaryKey = 'annotation_group_id';
    public $actsAs = array('Containable');
    public $hasMany = array('Annotation');

#	public $hasMany = array(
#	    'Annotation' => array(
#    			'className'     => 'Annotation',
#    			'conditions'    => array(
#    			    'Annotation.annotation_group_id = AnnotationGroup.annotation_group_id'),
#    			'foreignKey'    => false
#    		),
#	);

    private function isValidDate($date) {
        return (bool) preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date);
    }

    public function aggregate_events() {
        $raw = $this->query("SELECT annotation_group_id, news_id,
                             count(*) as total_annotations, 
                             GROUP_CONCAT(value) as value from annotation 
                             WHERE tag_id IN (2,4)
                             GROUP BY annotation_group_id
                             ORDER BY total_annotations");

        $inconsistentGroups = array();
        $annotationGroups = array();

        foreach ($raw as $r) {
            if ($r[0]['total_annotations'] != '2') {
                $inconsistentGroups[] = $r[0];
            } else {
                list($v1, $v2) = split(',', $r[0]['value']);
                if ($this->isValidDate($v1)) {
                    $date = $v1;
                    $city = $v2;
                } else {
                    if ($this->isValidDate($v2)) {
                        $date = $v2;
                        $city = $v1;
                    }
                }
                if (!empty($date) && !empty($city) && $city != "NA") {
                    $annotationGroups[] = array($date, $city, $r[0]);
                } else {
                    $inconsistentGroups[] = $r[0];
                }
            }
        }

        asort($annotationGroups);
        return array('annotationGroups' => $annotationGroups,
            'inconsistentGroups' => $inconsistentGroups);
    }

    public function aggregate_events_old() {
        $raw = $this->query("SELECT
            annotation_group.annotation_group_id, 
            annotation_group.news_id,
            annotation.value,
            tag.name,
            news.date
        FROM annotation_group 
        LEFT JOIN annotation ON annotation_group.annotation_group_id = 
            annotation.annotation_group_id
        LEFT JOIN tag  ON annotation.tag_id = tag.tag_id
        LEFT JOIN news ON
            annotation_group.news_id = news.news_id
        WHERE annotation.annotation_id IS NOT NULL
        AND tag.name IN ( 'Cidade', 'Data')
        ORDER BY news.date ASC");

        $groups = array();
        foreach ($raw as $r) {
            $group_id = $r['annotation_group']['annotation_group_id'];

            if ($r['tag']['name'] == 'Cidade') {
                $groups[$group_id]['Cidade'][] = $r['annotation']['value'];
            }
            if ($r['tag']['name'] == 'Data') {
                $groups[$group_id]['annotated_date'] = $r['annotation']['value'];
            }
            $groups[$group_id]['news_date'] = $r['news']['date'];
            $groups[$group_id]['news_id'] = $r['annotation_group']['news_id'];
            $groups[$group_id]['annotation_group_id'] = $group_id;
        }

        foreach ($groups as &$group) {
            if (empty($group['annotated_date'])) {
                $group['annotated_date'] = $group['news_date'];
                $group['news_date'] = $group['news_date'] . '*';
            }
        }

        usort($groups, 'sort_by_date');

        $date_flag = true;
        $previous_date = '';

        foreach ($groups as &$group) {
            if ($previous_date != $group['annotated_date']) {
                $date_flag = !$date_flag;
            }
            $group['date_flag'] = $date_flag;
            $previous_date = $group['annotated_date'];
        }

        return $groups;
    }

}
