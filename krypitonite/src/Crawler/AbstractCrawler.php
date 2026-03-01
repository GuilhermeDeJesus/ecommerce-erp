<?php

/**
 * Created by Eclipse.
 * User: guilherme
 * Date: 13/03/2016
 * Time: 12:16
 */
namespace Krypitonite\Crawler;

use Krypitonite\Command\Executable;
use Behat\Mink\Driver\GoutteDriver as GDriver;
use Behat\Mink\Session as Session;
use Behat\Mink\Exception\Exception;

abstract class AbstractCrawler implements Executable
{

    /**
     *
     */
    private $_session;

    /**
     *
     */
    private $_driver;

    function __construct()
    {
        try {
            if (! isset($this->_driver))
                $this->setDriver(new GDriver());
            if (! isset($this->_session))
                $this->setSession(new Session($this->getDriver()));
        } catch (Exception $e) {
            // this code log implements
            echo '<pre>';
            print_r($e->getMessage());
        }
    }

    /**
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->_session;
    }

    /**
     *
     * @param Session $session            
     */
    public function setSession($session)
    {
        $this->_session = $session;
    }

    /**
     *
     * @return Driver
     */
    public function getDriver()
    {
        return $this->_driver;
    }

    /**
     *
     * @param Session $session            
     */
    public function setDriver($driver)
    {
        $this->_driver = $driver;
    }
}