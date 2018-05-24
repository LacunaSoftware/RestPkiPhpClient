<?php

namespace Lacuna\RestPki;

/**
 * Class CadesTimestamp
 * @package Lacuna\RestPki
 *
 * @property-read $genTime string
 * @property-read $serialNumber string
 * @property-read $messageImprint mixed
 */
class CadesTimestamp extends CadesSignature
{
    private $_genTime;
    private $_serialNumber;
    private $_messageImprint;

    public function __construct($model)
    {
        parent::__construct($model);
        $this->_genTime = $model->genTime;
        $this->_serialNumber = $model->serialNumber;
        $this->_messageImprint = $model->messageImprint;
    }

    /**
     * Gets the gen time.
     * @return string The gentime.
     */
    public function getGenTime()
    {
        return $this->_genTime;
    }

    /**
     * Gets the serial number.
     *
     * @return string The serial number.
     */
    public function getSerialNumber()
    {
        return $this->_serialNumber;
    }

    /**
     * Gets the message imprint.
     *
     * @return mixed The message imprint.
     */
    public function getMessageImprint()
    {
        return $this->_messageImprint;
    }

    public function __get($prop)
    {
        switch ($prop) {
            case "genTime":
                return $this->getGenTime();
            case "serialNumber":
                return $this->getSerialNumber();
            case "messageImprint":
                return $this->getMessageImprint();
            default:
                return parent::__get($prop);
        }
    }
}