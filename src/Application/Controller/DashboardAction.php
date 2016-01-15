<?php

namespace Application\Controller;

use Framework\AbstractAction;

class DashboardAction extends AbstractAction
{
    public function __invoke()
    {
        return $this->render('dashboard.html.twig');
    }
}
