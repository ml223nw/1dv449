<?php

  require_once('model/ScraperModel.php');
  require_once('view/LayoutView.php');
  require_once('view/ScrapeView.php');
  require_once('controller/ScrapeController.php');

  $urlPost = "url";
  $submitPost = "submit";

  if (isset($_POST[$submitPost])) {
    
      $_COOKIE[\controller\ScrapeController::$urlBase] = $_POST[$urlPost];
      $controller = new \controller\ScrapeController();
      $controller->Scrape();
  }
  else
  {
      $view = new \view\LayoutView();
  
      echo $view->GetLayoutView("<form method='post'><label>Ange URL: </label><input type='text' name='" . $urlPost . "'>
      <input type='submit' name='" . $submitPost . "' value='Skrapa'></form>");
  }