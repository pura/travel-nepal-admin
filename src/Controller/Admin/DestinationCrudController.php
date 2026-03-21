<?php

namespace App\Controller\Admin;

use App\Entity\Destination;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DestinationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Destination::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            TextField::new('slug'),
            TextareaField::new('shortDescription')->hideOnIndex(),
            TextEditorField::new('longDescription')->hideOnIndex(),
            TextField::new('region')->hideOnIndex(),
            ArrayField::new('activityTags')->hideOnIndex(),
            ArrayField::new('bestMonths')->hideOnIndex(),
            IntegerField::new('minDays')->hideOnIndex(),
            IntegerField::new('maxDays')->hideOnIndex(),
            TextField::new('budgetLevel')->hideOnIndex(),
            TextField::new('difficultyLevel')->hideOnIndex(),
            BooleanField::new('isActive'),
            AssociationField::new('itineraryTemplateDays')->onlyOnDetail(),
            DateTimeField::new('createdAt')->onlyOnDetail(),
            DateTimeField::new('updatedAt')->onlyOnDetail(),
        ];
    }
}
