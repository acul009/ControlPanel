<?php

declare(strict_types=1);

namespace core\security;

use core\security\exceptions\ProtectedSingletonException;

/**
 * <pre>
 * The ProtectedSingleton is a parent class for singleton objects which should
 * be protected against other instances. This is important for security and consistency.
 * I'm still not sure how to actually deal with inheritance though...
 * Update: I could deal with inheritance be checking if the direct parent class is this.
 *          This would rule out inheritance completly though
 * </pre>
 * @author acul
 */
abstract class ProtectedSingleton {

    /**
     * Set of already created Signletons
     * @var array
     */
    private static array $created = [];

    protected final function __construct() {
        
    }

    /**
     * This function is used to initialize the object.
     * all arguments from create() are passed on.
     * <br><b>To make this implementation effective, the child class has to declare this method final!</b>
     */
    abstract protected function init(): void;

    public static final function create(): static {
        if (self::alreadyCreated()) {
            throw new ProtectedSingletonException();
        }
        self::$created[static::class] = true;
        $args = func_get_args();
        $instance = new static();
        $instance->init(...$args);
        return $instance;
    }

    public static final function alreadyCreated(): bool {
        return isset(self::$created[static::class]);
    }
    
    public final function __clone() {
        throw new ProtectedSingletonException('You aren\'t allowed to duplicate this singleton.');
    }
    
    public final function __unserialize() {
        throw new ProtectedSingletonException('You aren\'t allowed to serialize this singleton.');
    }
    
    public final function __serialize() {
        throw new ProtectedSingletonException('You aren\'t allowed to serialize this singleton.');
    }


}
