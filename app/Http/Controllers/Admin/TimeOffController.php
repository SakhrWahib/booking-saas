<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ProviderTimeOff;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;

class TimeOffController extends Controller
{
    public function index()
    {
        return view('admin.time-offs.index', [
            'upcoming' => ProviderTimeOff::with('serviceProvider:id,name')->whereDate('end_date', '>=', today())->orderBy('start_date')->get(),
            'past' => ProviderTimeOff::with('serviceProvider:id,name')->whereDate('end_date', '<', today())->latest('end_date')->limit(10)->get(),
            'providers' => ServiceProvider::where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_provider_id' => 'required|exists:service_providers,id,tenant_id,' . tenant_id(),
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:150',
        ]);

        ProviderTimeOff::create($data);

        // تنبيه إن كانت هناك حجوزات قائمة خلال الإجازة
        $conflicts = Booking::where('service_provider_id', $data['service_provider_id'])
            ->whereBetween('booking_date', [$data['start_date'], $data['end_date']])
            ->whereIn('status', ['pending', 'confirmed', 'paid'])->count();

        $message = 'تمت إضافة الإجازة';
        if ($conflicts) {
            $message .= " — تنبيه: يوجد {$conflicts} حجز قائم خلال هذه الفترة، راجعها وأعد جدولتها.";
        }

        return redirect()->route('admin.time-offs.index')->with('success', $message);
    }

    public function destroy(ProviderTimeOff $timeOff)
    {
        $timeOff->delete();

        return redirect()->route('admin.time-offs.index')->with('success', 'تم حذف الإجازة');
    }
}
