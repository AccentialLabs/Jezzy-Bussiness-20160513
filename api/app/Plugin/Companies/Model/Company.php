<?php
App::uses('CompaniesAppModel', 'Companies.Model');
/**
 * Company Model
 *
 * @property CompanyPreference $CompanyPreference
 * @property Offer $Offer
 * @property PaymentMethod $PaymentMethod
 * @property User $User
 */
class Company extends CompaniesAppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'fancy_name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'corporate_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Nome da empresa obrigatorio'													
			),
		),
		'fancy_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Nome fantasia da empresa obrigatorio',										
			),
		),
		'cnpj' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'CNPJ obrigatorio',										
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Email obrigatorio',										
			),
		),
		'address' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Endereco obrigatorio',										
			),
		),
		'city' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Cidade obrigatorio',						
			),
		),
		'state' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Estado obrigatorio',										
			),
		),		
		'responsible_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Nome do responsavel obrigatorio',										
			),
		),					
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	
	
/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = array(
		'CompanyPreference' => array(
			'className' => 'Companies.CompanyPreference',
			'foreignKey' => 'company_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	
	
	
	
/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(			
		'Offer' => array(
			'className' => 'Offers.Offer',
			'foreignKey' => 'company_id',
			'dependent' => false,			
		),
		'CompaniesInvitationsFilter' => array(
			'className' => 'Companies.CompaniesInvitationsFilter',
			'foreignKey' => 'company_id',
			'dependent' => false,			
		),
		'CompaniesInvitationsUser' => array(
			'className' => 'Companies.CompaniesInvitationsUser',
			'foreignKey' => 'company_id',
			'dependent' => false,			
		),		
		'CompaniesUser' => array(
			'className' => 'Companies.CompaniesUser',
			'foreignKey' => 'company_id',
			'dependent' => false,			
		),
		'CompaniesPaymentMethod' => array(
			'className' => 'Companies.CompaniesPaymentMethod',
			'foreignKey' => 'company_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),		
		'Checkout' => array(
			'className' => 'Payments.Checkout',
			'foreignKey' => 'company_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),		
		'UsersWishlistCompany' => array(
			'className' => 'Users.UsersWishlistCompany',
			'foreignKey' => 'company_id',
			'dependent' => false,			
		),	
		
		'CompaniesCategorySubCategory' => array(
				'className' => 'Companies.CompaniesCategorySubCategory',
				'foreignKey' => 'company_id',
				'dependent' => false,
		),
	);
	
	public $belongsTo = array(
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
		),
			
	);
	

	public $hasAndBelongsToMany = array(
		'User' => array(
			'className'              => 'Users.User',
            'joinTable'              => 'companies_users',
            'foreignKey'             => 'company_id',
            'associationForeignKey'  => 'user_id'
		)
	);
	
	
	/**
	 * Executa rotinas antes de salvar os dados
	 * @return bool
	 */
	public function beforeSave() {
		parent::beforeSave();

		# unbinding n:n associations. Has bugs.
		$unbind = array(
			'hasAndBelongsToMany' => array('User')
		);
		$this->unbindModel($unbind);
		
		# password hash
		if(!empty($this->data['Company']['password'])) {
			$this->data['Company']['password'] = md5($this->data['Company']['password']);
		} else {
			unset($this->data['Company']['password']);
		}

		# setting 'date_register' field
		if(empty($this->data['Company']['id'])) {
			$this->data['Company']['date_register'] = date('Y-m-d H:i:s');
		}
		
		return true;
	}
	
	
	/**
	 * Executa rotinas depois de buscar os dados
	 * @return array
	 */
	/*
	public function afterFind($results) {
		
		
		foreach($results as $key => $value) {
			if(!empty($value['Company']['password'])) {
				 unset($results[$key]['Company']['password']);
			}
		}
		return $results;
		
	}
	*/
}
