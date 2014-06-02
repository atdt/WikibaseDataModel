<?php

namespace Wikibase\DataModel\Term;

use Comparable;
use InvalidArgumentException;

/**
 * @since 0.7.3
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Thiemo Mättig
 */
class Fingerprint implements Comparable {

	/**
	 * @return Fingerprint
	 */
	public static function newEmpty() {
		return new self(
			new TermList( array() ),
			new TermList( array() ),
			new AliasGroupList( array() )
		);
	}

	private $labels;
	private $descriptions;
	private $aliasGroups;

	public function __construct( TermList $labels, TermList $descriptions, AliasGroupList $aliasGroups ) {
		$this->labels = $labels;
		$this->descriptions = $descriptions;
		$this->aliasGroups = $aliasGroups;
	}

	/**
	 * Returns a TermList containing all the labels.
	 *
	 * @since 0.7.3
	 *
	 * @return TermList|Term[]
	 */
	public function getLabels() {
		return $this->labels;
	}

	/**
	 * @since 0.7.4
	 *
	 * @param string $languageCode
	 * @return Term
	 */
	public function getLabel( $languageCode ) {
		return $this->labels->getByLanguage( $languageCode );
	}

	/**
	 * @since 1.0
	 *
	 * @param string $languageCode
	 * @param string $labelText
	 *
	 * @throws InvalidArgumentException
	 */
	public function setLabel( $languageCode, $labelText ) {
		$this->labels->setTerm( new Term( $languageCode, $labelText ) );
	}

	/**
	 * @since 0.7.4
	 *
	 * @param string $languageCode
	 */
	public function removeLabel( $languageCode ) {
		$this->labels->removeByLanguage( $languageCode );
	}

	/**
	 * Returns a TermList containing all the descriptions.
	 *
	 * @since 0.7.3
	 *
	 * @return TermList|Term[]
	 */
	public function getDescriptions() {
		return $this->descriptions;
	}

	/**
	 * @since 0.7.4
	 *
	 * @param string $languageCode
	 * @return Term
	 */
	public function getDescription( $languageCode ) {
		return $this->descriptions->getByLanguage( $languageCode );
	}

	/**
	 * @since 1.0
	 *
	 * @param string $languageCode
	 * @param string $descriptionText
	 *
	 * @throws InvalidArgumentException
	 */
	public function setDescription( $languageCode, $descriptionText ) {
		$this->descriptions->setTerm( new Term( $languageCode, $descriptionText ) );
	}

	/**
	 * @since 0.7.4
	 *
	 * @param string $languageCode
	 */
	public function removeDescription( $languageCode ) {
		$this->descriptions->removeByLanguage( $languageCode );
	}

	/**
	 * Returns an AliasGroupList containing all the alias groups.
	 *
	 * @since 0.7.4
	 *
	 * @return AliasGroupList|AliasGroup[]
	 */
	public function getAliasGroups() {
		return $this->aliasGroups;
	}

	/**
	 * @since 0.7.4
	 *
	 * @param string $languageCode
	 * @return AliasGroup
	 */
	public function getAliasGroup( $languageCode ) {
		return $this->aliasGroups->getByLanguage( $languageCode );
	}

	/**
	 * @since 1.0
	 *
	 * @param string $languageCode
	 * @param string[] $aliases
	 *
	 * @throws InvalidArgumentException
	 */
	public function setAliasGroup( $languageCode, array $aliases ) {
		$this->aliasGroups->setGroup( new AliasGroup( $languageCode, $aliases ) );
	}

	/**
	 * @since 0.7.4
	 *
	 * @param string $languageCode
	 */
	public function removeAliasGroup( $languageCode ) {
		$this->aliasGroups->removeByLanguage( $languageCode );
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

		return $this->descriptions->equals( $target->getDescriptions() )
			&& $this->labels->equals( $target->getLabels() )
			&& $this->aliasGroups->equals( $target->getAliasGroups() );
	}

	/**
	 * @since 0.7.4
	 *
	 * @return bool
	 */
	public function isEmpty() {
		return $this->labels->count() === 0
			&& $this->descriptions->count() === 0
			&& $this->aliasGroups->count() === 0;
	}

	/**
	 * @since 0.7.4
	 *
	 * @param TermList $labels
	 */
	public function setLabels( TermList $labels ) {
		$this->labels = $labels;
	}

	/**
	 * @since 0.7.4
	 *
	 * @param TermList $descriptions
	 */
	public function setDescriptions( TermList $descriptions ) {
		$this->descriptions = $descriptions;
	}

	/**
	 * @since 0.7.4
	 *
	 * @param AliasGroupList $groups
	 */
	public function setAliasGroups( AliasGroupList $groups ) {
		$this->aliasGroups = $groups;
	}

}
