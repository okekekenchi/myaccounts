<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

trait HasExport
{
  /**
   * The class that will be retrieved
   */
  protected $model;
	
	public function export(Request $request)
	{
		$validation = validator($request->all(), Static::exportRules());
		
		if ( $validation->fails() )
			return response( [ 'errors' => $validation->errors()->messages() ], 422 );
		
		$query = $this->model->forClient();

		$query = $this->filterQuery($query, $request);
		
		$records = $query->skip(intval($request->payload['offset']))
										 ->take(intval($request->payload['limit']))
										 ->get()
										 ->map(function ($record) use ($request) {
											return $record->only($request->selectedColumns);
										 })
										 ->all();

		return response($records);
	}

	private function filterQuery($query, $request)
	{
		if ( $request->filters )
		{
			foreach ($request->filters as $filter)
			{
				if ($filter['operator'] == 'like') {
					$query = $query->where( $filter['field'], 'like', "%{$filter['value']}%" );
				}
				elseif ($filter['operator'] == 'like%') {
					$query = $query->where( $filter['field'], 'like', "{$filter['value']}%" );
				}
				elseif ($filter['operator'] == '%like') {
					$query = $query->where( $filter['field'], 'like', "%{$filter['value']}" );
				}
				else {
					$query = $query->where( $filter['field'], $filter['operator'], $filter['value'] );
				}
			}
		}

		return $query;
	}

	private static function exportRules()
	{
		return [			
			'selectedColumns' => 'array',
			'filters' => 'array',
			'payload' => 'array',
			
			'payload.offset' => 'required|numeric|min:0',
			'payload.limit' => 'required|numeric|min:0|max:6000',

			'filters.*.field' => 'required',
			'filters.*.operator' =>[ 'required', Rule::in(Arr::pluck( config('filter.operators'), 'id'))],
			'filters.*.value' => 'required',
		];
	}
	
}