<?php

/**
 * Database connection
 */
class Database
{

	private function connect()
	{
		// code..
		$string = DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME;
		if (!$con = new PDO($string, DBUSER, DBPASS)) {
			die("could not connect to database");
		}

		return $con;
	}



	public function query($query, $data = [], $data_type = "object")
	{

		$con = $this->connect();
		$stm = $con->prepare($query);

		if ($stm && $stm->execute($data)) {

			// Determine if the statement returned columns (SELECT) or not (INSERT/UPDATE/DELETE)
			if ($stm->columnCount() > 0) {
				// This is a SELECT-like query: return rows
				return ($data_type === "object")
					? $stm->fetchAll(PDO::FETCH_OBJ)
					: $stm->fetchAll(PDO::FETCH_ASSOC);
			} else {
				// Non-SELECT query: return number of affected rows (0+)
				return $stm->rowCount();
			}
		}

		// On failure, return false
		return false;
	}
}
