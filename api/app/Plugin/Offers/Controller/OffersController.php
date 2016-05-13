<?php

/**
 * 
 * @author Rodrigo Salles, Wilson Junior
 * @copyright Accential
 */

App::uses('OffersAppController', 'Offers.Controller');

class OffersController extends OffersAppController {

	/**
	 * Método padrão de toda classe de API.
	 * 
	 * @see AppController::index()
	 */
	public function index() {
		$this->method = 'init';
		return parent::index();
	}
	
	/**
	 * Verifica qual metodo esta sendo solicitado atraves do requestType
	 * @return void
	 */
	protected function init(){				
		switch ($this->requestType) {
			case 'get':
				$this->getData();
				break;
			
			case 'save':
				$this->saveData();
				break;
			
			case 'delete':
				$this->deleteData();
				break;
			
			default:
				$this->getData();
				break;
		}
	}
	
	/**
	 * Faz select solicitado com conditions e unbind.
	 * @return void
	 */	
	protected function getData(){				
		$unbind 		= $this->model->associationsToUnbindForParams($this->requestParams);
		$this->model->unbindModel($unbind);		
		
		$conditions		 = $this->model->setConditionsForSelect($this->requestParams);										 
		$this->appData 	 = $this->model->find($this->findType, $conditions);		
	}
	

	/**
	 * Adiciona / edita dados de empresas
	 * @return void
	 */
	protected function saveData() {
		$modelClassName = get_class($this->model);
		
		$this->model->set($this->requestParams);
		if ($this->model->validates()) {
			if ($this->model->saveAll($this->requestParams)) {
				# save OK...
				
				$data = $this->model->find('first',array('conditions'=>array("{$modelClassName}.id" => $this->model->id)));
				$this->appData = array(
					'status' =>$this->crudOperationStatus['save_ok'],
					'data' => $data
				);
			} else {
				# save NOT OK...
				$this->appData = array(
					'status' =>$this->crudOperationStatus['save_error'],
					'data' => $this->requestParams
				);
			}
		} else {
			# validation error
			$this->appData = array(
				'status'=>$this->crudOperationStatus['save_validation'],
				'invalid_fields'=>$this->model->validationErrors,
				'data' => $this->requestParams
			);
		}
	}
	
	
	/**
	 * Exclui dados de empresas
	 * @return array
	 */
	protected function deleteData() {
		if (!empty($this->requestParams['id'])) {
			if($this->Offer->delete($this->requestParams['id'])) {
				# delete ok
				$this->appData = array('status'=>$this->crudOperationStatus['delete_ok']);
			} else {
				# delete error
				$this->appData = array(
					'status'=>$this->crudOperationStatus['delete_error'],
					'data' => $this->requestParams
				);
			}
		} else {
			# validation error
			$this->appData = array(
				'status'=>$this->crudOperationStatus['delete_invalid_id'],
				'data' => $this->requestParams
			);
		}
	}
}
