<?php
namespace InoOicServer\Oic\User\Authentication;

use InoOicServer\Util\UrlHelper;
use InoOicServer\Exception\MissingOptionException;
use InoOicServer\Util\OptionsTrait;

class Manager
{

    use OptionsTrait;

    const OPT_METHOD = 'method';

    const OPT_AUTH_ROUTE = 'auth_route';

    const OPT_RETURN_ROUTE = 'return_route';

    /**
     * @var UrlHelper
     */
    protected $urlHelper;

    /**
     * Constructor.
     *
     * @param array $options
     * @param UrlHelper $urlHelper
     */
    public function __construct(array $options, UrlHelper $urlHelper)
    {
        $this->setOptions($options);
        $this->setUrlHelper($urlHelper);
    }

    /**
     * @return UrlHelper
     */
    public function getUrlHelper()
    {
        return $this->urlHelper;
    }

    /**
     * @param UrlHelper $urlHelper
     */
    public function setUrlHelper(UrlHelper $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    /**
     * Returns the configured user authentication method.
     *
     * @return string
     */
    public function getAuthenticationMethod()
    {
        return $this->getOption(self::OPT_METHOD);
    }

    /**
     * Returns the corresponding full authentication URL.
     *
     * @throws MissingOptionException
     * @return string
     */
    public function getAuthenticationUrl()
    {
        $methodName = $this->getAuthenticationMethod();
        if (null === $methodName) {
            throw new MissingOptionException(self::OPT_METHOD);
        }

        $authRoute = $this->getOption(self::OPT_AUTH_ROUTE);
        if (null === $authRoute) {
            throw new MissingOptionException(self::OPT_AUTH_ROUTE);
        }

        return $this->getUrlHelper()->createUrlStringFromRoute($authRoute, array(
            'controller' => $methodName
        ));
    }

    /**
     * Returns the full URL the user will be returned to after authentication.
     *
     * @throws MissingOptionException
     * @return string
     */
    public function getReturnUrl()
    {
        $returnRoute = $this->getOption(self::OPT_RETURN_ROUTE);
        if (null === $returnRoute) {
            throw new MissingOptionException(self::OPT_RETURN_ROUTE);
        }

        return $this->getUrlHelper()->createUrlStringFromRoute($returnRoute);
    }
}