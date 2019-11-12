<?php

namespace Inz\Base\Assistors;

class ModelAssistor
{
    /**
     * Model name specified by the developer
     *
     * @var String
     */
    protected $modelName;
    /**
     * Model namespace
     *
     * @var String
     */
    protected $modelNamespace;
    /**
     * Input value where a slash is replaced with anti-slash
     *
     * @var String
     */
    protected $antiSlashedInput;

    public function __construct(String $input)
    {
        $this->antiSlashedInput = str_replace('/', '\\', $input);
        $exploded = explode('\\', $this->antiSlashedInput);
        $this->modelName = array_pop($exploded);
        $this->modelNamespace = app()->getNamespace() . $this->antiSlashedInput;
    }

    /**
     * Checking the existence of the model class
     *
     * @return bool
     */
    public function modelExist()
    {
        return class_exists($this->modelNamespace);
    }

    /**
     * Return the model's full namespace, model class name is included.
     *
     * @return String
     */
    public function getModelFullNamespace()
    {
        return $this->modelNamespace;
    }

    /**
     * Return the model's name.
     *
     * @return String
     */
    public function getModelName()
    {
        return $this->modelName;
    }
}
