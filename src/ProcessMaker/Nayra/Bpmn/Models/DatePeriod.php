<?php

namespace ProcessMaker\Nayra\Bpmn\Models;

use DateTime;
use DateInterval;
use DateTimeInterface;
use Exception;

/**
 * Application
 *
 * @package ProcessMaker\Models
 */
class DatePeriod
{
    /**
     * Start date of the period
     *
     * @var DateTime
     */
    public $start;

    public $current;

    /**
     * End date of the period
     *
     * @var DateTime
     */
    public $end;

    /**
     * Interval of the period
     *
     * @var DateInterval
     */
    public $interval;

    /**
     * Number of recurrences of the period
     *
     * @var int
     */
    public $recurrences;

    public $include_start_date = true;

    const INF_RECURRENCES = 0;
    const EXCLUDE_START_DATE = 1;

    public function __construct(...$args)
    {
        $expression = is_string($args[0]) ? $args[0] : null;
        //Improve Repeating intervals (R/start/interval/end) configuration
        if (preg_match('/^R\/([^\/]+)\/([^\/]+)\/([^\/]+)$/', $expression, $repeating)) {
            $this->start = new DateTime($repeating[1]);
            $this->interval = new DateInterval($repeating[2]);
            $this->end = new DateTime($repeating[3]);
            $this->recurrences = self::INF_RECURRENCES;
        //Improve Repeating intervals (R[n]/start/interval) or (R[n]/interval/end) configuration
        } elseif (preg_match('/^R(\d*)\/([^\/]+)\/([^\/]+)$/', $expression, $repeating)) {
            $withoutStart = substr($repeating[2], 0, 1) === 'P';
            $this->start = $withoutStart ? null : new DateTime($repeating[2]);
            $this->interval = new DateInterval($repeating[$withoutStart ? 2 : 3]);
            $this->end = $withoutStart ? new DateTime($repeating[3]) : null;
            $this->recurrences = $repeating[1] ? $repeating[1] + 1 : self::INF_RECURRENCES;
        //Improve Repeating intervals (R[n]/start/interval) configuration
        } elseif (preg_match('/^R(\d*)\/([^\/]+)$/', $expression, $repeating)) {
            $this->start = null;
            $this->interval = new DateInterval($repeating[2]);
            $this->end = null;
            $this->recurrences = $repeating[1] ? $repeating[1] + 1 : self::INF_RECURRENCES;
        } elseif (count($args) === 3 && is_array($args[2])) {
            $this->start = $args[0];
            $this->interval = $args[1];
            $this->end = $args[2][0];
            $this->recurrences = $args[2][1] + 1;
        } elseif (count($args) === 2 || count($args) === 3) {
            $this->start = $args[0];
            $this->interval = $args[1];
            $this->end = isset($args[2]) && $args[2] instanceof DateTimeInterface ? $args[2] : null;
            $this->recurrences = isset($args[2]) && is_int($args[2]) ? $args[2] + 1 : self::INF_RECURRENCES;
        } else {
            throw new Exception('Invalid DatePeriod definition');
        }
        // Validate properties
        if (isset($this->start) && !($this->start instanceof DateTimeInterface)) {
            throw new Exception('Invalid DatePeriod::start definition');
        }
        if (!($this->interval instanceof DateInterval)) {
            throw new Exception('Invalid DatePeriod::interval definition');
        }
        if (isset($this->end) && !($this->end instanceof DateTimeInterface)) {
            throw new Exception('Invalid DatePeriod::end definition');
        }
        if (!($this->recurrences >= 0)) {
            throw new Exception('Invalid DatePeriod::recurrences definition');
        }
    }
}