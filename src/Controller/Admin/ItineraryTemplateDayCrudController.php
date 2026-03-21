<?php

namespace App\Controller\Admin;

use App\Entity\ItineraryTemplateDay;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Provider\AdminContextProviderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
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

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        if (!$this->isEmbeddedInItineraryTemplateForm()) {
            yield AssociationField::new('itineraryTemplate')->setRequired(true)->autocomplete();
        }

        yield IntegerField::new('dayNumber');
        yield TextField::new('title');
        yield TextareaField::new('description')->hideOnIndex();
        yield AssociationField::new('destination')->setRequired(false)->autocomplete();
        yield TextField::new('hotelCategory')->hideOnIndex();
        yield TextField::new('transportType')->hideOnIndex();
        yield TextField::new('guideType')->hideOnIndex();
        yield TextareaField::new('activityNotes')->hideOnIndex();
    }
}
