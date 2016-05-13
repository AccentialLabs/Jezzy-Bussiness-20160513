<?php

App::uses('ServicesAppModel', 'Services.Model');

/**
 * Service Model
 */
class ServiceSecondaryUser extends ServicesAppModel {

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasOne = array(
        'SecondaryUser' => array(
            'className' => 'Service.SecondaryUser',
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
