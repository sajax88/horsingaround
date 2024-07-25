<?php
declare(strict_types=1);

namespace App\Main\Controllers;

use Phalcon\Mvc\Controller;

/**
 * Inherit from this controller if you need a standard controller for main App
 */
class BaseController extends Controller
{
    protected function initialize()
    {
        $this->view->setTemplateAfter('main');
        $this->view->current_user = $this->session->get('user');
    }
}
