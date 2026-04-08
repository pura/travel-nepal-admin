<?php

namespace App\Controller\Admin;

use App\Entity\TransportService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TransportServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TransportService::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            AssociationField::new('supplier')->setRequired(false),
            ChoiceField::new('serviceType')->setChoices(TransportService::getServiceTypeChoices())->hideOnIndex(),
            ChoiceField::new('vehicleType')->setChoices(TransportService::getVehicleTypeChoices())->hideOnIndex(),
            IntegerField::new('capacity')->hideOnIndex(),
            TextField::new('baseArea')->hideOnIndex(),
            TextareaField::new('priceNotes')->hideOnIndex(),
            BooleanField::new('isActive'),
        ];
    }
}
