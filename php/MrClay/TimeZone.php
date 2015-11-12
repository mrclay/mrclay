<?php

/**
 * Eases handling dates/times in different timezones by allowing you to create
 * a timezone context. This can be helpful when you need to, e.g., parse date
 * strings created in a different timezone.
 *
 * Currently the class only provides strtotime() and date(), but this is usually
 * sufficient.
 *
 * <code>
 * // parse a date string from Eastern Standard Time (no DST)
 * $tz = new MrClay_TimeZone(-5);
 * $time = $tz->strtotime('2007-06-01 08:00:00');
 *
 * // display in New York time
 * $tz = new MrClay_TimeZone('America/New_York');
 * echo $tz->date('G', $time); // echoes '9'
 * </code>
 *
 * @author Steve Clay <steve@mrclay.org>
 * @license http://www.opensource.org/licenses/mit-license.php  MIT License
 */
class MrClay_TimeZone {

    private $dtz = null;
    private $time = null;
    private $tz = null;

    /**
     * Create a "timezone shift" object.
     *
     * @param string $tz timezone. This can either be a string as given by
     * http://twiki.org/cgi-bin/xtra/tzdatepick.html or a GMT offset like -5.
     * -5 would be converted to the string "Etc/GMT+5" for you.
     *
     * @param int $time optional current timestamp in case you want to "set" time
     */
    public function __construct($tz, $time = null)
    {
        if (is_numeric($tz)) {
            $this->tz = (0 == $tz)
                ? 'Etc/GMT'
                : 'Etc/GMT' . ($tz > 0 ? '-' : '+') . abs($tz);
        } else {
            $this->tz = $tz;
        }
        $this->dtz = date_default_timezone_get();
        $this->time = ($time !== null)
            ? $time
            : (isset($_SERVER['REQUEST_TIME'])
                ? $_SERVER['REQUEST_TIME']
                : time()
            );
    }

    /**
     * Return the output of a function run with TZ set to the timezone given in the constructor.
     *
     * @param callable $func
     * @return mixed
     */
    public function inContext($func)
    {
        date_default_timezone_set($this->tz);
        $ret = call_user_func($func);
        date_default_timezone_set($this->dtz);

        return $ret;
    }

    /**
     * Format a "local" time/date according to the timezone given in the constructor.
     *
     * @link http://www.php.net/manual/en/function.date.php
     */
    public function date($format, $time = null)
    {
        if (null === $time) {
            $time = $this->time;
        }
        return $this->inContext(function () use ($format, $time) {
            return date($format, $time);
        });
    }

    /**
     * Parse about any English textual datetime description into a Unix timestamp local to
     * the timezone given in the constructor.
     *
     * @link http://www.php.net/manual/en/function.strtotime.php
     */
    public function strtotime($strTime, $now = null)
    {
        return $this->inContext(function () use ($strTime, $now) {
            return strtotime($strTime, $now);
        });
    }

    /**
     * Get Unix timestamp for a date in the timezone given in the constructor.
     *
     * @link http://php.net/manual/en/function.mktime.php
     */
    public function mktime($h = null, $m = null, $s = null, $mm = null, $dd = null, $yy = null)
    {
        return $this->inContext(function () use ($h, $m, $s, $mm, $dd, $yy) {
            return mktime($h, $m, $s, $mm, $dd, $yy);
        });
    }

    /**
     * Get date/time information local to the timezone given in the constructor.
     *
     * @link http://php.net/manual/en/function.getdate.php
     */
    public function getdate($time = null)
    {
        if (null === $time) {
            $time = $this->time;
        }
        return $this->inContext(function () use ($time) {
            return getdate($time);
        });
    }

    /**
     * Get the local time in the timezone given in the constructor.
     *
     * @link http://php.net/manual/en/function.localtime.php
     */
    public function localtime($time = null, $isAssoc = false)
    {
        if (null === $time) {
            $time = $this->time;
        }
        return $this->inContext(function () use ($time, $isAssoc) {
            return localtime($time, $isAssoc);
        });
    }

    /**
     * Format a local time/date according to the timezone given in the constructor.
     *
     * @link http://php.net/manual/en/function.strftime.php
     */
    public function strftime($format, $time = null)
    {
        if (null === $time) {
            $time = $this->time;
        }
        return $this->inContext(function () use ($format, $time) {
            return strftime($format, $time);
        });
    }
}
