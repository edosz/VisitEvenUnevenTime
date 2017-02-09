<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\VisitEvenUnevenTime\tests\Integration;

use Piwik\Tests\Framework\TestCase\IntegrationTestCase;
use Piwik\Tests\Framework\Fixture;
use Piwik\API\Request;
use Piwik\Tests\Framework\Mock\FakeAccess;
use Piwik\Date;
use Piwik\Tracker;

/**
 * @group VisitEvenUnevenTime
 * @group VisitEvenUnevenTest
 * @group Plugins
 */
class VisitEvenUnevenTest extends IntegrationTestCase
{
    public $date = '2017-01-01';
    public $time = '00:00:00';
    public $idSite = 1;
    private $visitCounter = 0;
    
    public function setUp()
    {
        parent::setUp();
        $this->setSuperUser();
        
        Fixture::createSuperUser();
        
        if (!Fixture::siteCreated($this->idSite)) {
            Fixture::createWebsite($this->date.' '.$this->time);
        }
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * 
     */
    public function testEmptyDayReport()
    {
        $this->checkResults(0, 0);
    }
    
    /**
     * 
     */
    public function testHalfDayReport()
    {
        $tracker = $this->getTracker();
        $this->generateVisit($tracker, '01:00:00');
        $this->generateVisit($tracker, '01:20:44');
        $this->generateVisit($tracker, '01:50:12');
        $this->generateVisit($tracker, '02:10:11');
        $this->generateVisit($tracker, '02:30:13');
        $this->generateVisit($tracker, '03:00:00');
        $this->checkResults(2,4);
    }
        
    
    /**
     * 
     */
    public function testFullDay()
    {
        $tracker = $this->getTracker();
        $this->generateVisit($tracker, '00:00:00');
        $this->generateVisit($tracker, '12:20:44');
        $this->generateVisit($tracker, '13:50:12');
        $this->generateVisit($tracker, '15:10:11');
        $this->generateVisit($tracker, '21:30:13');
        $this->generateVisit($tracker, '23:30:59');
        $this->generateVisit($tracker, '24:00:00');
        $this->checkResults(2,4);
    }
    
    /**
     *
     */
    public function testDayBoundaries()
    {
        $tracker = $this->getTracker();
        $this->generateVisit($tracker, '00:00:00');
        $this->generateVisit($tracker, '12:00:00');
        $this->generateVisit($tracker, '23:59:59');
        $this->generateVisit($tracker, '24:00:00');
        $this->checkResults(2,1);
    }
    
    /**
     * @param number $evenCount
     * @param number $unevenCount
     */
    private function checkResults($evenCount, $unevenCount)
    {
        $visits = $this->getEvenAndUnevenVisits();
        $this->assertEquals($evenCount, $visits['even']);
        $this->assertEquals($unevenCount, $visits['uneven']);
    }
    
    
    /**
     * @param Tracker $tracker
     * @param string $time
     */
    private function generateVisit($tracker, $time)
    {
        $tracker->setForceVisitDateTime($this->date.' '.$time);
        $tracker->setForceNewVisit();
        $tracker->doTrackPageView('site visit '.($this->visitCounter++));
    }
    
    /**
     * @return number[]
     */
    private function getEvenAndUnevenVisits()
    {
        $report = $this->generateReport();
        $table = $report['reportData'];
        
        $evenRow = $table->getRowFromLabel('VisitEvenUnevenTime_EvenHourLabel');
        $unevenRow = $table->getRowFromLabel('VisitEvenUnevenTime_UnevenHourLabel');
        
        return [
          'even' => $evenRow ? $evenRow->getColumn('nb_visits') : 0,
          'uneven' => $unevenRow ? $unevenRow->getColumn('nb_visits') : 0
        ];
    }
    
    /**
     * @return mixed|\Piwik\DataTable|\Piwik\API\Map|string
     */
    private function generateReport()
    {
        return Request::processRequest('API.getProcessedReport', array(
            'idSite' => $this->idSite,
            'period' => 'day',
            'date'   => $this->date,
            'apiModule' => 'VisitEvenUnevenTime',
            'apiAction' => 'getEvenUneventimes'
        ));
    }
    
    /**
     * @return Tracker
     */
    private function getTracker()
    {
        $tracker = Fixture::getTracker($this->idSite, $this->date.' '.$this->time, true, true);
        $tracker->setTokenAuth(Fixture::getTokenAuth());
        return $tracker;
    }
    

    protected function setSuperUser()
    {
        FakeAccess::$superUser = true;
    }


    public function provideContainerConfig()
    {
        return array(
            'Piwik\Access' => new FakeAccess()
        );
    }
}
