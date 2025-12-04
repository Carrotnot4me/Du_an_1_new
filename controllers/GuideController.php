<?php
require_once './models/GuideModel.php';

class GuideController {

    private $model;

    public function __construct() {
        $this->model = new GuideModel();
    }

    public function index() {
        $tours = $this->model->getAllTours();
        $departures = $this->model->getAllDepartures();
        $guides = $this->model->getGuides();

        include './views/admin/guide.php';
    }
}
