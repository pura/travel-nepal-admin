<?php

namespace App\Controller\Admin;

use App\Entity\Guide;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GuideCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Guide::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            AssociationField::new('supplier')->setRequired(false),
            ChoiceField::new('guideType')->setChoices(Guide::getGuideTypeChoices())->hideOnIndex(),
            ArrayField::new('languages')->hideOnIndex(),
            ArrayField::new('regionsSupported')->hideOnIndex(),
            MoneyField::new('dailyRateFrom')->setCurrency('USD')->setNumDecimals(0)->hideOnIndex(),
            MoneyField::new('dailyRateTo')->setCurrency('USD')->setNumDecimals(0)->hideOnIndex(),
            ArrayField::new('specialties')->hideOnIndex(),
            TextareaField::new('certificationNotes')->hideOnIndex(),
            BooleanField::new('isActive'),
        ];
    }
}
