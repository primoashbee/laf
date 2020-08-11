<?php

namespace App\Console\Commands;

use App\CWE;
use App\PPI;
use App\Client;
use App\ExcelReader;
use App\Transaction;
use Illuminate\Console\Command;

class FetchSpreadsheetRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:laf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch spreadsheet list then save to local database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new \Google_Client();
        $client->setApplicationName('My PHP App');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');

        // $jsonAuth = public_path('credentials.json');
        $jsonAuth = public_path('credentials-v2.json');
        $client->setAuthConfig($jsonAuth, true);

        $sheets = new \Google_Service_Sheets($client);



        // The range of A2:H will get columns A through H and all rows starting from row 2
        $spreadsheetId = '172nRLbv4cIjyYbphX1XqMnz_Jax69Qt-mQRy7GGxMSg';

        $range = 'A1:DP';
        $transactionRange = 2;
        $isEmpty = true;
        if (Transaction::count() == 0) {
            $range = 'A2:DP';
        }else{
            $transactionRange = Transaction::latest()->first()->range+1;
            $range = 'A'.$transactionRange.':DP';
            $isEmpty = false;
        }
        
        $currentRow = 1;
        $rows = collect($sheets->spreadsheets_values->get($spreadsheetId, $range, ['majorDimension' => 'ROWS']));
        $ctr = 0;
        $clientList = [];
        $batch_id = str_shuffle(uniqid());
        
        foreach ($rows as $key => $value) {
            
            $clients = new ExcelReader($rows[$ctr],$batch_id);
        
            $clientToDb = Client::create($clients->client);
            // PPI::create(array_merge(['client_id' => $clientToDb->id],$clients->ppi));
            // CWE::create(array_merge(['client_id' => $clientToDb->id],$clients->cwe));
            
            $ctr++;
        }


        
        if ($ctr> 0) {
            if ($isEmpty) {
                Transaction::create(
                    [
                    'range' => $transactionRange-1 + $ctr
                    ]
                );
            }else{
                Transaction::create(
                    [
                    'range' => $transactionRange -1 + $ctr
                    ]
                );
            }
        }

        $this->info('Imported!');
    }
}
