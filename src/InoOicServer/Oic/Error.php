<?php
namespace InoOicServer\Oic;

class Error
{

    /**
     * @var string
     */
    protected $message = 'general_error';

    /**
     * @var string
     */
    protected $description;

    /**
     * Constructor.
     *
     * @param string $message
     * @param string $description
     */
    public function __construct($message = null, $description = null)
    {
        if (null !== $message) {
            $this->setMessage($message);
        }

        if (null !== $description) {
            $this->setDescription($description);
        }
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}