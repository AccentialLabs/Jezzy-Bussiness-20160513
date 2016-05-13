<?php
App::uses('PaymentsAppModel', 'Payments.Model');
/**
 * PaymentMethod Model
 *
 * @property Checkout $Checkout
 * @property Company $Company
 */
class PaymentMethod extends PaymentsAppModel {

/**
 * Display field
 *
 * @var string
 */
	//public $displayField = 'name';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Checkout' => array(
			'className' => 'Checkout',
			'foreignKey' => 'payment_method_id',
			'dependent' => false,			
		),
		'CompaniesPaymentMethod' => array(
			'className' => 'Companies.CompaniesPaymentMethod',
			'foreignKey' => 'company_id',
			'dependent' => false,		
		)
	);

}
