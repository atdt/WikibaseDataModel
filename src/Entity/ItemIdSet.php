<?php

namespace Wikibase\DataModel\Entity;

use Comparable;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

/**
 * Immutable set of ItemId objects. Unordered and unique.
 * The constructor filters out duplicates.
 *
 * @since 0.7.4
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemIdSet implements IteratorAggregate, Countable, Comparable {

	/**
	 * @var ItemId[]
	 */
	private $ids = array();

	/**
	 * @param ItemId[] $ids
	 * @throws InvalidArgumentException
	 */
	public function __construct( array $ids = array() ) {
		foreach ( $ids as $id ) {
			if ( !( $id instanceof ItemId ) ) {
				throw new InvalidArgumentException( 'ItemIdSet can only contain instances of ItemId' );
			}

			$this->ids[$id->getNumericId()] = $id;
		}
	}

	/**
	 * @see Countable::count
	 * @return int
	 */
	public function count() {
		return count( $this->ids );
	}

	/**
	 * @see IteratorAggregate::getIterator
	 * @return Traversable
	 */
	public function getIterator() {
		return new \ArrayIterator( $this->ids );
	}

	/**
	 * @param ItemId $id
	 *
	 * @return bool
	 */
	public function has( ItemId $id ) {
		return array_key_exists( $id->getNumericId(), $this->ids );
	}

	/**
	 * @see Countable::equals
	 *
	 * @since 0.1
	 *
	 * @param mixed $target
	 *
	 * @return boolean
	 */
	public function equals( $target ) {
		if ( !( $target instanceof self ) ) {
			return false;
		}

		return $this->ids == $target->ids;
	}

}
