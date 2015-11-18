<?php

namespace view;

class LayoutView {

  public function GetLayoutView($body) {
    
      return "<!DOCTYPE html> <html>
              <head>
              <meta charset = 'utf-8'>
              <title> Scraper </title> </head> <div class = 'container'> 
              {$body}
              </div> </body> </html>";
      }
    
  }
