<?php

App::uses('AppModel', 'Model');
App::uses('BaseBehaviour', 'Model/Behavior');

/**
 * Description of OnLoginGeoLocationBehavior
 *
 * @author dareks
 */
class OnLoginGeoLocationBehavior extends BaseBehaviour
{

    private $_instance = null;

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
            $data['User'] = $this->onLogin($model);
            if ($data['User']) {
                unset($model->data['User']['username']);
                unset($model->data['User']['password']);
                $model->data = Hash::merge($model->data, $data);
            }
        } catch (UnsupportedOperation $e) {
            //@TODO show/save error if needed
        }
        parent::beforeSave($model, $options);
        return true;
    }

    /**
     * onLogin
     * @param Model $model
     * @return array $data
     */
    protected function onLogin(Model $model)
    {
        $result = false;
        $this->_instance = $model->findById($model->id);
        if ($this->_instance !== null && (empty($this->_instance['User']['lat']) || empty($this->_instance['User']['lng']))) {
            if (isset($model->data['User']['lat']) && !empty($model->data['User']['lat']) &&
                    isset($model->data['User']['lng']) && !empty($model->data['User']['lng'])) {
                $result = $this->onExistLatLng($model->data['User']['lat'], $model->data['User']['lng']);
            } else {
                $result = $this->onIpProcess();
            }
        } else {
            return array(
                'lat' => $this->_instance['User']['lat'],
                'lng' => $this->_instance['User']['lng'],
            );
        }
        
        return $this->onProcessAddressResult($result);
    }

}
