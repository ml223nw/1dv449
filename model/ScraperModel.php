<?php

namespace model;

class ScraperModel {
  
  private $movieTitleArray = array();
  
  private function GetScrapeUrl($url) {
    
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      $scrapeData = curl_exec($ch);
      curl_close($ch);
  
      return $scrapeData;
  }
  
    private function GetDomDocumentObj($scrapeData) {
    
      $domDocument = new \DomDocument();
      
      if ($domDocument->loadHTML($scrapeData)) {
        
          return $domDocument;
      }
      else
      {
          die("Fel vid inläsning av HTML");
      }
    
  }

  private function SetMovieTitle($movieTitle) {
    
      foreach ($this->movieTitleArray as $valueOfMovieTitle) {
        
          if ($movieTitle === $valueOfMovieTitle) {
            
              return;
          }
          
      }
      $this->movieTitleArray[] = $movieTitle;
  }

  public function GetUrlOfLinks($url) {
    
      $scrapeData = $this->GetScrapeUrl($url);
      $domDocument = $this->GetDomDocumentObj($scrapeData);
      $links = $domDocument->getElementsByTagName("a");
      
      $pageLinksArray = array();
  
      foreach ($links as $linkValue) {
        
          $pageLinksArray[] = $linkValue->getAttribute('href');
      }
  
      return $pageLinksArray;
  }

  public function GetAvailabilityOfMovies($url, $days) {
    
    $availableMoviesArray = array();
    
    $cinemaPage = $this->GetScrapeUrl($url);
    $domDocument = $this->GetDomDocumentObj($cinemaPage);
    $domXPath = new \DOMXPath($domDocument);
    
    $getMovieOptions = $domXPath->query('//select[@id = "movie"]/option[not(@disabled)]');
    $getDayOptions = $domXPath->query('//select[@id = "day"]/option[not(@disabled)]');

    foreach ($getDayOptions as $dayAttributeId) {
      
        if ($dayAttributeId->nodeValue === "Fredag") {
          
            $dayAttributeId->nodeValue = "Friday";
        }
        
        if ($dayAttributeId->nodeValue === "Lördag") {
          
            $dayAttributeId->nodeValue = "Saturday";
        }
        
        if ($dayAttributeId->nodeValue === "Söndag") {
          
            $dayAttributeId->nodeValue = "Sunday";
        }
      
    }

    foreach ($getDayOptions as $dayAttributeId) {
      
        if (in_array($dayAttributeId->nodeValue, $days)) {
          
          foreach ($getMovieOptions as $movieAttributeId) {
            
                $this->SetMovieTitle($movieAttributeId->nodeValue);
      
                $getMoviesInJson = $this->GetScrapeUrl($url . "/check?day=" . $dayAttributeId->getAttribute('value') ."&movie=" . $movieAttributeId->getAttribute('value'));
      
                if ($movieAttributeId->nodeValue == "Söderkåkar") {
                  
                    $firstMovieArray = $this->JsonDecode($getMoviesInJson, $dayAttributeId, 0);
                }
                
                if ($movieAttributeId->nodeValue == "Fabian Bom") {
                  
                    $secondMovieArray = $this->JsonDecode($getMoviesInJson, $dayAttributeId, 1);
                  
                }
                else
                {
                    $lastMovieArray = $this->JsonDecode($getMoviesInJson, $dayAttributeId, 2);
                }
            
            }
          
        }
        
    }

    $this->AddMovies($firstMovieArray, $availableMoviesArray);
    $this->AddMovies($secondMovieArray, $availableMoviesArray);
    $this->AddMovies($lastMovieArray, $availableMoviesArray);

    return $availableMoviesArray;
    }
    
  private function AddMovies($movies, &$moviesArray) {
    
    foreach ($movies as $jsonMovie) {
      
          if ($jsonMovie['status'] == true) {
            
            $moviesArray[] = $jsonMovie;
          }
      
      }
    
  }

  private function JsonDecode($jsonMovie, $dayAttributeId, $movieTitleIndex) {
    
      $movieArray = json_decode($jsonMovie, true);
  
      foreach ($movieArray as $valueOfMovieArray => $value) {
        
          $movieArray[$valueOfMovieArray]['movietitle'] = $this->movieTitleArray[$movieTitleIndex];
          $movieArray[$valueOfMovieArray]['movieday'] = $dayAttributeId->nodeValue;
      }
      return $movieArray;
  }

  private function GetAvailabilityOfPerson($url) {
    
    $availableDaysArray = array();
    
    $data = $this->GetScrapeUrl($url);

    $domDocumentObj = $this->GetDomDocumentObj($data);

    $days = $domDocumentObj->getElementsByTagName("th");
    $isValueOk = $domDocumentObj->getElementsByTagName("td");

    for ($index = 0; $index < $days->length; $index++) {
      
        if (strtolower($isValueOk->item($index)->nodeValue) == "ok") {
          
            $availableDaysArray[] = $days->item($index)->nodeValue;
        }
          
    }
      return $availableDaysArray;
    }
    
  // returns an array containing all the available days from all calendar persons.
  
  public function GetAvailabilityOfDays($url) {
    
      $pageLinksArray = $this->GetUrlOfLinks($url);
  
      for ($index = 0; $index < sizeof($pageLinksArray); $index++) {
        
          $availableDaysArray[] = $this->GetAvailabilityOfPerson($url . "/" . $pageLinksArray[$index]);
      }
      return call_user_func_array('array_intersect', $availableDaysArray);
  }
  
}
