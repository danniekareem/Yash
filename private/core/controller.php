<?php

/**
 * Base Controller Class
 *
 * This class provides two essential functions for any controller in your mini-framework:
 * 1. `view()` - loads views and passes data to them
 * 2. `load_model()` - loads model classes and returns instances
 *
 * All your controllers should extend this class to gain these features.
 */
class Controller
{
    /**
     * Load a view file and optionally pass data to it
     *
     * @param string $view The name/path of the view (relative to views folder)
     *                     Example: 'users/index' will load '../private/views/users/index.view.php'
     * @param array $data  Associative array of data to pass to the view
     *                     Example: ['name' => 'Kim', 'age' => 20]
     */
    protected function view($view, $data = [])
    {
        // Check if the passed $data is an array
        if (is_array($data)) {
            /**
             * Convert array keys into variables for the view
             *
             * Example:
             * $data = ['name' => 'Kim', 'age' => 20]
             * After extract(), inside the view file you can use:
             * $name  => 'Kim'
             * $age   => 20
             *
             * EXTR_SKIP prevents overwriting existing variables
             * if a variable with the same name already exists in the scope.
             */
            extract($data, EXTR_SKIP);
        }

        // Build the full path to the view file
        // The `.view.php` convention helps separate views from other PHP files
        $view_file = "../private/views/{$view}.view.php";

        // Check if the view file actually exists
        if (file_exists($view_file)) {
            /**
             * Include the view file
             * This is where the actual HTML/PHP template will be rendered
             * All extracted variables are available inside this view
             */
            require $view_file;
        } else {
            /**
             * If the view file does not exist, load a default 404 page
             * This prevents fatal errors and provides graceful error handling
             */
            require "../private/views/404.view.php";
        }
    }

    /**
     * Load a model class and return its instance
     *
     * @param string $model The name of the model class (e.g., 'User')
     * @return object|false Returns an instance of the model, or false if the file does not exist
     *
     * This method follows a naming convention:
     * - Models are stored in ../private/models/
     * - Model filenames are ucfirst($model) + '.php'
     * - Class name inside file should match filename
     *
     * Example:
     * $userModel = $this->load_model('user');
     * $userModel now holds an instance of the User class
     */
    protected function load_model($model)
    {
        // Capitalize the first letter to match file/class naming convention
        $class = ucfirst($model);

        // Build full path to the model file
        $path  = "../private/models/{$class}.php";

        // Check if the model file exists
        if (file_exists($path)) {
            // Include the model file once (prevents multiple includes)
            require_once $path;

            // Instantiate the model class and return the object
            return new $class();
        }

        // If the file does not exist, return false
        // This allows the controller to handle missing models gracefully
        return false;
    }
}
