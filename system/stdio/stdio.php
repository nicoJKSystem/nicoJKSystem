<?php

namespace STDIO{
	class PUTS{
		private $buffer = [];
		
		function __construct(){
		
		}

		public function puts($string){
			$this->buffer[] = $string;
		}

		function __destruct() {
       		print join($this->buffer, "\n");
       	}
	}
}

?>