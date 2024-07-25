<?php
declare(strict_types=1);


namespace App\Main\Controllers;

use App\Main\Models\Requests;

class IndexController extends BaseController {
    public function initialize() {
        parent::initialize();

        $this->tag->title()->set('Requests list');
    }

    public function indexAction(): void {
        $requests = Requests::find();
        $this->view->person_requests = $requests;
    }
}
