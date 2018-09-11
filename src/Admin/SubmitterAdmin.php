<?php

namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SubmitterAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('surname', TextType::class)
            ->add('name', TextType::class)
            ->add('email', TextType::class, ['required' => false])
            ->add('property', TextType::class, ['required' => false])
            ->add('faculty', TextType::class, ['required' => false])
            ->add('school', TextType::class, ['required' => false])
            ->add('sector', TextType::class, ['required' => false])
            ->add('lab', TextType::class, ['required' => false])
            ->add('phone', TextType::class, ['required' => false])
            ->add('phone_other', TextType::class, ['required' => false])
            ->add('hidden', CheckboxType::class, ['required' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('surname')
            ->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('surname')
            ->add('name')
            ->add('email')
            ->add('property')
            ->add('school')
            ->add('phone')
            ->add('phone_other')
            ->add('hidden')
        ;
    }
}
