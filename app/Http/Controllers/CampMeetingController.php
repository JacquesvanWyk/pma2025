<?php

namespace App\Http\Controllers;

use App\Models\AccommodationType;
use App\Models\MerchandiseItem;
use Illuminate\Http\Request;

class CampMeetingController extends Controller
{
    public function index()
    {
        $accommodationTypes = AccommodationType::active()->get();
        $merchandiseItems = MerchandiseItem::active()->get();

        return view('camp-meeting.index', compact('accommodationTypes', 'merchandiseItems'));
    }

    public function thankYou(Request $request)
    {
        return view('camp-meeting.thank-you', [
            'reference' => $request->query('ref'),
            'type' => $request->query('type', 'booking'),
        ]);
    }
}
