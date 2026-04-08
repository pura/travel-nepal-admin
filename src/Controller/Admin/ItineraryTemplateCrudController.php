<?php

namespace App\Controller\Admin;

use App\Entity\ItineraryTemplate;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
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
            ChoiceField::new('tripType')->setChoices(ItineraryTemplate::getTripTypeChoices())->hideOnIndex(),
            IntegerField::new('durationDays')->hideOnIndex(),
            TextField::new('budgetLevel')->hideOnIndex(),
            ChoiceField::new('comfortLevel')
                ->setChoices(ItineraryTemplate::getComfortLevelChoices())
                ->setLabel('Comfort Level (3 levels)')
                ->hideOnIndex(),
            ChoiceField::new('difficultyLevel')
                ->setChoices(ItineraryTemplate::getDifficultyLevelChoices())
                ->setLabel('Difficulty Level (3 levels)')
                ->hideOnIndex(),
            ChoiceField::new('interestTags')
                ->setChoices(ItineraryTemplate::getInterestTagChoices())
                ->allowMultipleChoices()
                ->setLabel('Interests (e.g. Mountains, Culture, Wildlife)')
                ->hideOnIndex(),
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
