<?php

namespace LinkZone\Core\PublicBundle\DependencyInjection\Helper;

class Utils
{
    /**
     * Generates random string of given length
     *
     * Examples are taken from {@link http://stackoverflow.com/questions/4356289/php-random-string-generator here}
     *
     * @param  int    $length The length of required random string
     * @return string         Result random string
     * @link http://stackoverflow.com/questions/4356289/php-random-string-generator
     */
    static function getRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}
