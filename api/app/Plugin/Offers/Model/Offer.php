<?php
App::uses('OffersAppModel', 'Offers.Model');
/**
 * Offer Model
 *
 * @property Company $Company
 * @property Checkout $Checkout
 * @property OffersFilter $OffersFilter
 * @property OffersPhoto $OffersPhoto
 * @property User $User
 */
class Offer extends OffersAppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';


/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'company_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'ID da empresa obrigatorio'													
			),
		),
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Titulo Obrigatorio'													
			),
		),
		'value' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Valor Obrigatorio'													
			),
		),
		'begins_at' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Data inicial obrigatorio'													
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
		'Company' => array(
			'className' => 'Companies.Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Checkout' => array(
			'className' => 'Payments.Checkout',
			'foreignKey' => 'offer_id',
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
		'OffersFilter' => array(
			'className' => 'Offers.OffersFilter',
			'foreignKey' => 'offer_id',
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
		'OffersPhoto' => array(
			'className' => 'Offers.OffersPhoto',
			'foreignKey' => 'offer_id',
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
			'foreignKey' => 'offer_id',
			'dependent' => false,
		),
		'OffersComment' => array(
			'className' => 'Offers.OffersComment',
			'foreignKey' => 'offer_id',
			'dependent' => false,
		),
		'UsersWishlistCompany' => array(
			'className' => 'Users.UsersWishlistCompany',
			'foreignKey' => 'offer_id',
			'dependent' => false,			
		),
	);

	
	public $hasAndBelongsToMany = array(
		'User' => array(
			'className'              => 'Users.User',
            'joinTable'              => 'offers_users',
            'foreignKey'             => 'offer_id',
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
		
		return true;
	}

}
