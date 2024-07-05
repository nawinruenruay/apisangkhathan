<?php
class Bootstrap{
	function __construct(){
                //echo $_GET['sid'];
		//$url = isset($_GET['url']) ? $_GET['url'] : null;
                $url = isset($_SERVER['PATH_INFO']) ? explode('/',preg_replace('~^/?(.*?)/?$~','$1',$_SERVER['PATH_INFO'])) : array() ;
		//$url = rtrim($url,'/');
		//$url = explode('/', $url);
		//print_r($url);
                

                //echo $url[0];
		if(empty($url[0])){
			require 'controllers/index.php';
			$controller = new Index();
			$controller->index();
			return false;
		}
		$file = 'controllers/'.$url[0].'.php';
		if(file_exists($file)){
			require $file;
		} else {
			$this->error();
		}

        if(file_exists($file)){
            $controller = new $url[0];
            $controller->loadModel($url[0]);
            // echo 'controllers/'.$url.'.php';
            
           $REQUEST_METHOD = $_SERVER["REQUEST_METHOD"];
           switch($REQUEST_METHOD){
                case 'OPTIONS' : //แสดงข้อมูล
                    $this->successOk();
                    break;	
                default:
                    if(isset($url[2])){
                        if(isset($url[3])){
                            if(isset($url[4])){
                                if(isset($url[5])){
                                    if(isset($url[6])){
                                        if(isset($url[7])){
                                            if(isset($url[8])){
                                                if(isset($url[9])){
                                                    if(isset($url[10])){
                                                        if(isset($url[11])){
                                                            if(method_exists($controller, $url[1])){
                                                                $controller->{$url[1]}($url[2],$url[3],$url[4],$url[5],$url[6],$url[7],$url[8],$url[9],$url[10],$url[11]);
                                                            } else {
                                                                $this->error();
                                                            } 
                                                        } else {
                                                            if(method_exists($controller, $url[1])){
                                                                $controller->{$url[1]}($url[2],$url[3],$url[4],$url[5],$url[6],$url[7],$url[8],$url[9],$url[10]);
                                                            } else {
                                                                $this->error();
                                                            } 
                                                        }
                                                    } else {
                                                        if(method_exists($controller, $url[1])){
                                                            $controller->{$url[1]}($url[2],$url[3],$url[4],$url[5],$url[6],$url[7],$url[8],$url[9]);
                                                        } else {
                                                                $this->error();
                                                        }
                                                    } 
                                                } else {
                                                    if(method_exists($controller, $url[1])){
                                                        $controller->{$url[1]}($url[2],$url[3],$url[4],$url[5],$url[6],$url[7],$url[8]);
                                                    } else {
                                                        $this->error();
                                                    }
                                                }
                                            } else {
                                                if(method_exists($controller, $url[1])){
                                                    $controller->{$url[1]}($url[2],$url[3],$url[4],$url[5],$url[6],$url[7]);
                                                } else {
                                                    $this->error();
                                                }
                                            }
                                        } else {
                                            if(method_exists($controller, $url[1])){
                                                $controller->{$url[1]}($url[2],$url[3],$url[4],$url[5],$url[6]);
                                            } else {
                                                $this->error();
                                            }
                                        }
                                    } else {
                                        if(method_exists($controller, $url[1])){
                                            $controller->{$url[1]}($url[2],$url[3],$url[4],$url[5]);
                                        } else {
                                            $this->error();
                                        }
                                    }
                                } else {
                                    if(method_exists($controller, $url[1])){
                                        $controller->{$url[1]}($url[2],$url[3],$url[4]);
                                    } else {
                                        $this->error();
                                    }
                                }
                            } else {
                                if(method_exists($controller, $url[1])){
                                    $controller->{$url[1]}($url[2],$url[3]);
                                } else {
                                    $this->error();
                                }
                            }
                        } else {
                            if(method_exists($controller, $url[1])){
                                $controller->{$url[1]}($url[2]);
                            } else {
                                $this->error();
                            }
                        }
                        //return false;
                }else{
                        if(isset($url[1])){
    
                            if(method_exists($controller, $url[1])){
                                    $controller->{$url[1]}();
                            } else {
                                    $this->error();
                            }
                        } else {
    
                            $controller->index();
                        }
            }
                    break;
            } 
        }
               
	}

	function error(){
        header("Content-Type: application/json; charset=UTF-8");
		// require 'controllers/error.php';
		// $controller = new Error1();
        // $controller->index();
        $status = array(
            'status' => "Error!"
        );
        echo  json_encode($status, JSON_PRETTY_PRINT); 
		http_response_code(404);
		return false;
	}
    
    function successOk(){
        header("Content-Type: application/json; charset=UTF-8");
        $status = array(
            'status' => "OK!"
        );
        echo  json_encode($status, JSON_PRETTY_PRINT); 
		http_response_code(200);
	}
}
	
?>