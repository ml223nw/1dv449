<?php

namespace controller;

class ScrapeController {
  
  private $scrapeView;
  private $scraperModel;
  private $layoutView;

  public static $urlBase;
  public static $urlStart = "ScrapeController::startUrl";

  public function __construct() {
    
      $this->layoutView = new \view\LayoutView();
      $this->scrapeView = new \view\ScrapeView();
      $this->scraperModel = new \model\ScraperModel();
  }

  public function Scrape() {
                 
      $_COOKIE[self::$urlStart] = serialize($this->scraperModel->GetUrlOfLinks($_COOKIE[self::$urlBase]));
  
      $getAvailableDaysFromCalendar = $this->scraperModel->GetAvailabilityOfDays(rtrim($_COOKIE[self::$urlBase]
      , "/") . unserialize($_COOKIE[self::$urlStart])[0]);
  
      if (empty($getAvailableDaysFromCalendar)) {
        
          $content = $this->scrapeView->DrawUnavailableDays();
      }

      if (!empty($getAvailableDaysFromCalendar)) {
        
          $getAvailableMoviesFromCinema = $this->scraperModel->GetAvailabilityOfMovies(rtrim($_COOKIE[self::$urlBase]
          , "/") . unserialize($_COOKIE[self::$urlStart])[1], $getAvailableDaysFromCalendar);
          $content = $this->scrapeView->DrawValuesOfScraping($getAvailableMoviesFromCinema);
      }
      echo $this->layoutView->GetLayoutView($content);
      }
    
  }
