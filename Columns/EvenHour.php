<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\VisitEvenUnevenTime\Columns;

use Piwik\Columns\Dimension;
use Piwik\Piwik;
use Piwik\Plugin\Segment;

class EvenHour extends Dimension
{

    public function getName()
    {
        return Piwik::translate('VisitEvenUnevenTime_EvenHour');
    }
    
    protected function configureSegments()
    {
        $segment = new Segment();
        $segment->setSegment('visitEvenHour');
        $segment->setCategory('General_Visit');
        $segment->setName('VisitEvenUnevenTime_EvenHour');
        $segment->setSqlSegment('HOUR(log_visit.visit_first_action_time) MOD 2');
        $segment->setAcceptedValues('0, 1');
        $this->addSegment($segment);
    }
}