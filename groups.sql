SELECT  annotation.annotation_group_id, 
annotation_detail.tag_detail_id,
 annotation.annotation_id,
 annotation_detail.value,
 annotation.news_id
 FROM annotation_detail
LEFT JOIN annotation ON annotation.annotation_id = annotation_detail.annotation_id
LEFT JOIN tag_detail ON tag_detail.tag_detail_id = annotation_detail.tag_detail_id
WHERE name IN ('cidade.cidade', 'data.data')
ORDER BY annotation.annotation_group_id, annotation_detail.name