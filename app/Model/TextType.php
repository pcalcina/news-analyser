<?php
App::uses('AppModel', 'Model');
/**
 * TextType Model
 *
 */
class TextType extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'text_type';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'text_type_id';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

}
