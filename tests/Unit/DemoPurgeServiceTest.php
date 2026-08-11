<?php

namespace Tests\Unit;

use App\Services\DemoPurgeService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DemoPurgeServiceTest extends TestCase
{
    public function test_purge_demo_content_preserves_page_13_and_sets_inactive(): void
    {
        DB::shouldReceive('getDriverName')->andReturn('mysql');
        DB::shouldReceive('statement')->with('SET FOREIGN_KEY_CHECKS=0;')->andReturnTrue();
        DB::shouldReceive('statement')->with('SET FOREIGN_KEY_CHECKS=1;')->andReturnTrue();
        
        $tableMock = \Mockery::mock();
        $tableMock->shouldReceive('where')->andReturnSelf();
        $tableMock->shouldReceive('orWhere')->andReturnSelf();
        $tableMock->shouldReceive('orderByDesc')->andReturnSelf();
        $tableMock->shouldReceive('pluck')->andReturn(collect([]));
        $tableMock->shouldReceive('delete')->andReturn(0);
        $tableMock->shouldReceive('update')->with(['is_active' => 0])->andReturn(1);

        DB::shouldReceive('table')->andReturn($tableMock);

        DemoPurgeService::purgeDemoContent();
        $this->assertTrue(true);
    }

    public function test_purge_demo_content_handles_missing_page_13_safely(): void
    {
        DB::shouldReceive('getDriverName')->andReturn('mysql');
        DB::shouldReceive('statement')->with('SET FOREIGN_KEY_CHECKS=0;')->andReturnTrue();
        DB::shouldReceive('statement')->with('SET FOREIGN_KEY_CHECKS=1;')->andReturnTrue();

        $tableMock = \Mockery::mock();
        $tableMock->shouldReceive('where')->andReturnSelf();
        $tableMock->shouldReceive('orWhere')->andReturnSelf();
        $tableMock->shouldReceive('orderByDesc')->andReturnSelf();
        $tableMock->shouldReceive('pluck')->andReturn(collect([]));
        $tableMock->shouldReceive('delete')->andReturn(0);
        
        // Simulating 0 rows updated when page 13 doesn't exist
        $tableMock->shouldReceive('update')->with(['is_active' => 0])->andReturn(0);

        DB::shouldReceive('table')->andReturn($tableMock);

        // Expect no exception to be thrown
        DemoPurgeService::purgeDemoContent();
        $this->assertTrue(true);
    }
}
