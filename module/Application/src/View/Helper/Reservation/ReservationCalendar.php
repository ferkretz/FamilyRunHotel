<?php

namespace Application\View\Helper\Reservation;

use Zend\View\Helper\AbstractHelper;

class ReservationCalendar extends AbstractHelper {

    protected $currentYear;
    protected $currentMonth;
    protected $selectedYear;
    protected $selectedMonth;
    protected $yearLimit;
    protected $id;

    public function __construct() {
        $time = mktime();
        $this->year = $this->currentYear = date('Y', $time);
        $this->month = $this->currentMonth = date('n', $time);
    }

    public function setDate($year,
                            $month,
                            $yearLimit = 10) {
        $this->selectedYear = $year < $this->currentYear ? $this->currentYear : ($year > $this->currentYear + $yearLimit - 1 ? $this->currentYear + $yearLimit - 1 : $year);
        $this->selectedMonth = (($this->selectedYear == $this->currentYear) && ($month < $this->currentMonth)) ? $this->currentMonth : $month;
        $this->yearLimit = $yearLimit;
    }

    public function setId($id) {
        $this->id = $id;
    }

    private function renderYearButton() {
        $url = $this->getView()->plugin('url');

        $html = '<div class="btn-group">'
                . '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">'
                . $this->selectedYear . ' <span class="caret"></span>'
                . '</button>'
                . '<ul class="dropdown-menu" style="max-height: 10em; overflow: auto">';
        for ($i = 0; $i < $this->yearLimit; $i++) {
            $year = $this->currentYear + $i;
            if ($year != $this->selectedYear) {
                $html .= '<li><a href="' . $url(NULL, ['action' => 'edit', 'id' => $this->id, 'year' => $year, 'month' => $this->selectedMonth]) . '">' . $year . '</a></li>';
            }
        }
        $html .= '</ul>'
                . '</div>';

        return $html;
    }

    private function renderMonthButton() {
        $monthNames = [];
        $fmt = new \IntlDateFormatter(NULL, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, null, null, "LLLL");
        $timestamp = strtotime('January');
        for ($i = 1; $i <= 12; $i++) {
            $monthNames[$i] = $fmt->format($timestamp);
            $timestamp = strtotime('+1 month', $timestamp);
        }
        $this->firstMonth = $this->selectedYear == $this->currentYear ? $this->currentMonth : 1;
        $url = $this->getView()->plugin('url');

        $html = '<div class="btn-group">'
                . '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">'
                . $monthNames[$this->selectedMonth] . ' <span class="caret"></span>'
                . '</button>'
                . '<ul class="dropdown-menu" style="max-height: 10em; overflow: auto">';
        for ($i = $this->firstMonth; $i < 12; $i++) {
            if ($i != $this->selectedMonth) {
                $html .= '<li><a href="' . $url(NULL, ['action' => 'edit', 'id' => $this->id, 'year' => $this->year, 'month' => $i]) . '">' . $monthNames[$i] . '</a></li>';
            }
        }
        $html .= '</ul>'
                . '</div>';

        return $html;
    }

    public function render() {
        $firstDay = ($this->selectedYear == $this->currentYear) && ($this->selectedMonth == $this->currentMonth) ? date('j', mktime()) : 1;
        $html = '<table class="table-calendar">'
                . '<tr>'
                . '<td colspan="4" class="group-cell">' . $this->renderYearButton() . '</td>'
                . '<td colspan="4" class="group-cell">' . $this->renderMonthButton() . '</td>'
                . '</tr>'
                . '<tr>'
                . '<th class="clickable-table"></th>';
        foreach ($this->weekDayNames() as $weekday) {
            $html .= '<th class="clickable-column">' . $weekday . '</th>';
        }
        $html .= '</tr>';
        foreach ($this->weeks() as $week => $days) {
            $html .= '<tr>'
                    . '<th class="clickable-row">' . $week . '</th>';
            for ($i = 1; $i <= 7; $i++) {
                if (isset($days[$i])) {
                    $html .= '<td class="pending">'
                            . '<label>'
                            . '<input class="date-checkbox" type="checkbox" name="days&#x5B;&#x5D;" value="' . $days[$i] . '"' . ($days[$i] < $firstDay ? ' disabled' : '') . '>'
                            . '</label>'
                            . '</td>';
                } else {
                    $html .= '<td></td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        echo $html;
    }

    protected function weekDayNames() {
        $weekDayNames = [];

        $fmt = new \IntlDateFormatter(NULL, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, null, null, "ccc");
        $timestamp = strtotime('next Monday');
        for ($i = 0; $i < 7; $i++) {
            $weekDayNames[] = $fmt->format($timestamp);
            $timestamp = strtotime('+1 day', $timestamp);
        }

        return $weekDayNames;
    }

    protected function weeks() {
        $weeks = [];

        $i = 1;
        while (TRUE) {
            $week = date('W', mktime(0, 0, 0, $this->selectedMonth, $i, $this->selectedYear));
            $weekDay = date('N', mktime(0, 0, 0, $this->selectedMonth, $i, $this->selectedYear));
            $day = date('d', mktime(0, 0, 0, $this->selectedMonth, $i, $this->selectedYear));

            if ($i > $day) {
                break;
            }

            $weeks[$week][$weekDay] = $day;
            $i++;
        }

        return $weeks;
    }

}
