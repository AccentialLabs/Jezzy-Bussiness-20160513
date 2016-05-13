<?php

App::uses('ServicesAppModel', 'Services.Model');

/**
 * Service Model
 */
class Service extends ServicesAppModel {

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'ServiceSecondaryUser' => array(
            'className' => 'Services.ServiceSecondaryUser',
            'foreignKey' => 'service_id',
            'dependent' => false
        )
    );

    public $hasOne = array(
        'Subclasse' => array(
            'className' => 'Service.Subclasse',
            'foreignKey' => 'id',
            'dependent' => false
        )
    );
    
    /**
     * Executa rotinas antes de salvar os dados
     * @return bool
     */
//    public function beforeSave() {
//        parent::beforeSave();
//
//        # unbinding n:n associations. Has bugs.
//        $unbind = array(
//            'hasAndBelongsToMany' => array('User')
//        );
//        $this->unbindModel($unbind);
//
//        return true;
//    }
}
