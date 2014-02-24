<?php
class ModelRolesRoles extends Model {
  /*
   * Not a model that interacts with the store database.
   * Instead it deals with dss roles management.
   */
	public function getRoles($id) {
	       $url = "https://" . ROLES_MANAGEMENT_API . "/people/" . $id . ".json";

	       $httpauth = ROLES_MANAGEMENT_APPNAME . ":" . ROLES_MANAGEMENT_SECRET;
	       $header = array("Accept" => "application/vnd.roles-management.v1");

	       $ch = curl_init($url);

	       curl_setopt($ch, CURLOPT_USERPWD, $httpauth);
	       curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	       curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	       $curlresult = curl_exec($ch);

	       curl_close($ch);

	       $roles = json_decode($curlresult);
	       $user_roles = array();

	       if (isset($roles->role_assignments)) {
		   foreach ($roles->role_assignments as $role) {
	       	       if ($role->application_id == ROLES_MANAGEMENT_APPID) {
		               $user_roles[] = $role->token;
		       }
		   }
	       
		   return $user_roles;
	       } else {
		   return false;
	       }
	}
}
?>