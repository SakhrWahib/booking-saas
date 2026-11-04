<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\Booking;

class FixInvoiceAmounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:fix-amounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إصلاح مبالغ الفواتير لتتطابق مع الحجوزات';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء إصلاح مبالغ الفواتير...');
        
        $invoices = Invoice::with(['booking.product', 'booking.selected_add_ons'])->get();
        $fixedCount = 0;
        
        foreach ($invoices as $invoice) {
            if ($invoice->booking) {
                $this->info("معالجة الفاتورة: {$invoice->invoice_number}");
                
                // تحديث الفاتورة من الحجز
                $invoice->updateFromBooking($invoice->booking);
                $fixedCount++;
                
                $this->info("تم إصلاح الفاتورة: {$invoice->invoice_number} - المبلغ الجديد: {$invoice->total_amount} ر.س");
            }
        }
        
        $this->info("تم إصلاح {$fixedCount} فاتورة بنجاح!");
        
        return 0;
    }
} 