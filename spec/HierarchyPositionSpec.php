<?php

namespace spec\Fosyl\Bridge\NestedSetForArchetype;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class HierarchyPositionSpec extends ObjectBehavior
{
    function it_assigns_nested_set_positions(ResourceInterface $pencilSkirts, ResourceInterface $skirts)
    {
        $pencilSkirts->getId()->willReturn(41);
        $skirts->getId()->willReturn(8);

        $this->beConstructedWith([
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

        $this->isDescendant($pencilSkirts, $skirts)->shouldBe(true);
    }
}
