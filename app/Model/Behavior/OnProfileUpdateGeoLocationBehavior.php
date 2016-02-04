<?php

App::uses('AppModel', 'Model');
App::uses('Country', 'Model');
App::uses('BaseBehaviour', 'Model/Behavior');

/**
 * Description of OnProfileUpdateGeoLocationBehavior
 *
 * @author dareks
 */
class OnProfileUpdateGeoLocationBehavior extends BaseBehaviour
{

    private $_instance = null;

    /**
     * afterSave
     * @param Model $model
     * @param boolean $created
     * @param array $options
     * @return boolean
     */
    public function beforeSave(Model $model, $options = array())
    {
        $data = array();
        try {
            //if update address field, we need save new lat lng by address
            $data['User'] = $this->onUpdated($model);
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
     * onUpdated
     * @param Model $model
     * @return boolean | array
     */
    protected function onUpdated(Model $model)
    {
        if (!$this->isEmptyAddress($model)) {
            if ($this->hasDiffAddress($model)) {
                $result = $this->onLatLngGetting($model);
                return $this->onProcessLatLngResult($result);
            }
        } else {
            return array(
                'lat' => '',
                'lng' => '',
            );
        }
        return false;
    }

    /**
     * hasDiffAddress
     * @param Model $model
     * @return boolean
     */
    protected function hasDiffAddress(Model $model)
    {
        $this->_instance = $model->findById($model->id);
        if ($this->_instance !== null && isset($model->data['User']['live_country'])) {
            $prevAddress = array(
                'live_country' => $this->_instance['User']['live_country'],
                'live_place' => $this->_instance['User']['live_place'],
                'live_address' => $this->_instance['User']['live_address'],
            );
            $currentAddress = array(
                'live_country' => $model->data['User']['live_country'],
                'live_place' => $model->data['User']['live_place'],
                'live_address' => $model->data['User']['live_address'],
            );
            $result = Hash::diff($prevAddress, $currentAddress);
            return !empty($result) ? true : false;
        }
        return false;
    }

    /**
     * onLatLngGetting
     * @param Model $model
     * @return array | boolean
     */
    protected function onLatLngGetting(Model $model)
    {
        $curl = new \Ivory\HttpAdapter\CurlHttpAdapter();
        $geocoder = new \Geocoder\Provider\GoogleMaps($curl);

        $model->loadModel('Country');
        $aCountryOptions = $model->Country->options();
        $countryCode = $model->data['User']['live_country'];
        $countryName = (isset($aCountryOptions[$countryCode])) ? $aCountryOptions[$countryCode] : $countryCode;

        $address = join(',', array(
            $model->data['User']['live_address'],
            $model->data['User']['live_place'],
            $countryName,
        ));
        $result = $geocoder->geocode($address);
        if ($result) {
            return $result;
        }
        return false;
    }

    /**
     * onProcessLatLngResult
     * @param boolean | Geocoder\Model\AddressCollection $result
     * @return boolean
     */
    protected function onProcessLatLngResult($result)
    {
        if ($result) {
            $first = $result->first();
            if ($first) {
                return array(
                    'lat' => $first->getLatitude(),
                    'lng' => $first->getLongitude(),
                );
            } else {
                //@TODO show/save error if needed
            }
        } else {
            //@TODO show/save error if needed
        }
        return false;
    }

    /**
     * isEmptyAddress
     * @param Model $model
     * @return boolean
     */
    protected function isEmptyAddress(Model $model)
    {
        if (empty($model->data['User']['live_address']) && empty($model->data['User']['live_place'])) {
            return true;
        }
        return false;
    }
}
