<?php

/**
 * Endereços adicionais de entrega para checkout
 *
 *
 */

App::uses('UsersAppModel', 'Users.Model');

class AditionalAddressesUser extends UsersAppModel {

	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = 'label';

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}