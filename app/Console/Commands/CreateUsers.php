<?php

namespace App\Console\Commands;

use App\Office;
use App\GDriveUser;
use Illuminate\Console\Command;

class CreateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create users from gdrive sheets';

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
        $offices = Office::where('level','unit')->orderBy('name','asc')->get();
        $client = new \Google_Client();
        $client->setApplicationName('My PHP App');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');

        // $jsonAuth = public_path('credentials.json');
        $jsonAuth = public_path('credentials-v2.json');
        $client->setAuthConfig($jsonAuth, true);

        $sheets = new \Google_Service_Sheets($client);



        // The range of A2:H will get columns A through H and all rows starting from row 2
        $spreadsheetId = '1ymdMHX48AeXIhmnSrLiG0nYQ5ZmtosZC52tjtPqkSOM';
        $range = 'A1:G';
        
        
        $rows = collect($sheets->spreadsheets_values->get($spreadsheetId, $range, ['majorDimension' => 'ROWS']));
        
        
        $gdrive_users = new GDriveUser($rows);
        $gdrive_users->createUsers();

        $this->info('Imported!');
    }
}
