<?php

namespace App\Controller\Admin;

use App\EasyAdmin\Field\JsonBlobField;
use App\EasyAdmin\Field\StringLinesField;
use App\Entity\ItineraryTemplate;
use App\Form\Type\JsonTextareaType;
use App\Form\Type\StringLinesArrayType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
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
            // StringLinesField (not EasyAdmin Field): JSON columns are auto-upgraded to ArrayField + CollectionType options,
            // which break StringLinesArrayType. Not TextareaField either: TextConfigurator rejects arrays.
            StringLinesField::new('interestTags', 'Interest tags')
                ->setFormType(StringLinesArrayType::class)
                ->setHelp('One tag per line (any text). Shown on the public site and in export.')
                ->formatValue(static function (mixed $v): string {
                    if (!\is_array($v) || [] === $v) {
                        return '—';
                    }

                    return implode(', ', array_map(static fn (mixed $x): string => (string) $x, $v));
                }),
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
                ->setRequired(false)
                ->setUploadDir('public/uploads/itinerary-hero')
                ->setBasePath('uploads/itinerary-hero')
                ->setUploadedFileNamePattern('[uuid].[extension]')
                ->hideOnIndex(),
            TextField::new('heroImageUrl', 'Hero image URL (optional)')
                ->setHelp('Full https URL if you prefer not to upload. When set, export uses this instead of the uploaded file.')
                ->hideOnIndex(),
            StringLinesField::new('galleryImageUrls', 'Gallery images')
                ->setFormType(StringLinesArrayType::class)
                ->setHelp('One full URL or site-relative path per line (paths are prefixed with PUBLIC_BASE_URL on export).')
                ->formatValue(static function (mixed $v): string {
                    if (!\is_array($v) || [] === $v) {
                        return '—';
                    }

                    return (string) \count($v).' image(s)';
                })
                ->hideOnIndex(),
            IntegerField::new('totalDistanceKm')->hideOnIndex(),
            IntegerField::new('altitudeMaxM')->hideOnIndex(),
            IntegerField::new('altitudeMinM')->hideOnIndex(),
            StringLinesField::new('servicesIncluded', 'Included services')
                ->setFormType(StringLinesArrayType::class)
                ->setHelp('One bullet per line. Exported under services.included.')
                ->formatValue(static function (mixed $v): string {
                    if (!\is_array($v) || [] === $v) {
                        return '—';
                    }

                    return (string) \count($v).' line(s)';
                })
                ->hideOnIndex(),
            StringLinesField::new('servicesExcluded', 'Excluded services')
                ->setFormType(StringLinesArrayType::class)
                ->setHelp('One bullet per line. Exported under services.excluded.')
                ->formatValue(static function (mixed $v): string {
                    if (!\is_array($v) || [] === $v) {
                        return '—';
                    }

                    return (string) \count($v).' line(s)';
                })
                ->hideOnIndex(),
            StringLinesField::new('servicesOptional', 'Optional add-ons')
                ->setFormType(StringLinesArrayType::class)
                ->setHelp('One bullet per line. Exported under services.optional.')
                ->formatValue(static function (mixed $v): string {
                    if (!\is_array($v) || [] === $v) {
                        return '—';
                    }

                    return (string) \count($v).' line(s)';
                })
                ->hideOnIndex(),
            StringLinesField::new('routeGrades', 'Route grades')
                ->setFormType(StringLinesArrayType::class)
                ->setHelp('One per line. Exported as routeGrades.')
                ->formatValue(static fn (mixed $v): string => \is_array($v) && [] !== $v ? (string) \count($v).' grade(s)' : '—')
                ->hideOnIndex(),
            StringLinesField::new('fitnessNotes', 'Fitness notes')
                ->setFormType(StringLinesArrayType::class)
                ->setHelp('One per line.')
                ->formatValue(static fn (mixed $v): string => \is_array($v) && [] !== $v ? (string) \count($v).' note(s)' : '—')
                ->hideOnIndex(),
            StringLinesField::new('recommendedSeasons', 'Recommended seasons')
                ->setFormType(StringLinesArrayType::class)
                ->setHelp('One per line (e.g. Autumn).')
                ->formatValue(static fn (mixed $v): string => \is_array($v) && [] !== $v ? implode(', ', array_map(static fn (mixed $x): string => (string) $x, $v)) : '—')
                ->hideOnIndex(),
            TextField::new('mapImageUrl', 'Map image URL')
                ->setHelp('Full URL for mapImageUrl in export.')
                ->hideOnIndex(),
            TextField::new('sourceReferenceUrl', 'Source / reference URL')
                ->setHelp('Original listing URL.')
                ->hideOnIndex(),
            JsonBlobField::new('priceTable', 'Price table (JSON)')
                ->setFormType(JsonTextareaType::class)
                ->setHelp('Structured JSON matching PublicTrip.priceTable.')
                ->formatValue(static function (mixed $v): string {
                    if (!\is_array($v) || [] === $v) {
                        return '—';
                    }

                    $s = json_encode($v, JSON_UNESCAPED_UNICODE);
                    if (\strlen($s) > 100) {
                        return \substr($s, 0, 97).'…';
                    }

                    return $s;
                })
                ->hideOnIndex(),
            StringLinesField::new('bookingFeeItems', 'Booking fee items')
                ->setFormType(StringLinesArrayType::class)
                ->hideOnIndex(),
            StringLinesField::new('gearChecklist', 'Gear checklist')
                ->setFormType(StringLinesArrayType::class)
                ->hideOnIndex(),
            TextareaField::new('trekkingGradeNotes')
                ->setHelp('Long notes for trekkingGradeNotes in export.')
                ->hideOnIndex(),
            JsonBlobField::new('faq', 'FAQ (JSON)')
                ->setFormType(JsonTextareaType::class)
                ->setHelp('Array of {question, answer} objects.')
                ->formatValue(static fn (mixed $v): string => \is_array($v) && [] !== $v ? (string) \count($v).' item(s)' : '—')
                ->hideOnIndex(),
            JsonBlobField::new('reviewSnippets', 'Review snippets (JSON)')
                ->setFormType(JsonTextareaType::class)
                ->setHelp('Array of {name, location, date}.')
                ->formatValue(static fn (mixed $v): string => \is_array($v) && [] !== $v ? (string) \count($v).' snippet(s)' : '—')
                ->hideOnIndex(),
            BooleanField::new('isActive'),
            CollectionField::new('days')
                ->useEntryCrudForm(ItineraryTemplateDayCrudController::class)
                ->setEntryIsComplex(true)
                ->renderExpanded(true)
                ->onlyOnForms(),
            AssociationField::new('days')->onlyOnDetail(),
            DateTimeField::new('createdAt')->onlyOnDetail(),
            DateTimeField::new('updatedAt')->onlyOnDetail(),
        ];
    }
}
