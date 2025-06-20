<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
	use HasFactory;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'phone', 'document', 'address'];

	/**
	* Get the sales associated with the client.
	*/
	public function sales()
	{
		return $this->hasMany(Sale::class);
	}
}
