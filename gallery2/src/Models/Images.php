<?php

/**
 * Exemple per a M613.
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Model les imatges.
 *
**/


/**
 * Imatges
*/
class Images
{
   private array $images = [
        ["title" => "Via lactea ", "url" => "via-lactea.jpg"],
        ["title" => "Figueres", "url" => "figueres.jpg"],
        ["title" => "Calella", "url" => "calella.jpg"],
        ["title" => "Nova York", "url" => "ny.jpg"],
        ["title" => "Fraser Island", "url" => "platja.jpg"],
        ["title" => "Lake Moraine", "url" => "llac.jpg"],
    ];

    public function get(): array  {
        return $this->images;
    }
     
}