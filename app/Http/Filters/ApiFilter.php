<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Exception;

class ApiFilter
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

    public function transform(Request $request): array
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
