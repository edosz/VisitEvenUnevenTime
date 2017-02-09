<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */

namespace Piwik\Plugins\VisitEvenUnevenTime;

use Piwik\Piwik;

/**
 * Check if label is for uneven hour
 * @param $label
 * @return bool
 */
function isUnevenHourLabel($label)
{
    return (bool)$label;
}
/**
 * Returns even or uneven translated label
 * @param $label
 * @return string
 */
function getEvenHourLabel($label)
{
    return isUnevenHourLabel($label) ? Piwik::translate('VisitEvenUnevenTime_UnevenHourLabel') : Piwik::translate('VisitEvenUnevenTime_EvenHourLabel');
}