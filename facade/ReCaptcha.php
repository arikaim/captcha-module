<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2017-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Modules\Recaptcha\Facade;

use Arikaim\Core\Utils\StaticFacade;

class Recaptcha extends StaticFacade
{
    public static function getInstanceClass()
    {
        return 'Arikaim\\Modules\\Recaptcha\\Recaptcha';
    }

    public static function getContainerItemName()
    {
        return null;
    }
}
