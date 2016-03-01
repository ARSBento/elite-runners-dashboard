<?php
 
define("CLIENT_ID",10388);
define("CLIENT_SECRET","4c2d240ea6e5cd961462105b1bb897ae1d2ffdc8");
session_start();

if(!isset($_SESSION['login'])){
  if(isset($_GET['state'])){
    if($_GET['state']=='login'){
      $body= array(
                      "client_id"=>CLIENT_ID,
                      "client_secret"=>CLIENT_SECRET,
                      "code"=>$_GET['code']
                        );
      $response = getUrlContent("https://www.strava.com/oauth/token", "POST", $body);
      $_SESSION['login']=json_decode($response);
      return start();
    }
  }

  header("Location: https://www.strava.com/oauth/authorize?client_id=10388&response_type=code&redirect_uri=http://arsbento.redirectme.net/strava.php&scope=write&state=login&approval_prompt=force");   

}
else{


  start();
}

function getUrlContent($url, $requestType=null, $body=null){ 
  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_URL, $url); 
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)'); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
  curl_setopt($ch, CURLOPT_TIMEOUT, 5); 
  if($requestType == "POST"){
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
  }
  $data = curl_exec($ch); 
  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
  curl_close($ch); return ($httpcode>=200 && $httpcode<300) ? $data : false;
}
function start(){
  echo "Your Runns: <br />";

  $content=json_decode(getUrlContent("https://www.strava.com/api/v3/activities?access_token=".$_SESSION['login']->access_token."&per_page=100"));
  echo "<table>";
  foreach($content as $row){
    echo "<tr>";
    foreach ($row as $column){
      
      if(is_int($column)|| is_string($column)){
        echo "<td>";
        echo $column;
        echo "</td>";
      }
        
      
    }
    echo "</tr>";
  }
  echo "</table>";
}

?>
