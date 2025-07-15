<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Discount;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class DiscountTest extends TestCase
{
    #[Test] // 
    public function it_returns_true_for_an_active_discount()
    {
        // 1. Persiapan Data Uji
        $discount = new Discount([
            'start_date' => Carbon::now()->subDay(),
            'end_date' => Carbon::now()->addDay(),
        ]);

        // 2. Eksekusi & 3. Evaluasi Hasil
        $this->assertTrue($discount->isActive());
    }

    #[Test]
    public function it_returns_false_for_an_expired_discount()
    {
        $discount = new Discount([
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => Carbon::now()->subDay(),
        ]);

        $this->assertFalse($discount->isActive());
    }

    #[Test]
    public function it_returns_false_for_a_future_discount()
    {
        $discount = new Discount([
            'start_date' => Carbon::now()->addDay(),
            'end_date' => Carbon::now()->addDays(5),
        ]);

        $this->assertFalse($discount->isActive());
    }

    #[Test]
    public function it_returns_true_for_a_same_day_discount()
    {
        $discount = new Discount([
            'start_date' => Carbon::today(),
            'end_date' => Carbon::today(),
        ]);

        $this->assertTrue($discount->isActive());
    }
}