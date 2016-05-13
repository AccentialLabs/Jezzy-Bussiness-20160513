<?php
App::uses('AppModel', 'Model');
/**
 * CompaniesPaymentMethod Model
 *
 * @property PaymentMethod $PaymentMethod
 * @property Company $Company
 */
class CompaniesPaymentMethod extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'payment_method_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'ID do pagamento obrigatorio',				
				'required' => true,		
				'on' => 'create'	
			),
		),
		'company_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'ID da empresa obrigatorio',				
				'required' => true,
				'on' => 'create'					
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'PaymentMethod' => array(
			'className' => 'Payments.PaymentMethod',
			'foreignKey' => 'payment_method_id',			
		),
		'Company' => array(
			'className' => 'Companies.Company',
			'foreignKey' => 'company_id',
			'conditions' => '',			
		)
	);
}
