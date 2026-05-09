<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Edits JSON-backed arrays/objects as pretty-printed JSON in a textarea.
 */
final class JsonTextareaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function (?array $model): string {
                if (null === $model || [] === $model) {
                    return '';
                }

                return json_encode($model, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            },
            function (mixed $view): ?array {
                if (!\is_string($view) || '' === trim($view)) {
                    return null;
                }

                $decoded = json_decode($view, true, 512, JSON_THROW_ON_ERROR);
                if (!\is_array($decoded)) {
                    throw new \InvalidArgumentException('JSON root must be an object or array.');
                }

                return $decoded;
            },
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'empty_data' => '',
            'attr' => ['rows' => 16],
        ]);
    }

    public function getParent(): string
    {
        return TextareaType::class;
    }
}
