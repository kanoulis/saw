<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use Carbon\Carbon;

class ClientPaymentsExportLatest30Days extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client_payments:export_latest_30_days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export latest client payments of the past 30 days';

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
     * @return int
     */
    public function handle()
    {
        $file_url = 'storage/client_payments_export_latest_30_days.csv';
        $file = fopen($file_url, 'w');

        fputcsv($file, ['id', 'name', 'surname', 'latest_payment', 'amount']);

        /* DB
        $clients = DB::table('clients')
            ->selectRaw('clients.id, clients.name, clients.surname, payments.created_at, payments.amount')
            ->leftJoin('payments', 'clients.id', '=', 'payments.user_id')
            ->whereRaw('payments.created_at = (SELECT MAX(created_at) FROM payments p1 WHERE p1.user_id = payments.user_id)')
            ->where('payments.created_at', '>', Carbon::now()->subDays(30))
            ->get();
        */
        $clients = Client::join('payments', 'payments.user_id', '=', 'clients.id')
            ->select(['clients.id', 'clients.name', 'clients.surname', 'payments.created_at', 'payments.amount'])
            ->orderBy('payments.created_at')
            ->latest('payments.created_at')
            ->where('payments.created_at', '>', Carbon::now()->subDays(30))
            ->get()->unique();

        foreach ($clients as $client) {
            $row['id'] = $client->id;
            $row['name'] = $client->name;
            $row['surname'] = $client->surname;
            $row['latest_payment'] = $client->created_at;
            $row['amount'] = $client->amount;
            fputcsv($file, $row);
        }

        fclose($file);
        $this->info("File $file_url is ready");

        return Command::SUCCESS;
    }
}
