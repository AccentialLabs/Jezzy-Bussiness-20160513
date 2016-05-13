<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	
	
	/**
	 * Verifica os relacionamentos para unbind segundo os params recebidos
	 * @param array $params
	 */
	public function associationsToUnbindForParams($params) {
		$p = array();
		foreach($params as $key => $param) {
			if(is_array($param)) {
				$p[] = $key;
			} else {
				$p[] = $param;
			}
		}
		
		$unbind = array();
		foreach($this->associations() as $assoc) {
			$association = $this->getAssociated($assoc);
			foreach($association as $key => $item) {
				if(in_array($item, $p)) {
					unset($association[$key]);
				}
			}
			$unbind[$assoc] = $association;
		}
		return $unbind;
	}

	/**
	 * Formata um array para ser usado com o método FIND(), na cláusula CONDITIONS
	 * @return array
	 **/
	public function setConditionsForSelect($params) {
		$conditions = array();
		
		foreach ($params as $key => $value) {				
			if (is_array($value)) {															
				$conditions = array_merge($conditions, $value);																									
			}
		}
	
		return $conditions;
	}	
}
