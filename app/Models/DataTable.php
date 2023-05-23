<?php

namespace App\Models;

use Illuminate\Http\Request;

class DataTable
{
    protected $offset;
    protected $limit;
    protected $orderBy;
    protected $order;
    protected $filter;
    protected $request;
    protected static $instance; 

    function __construct(Request $request)
    {        
        $this->request = $request;
        
        $this->offset   = $request->payload['currentPage'];
        $this->limit    = $request->payload['perPage'] ?? 10;
        $this->orderBy  = $request->payload['sortBy'] ?? 'created_at';
        $this->order    = $request->payload['sortDesc'] ? 'desc' : 'asc';
        $this->filter   = $request->payload['filter'] ?? null;
        self::$instance = $this;
    }

    function setOffset (int $offset): int
    {
        return $this->offset = $offset;
    }

    function getOffset () {
        return $this->offset;
    }

    function setLimit (int $limit): int
    {
        return $this->limit = $limit;
    }

    function getLimit ()
    {
        return $this->limit;
    }

    function setOrderBy ($orderBy) {
        return $this->orderBy = $orderBy;
    }

    function getOrderBy () {
        return $this->orderBy;
    }

    function setOrder (string $order): string
    {
        return $this->order = $order;
    }

    function getOrder () {
        return $this->order;
    }

    function setFilter (string $filter): string
    {
        return $this->filter = $filter;
    }

    function getFilter() {
        return $this->filter;
    }

    static function responseParameters (array $records, int $total_records, int $total_queried_records): object
    {
        return (object)[
            'data' => [
                "totalRecords" => $total_records,
                "recordsFiltered" => self::$instance->filter === null ? $total_records : $total_queried_records,
                "records" => $records
            ]
        ];
    }
}
