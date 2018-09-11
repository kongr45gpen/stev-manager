<?php

namespace App\Admin;


use App\Entity\Instance;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VolunteerAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('instance', EntityType::class, [
                'class' => Instance::class,
            ])
            ->add('surname', TextType::class)
            ->add('name', TextType::class)
            ->add('father_name', TextType::class, ['required' => false])
            ->add('email', TextType::class, ['required' => false])
            ->add('age', IntegerType::class, ['required' => false])
            ->add('phone', TextType::class, ['required' => false])
            ->add('property', TextType::class, ['required' => false])
            ->add('school', TextType::class, ['required' => false])
            ->add('level', TextType::class, ['required' => false])
            ->add('health', TextareaType::class, ['required' => false])
            ->add('interests', TextareaType::class, ['required' => false])
            ->add('subscription', CheckboxType::class, ['required' => false])
            ->add('updates', CheckboxType::class, ['required' => false])
            ->add('joined', CheckboxType::class, ['required' => false])
            ->add('preparation', CheckboxType::class, ['required' => false])
            ->add('gender', IntegerType::class, ['required' => false])
//            ->add('availability', TextareaType::class, ['disabled' => true])
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
            ->add('instance')
            ->add('email')
            ->add('age')
            ->add('phone')
        ;
    }
}
