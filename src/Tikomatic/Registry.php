<?php
namespace Tikomatic;

final class Registry
{       
    /**
     * Static instance
     * 
     * @var SingletonRegistry
     */
    private static $_instance;
    
    /**
     * Object hash map
     *
     * @var array
     */
    private $_map;
    
    /**
     * Private constructor
     *
     */
    private function __construct()
    {}
    
    /**
     * Get the single instance
     *
     * @return SingletonRegistry
     */
    public static function getInstance()
    {
            if(self::$_instance === null)
            {
                    //First and only construction.
                    self::$_instance = new self();
            }
            return self::$_instance;
    }
    
    /**
     * Get an object by key
     *
     * @param string|int $key
     * @return object
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->_map)) {
                throw new \DomainException('Object not found');
        }
        return $this->_map[$key];

    }
    
    /**
     * Set an object by key
     *
     * @param string|int $key
     */
    public function set($key, $object)
    {
            return $this->_map[$key] = $object;
    }
    
    /**
     * Disallow cloning
     *
     */
    private function __clone()
    {}
}