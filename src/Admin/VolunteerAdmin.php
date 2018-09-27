<?php

namespace App\Admin;


use App\Entity\Instance;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Show\ShowMapper;
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
            ->with('Basics')
                ->add('instance', EntityType::class, [
                    'class' => Instance::class,
                ])
            ->end()
            ->with('Personal Details', ['class' => 'col-md-6'])
                ->add('surname', TextType::class)
                ->add('name', TextType::class)
                ->add('father_name', TextType::class, ['required' => false])
                ->add('email', TextType::class, ['required' => false])
                ->add('age', IntegerType::class, ['required' => false])
                ->add('phone', TextType::class, ['required' => false])
            ->end()
            ->with('Volunteer Details', ['class' => 'col-md-6'])
                ->add('property', TextType::class, ['required' => false])
                ->add('school', TextType::class, ['required' => false])
                ->add('level', TextType::class, ['required' => false])
                ->add('subscription', CheckboxType::class, ['required' => false])
                ->add('updates', CheckboxType::class, ['required' => false])
                ->add('joined', CheckboxType::class, ['required' => false])
                ->add('preparation', CheckboxType::class, ['required' => false])
            ->end()
            ->add('health', TextareaType::class, ['required' => false])
            ->add('interests', TextareaType::class, ['required' => false])
            ->add('gender', IntegerType::class, ['required' => false])
            ->add('availabilities', CollectionType::class, [
                'required' => false,
            ], [
                'edit' => 'inline',
                'inline' => 'table',
            ])
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('surname')
            ->add('name')
            ->add('instance')
            ->add('email')
            ->add('age')
            ->add('phone')
            // You may also specify the actions you want to be displayed in the list
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
                'row_align' => 'right',
                'header_style' => 'width: 15%'
            ])

        ;
    }

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('Personal Details', ['class' => 'col-md-6'])
                ->add('surname')
                ->add('name')
                ->add('father_name')
                ->add('email')
                ->add('age')
                ->add('phone')
            ->end()
            ->with('Volunteer Details', ['class' => 'col-md-6'])
                ->add('property')
                ->add('school')
                ->add('level')
                ->add('subscription')
                ->add('updates')
                ->add('joined')
                ->add('preparation')
            ->end()
            ->with('Miscellaneous')
                ->add('health')
                ->add('interests')
                ->add('gender')
                ->add('availabilities')
            ->end()
        ;
    }
}
