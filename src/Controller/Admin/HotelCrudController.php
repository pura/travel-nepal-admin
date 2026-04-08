<?php

namespace App\Controller\Admin;

use App\Entity\Hotel;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HotelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Hotel::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('region'),
            AssociationField::new('supplier')->setRequired(false),
            TextField::new('name'),
            ChoiceField::new('category')->setChoices(Hotel::getCategoryChoices())->hideOnIndex(),
            MoneyField::new('nightlyPriceFrom')->setCurrency('USD')->setNumDecimals(0)->hideOnIndex(),
            MoneyField::new('nightlyPriceTo')->setCurrency('USD')->setNumDecimals(0)->hideOnIndex(),
            BooleanField::new('isActive'),
            TextareaField::new('notes')->hideOnIndex(),
        ];
    }
}
