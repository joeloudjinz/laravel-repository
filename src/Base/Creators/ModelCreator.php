<?php

namespace Inz\Base\Creators;

class ModelCreator
{
    /**
     * Application base namespace.
     *
     * @var string
     */
    protected $appNamespace;
    /**
     * Model name specified by the developer.
     *
     * @var string
     */
    protected $modelName;
    /**
     * Model namespace.
     *
     * @var string
     */
    protected $modelNamespace;
    /**
     * Input value where a slash is replaced with anti-slash.
     *
     * @var string
     */
    protected $antiSlashedInput;

    public function __construct(string $input)
    {
        $this->appNamespace = app()->getNamespace();
        // replacing a ll slash occurrences with anti-slash
        $this->antiSlashedInput = str_replace('/', '\\', $input);
        // extracting words from the input of the command
        $exploded = explode('\\', $this->antiSlashedInput);
        // extracting last element from the array, the model name
        $this->modelName = array_pop($exploded);
        // constructing the full namespace of the model
        $this->constructFullNamespace($exploded);
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
                $this->modelNamespace .= $value.'\\';
            }
        }
        $this->modelNamespace .= $this->modelName;
    }

    /**
     * Checking the existence of the model class.
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
     * @return string
     */
    public function getModelFullNamespace()
    {
        return $this->modelNamespace;
    }

    /**
     * Return the model's name.
     *
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }
}
