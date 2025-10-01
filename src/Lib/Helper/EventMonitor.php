<?php

namespace Jmrashed\Zkteco\Lib\Helper;

use Jmrashed\Zkteco\Lib\ZKTeco;

class EventMonitor
{
    private $zk;
    private $isMonitoring = false;
    private $eventHandlers = [];

    public function __construct(ZKTeco $zk)
    {
        $this->zk = $zk;
    }

    /**
     * Register event handler for specific event type.
     *
     * @param string $eventType Event type name.
     * @param callable $handler Event handler callback.
     */
    public function on($eventType, callable $handler)
    {
        if (!isset($this->eventHandlers[$eventType])) {
            $this->eventHandlers[$eventType] = [];
        }
        
        $this->eventHandlers[$eventType][] = $handler;
    }

    /**
     * Start monitoring events.
     *
     * @param int $timeout Monitoring timeout in seconds (0 = infinite).
     * @return bool Success status.
     */
    public function start($timeout = 0)
    {
        if ($this->isMonitoring) {
            return false;
        }

        $this->isMonitoring = true;
        
        return $this->zk->startEventMonitoring(
            [$this, 'handleEvent'],
            $timeout
        );
    }

    /**
     * Stop monitoring events.
     *
     * @return bool Success status.
     */
    public function stop()
    {
        if (!$this->isMonitoring) {
            return false;
        }

        $this->isMonitoring = false;
        
        return $this->zk->stopEventMonitoring();
    }

    /**
     * Handle incoming event.
     *
     * @param array $event Event data.
     */
    public function handleEvent($event)
    {
        $eventType = $event['type'];
        
        if (isset($this->eventHandlers[$eventType])) {
            foreach ($this->eventHandlers[$eventType] as $handler) {
                call_user_func($handler, $event);
            }
        }
        
        // Call generic handlers
        if (isset($this->eventHandlers['*'])) {
            foreach ($this->eventHandlers['*'] as $handler) {
                call_user_func($handler, $event);
            }
        }
    }

    /**
     * Check if monitoring is active.
     *
     * @return bool Monitoring status.
     */
    public function isMonitoring()
    {
        return $this->isMonitoring;
    }

    /**
     * Get registered event handlers.
     *
     * @return array Event handlers.
     */
    public function getEventHandlers()
    {
        return $this->eventHandlers;
    }

    /**
     * Clear all event handlers.
     */
    public function clearHandlers()
    {
        $this->eventHandlers = [];
    }

    /**
     * Remove event handler for specific event type.
     *
     * @param string $eventType Event type name.
     */
    public function off($eventType)
    {
        unset($this->eventHandlers[$eventType]);
    }
}