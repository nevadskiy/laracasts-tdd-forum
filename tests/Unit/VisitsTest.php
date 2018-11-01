<?php

namespace Tests\Unit;

use App\Thread;
use App\Visits;
use Tests\TestCase;

class VisitsTest extends TestCase
{
    /** @test */
    function it_records_each_visit()
    {
        // Given
        $thread = make(Thread::class, ['id' => 1]);
        $visits = new Visits(($thread));

        $visits->reset();
        $this->assertSame(0, $visits->count());

        $visits->record();
        $this->assertEquals(1, $visits->count());

        $visits->record();
        $this->assertEquals(2, $visits->count());
    }
}
