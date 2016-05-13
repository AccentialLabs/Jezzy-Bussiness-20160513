<?php
App::uses('CompaniesAppModel', 'Companies.Model');
/**
 * CompanyPreference Model
 *
 * @property Company $Company
 */
class CompanyPreference extends CompaniesAppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $validate = array(
		'company_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Empresa obrigatorio',							
			),
		),
		'bank_account' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Dados bancarios obrigatorios',						
				'on' => 'create'		
			),
		),
	);
	
	
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Company' => array(
			'className' => 'Companies.Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
