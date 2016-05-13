<?php
App::uses('OffersAppModel', 'Offers.Model');
/**
 * OffersUser Model
 *
 * @property Offer $Offer
 * @property User $User
 */
class OffersComment extends OffersAppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Offer' => array(
			'className' => 'Offers.Offer',
			'foreignKey' => 'offer_id',
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
		)
	);
}
