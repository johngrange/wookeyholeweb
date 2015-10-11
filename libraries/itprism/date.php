<?php
/**
 * @package      ITPrism
 * @subpackage   Dates
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

/**
 * This is a class that provides functionality for managing dates.
 *
 * @package      ITPrism
 * @subpackage   Dates
 */
class ITPrismDate extends JDate
{
    /**
     * Return last date.
     *
     * <code>
     * $date = new ITPrismDate();
     *
     * $yesterday = $date->getLastDay();
     * </code>
     *
     * @return ITPrismDate
     */
    public function getLastDay()
    {
        $day = clone $this;
        $day->modify("yesterday");

        return $day;
    }

    /**
     * Return the begin of a day.
     *
     * <code>
     * $date = new ITPrismDate();
     *
     * $beginOfDay = $date->getBeginOfDay();
     * </code>
     *
     * @return ITPrismDate
     */
    public function getBeginOfDay()
    {
        $day = clone $this;

        $day->setTime(0, 0, 0);

        return $day;
    }

    /**
     * Return the end of a day.
     *
     * <code>
     * $date = new ITPrismDate();
     *
     * $endOfDay = $date->getEndOfDay();
     * </code>
     *
     * @return ITPrismDate
     */
    public function getEndOfDay()
    {
        $day = clone $this;

        $day->setTime(0, 0, 0);

        $endOfDay = clone $day;
        $endOfDay->modify('tomorrow');
        $endOfDay->modify('1 second ago');

        return $endOfDay;
    }

    /**
     * Return a day of last week.
     *
     * <code>
     * $date = new ITPrismDate();
     *
     * $lastWeekDay = $date->getLastWeek();
     * </code>
     *
     * @return ITPrismDate
     */
    public function getLastWeek()
    {
        $day = clone $this;
        $day->modify("7 days ago");

        return $day;
    }

    /**
     * Return the first day of last week.
     *
     * <code>
     * $date = new ITPrismDate();
     *
     * $beginOfWeek = $date->getBeginOfWeek();
     * </code>
     *
     * @return ITPrismDate
     */
    public function getBeginOfWeek()
    {
        $monday = clone $this;
        $monday->modify(('Sunday' == $monday->format('l')) ? 'Monday last week' : 'Monday this week');

        return $monday;
    }

    /**
     * Return the last day of last week.
     *
     * <code>
     * $date = new ITPrismDate();
     *
     * $endOfWeek = $date->getEndOfWeek();
     * </code>
     *
     * @return ITPrismDate
     */
    public function getEndOfWeek()
    {
        $sunday = clone $this;
        $sunday->modify('Sunday this week');

        return $sunday;
    }

    /**
     * Return the first day of a month.
     *
     * <code>
     * $date = new ITPrismDate();
     *
     * $beginOfMonth = $date->getBeginOfMonth();
     * </code>
     *
     * @return ITPrismDate
     */
    public function getBeginOfMonth()
    {
        $firstDay = clone $this;
        $firstDay->modify('first day of this month');

        return $firstDay;
    }

    /**
     * Return the last day of a month.
     *
     * <code>
     * $date = new ITPrismDate();
     *
     * $endOfMonth = $date->getEndOfMonth();
     * </code>
     *
     * @return ITPrismDate
     */
    public function getEndOfMonth()
    {
        $lastDay = clone $this;
        $lastDay->modify('last day of this month');

        return $lastDay;
    }

    /**
     * Return the first day of an year.
     *
     * <code>
     * $date = new ITPrismDate();
     *
     * $beginOfYear = $date->getBeginOfYear();
     * </code>
     *
     * @return ITPrismDate
     */
    public function getBeginOfYear()
    {
        $firstDay = clone $this;
        $firstDay->modify('first day of this year');

        return $firstDay;
    }

    /**
     * Return the last day of an year.
     *
     * <code>
     * $date = new ITPrismDate();
     *
     * $endOfYear = $date->getEndOfYear();
     * </code>
     *
     * @return ITPrismDate
     */
    public function getEndOfYear()
    {
        $lastDay = clone $this;
        $lastDay->modify('last day of this year');

        return $lastDay;
    }

    /**
     * Return a period between two dates in days.
     *
     * <code>
     * $date  = new ITPrismDate();
     *
     * $date1 = new ITPrismDate("now");
     * $date2 = new ITPrismDate("01-01-2020);
     *
     * $period = $date->getDaysPeriod($date1, $date2);
     * </code>
     *
     * @param JDate $date1
     * @param JDate $date2
     *
     * @return DatePeriod
     */
    public function getDaysPeriod(JDate $date1, JDate $date2)
    {
        $period = new DatePeriod(
            $date1,
            new DateInterval('P1D'),
            $date2
        );

        return $period;
    }

    /**
     * Calculate end date from starting one and some days.
     *
     * <code>
     * $days   = 30;
     *
     * $date   = new ITPrismDate("now");
     *
     * $endDate = $date->calculateEndDate();
     * </code>
     *
     * @param int    $days This is period in days.
     *
     * @return ITPrismDate
     */
    public function calculateEndDate($days)
    {
        $endDate = clone $this;
        $endDate->modify("+" . (int)$days . " days");

        return $endDate;
    }

    /**
     * Check whether the date is part of the current week.
     *
     * <code>
     * $date   = new ITPrismDate("05-06-2014");
     *
     * if ($date->isCurrentWeekDay()) {
     * ...
     * }
     * </code>
     *
     * @return bool
     */
    public function isCurrentWeekDay()
    {
        $today        = new ITPrismDate();
        $startOfWeek  = $today->getBeginOfWeek();
        $endOfWeek    = $today->getEndOfWeek();

        if ($startOfWeek <= $this and $this <= $endOfWeek) {
            return true;
        } else {
            return false;
        }
    }
}
