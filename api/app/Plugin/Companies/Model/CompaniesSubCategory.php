<?php
App::uses ( 'CompaniesAppModel', 'Companies.Model' );
/**
 * CompaniesSubCateogry Model
 */
class CompaniesSubCategory extends CompaniesAppModel {
	
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
							'message' => 'Nome de sub categoria obrigatorio' 
					) 
			) 
	);
	
	// The Associations below have been created with all possible keys, those that are not needed can be removed
	public $hasMany = array (
			'Company' => array (
					'className' => 'Companies.Company',
					'foreignKey' => 'sub_category_id',
					'dependent' => false 
			),
			'UsersWishlistCompany' => array (
					'className' => 'Users.UsersWishlistCompany',
					'foreignKey' => 'sub_category_id',
					'dependent' => false 
			),
			
			'CompaniesCategorySubCategory' => array (
					'className' => 'Companies.CompaniesCategorySubCategory',
					'foreignKey' => 'sub_category_id',
					'dependent' => false,
					'conditions' => '' 
			) 
	);
	public $belongsTo = array (
			'CompaniesCategory' => array (
					'className' => 'Companies.CompaniesCategory',
					'foreignKey' => 'category_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
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
