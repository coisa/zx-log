<?php

/**
 * Zend Extended Logger
 *
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @since 2015-08-11
 */
namespace Zx\Log;

/**
 * Class Logger
 * @package Zx\Log
 */
class Logger extends \Zend\Log\Logger
{
    /**
     * @var Formatter\Simple
     */
    private $formatter;

    /**
     * {@inheritdoc}
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->setFormatter(new Formatter\Simple);
    }

    /**
     * {@inheritdoc}
     */
    public function log($priority, $message, $extra = array())
    {
        $this->getFormatter()->setFormat($message);

        return parent::log($priority, $this->getFormatter()->format($extra), $extra);
    }

    /**
     * Return message formatter
     *
     * @return Formatter\Simple
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * Set message formatter
     *
     * @param Formatter\Simple $formatter
     * @return Logger
     */
    public function setFormatter(Formatter\Simple $formatter)
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * Set formatter extra variable
     *
     * @param $name
     * @param mixed $value
     * @return Logger
     */
    public function setVariable($name, $value = null)
    {
        $this->getFormatter()->setVariable($name, $value);

        return $this;
    }

    /**
     * Set extra variables
     *
     * @param array $extra
     * @return Simple
     */
    public function setVariables(array $extra = array())
    {
        $this->getFormatter()->setVariables($extra);

        return $this;
    }

    /**
     * Get extra variables
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->getFormatter()->getVariables();
    }
}