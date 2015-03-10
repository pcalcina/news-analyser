<?php
App::uses('AppModel', 'Model');
/**
 * Event Model
 *
 */
class Event extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'event';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'event_id';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

}
