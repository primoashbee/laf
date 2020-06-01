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
            'first_name' => $row[0],
            'middle_name' => $row[1],
            'last_name' => $row[2],
            'nickname' => $row[3]
        ]);
    }
}
