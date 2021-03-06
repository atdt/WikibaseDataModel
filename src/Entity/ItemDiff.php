<?php

namespace Wikibase\DataModel\Entity;

use Diff\DiffOp\Diff\Diff;

/**
 * Represents a diff between two Wikibase\Item instances.
 *
 * @since 0.1
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemDiff extends EntityDiff {

	/**
	 * Constructor.
	 *
	 * @param \Diff\DiffOp[] $operations
	 */
	public function __construct( array $operations = array() ) {
		$this->fixSubstructureDiff( $operations, 'links' );

		parent::__construct( $operations, true );
	}

	/**
	 * Returns a Diff object with the sitelink differences.
	 *
	 * @since 0.1
	 *
	 * @return Diff
	 */
	public function getSiteLinkDiff() {
		return isset( $this['links'] ) ? $this['links'] : new Diff( array(), true );
	}

	/**
	 * @see EntityDiff::isEmpty
	 *
	 * @since 0.1
	 *
	 * @return boolean
	 */
	public function isEmpty() {
		return parent::isEmpty()
			&& $this->getSiteLinkDiff()->isEmpty();
	}

	/**
	 * @see DiffOp::getType();
	 *
	 * @return string 'diff/' . Item::ENTITY_TYPE
	 */
	public function getType() {
		return 'diff/' . Item::ENTITY_TYPE;
	}
}
