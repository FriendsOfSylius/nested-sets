<?php

namespace Fosyl\Bridge\NestedSetForArchetype;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class ArchetypeTree
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array
     *
     * @throws \UnexpectedValueException
     */
    public function create()
    {
        $trees = [];

        /** @var ResourceInterface[] $roots */
        $roots = $this->repository->findBy(['parent_id' => null]);

        foreach ($roots as $id => $root) {
            $trees[$root->getId()] = $this->branchOut($root);
        }

        return $trees;
    }

    /**
     * @param ResourceInterface $node
     *
     * @return array|int
     *
     * @throws \UnexpectedValueException
     */
    private function branchOut(ResourceInterface $node)
    {
        /** @var ResourceInterface[] $children */
        $children = $this->repository->findBy(['parent_id' => $node->getId()]);

        if (0 === count($children)) {
            return $node->getId();
        }

        $branch = [];

        foreach ($children as $child) {
            $branch[$child->getId()] = $this->branchOut($child);
        }

        return $branch;
    }
}
