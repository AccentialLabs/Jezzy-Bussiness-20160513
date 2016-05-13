<?php
App::uses('CompaniesAppModel', 'Companies.Model');
/**
 * CompanyPreference Model
 *
 * @property Company $Company
 */
class CompaniesInvitationsFilter extends CompaniesAppModel {


		
	
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
	
	public $hasMany = array(
		'CompaniesInvitationsUser' => array(
			'className' => 'Companies.CompaniesInvitationsUser',
			'foreignKey' => 'invitation_id',
			'dependent' => false,			
		),
	);
}
