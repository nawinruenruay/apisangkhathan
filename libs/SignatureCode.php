<?php
class Signature{


	

	public function GenarateSignature(){
		function randomString($length = 5) {
			$str = "";
			$characters = array_merge(range('A','Z'), range('a','z'));
			$max = count($characters) - 1;
			for ($i = 0; $i < $length; $i++) {
				$rand = mt_rand(0, $max);
				$str .= $characters[$rand];
			}
			return $str;
		}


		$strDate = date("Y-m-d"); 
		$strTime = date("H:i:s");		
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
		$setDateSignature = "$strDay $strMonthThai $strYear";

		$setCodeSignature = sprintf('%05s-%05s-%05s-%05s',randomString(),randomString(),randomString(),randomString(),randomString());
		$a = array(
			'setDateSignature' => $setDateSignature." เวลา ".$strTime.", Non-PKI Server Sign,",
			'setCodeSignature' => "Signature Code : ".$setCodeSignature
		);
		return $a;
	}

	
}
	
?>