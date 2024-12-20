<?php

namespace Tests\Feature\SearchParams;

use App\Http\SearchParams\BaseSearchParams;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class TempSearchParams extends BaseSearchParams
{
    protected $relationNames = [
        'profile'
    ];

    protected $safeParams = [
        'a' => ['eq'],
        'b' => ['neq'],
        'c' => ['lt'],
        'd' => ['lte'],
        'e' => ['gt'],
        'f' => ['gte']
    ];
}

class BaseSearchParamsTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $filteredQuery;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testMakeEloquentQuery(): void
    {
        $searchQuery = [
            'a' => ['eq' => 1],
            'b' => ['neq' => 1],
            'c' => ['lt' => 1],
            'd' => ['lte' => 1],
            'e' => ['gt' => 1],
            'f' => ['gte' => 1],
            'g' => ['eq' => 1]
        ];
        $request = Request::create('/', 'GET', $searchQuery);

        $tsp = new TempSearchParams();
        $this->filteredQuery = $tsp->makeEloquentQuery($request);
        $expected = [
            ['a', '=', 1],
            ['b', '!=', 1],
            ['c', '<', 1],
            ['d', '<=', 1],
            ['e', '>', 1],
            ['f', '>=', 1]
        ];

        $this->assertEquals($expected, $this->filteredQuery);
    }

    public function testIncludeRelationsOnModel(): void
    {
        $request = Request::create('/', 'GET', ['includeProfile' => true]);
        $tsp = new TempSearchParams();
        $obj = $tsp->includeRelations($this->user, $request);
        $loaded = $obj->relationLoaded('profile');
        $this->assertEquals(true, $loaded);
    }

    public function testIncludeRelationsOnBuilder(): void
    {
        $request = Request::create('/', 'GET', ['includeProfile' => true]);

        $builder = User::where($this->filteredQuery);
        $tsp = new TempSearchParams();
        $builder = $tsp->includeRelations($builder, $request);
        $builderRelations = $builder->getEagerLoads();
        $this->assertEquals(true, isset($builderRelations['profile']));
    }
}
