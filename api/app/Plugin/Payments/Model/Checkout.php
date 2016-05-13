<?php
App::uses('PaymentsAppModel', 'Payments.Model');
/**
 * Checkout Model
 *
 * @property User $User
 * @property PaymentMethod $PaymentMethod
 * @property Offer $Offer
 * @property PaymentState $PaymentState
 */
class Checkout extends PaymentsAppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

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
		'PaymentMethod' => array(
			'className' => 'PaymentMethod',
			'foreignKey' => 'payment_method_id',
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
		),
		'PaymentState' => array(
			'className' => 'PaymentState',
			'foreignKey' => 'payment_state_id',
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
		)
	);
}
