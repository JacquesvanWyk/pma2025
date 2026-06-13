<?php

namespace App\Http\Controllers;

use App\Models\AccommodationType;
use App\Models\MerchandiseItem;
use Illuminate\Http\Request;

class CampMeetingController extends Controller
{
    private const PASSWORD = 'pma2026';

    private const SESSION_KEY = 'camp_access';

    public function index()
    {
        if (! session(self::SESSION_KEY)) {
            return view('camp-meeting.gate');
        }

        $accommodationTypes = AccommodationType::active()->get();
        $tshirt = MerchandiseItem::active()->first();

        return view('camp-meeting.index', compact('accommodationTypes', 'tshirt'));
    }

    public function unlock(Request $request)
    {
        if ($request->input('password') === self::PASSWORD) {
            session([self::SESSION_KEY => true]);

            return redirect()->route('camp-meeting');
        }

        return back()->withErrors(['password' => 'Incorrect password.']);
    }

    public function thankYou(Request $request)
    {
        return view('camp-meeting.thank-you', [
            'reference' => $request->query('ref'),
            'type' => $request->query('type', 'booking'),
        ]);
    }
}
