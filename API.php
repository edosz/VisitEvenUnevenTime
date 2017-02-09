<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\VisitEvenUnevenTime;

use Piwik\DataTable;
use Piwik\DataTable\Row;
use Piwik\Piwik;
use Piwik\Archive;

require_once PIWIK_INCLUDE_PATH . '/plugins/VisitEvenUnevenTime/functions.php';

/**
 * The VisitEvenUnevenTime API lets you access reports about your Visits even and uneven hours
 */
class API extends \Piwik\Plugin\API
{

    
    /**
     * @param int    $idSite
     * @param string $period
     * @param string $date
     * @param bool|string $segment
     * @return DataTable
     */
    public function getEvenUneventimes($idSite, $period, $date, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);
        
        $archive = Archive::build($idSite, $period, $date, $segment);
        $dataTable = $archive->getDataTable(Archiver::VISITEVENUNEVENTIME_ARCHIVE_RECORD);
        $dataTable->filter('Sort', array('nb_visits', 'desc', true, false));
        $dataTable->queueFilter('ColumnCallbackReplace', array('label',__NAMESPACE__ . '\getEvenHourLabel'));
        $dataTable->queueFilter('ReplaceColumnNames');
        
        return $dataTable;
    }
}
