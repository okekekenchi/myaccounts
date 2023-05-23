<?php
  

	function combine_arrays($column_names, $record)
	{
		$acount = count($column_names);
		$bcount = count($record);
		
		$size = ($acount > $bcount) ? $bcount : $acount;

		$column_names = array_slice($column_names, 0, $size);
		$record = array_slice($record, 0, $size);
		
		foreach ($record as $key => $data) {
			$combined[ $column_names[$key] ] = $data;
		}
		return $combined;
	}

	/**
	 * Checks if the string exists in the Array values
	 */
	function in_array_values($str, $array)
	{
		$exists = false;

		foreach ($array as $key => $value) {
			if ( $value == $str ) {
				$exists = true;
				break;
			}
		}

		return $exists;
	}

	/**
	 * Checks if the string exists in the Array keys
	 */
	function in_array_keys($str, $array)
	{
		$exists = false;

		foreach ($array as $key => $value) {
			if ( $key == $str ) {
				$exists = true;
				break;
			}
		}

		return $exists;
	}