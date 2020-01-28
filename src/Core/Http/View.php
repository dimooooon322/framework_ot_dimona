<?php
namespace Core\Http;

class View
{
    /**
     * Path to root directory
     */
    const PATH = "";
    /**
     * @var array
     */
    public $data;
    /**
     * @var string
     */
    protected $template;

    /**
     * View constructor.
     * @param string $template
     * @param array $data
     */
    public function __construct(string $template="", array $data=[])
    {
        $this->setTemplate($template);
        $this->data = $data;
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate(string $template)
    {
        $this->template = realpath($_SERVER['DOCUMENT_ROOT'] . $template);
        return $this;
    }
    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function display()
    {
        foreach ($this->data as $key => $value){
            $$key = $value;
        }

        include $this->getTemplate();
    }
}