<?php

namespace App\Http\Controllers;

use App\Models\PledgeProgress;

class DonateController extends Controller
{
    public function index()
    {
        return view('donate.index');
    }

    public function once()
    {
        return view('donate.once');
    }

    public function monthly()
    {
        return view('donate.monthly');
    }

    public function pledge()
    {
        $pledgeProgress = PledgeProgress::current();

        return view('donate.pledge', [
            'currentAmount' => $pledgeProgress ? $pledgeProgress->current_amount : 0,
            'month' => $pledgeProgress ? $pledgeProgress->month : now()->format('F'),
            'goalAmount' => $pledgeProgress ? $pledgeProgress->goal_amount : 35000,
            'percentage' => $pledgeProgress ? $pledgeProgress->percentage : 0,
        ]);
    }
}
