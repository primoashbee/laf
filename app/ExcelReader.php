<?php

namespace App;


class ExcelReader
{
    protected $data;
    public $client = array();
    public $cwe;
    public $ppi;

    public function __construct($array)
    {
        $this->data = collect($array['values']);
        foreach($this->data as $data) {

            $this->client[] = array(
                'first_name' => $data[1]
            );
        }
        $this->showData();
    }

    public function showData(){
        dd($this->client);
    }

    
}