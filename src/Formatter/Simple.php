<?php

/**
 * Zend Extended Logger Simple Formatter
 *
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @since 2015-08-11
 */
namespace Zx\Log\Formatter;

use Zend\Http\PhpEnvironment\RemoteAddress;
use Zend\Serializer\Serializer;

/**
 * Class Simple
 * @package Zx\Log\Formatter
 */
class Simple extends \Zend\Log\Formatter\Simple
{
    /**
     * @const Default DateTime format
     */
    const DEFAULT_DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @const Default format
     */
    const DEFAULT_FORMAT = '%timestamp% %remote% %priorityName% [PID:%pid%]: %message% %extra%';

    /**
     * @var array Extra variables for logs
     */
    protected $variables = array();

    /**
     * Get the current format
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set the format
     * @param string $format
     * @return Simple
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function format($event)
    {
        $remote = new RemoteAddress();

        $defaults = array(
            'remote' => $remote->getIpAddress(),
            'proxy' => $remote->getUseProxy(),
            'pid' => getmypid(),
            'gid' => getmygid(),
            'uid' => getmyuid()
        );

        $event = array_merge($defaults, $this->getVariables(), $event);

        return preg_replace('/\s\s+/', ' ', parent::format($event));
    }

    /**
     * {@inheritdoc}
     */
    protected function normalize($value)
    {
        if (is_scalar($value) || null === $value) {
            return $value;
        }

        if ($value instanceof \DateTime) {
            $value = $value->format($this->getDateTimeFormat());
        }

        if ($value instanceof \Traversable) {
            $value = iterator_to_array($value);
        }

        if (is_resource($value)) {
            $value = sprintf('resource(%s)', get_resource_type($value));
        }

        if (!is_string($value)) {
            $value = Serializer::serialize($value, 'Json');
        }

        return $value;
    }

    /**
     * Set extra variable
     * @param $name
     * @param mixed $value
     * @return Simple
     */
    public function setVariable($name, $value = null)
    {
        $this->variables[$name] = $value;

        return $this;
    }

    /**
     * Set extra variables
     * @param array $extra
     * @return Simple
     */
    public function setVariables(array $extra = array())
    {
        foreach ($extra as $name => $value) {
            $this->setVariable($name, $value);
        }

        return $this;
    }

    /**
     * Get extra variables
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }
}