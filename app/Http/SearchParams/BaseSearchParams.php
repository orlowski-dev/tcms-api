<?php

namespace App\Http\SearchParams;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Exception;

class BaseSearchParams
{
    protected $relationNames = [];

    protected $operatorMap = [
        'eq' => '=',
        'neq' => '!=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>='
    ];

    protected $safeParams = [];
    protected $columnMap = [];

    /**
     * Transorms the search parameters into an eloquent query that respects the allowed parameter names and their operators.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function makeEloquentQuery(Request $request): array
    {
        $eloquentQuery = [];

        foreach ($this->safeParams as $param => $operators) {
            $query = $request->query($param);

            if (!isset($query)) {
                continue;
            }

            $column = $this->columnMap[$param] ?? $param;

            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloquentQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloquentQuery;
    }

    /**
     * Enables dynamic inclusion of relations in Eloquent model (Model or Builder) based on safe parameters passed in the request.
     * @param mixed $obj
     * @param \Illuminate\Http\Request $request
     * @throws \Exception
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
     */
    public function includeRelations($obj, Request $request): Model|Builder
    {
        foreach ($this->relationNames as $relationName) {
            $requestSearchParam = $request->get('include' . ucfirst($relationName));

            if ($requestSearchParam != true) {
                continue;
            }

            switch ($obj) {
                case ($obj instanceof Builder):
                    $obj = $obj->with($relationName);
                    break;
                case ($obj instanceof Model):
                    $obj = $obj->load($relationName);
                    break;
                default:
                    throw new Exception('$obj must be an instance of Model or Builder.');
            }
        }

        return $obj;
    }
}
