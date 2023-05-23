<?php

namespace App\Traits;

use App\Models\DataTable;
use Illuminate\Http\Request;

trait HasDatatable
{

  public function scope_datatable($query)
  {
    return $query->forClient()->forCompany();
  }
	
	public function datatable(Request $request, DataTable $dataTable)
	{
		$query = $request->trashed ? $this->model::onlyTrashed() : $this->model;
		$query = $this->scope_datatable($query);

		if ($request->parent_id)
    {
			$children_ids = $this->model->getChildrenIds( $request->parent_id );
			$query = $query->whereIn('id', $children_ids);
		}

		if ($request->filter)
    {
			$filters = [];

			foreach ($request->filter as $key => $value) {
				if ( in_array_values($key, $this->model->getFillable()) ) {
					$filters[$key] = $value;
				}
			}

      foreach ($filters as $key => $value) {
        $query = gettype($value) === 'array' ?
                      $query->whereIn($key, $value)
                          : $query->where($key, $value);
      }
		}
		
		$total_records = $query->count();
		
		if ( $dataTable->getFilter() )
		{
			$query = $query->where('id', 'LIKE', "%{$dataTable->getFilter()}%")
										 ->orWhere('name', 'LIKE', "%{$dataTable->getFilter()}%");
		}

		$queried_records = $query->orderBy($dataTable->getOrderBy(), $dataTable->getOrder())
											->skip($dataTable->getOffset())
											->take($dataTable->getLimit())
											->get()
											->map(function ($data) use ($request) {
												return $request->selectedColumns == '*' ? $data
																									: $data->only($request->selectedColumns);
											});

		$total_queried_records = $queried_records->count();
		$response = $dataTable::responseParameters( $queried_records->all(),
																								$total_records,
																								$total_queried_records
																							);
		return response($response->data);
	}

}