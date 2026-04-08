<?php

namespace App\Controller\Admin;

use App\Entity\Representative;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RepresentativeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Representative::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            TextField::new('email')->setRequired(false),
            TextField::new('phone')->setRequired(false),
            TextField::new('whatsapp')->setRequired(false),
            ArrayField::new('languages')->hideOnIndex(),
            TextField::new('activeHours')->setRequired(false)->hideOnIndex(),
            BooleanField::new('isActive'),
            DateTimeField::new('createdAt')->onlyOnDetail(),
            DateTimeField::new('updatedAt')->onlyOnDetail(),
        ];
    }
}
