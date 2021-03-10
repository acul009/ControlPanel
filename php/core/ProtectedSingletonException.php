<?php

namespace core;

/**
 * Thrown when trying to create a protected Singleton, that has already been created
 *
 * This should probably instantly exit
 *
 * @author acul
 */
class ProtectedSingletonException extends BadMethodCallException {

}
