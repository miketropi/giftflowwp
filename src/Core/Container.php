<?php
/**
 * PSR-11 compatible service container for GiftFlow.
 *
 * @package GiftFlow
 * @subpackage Core
 */

namespace GiftFlow\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lightweight service container supporting lazy instantiation,
 * singleton factories, and filter-based service registration.
 */
class Container {

	/**
	 * Registered service definitions.
	 *
	 * @var array<string, callable|object>
	 */
	private $definitions = array();

	/**
	 * Resolved singleton instances.
	 *
	 * @var array<string, object>
	 */
	private $instances = array();

	/**
	 * Register a service definition.
	 *
	 * @param string          $id     Service identifier (class name or alias).
	 * @param callable|object $value  Factory callable or pre-built instance.
	 * @return void
	 */
	public function set( string $id, $value ): void {
		$this->definitions[ $id ] = $value;
		unset( $this->instances[ $id ] );
	}

	/**
	 * Retrieve a service instance.
	 *
	 * @template T
	 * @param class-string<T> $id Service identifier.
	 * @return T
	 * @throws \RuntimeException If the service cannot be resolved.
	 */
	public function get( string $id ): object {
		if ( isset( $this->instances[ $id ] ) ) {
			return $this->instances[ $id ];
		}

		if ( ! isset( $this->definitions[ $id ] ) ) {
			$instance = new $id( $this );
			$this->instances[ $id ] = $instance;
			return $instance;
		}

		$definition = $this->definitions[ $id ];

		if ( is_callable( $definition ) ) {
			$instance = $definition( $this );
		} else {
			$instance = $definition;
		}

		$this->instances[ $id ] = $instance;
		return $instance;
	}

	/**
	 * Check if a service is registered.
	 *
	 * @param string $id Service identifier.
	 * @return bool
	 */
	public function has( string $id ): bool {
		return isset( $this->definitions[ $id ] );
	}

	/**
	 * Register multiple services at once.
	 *
	 * @param array<string, callable|object> $services Map of id => factory or instance.
	 * @return void
	 */
	public function register( array $services ): void {
		foreach ( $services as $id => $definition ) {
			$this->set( $id, $definition );
		}
	}

	/**
	 * Invoke a callable with resolved dependencies from the container.
	 *
	 * Accepts a class name and method, resolving the class from the container
	 * and calling the method.
	 *
	 * @param string $class  Fully qualified class name.
	 * @param string $method Method name to call on the resolved instance.
	 * @param array  $args   Arguments to pass to the method.
	 * @return mixed
	 */
	public function call( string $class, string $method, array $args = array() ) {
		$instance = $this->get( $class );
		return $instance->{$method}( ...$args );
	}
}
