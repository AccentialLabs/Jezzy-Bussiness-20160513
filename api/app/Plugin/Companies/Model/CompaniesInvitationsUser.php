<?php
App::uses('CompaniesAppModel', 'Companies.Model');
/**
 * CompanyPreference Model
 *
 * @property Company $Company
 */
class CompaniesInvitationsUser extends CompaniesAppModel {


	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'CompaniesInvitationsFilter' => array(
			'className' => 'Companies.CompaniesInvitationsFilter',
			'foreignKey' => 'invitation_id',
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
		)
	);
}
