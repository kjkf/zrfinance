<?
function write_to_file($file_name, $message) {
  $usersModel = new \App\Models\UsersModel();
  $loggedUserID = session()->get('loggedUser');
  $userInfo = $usersModel->find($loggedUserID);
  $user = "user_id: ".$userInfo['id']."; user_role:".$userInfo['role'];

  $month = date("n");
//  $path = "writable/zr_logs/".date('Y')."/";
//  if (!file_exists(base_url($path))) {
//    mkdir(base_url($path), 0777, true);
//}
  $file_name = $month."_".$file_name.".txt";
  //$file_name = base_url($file_name);
  
  $myfile = fopen($file_name, "a") or die("Unable to open file!");
  $date = date("Y-m-d H:i:s");;
  fwrite($myfile, $date." _____ ".$user." _____ ".$message.PHP_EOL);
  
  fclose($myfile);
}
