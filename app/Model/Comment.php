<?php
App::uses('AppModel', 'Model');
/**
 * Comment Model
 *
 */
class Comment extends AppModel {

	public $useTable = 'comment';

	public $primaryKey = 'comment_id';

	public $displayField = 'text';
    
    public $hasOne = array(
		'user' => array(
			'className' => 'User',
			'foreignKey' => false,
			'conditions' => 'user.id = Comment.user_id',
			'fields' => 'username',
			'order' => ''
		)
	);

}
