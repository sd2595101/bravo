<?php

namespace Tests\Bravo;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Jobs\ZhongHengStore;
use App\Models\Book\TaskRoot;


class ZhongHengStoreTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $model = TaskRoot::query()->find(1);
        ZhongHengStore::dispatchNow($model);
    }
}
