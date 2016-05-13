<?php
App::uses ( 'CompaniesAppModel', 'Companies.Model' );
/**
 * CompaniesCateogry Model
 */
class CompaniesCategory extends CompaniesAppModel {
	
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
	public $validate = array (
			'name' => array (
					'notempty' => array (
							'rule' => array (
									'notempty' 
							),
							'message' => 'Nome de categoria obrigatorio' 
					) 
			) 
	);
	
	// The Associations below have been created with all possible keys, those that are not needed can be removed
	public $hasMany = array (
			'Company' => array (
					'className' => 'Companies.Company',
					'foreignKey' => 'category_id',
					'dependent' => false,
					'conditions' => '' 
			),
			'UsersWishlistCompany' => array (
					'className' => 'Users.UsersWishlistCompany',
					'foreignKey' => 'category_id',
					'dependent' => false 
			),
			'CompaniesSubCategory' => array (
					'className' => 'Companies.CompaniesSubCategory',
					'foreignKey' => 'category_id',
					'dependent' => false,
					'conditions' => '' 
			),
			
			'CompaniesCategorySubCategory' => array (
					'className' => 'Companies.CompaniesCategorySubCategory',
					'foreignKey' => 'company_id',
					'dependent' => false,
					'conditions' => '' 
			) 
	);
	
	/**
	 * Executa rotinas antes de salvar os dados
	 *
	 * @return bool
	 */
	public function beforeSave() {
		parent::beforeSave ();
	}

/**
 * Executa rotinas depois de buscar os dados
 *
 * @return array
 */
	/*
	 * public function afterFind($results) { foreach($results as $key => $value) { if(!empty($value['Company']['password'])) { unset($results[$key]['Company']['password']); } } return $results; }
	 */
}
