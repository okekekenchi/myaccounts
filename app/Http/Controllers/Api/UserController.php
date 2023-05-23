<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataTable;
use App\Models\User;
use App\Traits\HasCrud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
	use HasCrud;

	protected $storageDisk;
	protected $storageLocation;

	public function __construct()
	{
		$this->model = new User;
		$this->storageDisk = 'public';
		$this->storageLocation = Config::get('filesystems.location.profile_picture');
	}

	public function create(Request $request)
	{
		$request->merge(['created_by' => Auth::id()]);
				
		$validation = validator($request->all(), Static::ruleList());

		if ( $validation->fails() )
			return response( [ 'errors' => $validation->errors()->messages() ], 422 );

		$user_data = $request->all();
		$user_data['type'] = $request->type ?? Auth::user()->type;

		if ( $user = $this->model::create($user_data) )
		{
			return response( ['message' => 'Record created', 'data' => $user], 201 );
		} 

		return response([ 'message' => 'Record creation failed'], 422);
	}

	public function update(Request $request)
	{
		$request->merge(['updated_by' => Auth::id()]);
				
		$rules = Static::ruleList();
		array_push( $rules, ['id' => 'required|exists:users,id']);

		$validation = validator($request->all(), $rules);

		if ( $validation->fails() )
			return response( [ 'errors' => $validation->errors()->messages() ], 422 );
		
		$update_exceptions = [ 'two_factor_auth', 'type'];
		$user = $this->model::find($request->id);

		if ( $user->update( $request->except($update_exceptions) ) )
		{
			return response( ['message' => 'Record updated', 'data' => $user], 200);
		}
		
		return response( ['error' => 'Record not updated'], 403);
	}
	
	public function saveSignature(Request $request)
	{
		if ( isset( $request->id, $request->type, $request->content) )
		{
			$user = User::find($request->id);
			
			$user->signature = array_merge( $user->signature ?? [], [ $request->type => $request->content ]);
			
			return $user->save() ?
							response( [ 'message' => 'signature saved' ], 200 ) :
							response( ['message' => 'Unprocessible entity, Error saving Signature'], 403 );
		}
		else return response( ['message' => 'Missing parameters. Could not process request'], 404 );
	}  

	public function saveProfileImage(Request $request)
	{
		if ( isset( $request->profile_picture, $request->id ) )
		{
			if ( $user = User::find($request->id) )
			{
					$user->profile_picture = $request->profile_picture;
						
					if( $user->save() ) {
					
						return response( [ 'url' => $user->profile_picture ], 200 );
					}
					else return response( ['message' => 'Unprocessible entity, Error uploading image'], 403 );
			}
			return response( ['message' => 'User not found'], 404 );
		}
		return response( ['message' => 'An invalid image file was uploaded'], 403 );
	}

	public function removeProfileImage(Request $request)
	{
		if ( $request->has('id') )
		{
			if ( $user = User::find($request->id) )
			{
				$user->profile_picture = null;

				if ( $user->save() ) {
					return response( ['message' => 'Image removed'], 200 );
				}
			}
			return response( ['message' => 'User not found'], 404 );
		}

		return response( ['message' => 'Invalid id parameter'], 403 );
	}

	public function changePassword(Request $request, User $user)
	{
		$rules= [ 'id' => 'bail|required',
							'old' => 'bail|required|min:8|max:50',
							'password' => 'bail|required|min:8|max:50|confirmed' ];

		$validation = validator($request->all(), $rules);

		if ( $validation->fails() )
			return response( [ 'errors' => $validation->errors()->messages() ], 422 );
		
		if ( $user = $user::find($request->id) )
		{
			if (Hash::check( $request->old, $user->password ))
			{
				$user->password = Hash::make($request->new);

				if ( $user->save() ) {
					return response( ['message' => 'Password Changed'], 200 );
				}
				return response( ['message' => 'Password update failed'], 403 );
			}
			return response( [ 'errors' => [ 'old' => 'Invalid password.' ] ], 422 );
		}
		return response( ['message' => 'User not found'], 404 );
	}

   public function dataTable(Request $request, DataTable $dataTable, User $user)
	{
		$users = $request->trashed ? $user::onlyTrashed() : $user;
		$users = $users->ScopeAgentOrClient()
									 ->type($request->userType ?? Auth::user()->type)
									 ->withoutMe();

		$total_records = $users->count();

		if ( $dataTable->getFilter() )
		{
			$users = $users->where('id', 'LIKE', "%{$dataTable->getFilter()}%")
								->orWhereRaw("concat(first_name, ' ', last_name) 'LIKE' %{$dataTable->getFilter()}%")
								->orWhere('middle_name', 'LIKE', "%{$dataTable->getFilter()}%");
		}

		$users = $users->orderBy($dataTable->getOrderBy(), $dataTable->getOrder())
							->skip($dataTable->getOffset())
							->take($dataTable->getLimit())
							->get()
							->map(function ($user) use ($request) { return $user->only($request->selectedColumns); });

		$total_queried_records = $users->count();
		$response = $dataTable::responseParameters( $users->all(), $total_records, $total_queried_records );
		return response($response->data);
	}

	private static function ruleList()
	{
		return [
			'first_name' => 'required|string|max:50',
			'last_name' => 'required|string|max:50',
			'middle_name' => 'max:50',
			'role_id' => 'max:200|exists:roles,id',
			'superior' => 'max:200|exists:users,id',
			'team_id' => 'max:200|exists:teams,id',
			'agent_id' => 'exists:agents,id',
			'client_id' => 'exists:clients,id',
			'designation' => 'max:100',
			'email' => 'string|email|max:200',
			'comment' => 'max:225',
			'type' => 'max:225'
		];
	}

}


/*$this->validate($request, [ 'profilePicture' => 'image|mimes:jpeg,png,jpg,gif,svg' ]);

        if ( $request->hasFile('profilePicture') && $request->file('profilePicture')->isValid() )
        {
            $profilePicture = $request->file('profilePicture');

            $file = [
                'name' => basename( $profilePicture->store($this->storageLocation, $this->storageDisk) ),
                'original_name' => $profilePicture->getClientOriginalName(),
                'size' => $profilePicture->getSize()
            ];

            $user = User::find($request->id);

            if ( isset($user->profile_picture) ) {
                Storage::disk($this->storageDisk)->delete( $this->storageLocation.'/'.$user->profile_picture['name'] );
            }

            $user->profile_picture = $file;
            
            if( $user->save() )
                return response( [
                    'url' => Storage::url($this->storageLocation.'/'.$user->profile_picture['name'])
                    ], 200 );
            else 
                return response( ['message' => 'Unprocessible entity, Error uploading image'], 403 );
        }
        else return response( ['message' => 'An invalid image file was uploaded'], 403 );
 */