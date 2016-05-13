<?php
App::uses('OffersAppModel', 'Offers.Model');
/**
 * OffersPhoto Model
 *
 * @property Offer $Offer
 */
class OffersPhoto extends OffersAppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Offer' => array(
			'className' => 'Offer',
			'foreignKey' => 'offer_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
