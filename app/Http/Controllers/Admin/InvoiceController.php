<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * عرض قائمة الفواتير
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'booking', 'creator']);

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('issue_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('issue_date', '<=', $request->date_to);
        }

        // البحث في رقم الفاتورة أو اسم العميل
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->orderBy('issue_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // إحصائيات للوحة التحكم
        $stats = [
            'total' => Invoice::count(),
            'unpaid' => Invoice::where('status', 'unpaid')->count(),
            'paid' => Invoice::where('status', 'paid')->count(),
            'partially_paid' => Invoice::where('status', 'partially_paid')->count(),
            'overdue' => Invoice::overdue()->count(),
            'total_amount' => Invoice::sum('total_amount'),
            'paid_amount' => Invoice::sum('paid_amount'),
            'remaining_amount' => Invoice::sum('remaining_amount'),
        ];

        return view('admin.invoices.index', compact('invoices', 'stats'));
    }

    /**
     * عرض نموذج إنشاء فاتورة جديدة
     */
    public function create(Request $request)
    {
        $customers = Customer::orderBy('name')->get();
        $bookings = Booking::with(['customer', 'product', 'serviceProvider'])
            ->whereDoesntHave('invoice')
            ->where('status', 'completed')
            ->orderBy('booking_date', 'desc')
            ->get();

        // إذا تم تمرير booking_id، قم بتحديد الحجز
        $selectedBooking = null;
        if ($request->filled('booking_id')) {
            $selectedBooking = Booking::with(['customer', 'product', 'serviceProvider'])
                ->find($request->booking_id);
        }

        return view('admin.invoices.create', compact('customers', 'bookings', 'selectedBooking'));
    }

    /**
     * حفظ فاتورة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date|after:issue_date',
            'subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'invoice_items' => 'required|array|min:1',
            'invoice_items.*.name' => 'required|string',
            'invoice_items.*.quantity' => 'required|numeric|min:1',
            'invoice_items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string'
        ]);

        $customer = Customer::findOrFail($request->customer_id);

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'customer_id' => $request->customer_id,
            'booking_id' => $request->booking_id,
            'created_by' => Auth::id(),
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date ?: now()->addDays(30),
            'subtotal' => $request->subtotal,
            'tax_rate' => $request->tax_rate,
            'discount_amount' => $request->discount_amount ?? 0,
            'invoice_items' => $request->invoice_items,
            'notes' => $request->notes,
            'terms_conditions' => $request->terms_conditions,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'customer_address' => $customer->address ?? ''
        ]);

        $invoice->calculateAmounts();

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'تم إنشاء الفاتورة بنجاح');
    }

    /**
     * عرض تفاصيل فاتورة معينة
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'booking', 'creator']);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * عرض نموذج تعديل الفاتورة
     */
    public function edit(Invoice $invoice)
    {
        $customers = Customer::orderBy('name')->get();
        $bookings = Booking::with(['customer', 'product', 'serviceProvider'])
            ->where(function($query) use ($invoice) {
                $query->whereDoesntHave('invoice')
                      ->orWhere('id', $invoice->booking_id);
            })
            ->orderBy('booking_date', 'desc')
            ->get();

        return view('admin.invoices.edit', compact('invoice', 'customers', 'bookings'));
    }

    /**
     * تحديث الفاتورة
     */
    public function update(Request $request, Invoice $invoice)
    {
        // لا يمكن تعديل الفاتورة إذا كانت مدفوعة بالكامل
        if ($invoice->status === 'paid') {
            return back()->withErrors(['status' => 'لا يمكن تعديل فاتورة مدفوعة بالكامل']);
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date|after:issue_date',
            'subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'invoice_items' => 'required|array|min:1',
            'invoice_items.*.name' => 'required|string',
            'invoice_items.*.quantity' => 'required|numeric|min:1',
            'invoice_items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string'
        ]);

        $customer = Customer::findOrFail($request->customer_id);

        // استخدام المجموع الفرعي المرسل من النموذج
        $subtotal = $request->subtotal ?? 0;

        $invoice->update([
            'customer_id' => $request->customer_id,
            'booking_id' => $request->booking_id,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date ?: now()->addDays(30),
            'subtotal' => $subtotal,
            'tax_rate' => $request->tax_rate,
            'discount_amount' => $request->discount_amount ?? 0,
            'invoice_items' => $request->invoice_items,
            'notes' => $request->notes,
            'terms_conditions' => $request->terms_conditions,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'customer_address' => $customer->address ?? ''
        ]);

        // إعادة حساب المبالغ وحفظها
        $invoice->calculateAmounts();

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'تم تحديث الفاتورة بنجاح');
    }

    /**
     * حذف الفاتورة
     */
    public function destroy(Invoice $invoice)
    {
        // لا يمكن حذف الفاتورة إذا كانت مدفوعة جزئياً أو كلياً
        if (in_array($invoice->status, ['paid', 'partially_paid'])) {
            return back()->withErrors(['status' => 'لا يمكن حذف فاتورة تم دفعها']);
        }

        $invoice->delete();
        return redirect()->route('admin.invoices.index')
            ->with('success', 'تم حذف الفاتورة بنجاح');
    }

    /**
     * تسجيل دفعة
     */
    public function recordPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $invoice->remaining_amount,
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,digital_wallet,other',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $paymentDetails = [
            'method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes
        ];

        $invoice->recordPayment($request->amount, $request->payment_method, $paymentDetails);

        return back()->with('success', 'تم تسجيل الدفعة بنجاح');
    }

    /**
     * تطبيق خصم
     */
    public function applyDiscount(Request $request, Invoice $invoice)
    {
        $request->validate([
            'discount_amount' => 'required|numeric|min:0|max:' . $invoice->subtotal,
            'discount_reason' => 'nullable|string'
        ]);

        $invoice->applyDiscount($request->discount_amount);

        return back()->with('success', 'تم تطبيق الخصم بنجاح');
    }

    /**
     * تحديث حالة الفاتورة
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:unpaid,paid,partially_paid,overdue,cancelled,refunded',
            'payment_method' => 'nullable|string',
            'payment_notes' => 'nullable|string'
        ]);

        $oldStatus = $invoice->status;
        
        $invoice->update(['status' => $request->status]);

        if ($request->status === 'paid') {
            if (!$invoice->payment_date) {
                $invoice->update([
                    'payment_date' => now(),
                    'paid_amount' => $invoice->total_amount,
                    'remaining_amount' => 0,
                    'payment_method' => $request->payment_method ?? 'cash',
                    'payment_details' => $request->payment_notes ? ['notes' => $request->payment_notes] : null
                ]);
            }
            
            // تحديث حالة الحجز إلى "تم الدفع"
            if ($invoice->booking && $invoice->booking->status !== 'completed') {
                $invoice->booking->update(['status' => 'paid']);
            }
        }

        return back()->with('success', 'تم تحديث حالة الفاتورة' . ($oldStatus !== $request->status && $request->status === 'paid' ? ' وحالة الحجز' : '') . ' بنجاح');
    }

    /**
     * إنشاء فاتورة من حجز
     */
    public function createFromBooking(Booking $booking)
    {
        // التحقق من أن الحجز لا يحتوي على فاتورة مسبقاً
        if ($booking->invoice) {
            return redirect()->route('admin.invoices.show', $booking->invoice)
                ->with('info', 'هذا الحجز يحتوي على فاتورة مسبقاً');
        }

        $invoice = Invoice::createFromBooking($booking, Auth::id());

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'تم إنشاء الفاتورة من الحجز بنجاح');
    }

    /**
     * طباعة الفاتورة
     */
    public function print(Invoice $invoice)
    {
        $invoice->load(['customer', 'booking', 'creator']);
        return view('admin.invoices.print', compact('invoice'));
    }

    /**
     * تصدير الفواتير
     */
    public function export(Request $request)
    {
        // يمكن إضافة تصدير Excel/PDF هنا لاحقاً
        return back()->with('info', 'سيتم إضافة ميزة التصدير قريباً');
    }

    /**
     * إحصائيات الفواتير
     */
    public function statistics()
    {
        $stats = [
            'monthly_revenue' => Invoice::where('status', 'paid')
                ->whereMonth('payment_date', now()->month)
                ->sum('total_amount'),
            'pending_amount' => Invoice::whereIn('status', ['unpaid', 'partially_paid'])
                ->sum('remaining_amount'),
            'overdue_count' => Invoice::overdue()->count(),
            'this_month_invoices' => Invoice::whereMonth('issue_date', now()->month)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * تحديث فاتورة من الحجز المرتبط
     */
    public function updateFromBooking(Invoice $invoice)
    {
        if ($invoice->booking) {
            $invoice->updateFromBooking($invoice->booking);
            
            return redirect()->route('admin.invoices.show', $invoice)
                ->with('success', 'تم تحديث الفاتورة بنجاح');
        }
        
        return redirect()->back()->with('error', 'لا يوجد حجز مرتبط بهذه الفاتورة');
    }
} 