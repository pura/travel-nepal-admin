<?php

declare(strict_types=1);

namespace App\EasyAdmin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Contracts\Translation\TranslatableInterface;

/**
 * Same idea as EasyAdmin's generic {@see \EasyCorp\Bundle\EasyAdminBundle\Field\Field}, but a distinct class name so
 * {@see \EasyCorp\Bundle\EasyAdminBundle\Factory\FieldFactory} does not replace JSON-backed properties with ArrayField
 * (which forces CollectionType options incompatible with {@see \App\Form\Type\StringLinesArrayType}).
 */
final class StringLinesField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, TranslatableInterface|string|bool|null $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label);
    }
}
