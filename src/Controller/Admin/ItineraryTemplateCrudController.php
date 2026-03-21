<?php

namespace App\Controller\Admin;

use App\Entity\ItineraryTemplate;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
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
            TextField::new('tripType')->hideOnIndex(),
            IntegerField::new('durationDays')->hideOnIndex(),
            TextField::new('budgetLevel')->hideOnIndex(),
            TextField::new('comfortLevel')->hideOnIndex(),
            TextField::new('difficultyLevel')->hideOnIndex(),
            ArrayField::new('interestTags')->hideOnIndex(),
            TextareaField::new('summary')->hideOnIndex(),
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
