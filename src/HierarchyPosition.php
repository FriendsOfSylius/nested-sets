<?php

namespace Fosyl\Bridge\NestedSetForArchetype;

use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class HierarchyPosition
{
    /**
     * @var array
     */
    private $nestedSet;

    /**
     * @param array $nestedSet
     */
    public function __construct(array $nestedSet)
    {
        $this->nestedSet = $nestedSet;
    }

    /**
     * Is A descendant of B ?
     *
     * @param ResourceInterface $child
     * @param ResourceInterface $ancestor
     *
     * @return bool
     */
    public function isDescendant(ResourceInterface $child, ResourceInterface $ancestor)
    {
        // Too far to the left.
        if ($this->readLeft($child) < $this->readLeft($ancestor)) {
            return false;
        }

        // Too far to the right.
        if ($this->readRight($child) > $this->readRight($ancestor)) {
            return false;
        }

        return true;
    }

    /**
     * @param ResourceInterface $node
     *
     * @return mixed
     */
    private function readLeft(ResourceInterface $node)
    {
        return $this->nestedSet[$node->getId()]['left'];
    }

    /**
     * @param ResourceInterface $node
     *
     * @return mixed
     */
    private function readRight(ResourceInterface $node)
    {
        return $this->nestedSet[$node->getId()]['right'];
    }
}
