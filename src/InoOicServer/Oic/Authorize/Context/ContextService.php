<?php
namespace InoOicServer\Oic\Authorize\Context;

/**
 * Authorize context service.
 */
class ContextService implements ContextServiceInterface
{

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var ContextFactoryInterface
     */
    protected $factory;

    /**
     * Constructor.
     *
     * @param StorageInterface $storage
     * @param ContextFactoryInterface $factory$context
     */
    public function __construct(StorageInterface $storage, ContextFactoryInterface $factory = null)
    {
        $this->setStorage($storage);

        if (null === $factory) {
            $factory = new ContextFactory();
        }
        $this->setFactory($factory);
    }

    /**
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return ContextFactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param ContextFactoryInterface $factory
     */
    public function setFactory(ContextFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Authorize\Context\ContextServiceInterface::createContext()
     */
    public function createContext()
    {
        $context = $this->getFactory()->createContext();
        $this->saveContext($context);

        return $context;
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Authorize\Context\ContextServiceInterface::saveContext()
     */
    public function saveContext(Context $context)
    {
        $this->getStorage()->save($context);
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Authorize\Context\ContextServiceInterface::loadContext()
     */
    public function loadContext()
    {
        return $this->getStorage()->load();
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Authorize\Context\ContextServiceInterface::clearContext()
     */
    public function clearContext()
    {
        $this->getStorage()->clear();
    }

    /**
     * Returns true, if there exists a valid context saved.
     *
     * @return boolean
     */
    public function existsValidContext()
    {
        return ($this->loadContext() !== null);
    }
}