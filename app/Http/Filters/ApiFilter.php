<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Exception;

class ApiFilter
{
    protected $operatorMap = [
        'eq' => '=',
        'neq' => '!=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>='
    ];

    /**
     * Map of search parameters and allowed operators.
     * eg. 'email' => ['eq', 'neq']
     */
    protected $safeParams = [];

    /**
     * Map of colums names from request and columns names in the migration.
     * eg. 'roleId' => 'role_id'
     */
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

    public function includeRelations($param, Request $request, array $relationNames)
    {
        foreach ($relationNames as $relationName) {
            $requestSearchParam = $request->get('include' . ucfirst($relationName));

            if ($requestSearchParam != true) {
                continue;
            }

            switch ($param) {
                case ($param instanceof Builder):
                    $param = $param->with($relationName);
                    break;
                case ($param instanceof Model):
                    $param = $param->load($relationName);
                    break;
                default:
                    throw new Exception('$param must be an instance of Model or Collection.');
            }
        }

        return $param;
    }
}
