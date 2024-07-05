<?php
	class Database extends PDO
	{
		public function __construct()
		{
			parent::__construct(DB_TYPE.':Server='.DB_HOST.';dbname='.DB_DBNAME.';charset='.DB_FONTS, DB_USER ,DB_PASSWORD);	
			// parent::__construct("sqlsrv:Server=db-itars.kpru.ac.th;Database=db_equipment", "sa", "51640826@tb");		
			// parent::__construct("mysql:Server=localhost;Database=stdwork", "root", "");		
		}	
	}

?>