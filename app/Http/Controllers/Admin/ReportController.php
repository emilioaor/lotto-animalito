<?php

namespace App\Http\Controllers\Admin;

use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{

    /**
     * Carga la vista de reporte
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function report()
    {
        return view('user.report');
    }

    /**
     * Carga una vista con el reporte
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function generateReport(Request $request)
    {
        $start = \DateTime::createFromFormat('Y-m-d', $request->start);
        $end = \DateTime::createFromFormat('Y-m-d', $request->end);

        $start->setTime(00, 00, 00);
        $end->setTime(23, 59, 59);

        $tickets = Ticket::where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->where('status', Ticket::STATUS_ACTIVE)
            ->orderBy('id', 'DESC')
            ->get()
        ;

        $total = 0;
        $gain = 0;
        foreach ($tickets as $ticket) {
            $total += $ticket->total();
            $gain += $ticket->gainAmount();
        }

        return view('report.daily', [
            'tickets' => $tickets,
            'start' => $start,
            'end' => $end,
            'total' => $total,
            'gain' => $gain,
        ]);
    }
}
