<?php

namespace ProcessMaker\Nayra\Bpmn\Events;

use ProcessMaker\Nayra\Contracts\Bpmn\ProcessInterface;
use ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface;

/**
 * Event raised when process instance is created.
 *
 * @package ProcessMaker\Nayra\Bpmn\Events
 */
class ProcessInstanceCreatedEvent
{
    /**
     * @var \ProcessMaker\Nayra\Contracts\Bpmn\ProcessInterface
     */
    public $process;

    /**
     * @var \ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface
     */
    public $instance;

    /**
     * ProcessInstanceCreatedEvent constructor.
     *
     * @param ProcessInterface $process
     * @param ExecutionInstanceInterface $instance
     */
    public function __construct(ProcessInterface $process, ExecutionInstanceInterface $instance)
    {
        $this->process = $process;
        $this->instance = $instance;
    }
}
