<?php

namespace App\Admin;


use App\Entity\Instance;
use App\Entity\Volunteer;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VolunteerAvailabilityAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('volunteer', EntityType::class, [
                'class' => Volunteer::class
            ])
            ->add('start', DateTimeType::class)
            ->add('end', DateTimeType::class, ['required' => false])
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('volunteer')
            ->add('start')
            ->add('end')
            // You may also specify the actions you want to be displayed in the list
            ->add('_action', null, [
                'actions' => [
//                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
                'row_align' => 'right',
                'header_style' => 'width: 15%'
            ])

        ;
    }
}
