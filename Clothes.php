<?php 
include 'keys.php';
require 'vendor/autoload.php';

class Clothes
{
    public $index;

    public function __construct($indexName)
    {
        // APPLICATION_ID & API_KEY are defined in keys.php
        $client = new \AlgoliaSearch\Client(APPLICATION_ID, API_KEY);
        $this->index = $client->initIndex($indexName);
    }

    public function getIndex() {
        return $this->index;
    }

    public function getInformationClothes($clothes = "dress", $hitsPerPage = 10)
    {
        $query = $this->index->search($clothes, 
                            array("hitsPerPage" => $hitsPerPage, 
                                "attributesToRetrieve"=>"Name,Price,Designer"));
        if($this->validateNbHits($query)) {
            return $this->arrayInformation($query);
        } else {
            return "Please, try with other option";
        }
    }

    public function validateNbHits($query)
    {
        if($query["nbHits"] == 0) {
            return 0;
        }
        return 1;
    }
    public function arrayInformation($query)
    {
        $information = array();
        for($i = 0; $i < sizeof($query["hits"]); $i++) {
          $information[$i] = array("name"     => $query["hits"][$i]["Name"], 
                                   "price"    => $query["hits"][$i]["Price"],
                                   "designer" => $query["hits"][$i]["Designer"]);
        }
        return $information;
    }

    public function arrayToJSON($array)
    {
        if(is_array($array)) {
            return json_encode(array("items" =>$array), JSON_PRETTY_PRINT);
        }
    }
}