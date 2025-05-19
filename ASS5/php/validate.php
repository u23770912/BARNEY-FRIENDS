<?php
class Validate {
    // Allowed columns for sorting/searching
    public static $SORT_COLUMNS = ['id', 'title', 'brand', 'initial_price', 'final_price', 'date_first_available'];
    public static $RETURN_FIELDS = ['id', 'title', 'brand', 'description', 'initial_price', 'final_price', 'categories', 'image_url', 'manufacturer', 'department', 'features', 'is_available', 'images', 'countryoforigin'];
    public static $SEARCH_COLUMNS = [];

    // Initialize SEARCH_COLUMNS at runtime
    public static function init() {
        self::$SEARCH_COLUMNS = array_merge(self::$SORT_COLUMNS, ['pricemin', 'pricemax']);
    }

    /**
     * Validate sort column
     */
    public static function sortColumn($input) {
        return in_array($input, self::$SORT_COLUMNS) ? $input : 'id';
    }

    /**
     * Validate order direction
     */
    public static function order($input) {
        return in_array(strtoupper($input), ['ASC', 'DESC']) ? strtoupper($input) : 'ASC';
    }

    /**
     * Validate limit (1-500)
     */
    public static function limit($input) {
        return max(1, min(500, (int)$input));
    }

    /**
     * Validate return fields
     */
    public static function returnFields($input) {
        if ($input === '*' || empty($input)) return ['*'];
        return array_intersect($input, self::$RETURN_FIELDS);
    }

  
    public static function searchCriteria($input) {
        $search = [];
        foreach ($input as $key => $value) {
            if (in_array($key, self::$SEARCH_COLUMNS)) {
                $search[$key] = $value;
            }
        }
        return $search;
    }
}

Validate::init();
?>
