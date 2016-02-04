<?php

App::uses('AppModel', 'Model');
App::uses('BaseBehaviour', 'Model/Behavior');

/**
 * Description of OnRegisterGeoLocationBehavior
 *
 * @author dareks
 */
class OnRegisterGeoLocationBehavior extends BaseBehaviour
{

    /**
     * beforeSave
     * @param Model $model
     * @param boolean $created
     * @param array $options
     * @return boolean
     */
    public function beforeSave(Model $model, $options = array())
    {
        $data = array();
        try {
            $data['User'] = $this->onCreated($model);
            if ($data['User']) {
                $model->data = Hash::merge($model->data, $data);
            }
        } catch (UnsupportedOperation $e) {
            //@TODO show/save error if needed
        }
        parent::beforeSave($model, $options);
        return true;
    }

    /**
     * onCreated
     * @param Model $model
     * @return array $data
     */
    protected function onCreated(Model $model)
    {
        if (isset($model->data['User']['lat']) && !empty($model->data['User']['lat']) &&
                isset($model->data['User']['lng']) && !empty($model->data['User']['lng'])) {
            $result = $this->onExistLatLng($model->data['User']['lat'], $model->data['User']['lng']);
        } else {
            $result = $this->onIpProcess();
        }
        return $this->onProcessAddressResult($result);
    }

}
