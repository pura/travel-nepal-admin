<?php

namespace App\Controller\Admin;

use App\Entity\ItineraryTemplate;
use App\Form\Type\StringLinesArrayType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ItineraryTemplateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ItineraryTemplate::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            TextField::new('slug'),
            AssociationField::new('startingRegion')->setRequired(false),
            ChoiceField::new('tripType')->setChoices(ItineraryTemplate::getTripTypeChoices())->hideOnIndex(),
            IntegerField::new('durationDays')->hideOnIndex(),
            ChoiceField::new('budgetLevel')
                ->setChoices(ItineraryTemplate::getBudgetLevelChoices())
                ->hideOnIndex(),
            ChoiceField::new('comfortLevel')
                ->setChoices(ItineraryTemplate::getComfortLevelChoices())
                ->setLabel('Comfort Level (3 levels)')
                ->hideOnIndex(),
            ChoiceField::new('difficultyLevel')
                ->setChoices(ItineraryTemplate::getDifficultyLevelChoices())
                ->setLabel('Difficulty Level (3 levels)')
                ->hideOnIndex(),
            Field::new('interestTags', 'Interest tags')
                ->setFormType(StringLinesArrayType::class)
                ->setHelp('One tag per line (any text). Shown on the public site and in export.')
                ->formatValue(static fn (?array $v): string => null !== $v && [] !== $v ? implode(', ', $v) : '—'),
            TextareaField::new('summary')->hideOnIndex(),
            TextareaField::new('description')
                ->setHelp('Long description for the public trip page and export.')
                ->hideOnIndex(),
            TextField::new('priceAmount', 'Price from (amount)')
                ->setHelp('As shown to visitors, e.g. 1910 (string, no currency symbol).')
                ->hideOnIndex(),
            TextField::new('priceCurrency', 'Price currency')
                ->setHelp('ISO code, e.g. GBP, USD, EUR, NPR.')
                ->hideOnIndex(),
            ImageField::new('heroImagePath', 'Hero image (upload)')
                ->setUploadDir('public/uploads/itinerary-hero')
                ->setBasePath('uploads/itinerary-hero')
                ->setUploadedFileNamePattern('[uuid].[extension]')
                ->hideOnIndex(),
            TextField::new('heroImageUrl', 'Hero image URL (optional)')
                ->setHelp('Full https URL if you prefer not to upload. When set, export uses this instead of the uploaded file.')
                ->hideOnIndex(),
            Field::new('galleryImageUrls', 'Gallery images')
                ->setFormType(StringLinesArrayType::class)
                ->setHelp('One full URL or site-relative path per line (paths are prefixed with PUBLIC_BASE_URL on export).')
                ->formatValue(static fn (?array $v): string => null !== $v && [] !== $v ? (string) \count($v).' image(s)' : '—')
                ->hideOnIndex(),
            IntegerField::new('totalDistanceKm')->hideOnIndex(),
            IntegerField::new('altitudeMaxM')->hideOnIndex(),
            IntegerField::new('altitudeMinM')->hideOnIndex(),
            Field::new('servicesIncluded', 'Included services')
                ->setFormType(StringLinesArrayType::class)
                ->setHelp('One bullet per line. Exported under services.included.')
                ->formatValue(static fn (?array $v): string => null !== $v && [] !== $v ? (string) \count($v).' line(s)' : '—')
                ->hideOnIndex(),
            Field::new('servicesExcluded', 'Excluded services')
                ->setFormType(StringLinesArrayType::class)
                ->setHelp('One bullet per line. Exported under services.excluded.')
                ->formatValue(static fn (?array $v): string => null !== $v && [] !== $v ? (string) \count($v).' line(s)' : '—')
                ->hideOnIndex(),
            Field::new('servicesOptional', 'Optional add-ons')
                ->setFormType(StringLinesArrayType::class)
                ->setHelp('One bullet per line. Exported under services.optional.')
                ->formatValue(static fn (?array $v): string => null !== $v && [] !== $v ? (string) \count($v).' line(s)' : '—')
                ->hideOnIndex(),
            BooleanField::new('isActive'),
            CollectionField::new('days')
                ->useEntryCrudForm(ItineraryTemplateDayCrudController::class)
                ->setEntryIsComplex(true)
                ->onlyOnForms(),
            AssociationField::new('days')->onlyOnDetail(),
            DateTimeField::new('createdAt')->onlyOnDetail(),
            DateTimeField::new('updatedAt')->onlyOnDetail(),
        ];
    }
}
