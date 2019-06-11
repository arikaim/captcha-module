<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2017-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Modules\Recaptcha;

use Arikaim\Core\Utils\Utils;
use Arikaim\Core\Module\Module;

class Recaptcha extends Module
{
    private $recaptcha;
    private $verify_errors;

    public function __construct()
    {
        $this->verify_errors = [];
        $this->loadConfig('recaptcha');

        $this->recaptcha = new \ReCaptcha\ReCaptcha($this->getConfig('secret_key'));
        // todo
       // $this->recaptcha->setExpectedHostname(ARIKAIM_DOMAIN);
    }

    public function boot()
    {
        
    }

    public function verify($captcha_response, $remote_ip)
    {
        if (is_object($this->recaptcha) == false) {
            return false;
        }
        $this->verify_errors = [];
        $response = $this->recaptcha->verify($captcha_response,$remote_ip);
        if ($response->isSuccess() == true) {
            return true;
        }
        $this->verify_errors = $response->getErrorCodes();
        return false;
    }

    public function getErrors()
    {
        return $this->verify_errors;
    }

    public function __call($method, $args) 
    {
       
    }

    public function test()
    {
        try {
            
        } catch(\Exception $e) {
            $this->error = $e->getMessage();         
            return false;
        }
        $this->error = null;
        return true;
    }
}
