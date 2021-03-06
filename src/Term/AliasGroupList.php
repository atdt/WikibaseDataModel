<?php

namespace Wikibase\DataModel\Term;

use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use OutOfBoundsException;
use Traversable;

/**
 * Unordered list of AliasGroup objects.
 * Only one group per language code. If multiple groups with the same language code
 * are provided, only the last one will be retained.
 *
 * Empty groups are not stored.
 *
 * @since 0.7.3
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class AliasGroupList implements Countable, IteratorAggregate {

	/**
	 * @var AliasGroup[]
	 */
	private $groups = array();

	/**
	 * @param AliasGroup[] $aliasGroups
	 * @throws InvalidArgumentException
	 */
	public function __construct( array $aliasGroups = array() ) {
		foreach ( $aliasGroups as $aliasGroup ) {
			if ( !( $aliasGroup instanceof AliasGroup ) ) {
				throw new InvalidArgumentException( 'AliasGroupList can only contain AliasGroup instances' );
			}

			$this->setGroup( $aliasGroup );
		}
	}

	/**
	 * @see Countable::count
	 * @return int
	 */
	public function count() {
		return count( $this->groups );
	}

	/**
	 * @see IteratorAggregate::getIterator
	 * @return Traversable
	 */
	public function getIterator() {
		return new \ArrayIterator( $this->groups );
	}

	/**
	 * @param string $languageCode
	 *
	 * @return AliasGroup
	 * @throws InvalidArgumentException
	 * @throws OutOfBoundsException
	 */
	public function getByLanguage( $languageCode ) {
		$this->assertIsLanguageCode( $languageCode );

		if ( !array_key_exists( $languageCode, $this->groups ) ) {
			throw new OutOfBoundsException(
				'There is no AliasGroup with language code "' . $languageCode . '" in the list'
			);
		}

		return $this->groups[$languageCode];
	}

	/**
	 * @param string $languageCode
	 * @throws InvalidArgumentException
	 */
	public function removeByLanguage( $languageCode ) {
		$this->assertIsLanguageCode( $languageCode );
		unset( $this->groups[$languageCode] );
	}

	private function assertIsLanguageCode( $languageCode ) {
		if ( !is_string( $languageCode ) ) {
			throw new InvalidArgumentException( '$languageCode should be a string' );
		}
	}

	/**
	 * If the group is empty, it will not be stored.
	 * In case the language of that group had an associated group, that group will be removed.
	 *
	 * @param AliasGroup $group
	 */
	public function setGroup( AliasGroup $group ) {
		if ( $group->isEmpty() ) {
			unset( $this->groups[$group->getLanguageCode()] );
		}
		else {
			$this->groups[$group->getLanguageCode()] = $group;
		}
	}

	/**
	 * @see Comparable::equals
	 *
	 * @since 0.7.4
	 *
	 * @param mixed $target
	 *
	 * @return boolean
	 */
	public function equals( $target ) {
		if ( !( $target instanceof self ) ) {
			return false;
		}

		if ( $this->count() !== $target->count() ) {
			return false;
		}

		foreach ( $this->groups as $group ) {
			if ( !$target->hasAliasGroup( $group ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @since 0.7.4
	 *
	 * @param AliasGroup $group
	 *
	 * @return boolean
	 */
	public function hasAliasGroup( AliasGroup $group ) {
		return array_key_exists( $group->getLanguageCode(), $this->groups )
			&& $this->groups[$group->getLanguageCode()]->equals( $group );
	}

	/**
	 * @since 0.8
	 *
	 * @param string $languageCode
	 *
	 * @return boolean
	 */
	public function hasGroupForLanguage( $languageCode ) {
		$this->assertIsLanguageCode( $languageCode );
		return array_key_exists( $languageCode, $this->groups );
	}

	/**
	 * @since 0.8
	 *
	 * @param string $languageCode
	 * @param string[] $aliases
	 */
	public function setAliasesForLanguage( $languageCode, array $aliases ) {
		$this->setGroup( new AliasGroup( $languageCode, $aliases ) );
	}

}
