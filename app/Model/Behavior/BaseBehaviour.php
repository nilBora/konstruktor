<?php

App::uses('AppModel', 'Model');

/**
 * Description of BaseBehaviour
 *
 * @author dareks
 */
class BaseBehaviour extends ModelBehavior
{

    /**
     * onExistLatLng
     * @param float $lat
     * @param float $lng
     * @return boolean | Geocoder\Model\AddressCollection
     */
    protected function onExistLatLng($lat, $lng)
    {
        $curl = new \Ivory\HttpAdapter\CurlHttpAdapter();
        $geocoder = new \Geocoder\Provider\GoogleMaps($curl);
        $result = $geocoder->reverse($lat, $lng);
        if ($result) {
            return $result;
        }
        return false;
    }

    /**
     * onIpProcess
     * @return boolean  | Geocoder\Model\AddressCollection
     */
    protected function onIpProcess()
    {
        $ip = $this->getIp();
        try{
            // Maxmind GeoIP2 Provider: e.g. the database reader
            $reader = new \GeoIp2\Database\Reader(ROOT . DS . APP_DIR . DS . 'Plugin' . DS . 'GeoLite2' . DS . 'GeoLite2-City.mmdb');
            $adapter = new \Geocoder\Adapter\GeoIP2Adapter($reader);
            $geocoder = new \Geocoder\Provider\GeoIP2($adapter);
            $result = $geocoder->geocode($ip);
            if ($result) {
                return $result;
            }
        } catch(Exception $e) {
            return false;
        }
        return false;
    }

    /**
     * getIp
     * @return string IP
     */
    protected function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {    //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * onProcessAddressResult
     * @param boolean | Geocoder\Model\AddressCollection $result
     * @return boolean
     */
    protected function onProcessAddressResult($result = null)
    {
        //on localhost this code generate error in login process
        //TODO: beautify code to prevent error rise up

        try {
          if ($result) {
            $first = $result->first();
            if ($first) {
                $address = $first->getStreetName();
                $streetNumber = $first->getStreetNumber();
                if (!empty($streetNumber))
                    $address .= ', ' . $first->getStreetNumber();
                return array(
                    'live_country' => $first->getCountryCode(),
                    'live_place' => $first->getLocality(),
                    'live_address' => $address,
                    'lat' => $first->getLatitude(),
                    'lng' => $first->getLongitude(),
                );
            } else {
                //@TODO show/save error if needed
            }
          } else {
              //@TODO show/save error if needed
          }
      //    return false;
        } catch (Exception $e) {
          return false;
        }

    }
}
