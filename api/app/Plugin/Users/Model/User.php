<?php
App::uses('UsersAppModel', 'Users.Model');
/**
 * User Model
 *
 * @property Checkout $Checkout
 * @property FacebookProfile $FacebookProfile
 * @property Company $Company
 * @property Offer $Offer
 */
class User extends UsersAppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Preencha o campo NOME',
				//'required' => true
			),
		),
		'email' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Preencha o campo E-MAIL',
				//'required' => true
			),
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Informe uma SENHA',
				'required' => true,
				'on' => 'create'
			),
		),
	);

/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = array(
		'FacebookProfile' => array(
			'className' => 'Users.FacebookProfile',
			'foreignKey' => 'user_id',
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
		'CompaniesUser' => array(
			'className' => 'Companies.CompaniesUser',
			'foreignKey' => 'user_id',
			'dependent' => false,
		),
		'Checkout' => array(
			'className' => 'Payments.Checkout',
			'foreignKey' => 'user_id',
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
		'OffersUser' => array(
			'className' => 'Offers.OffersUser',
			'foreignKey' => 'user_id',
			'dependent' => false,
		),
		'AditionalAddressesUser' => array(
			'className' => 'Users.AditionalAddressesUser',
			'foreignKey' => 'user_id',
			'dependent' => false,
		),
		'CompaniesInvitationsUser' => array(
			'className' => 'Companies.CompaniesInvitationsUser',
			'foreignKey' => 'invitation_id',
			'dependent' => false,			
		),
		'UsersWishlist' => array(
			'className' => 'Users.UsersWishlist',
			'foreignKey' => 'user_id',
			'dependent' => false,
		),	
		'UsersWishlistCompany' => array(
			'className' => 'Users.UsersWishlistCompany',
			'foreignKey' => 'user_id',
			'dependent' => false,			
		),	
	);


	public $hasAndBelongsToMany = array(
		'Company' => array(
			'className'              => 'Companies.Company',
            'joinTable'              => 'companies_users',
            'foreignKey'             => 'user_id',
            'associationForeignKey'  => 'company_id'
		),
		'Offer' => array(
			'className'              => 'Offers.Offer',
            'joinTable'              => 'offers_users',
            'foreignKey'             => 'user_id',
            'associationForeignKey'  => 'offer_id'
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
			'hasAndBelongsToMany' => array('Company', 'Offer')
		);
		$this->unbindModel($unbind);
		
		# password hash
		if(!empty($this->data['User']['password'])) {
			$this->data['User']['password'] = md5($this->data['User']['password']);
		} 
		# setting 'date_register' field
		if(empty($this->data['User']['id'])) {
			$this->data['User']['date_register'] = date('Y-m-d H:i:s');
		}

		return true;
	}
		
}
