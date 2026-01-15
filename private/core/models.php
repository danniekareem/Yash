<?php

/**
 * Main Model class
 *
 * This class provides simple CRUD helpers for table-based models.
 * It extends `Database` and uses its `query()` method to execute SQL.
 */
class Model extends Database
{
	// Optional: a model can declare a `$table` property. If not set,
	// the constructor will generate a default table name.
	protected string $table = '';

	// Optional per-model whitelist of allowed column names.
	// If empty, no whitelist is applied and behavior falls back to
	// regex-based validation. Concrete models can set this property
	// to an array like: ['id','email','firstname',...]
	protected array $columns = [];


	/**
	 * Constructor
	 * If the concrete model class does not declare a `$table` property,
	 * build a simple default: "ClassName" -> "ClassNames".
	 */
	function __construct()
	{
		if (empty($this->table)) {
			$this->table = ucfirst($this::class) . 's';
		}
	}


	/**
	 * where()
	 * Simple helper to return rows matching a single column.
	 *
	 * @param string $column Column name to filter by
	 * @param mixed  $value  Value to match (bound safely)
	 * @return array|false   Result from Database::query()
	 */
	public function where($column, $value)
	{
		// If a per-model whitelist exists (non-empty), require the column be in it
		if (!empty($this->columns)) {
			if (!in_array($column, $this->columns, true)) {
				return false;
			}
		} else {
			// Otherwise validate the column name with a safe regex
			if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
				return false;
			}
		}

		// Wrap column in backticks to avoid reserved-word issues
		$column = "`$column`";

		$query = "SELECT * FROM {$this->table} WHERE $column = :value";
		return $this->query($query, [
			'value' => $value
		]);
	}


	/**
	 * findAll()
	 * Return all rows from this model's table.
	 *
	 * @return array|false
	 */
	public function findAll()
	{
		$query = "SELECT * FROM {$this->table}";
		return $this->query($query);
	}


	/**
	 * insert()
	 * Insert a row using an associative array of column => value.
	 * Uses named placeholders so values are bound safely by PDO.
	 *
	 * @param array $data
	 * @return mixed
	 */
	public function insert($data)
	{
		// Extract array keys (column names)
		$keys = array_keys($data);

		// If a whitelist is provided on the model, filter incoming data to it
		if (!empty($this->columns)) {
			$data = array_intersect_key($data, array_flip($this->columns));
			$keys = array_keys($data);
			if (empty($keys)) {
				// Nothing to insert after filtering
				return false;
			}
		}

		/**
		 * Build column list and wrap each column in backticks.
		 * This protects us from:
		 * - MySQL reserved keywords (e.g. rank)
		 * - Special characters
		 *
		 * Result:
		 * `firstname`,`lastname`,`email`,`rank`
		 */
		$columns = '`' . implode('`,`', $keys) . '`';

		// Build named placeholders
		$placeholders = ':' . implode(',:', $keys);

		// Final SQL
		$query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

		/**
		 * Execute query using prepared statements
		 * $data is passed directly so PDO binds values safely
		 * Database::query now returns affected rows for non-SELECT queries
		 */
		return $this->query($query, $data, "insert");
	}


	/**
	 * update()
	 * Update a row identified by $id_column = $id with data in $data array.
	 *
	 * @param mixed  $id
	 * @param array  $data
	 * @param string $id_column
	 * @return mixed
	 */
	public function update($id, $data, $id_column = 'id')
	{
		/**
		 * $id         → value of the record identifier
		 * $data       → associative array of columns to update
		 * $id_column  → primary key column name (default: id)
		 */

		$keys = array_keys($data);

		// If a whitelist is provided on the model, filter incoming data to it
		if (!empty($this->columns)) {
			$data = array_intersect_key($data, array_flip($this->columns));
			$keys = array_keys($data);
			if (empty($keys)) {
				// Nothing to update after filtering
				return false;
			}
		}

		/**
		 * Build SET clause:
		 * `firstname` = :firstname,
		 * `email`     = :email
		 */
		$set = [];
		foreach ($keys as $key) {
			$set[] = "`$key` = :$key";
		}

		$set_clause = implode(', ', $set);

		/**
		 * Final SQL query
		 */
		$query = "UPDATE {$this->table}
				SET $set_clause
				WHERE `$id_column` = :__id";

		/**
		 * Add ID placeholder safely
		 */
		$data['__id'] = $id;

		return $this->query($query, $data, "update");
	}


	/**
	 * delete()
	 * Remove a row from the table by id.
	 *
	 * @param mixed  $id        Value of the identifier
	 * @param string $id_column Column name of the identifier (default: id)
	 * @return mixed            Result from Database::query()
	 */
	public function delete($id, $id_column = 'id')
	{
		// Build delete SQL using a named placeholder for safety
		$query = "DELETE FROM {$this->table} WHERE `$id_column` = :__id";

		// Execute with id bound
		return $this->query($query, ['__id' => $id], "delete");
	}

	/**
	 * findById()
	 * Return a single row object by id or false when not found.
	 * This uses `where()` internally so column validation/whitelist is applied.
	 *
	 * @param mixed  $id
	 * @param string $id_column
	 * @return object|false
	 */
	public function findById($id, $id_column = 'id')
	{
		// Ignore deleted_at when fetching for edit
		$query = "SELECT * FROM {$this->table} WHERE `$id_column` = :value";
		$rows = $this->query($query, ['value' => $id]);

		if ($rows && count($rows) > 0) {
			return $rows[0];
		}
		return false;
	}



	/**
	 * deleteById()
	 * Validate the id column then delete the row, returning affected rows (int)
	 * or false on error.
	 *
	 * @param mixed  $id
	 * @param string $id_column
	 * @return int|false
	 */
	public function deleteById($id, $id_column = 'id')
	{
		// Validate id column against whitelist or regex (same rules as where())
		if (!empty($this->columns)) {
			if (!in_array($id_column, $this->columns, true)) {
				return false;
			}
		} else {
			if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $id_column)) {
				return false;
			}
		}

		$query = "DELETE FROM {$this->table} WHERE `$id_column` = :__id";
		return $this->query($query, ['__id' => $id], "delete");
	}
}
