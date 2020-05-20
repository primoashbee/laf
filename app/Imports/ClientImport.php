<?php

namespace App\Imports;

use App\Client;
use Maatwebsite\Excel\Concerns\ToModel;

class ClientImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Client([
            'Name' => $row[0],
            'ID' => $row[1],
            'Status' => $row[2],
            'Gender' => $row[3]
        ]);
    }
}
