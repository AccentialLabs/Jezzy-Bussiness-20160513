<?php
App::uses ( 'CompaniesAppModel', 'Companies.Model' );

/**
 * CompaniesCategorySubCategory model
 */
class CompaniesCategorySubCategory extends CompaniesAppModel {
	
	public $belongsTo = array (
			
			'Company' => array (
					'className' => 'Companies.Company',
					'foreignKey' => 'company_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			),
			
			'CompaniesCategory' => array (
					'className' => 'Companies.CompaniesCategory',
					'foreignKey' => 'category_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			),
			
			'CompaniesSubCategory' => array (
					'className' => 'Companies.CompaniesSubCategory',
					'foreignKey' => 'sub_category_id',
					'conditions' => '',
					'fields' => '',
					'order' => '' 
			) 
	);
	
}
