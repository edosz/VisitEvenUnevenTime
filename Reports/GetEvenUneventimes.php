<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\VisitEvenUnevenTime\Reports;

use Piwik\Piwik;
use Piwik\Plugin\Report;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\VisitEvenUnevenTime\Columns\EvenHour;
use Piwik\View;
use Piwik\Widget\WidgetsList;
use Piwik\Report\ReportWidgetFactory;

/**
 * This class defines a Even Uneven hours report.
 */
class GetEvenUneventimes extends Base
{
    protected function init()
    {
        parent::init();

        $this->name          = Piwik::translate('VisitEvenUnevenTime_EvenUneventimes');
        $this->dimension     = new EvenHour();
        $this->documentation = Piwik::translate('VisitEvenUnevenTime_EvenUneventimesDocumentation');
        $this->order = 42;
        $this->metrics       = array('nb_visits');
        $this->constantRowsCount = true;
        $this->subcategoryId = $this->name;
    }
    
    public function configureView(ViewDataTable $view)
    {
        if (!empty($this->dimension)) {
            $view->config->addTranslations(array('label' => $this->dimension->getName()));
        }
        $view->config->show_search = false;
        $view->config->columns_to_display = array_merge(array('label'), $this->metrics);
    }
}
