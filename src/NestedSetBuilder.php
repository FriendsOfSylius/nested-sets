<?php

namespace Fosyl\Bridge\NestedSetForArchetype;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class NestedSetBuilder
{
    /**
     * @var ArchetypeTree
     */
    private $archetypeTree;

    /**
     * @param ArchetypeTree $archetypeTree
     */
    public function __construct(ArchetypeTree $archetypeTree)
    {
        $this->archetypeTree = $archetypeTree;
    }

    /**
     * @param int $root
     *
     * @return array
     *
     * @throws \UnexpectedValueException
     */
    public function build($root)
    {
        $arrayTree = $this->archetypeTree->create();

        $inner        = new \RecursiveArrayIterator($arrayTree[$root]);
        $iteratorTree = new \RecursiveTreeIterator($inner);

        $n    = 1;
        $mptt = [];
        $id   = null;

        $breadcrumbs   = [];
        $previousLevel = 0;

        $mptt[$root] = [
            'left'  => $n,
            'right' => null,
            'level' => $previousLevel,
            'root'  => $root,
        ];

        foreach ($iteratorTree as $id => $node) {
            $currentLevel = 1 + $iteratorTree->getDepth();

            if ($previousLevel >= $currentLevel) {
                $lastNode = array_pop($breadcrumbs);

                ++$n;

                $mptt[$lastNode]['right'] = $n;

                if ($previousLevel > $currentLevel) {
                    $delta = $previousLevel - $currentLevel;

                    $lastNode = null;

                    ++$n;

                    for (; $delta > 0; --$delta) {
                        $lastNode = array_pop($breadcrumbs);

                        $mptt[$lastNode]['right'] = $n;
                    }
                }
            }

            ++$n;

            $mptt[$id] = [
                'left'  => $n,
                'right' => null,
                'level' => $currentLevel,
                'root'  => $root,
            ];

            $previousLevel = $currentLevel;

            $breadcrumbs[] = $id;
        }

        $mptt[$id]['right'] = 2 * count($mptt) - 1;
        $mptt[$root]['right'] = 2 * count($mptt);

        return $mptt;
    }
}
