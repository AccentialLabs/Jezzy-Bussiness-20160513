<?php

/**
 * Endereços adicionais de entrega para checkout
 *
 *
 */

App::uses('UsersAppModel', 'Users.Model');

class UsersWishlist extends UsersAppModel {

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
		),
		
		'CompaniesCategory' => array(
			'className' => 'Companies.CompaniesCategory',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
		'CompaniesSubCategory' => array(
			'className' => 'Companies.CompaniesSubCategory',
			'foreignKey' => 'sub_category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public $hasMany = array(
		'UsersWishlistCompany' => array(
			'className' => 'Users.UsersWishlistCompany',
			'foreignKey' => 'wishlist_id',
			'dependent' => false,			
		),
	);
}