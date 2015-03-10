<?php
App::uses('AppModel', 'Model');
/**
 * Tag Model
 *
 */
class Tag extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'tag';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'tag_id';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

}
