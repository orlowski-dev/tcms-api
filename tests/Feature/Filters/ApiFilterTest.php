<?php

namespace Tests\Feature\Filters;

use App\Http\Filters\ApiFilter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class TempFilters extends ApiFilter
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

class ApiFilterTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $filteredQuery;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testTransform(): void
    {
        $searchQuery = [
            'a' => ['eq' => 1],
            'b' => ['neq' => 1],
            'c' => ['lt' => 1],
            'd' => ['lte' => 1],
            'e' => ['gt' => 1],
            'f' => ['gte' => 1]
        ];
        $request = Request::create('/', 'GET', $searchQuery);

        $filter = new TempFilters();
        $this->filteredQuery = $filter->transform($request);
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
        $filter = new TempFilters();
        $obj = $filter->includeRelations($this->user, $request);
        $loaded = $obj->relationLoaded('profile');
        $this->assertEquals(true, $loaded);
    }

    public function testIncludeRelationsOnBuilder(): void
    {
        $request = Request::create('/', 'GET', ['includeProfile' => true]);

        $builder = User::where($this->filteredQuery);
        $filters = new TempFilters();
        $builder = $filters->includeRelations($builder, $request);
        $builderRelations = $builder->getEagerLoads();
        $this->assertEquals(true, isset($builderRelations['profile']));
    }
}
