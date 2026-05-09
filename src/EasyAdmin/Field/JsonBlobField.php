<?php

declare(strict_types=1);

namespace App\EasyAdmin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Contracts\Translation\TranslatableInterface;

/**
 * Like {@see StringLinesField}: avoids EasyAdmin auto-mapping JSON doctrine columns to ArrayField/CollectionType.
 * Use with {@see \App\Form\Type\JsonTextareaType} for object/array JSON.
 */
final class JsonBlobField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, TranslatableInterface|string|bool|null $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label);
    }
}
