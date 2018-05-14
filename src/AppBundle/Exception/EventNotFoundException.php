<?php
/**
 * Created by PhpStorm.
 * User: mtomczak
 * Date: 22/05/2017
 * Time: 17:52
 */

namespace AppBundle\Exception;


class EventNotFoundException extends \Exception
{

    /**
     * CityNotFoundException constructor.
     * @param $slug
     */
    public function __construct($slug)
    {
        parent::__construct('City '.$slug.' not found.');
    }
}