<?php

namespace App\Traits;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

trait HasCrud
{
  /**
   * The class that will be retrieved
   */
  protected $model;

  /**
   * List of fields to exclude from record update
   */
  protected $exclude_fields_from_update;

  /**
   * reason why record cannot be created
   */
  protected $cannot_create_reason;

  /**
   * reason why record cannot be updated
   */
  protected $cannot_update_reason;

  /**
   * decides if record can be created
   * @return boolean
   */
  public function can_create()
  {
    return true;
  }

  public function set_cannot_create_reason($reason)
  {
    $this->cannot_create_reason = $reason;
  }

  /**
   * decides if record can be updated
   * @return boolean
   */
  public function can_update()
  {
    return true;
  }

  public function set_cannot_update_reason($reason)
  {
    $this->cannot_update_reason = $reason;
  }

  /**
   * Create a new model
   */
	public function create(Request $request)
	{
    if (!$this->can_create()) {
      return response([ 'message' => $this->cannot_create_reason], 406);
    }

		$this->merge_create_request($request);
		
		validator($request->all(), $this->rules($request))->validate();

		if ( $model = $this->model::create($request->all()) )
		{
			return response( ['message' => 'Record created', 'data' => $model], 201 );
		}

		return response([ 'message' => 'Record creation failed'], 403);
	}

  /**
   * Update an existing model
   */
	public function update(Request $request)
	{
    if (!$this->can_update()) {
      return response([ 'message' => $this->cannot_update_reason], 406);
    }

		$this->merge_update_request($request);

		validator($request->all(), $this->rules($request))->validate();
		
		$model = $this->model::find($request->id);
    
		if ( $model->update( $request->only( $this->fields_to_update()) ) )
		{
      /**
       * If the model is not dirty, the update event is not triggered
       * This ensure that the updated event is triggered 
       */
      if ( !$model->isDirty() ) event('eloquent.updated: '.$model::class, $model);

			return response( ['message' => 'Record updated', 'data' => $model], 200);
		}
		
		return response( ['error' => 'Record not updated'], 304);
	}
  
	public function clone(Request $request)
	{
    if (!$this->can_create()) {
      return response([ 'message' => $this->cannot_create_reason], 406);
    }

		$this->merge_create_request($request);

		validator($request->all(),
              Arr::only($this->rules($request), ['id', 'name']))->validate();

		$model = $this->model::find($request->id);
    
    if( $cloned_model = $model->replicate() )
    {
      $cloned_model->fill([ 'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'name' => $request->name ]);

      if( $cloned_model->save() )
      {
        return response( [ 'message' => 'Record Cloned' ], 200 );
      }
    }

    return response([ 'message' => 'Record clonning failed.' ], 304);
	}

  /**
   * Merges additional parameters to the request
   */
	private function merge_create_request(Request $request): void
	{
    $user = Auth::user();

		$request->merge([
			'created_by' => $user->id,
			'updated_by' => $user->id,
			'client_id' => $user->client_id,
      'active' => true
		]);
	}

  /**
   * Merges additional parameters to the request
   */
	private function merge_update_request(Request $request): void
	{
    $user = Auth::user();

		$request->merge([
			'updated_by' => $user->id
		]);
	}

	/**
	 * Only the fields/columns specified here will be updated
	 */	
	private function fields_to_update()
	{
    $fillables = $this->model->getFillable();

    if ($this->exclude_fields_from_update)
    {
      foreach ($this->exclude_fields_from_update as $key => $value)
      {
				if (($key = array_search($value, $fillables)) !== false) {
					unset($fillables[$key]);
				}
			}
    }
		return $fillables;
	}

  /**
   * Clears empty all records that have been soft deleted
   */ 

  public function clearTrash()
  {
    $cleared = $this->model::onlyTrashed()->forceDelete();
    
    return new JsonResponse(
        [ 'message' => $cleared ? 'Trashed records cleared' : 'Trash not cleared'],
        $cleared ? 200 : 422
    );
  }

  /**
   * Restores a record that has been soft deleted
   */
  public function restore(Request $request): JsonResponse
  {
    if ( $this->model::onlyTrashed()->find($request?->id)->restore() )
    {
      return new JsonResponse([ 'message' => 'Record restored'], 200);
    }
    
    return new JsonResponse([
        'error' => 'record not restored',
        'message' => 'either not an instance of soft delete model or record does not exist. :('
      ], 404);
  }

  /**
   * Finds a record based on parameters passed in the request
   */
  public function find(Request $request)
  {
    if (empty($request->all()))  return new JsonResponse(['error' => 'No parameter passed'], 422);

    $models = $request->trashed ? $this->model::onlyTrashed() : $this->model;

    if ($request->with) $models = $models->with($request->with);
    
    $model = $models->find($request->id);

    if ($model)
    {
      return new JsonResponse($model);
    }

    return new JsonResponse(['error' => 'No record found'], 404);
  }

  /**
   * Returns a list of records with only the selected columns
   */
  public function list(Request $request)
  {
    $models = $request->trashed ? $this->model::onlyTrashed() : $this->model;

    $models = $models->where('active', true);
    
    if ($request->filter)
    {
      foreach ($request->filter as $key => $value) {
        $models = gettype($value) === 'array' ?
                      $models->whereIn($key, $value)
                          : $models->where($key, $value);
      }
    }
    
		$models = $models->get()
                     ->map(function ($model) use ($request) {
												return $request->selectedColumns == '*'
                                        ? $model
																				: $model->only($request->selectedColumns);
                     });
                     
    if ( $models ) return response($models);

    return response(['error' => 'No record found'], 404);
  }

  /**
   * Hard deletion type permanently deletes a record
   * Soft delete tags a record and prevents it from being retrieved
   */
  public function delete(Request $request)
  {
    $deleted = false;

    if (! isset( $request->id, $request->type) )
      return new JsonResponse([ 'message'=>'Missing parameters. Record not deleted' ], 422);
    
    if ( !static::validDeletionType($request->type))
    {
      return response([
          'error' => 'Invalid value parsed for parameter:type',
          'message' => $request->type.' is not a valid deletion type'
      ], 422);
    }

    /**
     * Deletion can occur on multiple IDs as an array
     * or on a single ID as an integer
     */
    if ( gettype($request->id) == 'array' ) {
      try
      {
        DB::beginTransaction();
        $this->model::find($request->id)
                    ->each( function($item) use ($request)
                    {
                      if (!$item->hasChildren) {
                        strtolower($request->type) == 'hard' ?
                                                  $item->forceDelete() : $item->delete();
                      }
                    });
        $deleted = true;
        DB::commit();
      }
      catch(Exception $ex) {
        DB::rollBack();
      }
    }
    else {
      $model = $this->model::find($request->id);

      /**
       * Ensures the parent is responsible and does not leave the children
       * Deleting a parent with children breaks the link
       * This could lead to redundant data in the database
       */

      if (!$model->has_children) {
        $deleted = strtolower($request->type) == 'hard' ?
                    $model->forceDelete() :
                        $model->delete();
      }
      else return response( [ 'message' => 'Record not deleted. Break links to delete' ], 422);
    }

    return response( [ 'message' => $deleted ? 'Record deleted' : 'Record not deleted' ], 200);
  }

  protected static function validDeletionType(string $type): bool
  {
    return in_array(strtolower($type), ['hard', 'soft'], true);
  }

  private function rules(Request $request, $id=null)
  {
    return [];
  }
}