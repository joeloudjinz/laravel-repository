<?php

namespace Inz\Repository\Base;

class ModelCreator
{
    /**
     * Application base namespace.
     *
     * @var string
     */
    protected $appNamespace;
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
        $this->appNamespace = app()->getNamespace();
        // replacing a ll slash occurrences with anti-slash
        $this->antiSlashedInput = str_replace('/', '\\', $input);
        // extracting words from the input of the command
        $exploded = explode('\\', $this->antiSlashedInput);
        // extracting last element from the array, the model name
        $this->modelName = array_pop($exploded);
        // constructing the full namespace of the model
        $this->modelNamespace = $this->appNamespace . $this->modelName;
    }

    /**
     * Constructing the full namespace of the model.
     *
     * @param array $explodedInput
     */
    public function constructFullNamespace(array $explodedInput)
    {
        $this->modelNamespace = $this->appNamespace;
        if (count($explodedInput) > 0) {
            foreach ($explodedInput as $value) {
                $this->modelNamespace += $value . '\\';
            }
        }
        $this->modelNamespace += $this->modelName;
    }

    /**
     * Checking the existence of the model class
     *
     * @param String $modelNamespace
     * @return bool
     */
    public function modelExist(String $model)
    {
        return class_exists($model);
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
