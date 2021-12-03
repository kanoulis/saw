<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Client;

class ClientController extends Controller
{
    public function getClients()
    {
        /* DB
        $clients = DB::table('clients')
            ->selectRaw('clients.id, clients.name, clients.surname, payments.created_at, payments.amount')
            ->leftJoin('payments', 'clients.id', '=', 'payments.user_id')
            ->whereRaw('payments.created_at = (SELECT MAX(created_at) FROM payments p1 WHERE p1.user_id = payments.user_id)')
            ->get();
        */
        $clients = Client::join('payments', 'payments.user_id', '=', 'clients.id')
            ->select(['clients.id', 'clients.name', 'clients.surname', 'payments.created_at', 'payments.amount'])
            ->orderBy('payments.created_at')
            ->latest('payments.created_at')
            ->get()->unique();

        return view('welcome', ['clients' => $clients]);
    }

    public function getClientsByDateRange(Request $request)
    {
        $from = $request['startDate'];
        $to = $request['endDate'];

        /* DB
        $clients = DB::table('clients')
            ->selectRaw('clients.id, clients.name, clients.surname, payments.created_at, payments.amount')
            ->leftJoin('payments', 'clients.id', '=', 'payments.user_id')
            ->whereRaw('payments.created_at = (SELECT MAX(created_at) FROM payments p1 WHERE p1.user_id = payments.user_id)')
            ->whereBetween('payments.created_at', [$from, $to])
            ->get();
        */
        $clients = Client::join('payments', 'payments.user_id', '=', 'clients.id')
            ->select(['clients.id', 'clients.name', 'clients.surname', 'payments.created_at', 'payments.amount'])
            ->orderBy('payments.created_at')
            ->latest('payments.created_at')
            ->whereBetween('payments.created_at', [$from, $to])
            ->get()->unique();

        return ['clients' => $clients];
    }
}
