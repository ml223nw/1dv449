<?php

namespace view;

class ScrapeView {
  
    private static $thisMovie = "movie";
    private static $thisTime = "time";
    private static $thisDay = "day";

    public function GetScrapingUrl() {
      
        return isset($_POST[self::$postScrapingUrl]);
    }
  
    public function StartScraping() {
      
        return isset($_POST[self::$postScrapingStart]);
    }
    
    public function DrawValuesOfScraping($moviesStringArray) {

      $weekendDays = array("Friday" => "Fredag", 
                           "Saturday" => "Lördag", 
                           "Sunday" => "Söndag");

      $drawThis = "<h2>Följande filmer hittades</h2>";
      
      $drawThis .= "<div>";
      
        foreach ($moviesStringArray as $valueOfMovie) {
          
            $weekendDaysSwe = $weekendDays[$valueOfMovie['movieday']];
            
            $drawThis .= "<div class = 'container'>
            Filmen <b> {$valueOfMovie['movietitle']}
            </b> klockan {$valueOfMovie['time']} på {$weekendDaysSwe}
            <a ". self::$thisMovie . " = {$valueOfMovie['movietitle']} & "
            . self::$thisTime . " = {$valueOfMovie['time']} & " . self::$thisDay .
            "</a>";
        }
        $drawThis .= "</div>";
        return $drawThis;
    }
    
    public function DrawUnavailableDays() {
      
      return "<div>Inga dagar tillgängliga denna helg</div>";
    }
  
}
