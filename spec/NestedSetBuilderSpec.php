<?php

namespace spec\Fosyl\Bridge\NestedSetForArchetype;

use PhpSpec\ObjectBehavior;
use Fosyl\Bridge\NestedSetForArchetype\ArchetypeTree;
use Fosyl\Bridge\NestedSetForArchetype\NestedSetBuilder;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class NestedSetBuilderSpec extends ObjectBehavior
{
    function let(ArchetypeTree $archetypeTree)
    {
        $this->beConstructedWith($archetypeTree);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NestedSetBuilder::class);
    }

    function it_builds_a_Modified_Preorder_Tree_Traversal_sequence_from_a_tree_node(ArchetypeTree $archetypeTree)
    {
        $archetypeTree->create()->willReturn([
            1 => [
                5 => 5,
                8 => [
                    41 => 41,
                    43 => 43,
                ],
                9 => 9,
            ],
            3 => [
                26 => [
                    777 => 777,
                    888 => 888,
                ],
                27 => 27,
                29 => 29,
                31 => 31,
            ],
        ]);

        // First element key, and root should be the same value as the input.
        $this->build(1)->shouldReturn([
            1 => [
                'left'  => 1,  // Tree Order
                'right' => 12, // Tree Order

                'level' => 0,  // Tree Order
                'root'  => 1,  // ID
            ],
            5 => [
                'left'  => 2,
                'right' => 3,

                'level' => 1,
                'root'  => 1,
            ],
            8 => [
                'left'  => 4,
                'right' => 9,

                'level' => 1,
                'root'  => 1,
            ],
            41 => [
                'left'  => 5,
                'right' => 6,

                'level' => 2,
                'root'  => 1,
            ],
            43 => [
                'left'  => 7,
                'right' => 8,

                'level' => 2,
                'root'  => 1,
            ],
            9 => [
                'left'  => 10,
                'right' => 11,

                'level' => 1,
                'root'  => 1,
            ],
        ]);
    }
}
