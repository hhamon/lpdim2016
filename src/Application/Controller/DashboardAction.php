<?php

namespace Application\Controller;

use Framework\AbstractAction;

class DashboardAction extends AbstractAction
{
    public function __invoke()
    {
        $this->denyAccessUnlessAuthenticated();

        return $this->render('dashboard.html.twig');
    }
}
