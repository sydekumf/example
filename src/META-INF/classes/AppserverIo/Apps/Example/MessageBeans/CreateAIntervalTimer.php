<?php

/**
 * AppserverIo\Apps\Example\MessageBeans\CreateAIntervalTimer
 *
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
 * @author     Tim Wagner <tw@appserver.io>
 * @copyright  2014 TechDivision GmbH <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/appserver-io-apps/example
 * @link       http://www.appserver.io
 */

namespace AppserverIo\Apps\Example\MessageBeans;

use AppserverIo\Lang\String;
use AppserverIo\Psr\EnterpriseBeans\TimerInterface;
use AppserverIo\Psr\MessageQueueProtocol\Message;
use AppserverIo\Appserver\MessageQueue\Receiver\AbstractReceiver;
use AppserverIo\Appserver\PersistenceContainer\TimerServiceContext;

/**
 * This is the implementation of a message bean that simply creates and starts an interval timer.
 *
 * @category   Appserver
 * @package    Apps
 * @subpackage Example
 * @author     Tim Wagner <tw@appserver.io>
 * @copyright  2014 TechDivision GmbH <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/appserver-io-apps/example
 * @link       http://www.appserver.io
 *
 * @MessageDriven
 */
class CreateAIntervalTimer extends AbstractReceiver
{

    /**
     * Will be invoked when a new message for this message bean will be available.
     *
     * @param \AppserverIo\Psr\MessageQueueProtocol\Message $message   A message this message bean is listen for
     * @param string                                        $sessionId The session ID
     *
     * @return void
     * @see \AppserverIo\Psr\MessageQueueProtocol\Receiver::onMessage()
     */
    public function onMessage(Message $message, $sessionId)
    {

        // load the timer service
        $timerServiceRegistry = $this->getApplication()->getManager(TimerServiceContext::IDENTIFIER);
        $timerService = $timerServiceRegistry->locate(substr(strrchr(__CLASS__, '\\'), 1));

        // our single action timer should be invoked 10 seconds from now, every 1 second
        $initialExpiration = 10000000;
        $intervalDuration = 1000000;

        // we create the interval timer
        $timerService->createIntervalTimer($initialExpiration, $intervalDuration, new String($message->getMessage()));

        // log a message that the single action timer has been successfully created
        $this->getApplication()->getInitialContext()->getSystemLogger()->info(
            sprintf(
                'Successfully created a interval timer starting in %d seconds and a interval of %d seconds',
                $initialExpiration / 1000000,
                $intervalDuration / 1000000
            )
        );

        // update the message monitor for this message
        $this->updateMonitor($message);
    }

    /**
     * Invoked by the container upon timer expiration.
     *
     * @param \AppserverIo\Psr\EnterpriseBeans\TimerInterface $timer Timer whose expiration caused this notification
     *
     * @return void
     * @Timeout
     **/
    public function timeout(TimerInterface $timer)
    {

        // log a message with the directory name we found
        $this->getApplication()->getInitialContext()->getSystemLogger()->info(
            sprintf(
                '%s has successfully been invoked by @Timeout annotation to watch directory %s',
                __METHOD__,
                $timer->getInfo()
            )
        );
    }
}
