<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PPI extends Model
{
    protected $table = 'ppi_answers';
    protected $fillable = [
        'client_id',
    	'ppi_q_1',
    	'ppi_q_2',
    	'ppi_q_3',
    	'ppi_q_4',
    	'ppi_q_5',
    	'ppi_q_6',
    	'ppi_q_7',
    	'ppi_q_8',
    	'ppi_q_9',
    	'ppi_q_10'
    ];
    public function client(){
        return $this->belongsTo(Client::class);
    }
}
