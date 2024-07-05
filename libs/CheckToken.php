<?php
    function GenarateTokenSecret($username){
        $key = "Api";
        $token = array(
            "iat" => time(),
            "exp" => time()+(60*60*24*365),
            "userid" => $username
        );
        $jwt = Firebase\JWT\JWT::encode($token, $key);
        return $jwt;
    }
    
    // function GenarateToken($username,$typeuser,$user_id){
    //     $key = "Api";
    //     $token = array(
    //         "iat" => time(),
    //         "exp" => time()+(60*60*60),
    //         "typeuser" => $typeuser,
    //         "userid" => $username,
    //         "user_id" => $user_id
    //     );

    //     $jwt = Firebase\JWT\JWT::encode($token, $key);
    //     return $jwt;
    // }

    function GenarateToken($username){
        $key = "Api";
        $token = array(
            "iat" => time(),
            "exp" => time()+(60*60*24),
            "username" => $username,
        );

        $jwt = Firebase\JWT\JWT::encode($token, $key);
        return $jwt;
    }


    function CheckToken($Token){
        $key = "Api";
        try{
            $token = (array)Firebase\JWT\JWT::decode($Token, $key, array('HS256'));
            
            return $token;
        }
        catch (\Exception $e){
            http_response_code(401);
        }
    }



?>