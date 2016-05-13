<?php

/**
 * Endereços adicionais de entrega para checkout
 *
 *
 */

App::uses('UsersAppModel', 'Users.Model');

class UsersWishlistCompany extends UsersAppModel {

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
		'UsersWishlist' => array(
			'className' => 'Users.UsersWishlist',
			'foreignKey' => 'wishlist_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),				
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Company' => array(
			'className' => 'Companies.Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Offer' => array(
			'className' => 'Offers.Offer',
			'foreignKey' => 'offer_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}