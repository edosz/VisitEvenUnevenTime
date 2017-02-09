<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\VisitEvenUnevenTime\tests\Unit;

require_once PIWIK_INCLUDE_PATH . '/plugins/VisitEvenUnevenTime/functions.php';

/**
 * @group VisitEvenUnevenTime
 * @group EvenHourLabelTest
 * @group Plugins
 */
class EvenHourLabelTest extends \PHPUnit_Framework_TestCase
{
    public function testUnevenHourLabel()
    {
        $this->assertEquals(\Piwik\Plugins\VisitEvenUnevenTime\isUnevenHourLabel(0), false);
        $this->assertEquals(\Piwik\Plugins\VisitEvenUnevenTime\isUnevenHourLabel('0'), false);
        $this->assertEquals(\Piwik\Plugins\VisitEvenUnevenTime\isUnevenHourLabel(1), true);
        $this->assertEquals(\Piwik\Plugins\VisitEvenUnevenTime\isUnevenHourLabel('1'), true);
    }

}
