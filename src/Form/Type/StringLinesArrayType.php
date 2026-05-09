<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Edits string[] as a textarea (one value per line). Empty lines are ignored.
 */
final class StringLinesArrayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function (mixed $model): string {
                if (null === $model || [] === $model) {
                    return '';
                }

                if (!\is_array($model)) {
                    return trim((string) $model);
                }

                $strings = [];
                foreach ($model as $item) {
                    $strings[] = trim((string) $item);
                }

                return implode("\n", array_filter($strings, static fn (string $s): bool => $s !== ''));
            },
            function (mixed $view): array {
                if (!\is_string($view) || '' === trim($view)) {
                    return [];
                }

                $lines = preg_split('/\r\n|\r|\n/', $view);
                if (false === $lines) {
                    return [];
                }

                return array_values(array_filter(
                    array_map(static fn (string $s): string => trim($s), $lines),
                    static fn (string $s): bool => $s !== '',
                ));
            },
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'empty_data' => '',
        ]);
    }

    public function getParent(): string
    {
        return TextareaType::class;
    }
}
