<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use function PHPSTORM_META\type;

trait HasImport
{
  /**
   * The class that will be retrieved
   */
  protected $model;

	public function import(Request $request)
	{
		$this->merge_request_with_new_parameters($request);

		$validation = validator($request->all(), ['file' => 'required']);
		
		if ( $validation->fails() )
			return response( [ 'errors' => $validation->errors()->messages() ], 422 );

		$data_from_file = $this->getCSVData( $request->file('file') );
		
		//Ensures that file has at least a header and record row
		if ( count($data_from_file) < 2 ) return response( [ 'errors' => 'File is empty.' ], 422 );

		$this->mapColumnNamesToEachRecord($data_from_file);
		
		foreach ($data_from_file as $key => $data)
		{
      /**
       * Validate each record againt the import rules
       * if validation fails, the error is added to the line data item
       */
			$validation = validator( $data, $this->importRules($request, $data['id'] ?? null) );
		
			if ( $validation->fails() )
      {
				$list_of_data_with_error[$key] = $data;
				$list_of_data_with_error[$key] = array_merge(
																				$list_of_data_with_error[$key],
																				[ 'errors' => $validation->errors()->messages() ] );
			}
			else
      {
				if ( Arr::has($data, 'id') ) //Updates record if id is set else the record is created
				{
					$model = $this->model::find($data['id']);
					$updated = $model->update( Arr::only( $data, $this->fields_to_update()));					
					/**
					 * If the model is not dirty, the update event is not triggered
					 * This ensure that the updated event is triggered
					 */
					if ( $updated && !$model->isDirty()) {
						event('eloquent.updated: '.$this->model::class, $model);
					}			
				}
				else $this->model::create( Arr::only($data, $this->model->getFillable()) );
			}
		}
		
		return response([ 'message' => 'Record import completed',
											'list_of_data_with_error' => $list_of_data_with_error ?? [] ],
											201
										);
	}
	
	protected function getCSVData($file)
	{
		return array_map('str_getcsv', file($file));
	}

	/**
	 * Preg replace removes the hidden character added to the first cell by excel
	 * for encoding UTF-8 BOM
	 */
	protected function getColumnNames($csv_data)
	{
		return preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $csv_data[0]);
	}

	protected function removeColumnNames(&$csv_data)
	{
		array_shift($csv_data);
	}

	protected function  mapColumnNamesToEachRecord(&$csv_data)
	{
		$column_names = $this->getColumnNames($csv_data);
		$this->removeColumnNames($csv_data);

		array_walk($csv_data, function (&$record) use ($column_names)
		{			
			$record = combine_arrays($column_names, $record);
			$record = array_merge($record,
														[
															'client_id' =>request()->client_id,
															'company_id' =>request()->company_id,
															'created_by' =>request()->created_by,
															'updated_by' =>request()->updated_by ]
													);
		});
	}

  /**
	 * Rules that must be met for import to take place
   * Uses the default rules if not set
	 */	
	private function importRules($request, $id)
	{
		return $this->rules($request, $id);
	}

}