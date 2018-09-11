<?php

namespace App\Admin;


use App\Entity\Instance;
use App\Entity\Space;
use App\Entity\Submitter;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SpaceAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', TextType::class)
            ->add('address', TextareaType::class, ['required' => false])
            ->add('display', TextareaType::class, ['required' => false])
            ->add('capacity', NumberType::class, ['required' => false])
            ->add('technical_details', TextareaType::class, ['required' => false])
            ->add('logistic_details', TextareaType::class, ['required' => false])
            ->add('contact_name', TextType::class, ['required' => false])
            ->add('contact_email', TextType::class, ['required' => false])
            ->add('contact_phone', TextType::class, ['required' => false])
            ->add('contact_information', TextType::class, ['required' => false])
//            ->add('data', TextareaType::class, ['disabled' => true])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name')
            ->add('address')
            ->add('capacity')
            ->add('contact_name')
            ->add('contact_email')
            ->add('contact_phone')
        ;
    }
}
