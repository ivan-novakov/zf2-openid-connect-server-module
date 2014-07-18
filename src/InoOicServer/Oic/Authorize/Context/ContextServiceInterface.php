<?php
namespace InoOicServer\Oic\Authorize\Context;

interface ContextServiceInterface
{

    /**
     * Creates a new context, saves it and returns it.
     *
     * @return Context
     */
    public function createContext();

    /**
     * Saves the provided authorize context.
     *
     * @param Context $context
     */
    public function saveContext(Context $context);

    /**
     * Loads a previously saved authorize context.
     *
     * @return Context
     */
    public function loadContext();

    /**
     * Clears the currently saved context.
     */
    public function clearContext();
}