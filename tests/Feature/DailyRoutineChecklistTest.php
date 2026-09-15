<?php

namespace Tests\Feature;

use App\Models\DailyRoutineChecklist;
use Tests\TestCase;

class DailyRoutineChecklistTest extends TestCase
{
     public function test_daily_routine_checklist_mass_assignment_accepts_location_and_uncheck_reason_fields(): void
     {
          $checklist = new DailyRoutineChecklist([
               'daily_routine_id' => 1,
               'title' => 'Cek report harian',
               'is_done' => false,
               'day_name' => 'senin',
               'latitude' => -6.2,
               'longitude' => 106.816666,
               'address' => 'Jakarta',
               'uncheck_reason' => 'Belum sesuai standar',
               'checked_at' => '2026-09-15 08:00:00',
          ]);

          $this->assertSame('senin', $checklist->day_name);
          $this->assertSame(-6.2, (float) $checklist->latitude);
          $this->assertSame(106.816666, (float) $checklist->longitude);
          $this->assertSame('Jakarta', $checklist->address);
          $this->assertSame('Belum sesuai standar', $checklist->uncheck_reason);
          $this->assertSame('2026-09-15 08:00:00', $checklist->checked_at->format('Y-m-d H:i:s'));
     }
}
