<?php
class TextReportFormat extends ReportFormatBase {
	public static function display(&$report, &$request) {
		$page_template = array(
			'content'=>$report->renderReportPage('text/report','text/page')
		);
		
		header("Content-type: text/plain");
		header("Pragma: no-cache");
		header("Expires: 0");

		/**
		 * This code taken from Stack Overflow answer by ehudokai
		 * http://stackoverflow.com/a/4597190
		 */

		//first get your sizes
		$sizes = array();
		$first_row = $report->options['Rows'][0];
		foreach($first_row['values'] as $key=>$value){
			$key = $value['key'];
			$value = $value['raw_value'];
			
			//initialize to the size of the column name
			$sizes[$key] = strlen($key);
		}
		foreach($report->options['Rows'] as $row) {
			foreach($row['values'] as $key=>$value){
				$key = $value['key'];
				$value = $value['raw_value'];
				
				$length = strlen($value);
				if($length > $sizes[$key]) $sizes[$key] = $length; // get largest result size
			}
		}

		//top of output
		foreach($sizes as $length){
			echo "+".str_pad("",$length+2,"-");
		}
		echo "+\n";

		// column names
		foreach($first_row['values'] as $key=>$value){
			$key = $value['key'];
			$value = $value['raw_value'];
			
			echo "| ";
			echo str_pad($key,$sizes[$key]+1);
		}
		echo "|\n";

		//line under column names
		foreach($sizes as $length){
			echo "+".str_pad("",$length+2,"-");
		}
		echo "+\n";

		//output data
		foreach($report->options['Rows'] as $row) {
			foreach($row['values'] as $key=>$value){
				$key = $value['key'];
				$value = $value['raw_value'];
				
				echo "| ";
				echo str_pad($value,$sizes[$key]+1);
			}
			echo "|\n";
		}

		//bottom of output
		foreach($sizes as $length){
			echo "+".str_pad("",$length+2,"-");
		}
		echo "+\n";
	}
}