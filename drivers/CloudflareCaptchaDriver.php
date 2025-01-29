<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Modules\Captcha\Drivers;

use Arikaim\Core\Driver\Traits\Driver;
use Arikaim\Core\Interfaces\Driver\DriverInterface;
use Arikaim\Modules\Captcha\CaptchaInterface;

use Turnstile\Error\Code;
use Turnstile\Turnstile;

/**
 * Cloudflare captcha driver class
 */
class CloudflareCaptchaDriver implements DriverInterface, CaptchaInterface
{   
    use Driver;

    /**
     * Verification errors 
     *
     * @var array|null
     */
    private $errors = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDriverParams('cloudflare','captcha','Turnstile captcha','Driver for Turnstile captcha service');      
    }

    /**
     * Initialize driver
     *
     * @return void
     */
    public function initDriver($properties)
    {
        global $arikaim;

        $secretKey = \trim($properties->getValue('secret_key',''));
     
        $this->instance = new Turnstile(
            $arikaim->get('http')->getAdapter(),
            $secretKey,
        );
      
        $this->clearErrors();
    }

    /**
     * Create driver config properties array
     *
     * @param Arikaim\Core\Collection\Properties $properties
     * @return array
     */
    public function createDriverConfig($properties)
    {
        $properties->property('site_key',function($property) {
            $property
                ->title('Site Key')
                ->type('text')
                ->default('')
                ->required(true);
        });

        $properties->property('secret_key',function($property) {
            $property
                ->title('Secret Key')
                ->type('key')
                ->default('')
                ->required(true);
        });
    }

    /**
     * Verify captcha
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request    
     * @param array|null $data
     * @return bool
     */
    public function verify($request, ?array $data = null): bool
    {
        if ($this->instance == null) {
            return false;
        }
        $this->clearErrors();
        
        $token = $data['cf-turnstile-response'] ?? null;
        if (empty($token) == true) {
            return false;
        }
        $remoteIp = $request->getAttribute('client_ip');

        $response = $this->instance->verify($token,$remoteIp);
        if ($response->success == true) {
            return true;
        }

        $this->errors = $response->errorCodes;
        
        return false;
    }

    /**
     * Get verification errors
     *
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }   

    /**
     * Clear errors
     *
     * @return void
     */
    public function clearErrors(): void
    {
        $this->errors = null;
    }
}
