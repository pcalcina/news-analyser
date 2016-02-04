<?php
App::uses('AppModel', 'Model');
/**
 * TagType Model
 *
 */
class TagType extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'tag_type';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'tag_type_id';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

}
