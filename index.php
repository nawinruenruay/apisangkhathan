<?php
        ini_set('display_errors', 1);
        ini_set('memory_limit', '256M'); 

        header("Cache-Control: no-cache, must-revalidate"); //[PHP Clear Cache] เครียด้วย php
        header("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT"); //[PHP Clear Cache] เครียด้วย php
        // header("Access-Control-Allow-Origin: *");

        require 'libs/Bootstrap.php';
    	require 'libs/Controller.php';
    	require 'libs/Model.php';
    	require 'libs/View.php';
        require 'libs/Allows.php';

    	require 'libs/Database.php';
    	require 'libs/Session.php';

        // require 'public/PHPExcel.php';
        // require 'public/PHPWord.php';

        // require 'libs/DefineVar.php';

        require 'libs/CheckToken.php';
        // require 'libs/CheckCitizent.php';
        
        require 'config/paths.php';
        require 'config/database.php';

        
        // require 'public/phpmailer/class.phpmailer.php';
        // require 'public/phpmailer/sms.class.php';
        //require 'public/phpmailer/PHPMailerAutoload.php';
        
        // create PDF //
        require 'public/includes/thainumber.php';
        require('public/fpdf/fpdf.php');
        require 'public/includes/code39.php';
        require('public/includes/rotate.php');
        require('public/includes/qrcode.class.php');
        define ('FPDF_FONTPATH','public/fpdf/font/');

        // require 'public/jwt/JWT.php';
        include_once 'public/jwt/BeforeValidException.php';
        include_once 'public/jwt/ExpiredException.php';
        include_once 'public/jwt/SignatureInvalidException.php';
        include_once 'public/jwt/JWT.php';
	$app = new Bootstrap();

?>