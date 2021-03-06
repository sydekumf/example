<?php

/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category   Appserver
 * @package    Apps
 * @subpackage Example
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Apps\Example\Aspects;

use AppserverIo\Doppelgaenger\Entities\MethodInvocation;

/**
 * AppserverIo\Apps\Example\Aspects\LoggerAspect
 *
 * Aspect which allows for logging within the app's classes
 *
 * @category   Appserver
 * @package    Apps
 * @subpackage Example
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 *
 * @Aspect
 */
class LoggerAspect
{

    /**
     * Pointcut which targets all index actions for all action classes
     *
     * @return null
     *
     * @Pointcut("call(\AppserverIo\Apps\Example\Actions\*->indexAction())")
     */
    public function allIndexActions()
    {
    }

    /**
     * Advice used to log the call to any advised method
     *
     * @param \AppserverIo\Doppelgaenger\Entities\MethodInvocation $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @Before("pointcut(allIndexActions())")
     */
    public function logInfoAdvice(MethodInvocation $methodInvocation)
    {
        $methodInvocation->getContext()
            ->getServletRequest()
            ->getContext()
            ->getInitialContext()
            ->getSystemLogger()
            ->info(sprintf(
                'The method %s::%s is about to be called',
                $methodInvocation->getStructureName(),
                $methodInvocation->getName()
            ));
    }
}
