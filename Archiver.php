<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\VisitEvenUnevenTime;

use Piwik\DataArray;

/**
 * Archiver for VisitEvenUnevenTime Plugin
 * @see PluginsArchiver
 */
class Archiver extends \Piwik\Plugin\Archiver
{
    const VISITEVENUNEVENTIME_ARCHIVE_RECORD = "VisitEvenUnevenTime_archive_record";

    /**
     * Daily archive of Even Uneven report. Processes reports for Visits by hour of first time action modulo 2
     */
    public function aggregateDayReport()
    {
        $dataArray = $this->getLogAggregator()->getMetricsFromVisitByDimension("HOUR(log_visit.visit_first_action_time) MOD 2");
        $this->ensureAllRowsExists($dataArray);
        $report = $dataArray->asDataTable()->getSerialized();
        $this->getProcessor()->insertBlobRecord(self::VISITEVENUNEVENTIME_ARCHIVE_RECORD, $report);
    }

    /**
     * Period archiving: sums of daily archives
     */
    public function aggregateMultipleReports()
    {
        $this->getProcessor()->aggregateDataTableRecords(self::VISITEVENUNEVENTIME_ARCHIVE_RECORD);
    }
    
    private function ensureAllRowsExists(DataArray &$array)
    {
        $data = $array->getDataArray();
        for ($i = 0; $i <= 1; $i++) {
            if (empty($data[$i])) {
                $array->sumMetricsVisits($i, DataArray::makeEmptyRow());
            }
        }
    }
}
