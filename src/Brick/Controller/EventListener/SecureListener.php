<?php

namespace Brick\Controller\EventListener;

use Brick\Event\Event;
use Brick\Application\Event\RouteMatchedEvent;
use Brick\Http\Exception\HttpRedirectException;

use Doctrine\Common\Annotations\Reader;

/**
 * Configures the HTTP(S) protocol allowed on a controller with annotations.
 *
 * If the Secure annotation is present on a controller class or method, HTTPS is enforced.
 * If the annotation is not present, both protocols are allowed, unless $forceUnsecured has been set,
 * in which case HTTP is enforced.
 *
 * Protocol is enforced with a 301 redirect.
 */
class SecureListener extends AbstractAnnotationListener
{
    /**
     * @var boolean
     */
    private $forceUnsecured = false;

    /**
     * Class constructor.
     *
     * @param Reader  $annotationReader
     * @param boolean $forceUnsecured
     */
    public function __construct(Reader $annotationReader, $forceUnsecured = false)
    {
        parent::__construct($annotationReader);

        $this->forceUnsecured = $forceUnsecured;
    }

    /**
     * {@inheritdoc}
     */
    public function handleEvent(Event $event)
    {
        if ($event instanceof RouteMatchedEvent) {
            $controller = $event->getRouteMatch()->getControllerReflection();
            $request = $event->getRequest();

            $secure = $this->hasControllerAnnotation($controller, 'Brick\Controller\Annotation\Secure');

            if ($secure != $request->isSecure()) {
                if ($secure || $this->forceUnsecured) {
                    $url = preg_replace_callback('/^https?/', function (array $matches) {
                        return $matches[0] == 'http' ? 'https' : 'http';
                    }, $request->getUrl());

                    throw new HttpRedirectException($url, 301);
                }
            }
        }
    }
}
