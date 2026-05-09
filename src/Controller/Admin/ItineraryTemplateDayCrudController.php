<?php

namespace App\Controller\Admin;

use App\Entity\ItineraryTemplateDay;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Provider\AdminContextProviderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ItineraryTemplateDayCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly AdminContextProviderInterface $adminContextProvider,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return ItineraryTemplateDay::class;
    }

    /**
     * When this form is embedded in ItineraryTemplateCrudController (days collection), the parent
     * ItineraryTemplate is set via addDay(); omit the association to avoid a duplicate selector.
     */
    private function isEmbeddedInItineraryTemplateForm(): bool
    {
        $context = $this->adminContextProvider->getContext();

        return null !== $context
            && ItineraryTemplateCrudController::class === $context->getCrud()?->getControllerFqcn();
    }

    /**
     * Hide long fields only on the standalone “Itinerary template days” list.
     * When embedded in an itinerary edit form, do not call hideOnIndex() — EasyAdmin applies that
     * to collection rows and would strip fields from the parent form.
     */
    /**
     * @return TextareaField|TextField|IntegerField|NumberField
     */
    private function compactDayIndexColumns(TextareaField|TextField|IntegerField|NumberField $field): TextareaField|TextField|IntegerField|NumberField
    {
        if (!$this->isEmbeddedInItineraryTemplateForm()) {
            $field->hideOnIndex();
        }

        return $field;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        if (!$this->isEmbeddedInItineraryTemplateForm()) {
            yield AssociationField::new('itineraryTemplate')->setRequired(true)->autocomplete();
        }

        yield IntegerField::new('dayNumber');
        yield TextField::new('title');
        yield $this->compactDayIndexColumns(TextareaField::new('description'));
        yield $this->compactDayIndexColumns(
            TextareaField::new('details')
                ->setHelp('Long narrative for this day (public/export). If empty, Activity notes is used on export.'),
        );
        yield $this->compactDayIndexColumns(
            TextField::new('meals')
                ->setHelp('e.g. "Meals: Breakfast, Lunch, Dinner"'),
        );
        yield $this->compactDayIndexColumns(
            TextField::new('destinationName')
                ->setHelp('Plain name for export (e.g. Kathmandu). If empty, the linked Destination name is used.'),
        );
        yield IntegerField::new('distanceKm')->setLabel('Distance (km)');
        yield IntegerField::new('altitudeMaxM')->setLabel('Day altitude max (m)');
        yield IntegerField::new('altitudeMinM')->setLabel('Day altitude min (m)');
        yield NumberField::new('durationHours')
            ->setLabel('Duration (hours)')
            ->setNumDecimals(2);
        yield TextField::new('accommodation')->setHelp('e.g. Tea house / mountain lodge');
        yield AssociationField::new('destination')->setRequired(false)->autocomplete();
        yield $this->compactDayIndexColumns(TextField::new('hotelCategory'));
        yield $this->compactDayIndexColumns(TextField::new('transportType'));
        yield $this->compactDayIndexColumns(TextField::new('guideType'));
        yield $this->compactDayIndexColumns(TextareaField::new('activityNotes'));
    }
}
