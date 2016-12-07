<?php

namespace spec\Fosyl\Bridge\NestedSetForArchetype;

use PhpSpec\ObjectBehavior;
use Fosyl\Bridge\NestedSetForArchetype\ArchetypeTree;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class ArchetypeTreeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ArchetypeTree::class);
    }

    function let(RepositoryInterface $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_builds_a_tree(
        RepositoryInterface $repository,
        ResourceInterface $clothes,
        ResourceInterface $accessories,
        ResourceInterface $dress,
        ResourceInterface $skirt,
        ResourceInterface $suit,
        ResourceInterface $hat,
        ResourceInterface $gloves,
        ResourceInterface $sunglasses,
        ResourceInterface $tie,
        ResourceInterface $pencilSkirts,
        ResourceInterface $wrapSkirts,
        ResourceInterface $leatherGloves,
        ResourceInterface $woolGloves
    ) {
        $repository->findBy(['parent_id' => null])->willReturn([$clothes, $accessories]);

        // Level 0

        $clothes->getId()    ->willReturn(1);
        $accessories->getId()->willReturn(3);

        $repository->findBy(['parent_id' => 1])->willReturn([$dress, $skirt, $suit]);
        $repository->findBy(['parent_id' => 3])->willReturn([$gloves, $hat, $sunglasses, $tie]);

        // Level 1

        $dress->getId()->willReturn(5);
        $skirt->getId()->willReturn(8);
        $suit->getId() ->willReturn(9);

        $gloves->getId()    ->willReturn(26);
        $hat->getId()       ->willReturn(27);
        $sunglasses->getId()->willReturn(29);
        $tie->getId()       ->willReturn(31);

        $repository->findBy(['parent_id' => 5])->willReturn([]);
        $repository->findBy(['parent_id' => 8])->willReturn([$pencilSkirts, $wrapSkirts]);
        $repository->findBy(['parent_id' => 9])->willReturn([]);

        $repository->findBy(['parent_id' => 26])->willReturn([$leatherGloves, $woolGloves]);
        $repository->findBy(['parent_id' => 27])->willReturn([]);
        $repository->findBy(['parent_id' => 29])->willReturn([]);
        $repository->findBy(['parent_id' => 31])->willReturn([]);

        // Level 2

        $pencilSkirts->getId()->willReturn(41);
        $wrapSkirts->getId()->willReturn(43);

        $leatherGloves->getId()->willReturn(777);
        $woolGloves->getId()->willReturn(888);

        $repository->findBy(['parent_id' => 41])->willReturn([]);
        $repository->findBy(['parent_id' => 43])->willReturn([]);

        $repository->findBy(['parent_id' => 777])->willReturn([]);
        $repository->findBy(['parent_id' => 888])->willReturn([]);

        // End

        $this->create()->shouldBe([
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
    }
}
