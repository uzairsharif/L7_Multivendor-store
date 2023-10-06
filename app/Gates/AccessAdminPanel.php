<?php
	namespace App\Gates; 
	use Illuminate\Support\Facades\DB;
		class AccessAdminPanel {
			public function allowed($user, $allowed){
				$roles =  DB::table('role_user')->leftjoin('roles','role_user.role_id','roles.id')->where('role_user.user_id',$user->id)->pluck('name')->toArray();
	            $allowed = explode(":",$allowed);
	            	return array_intersect($allowed, $roles);
			}
		}
?>