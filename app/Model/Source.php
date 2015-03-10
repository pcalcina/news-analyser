<?php
App::uses('AppModel', 'Model');
/**
 * Source Model
 *
 */
class Source extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'source';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'source_id';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

}
