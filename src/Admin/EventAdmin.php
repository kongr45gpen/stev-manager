<?php

namespace App\Admin;


use App\Entity\Instance;
use App\Entity\Space;
use App\Entity\Submitter;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Sonata\CoreBundle\Form\Type\ImmutableArrayType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EventAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            // TODO: Disable editing instance if we're not creating a new event
            ->with('Instance')
                ->add('instance', EntityType::class, [
                    'class' => Instance::class,
    //                'disabled' => true
                ])
            ->end()
            ->with('Basic Information', ['class' => 'col-md-9'])
                ->add('team', TextType::class, ['required' => false])
                ->add('title', TextType::class)
                ->add('space', ModelListType::class, [
                    'class' => Space::class,
                    'required' => false,
                    'btn_delete' => false
                ])
            ->end()
            ->with('Status information', ['class' => 'col-md-3'])
                ->add('status', NumberType::class, ['required' => false])
                ->add('scheduled', NumberType::class, ['required' => false])
                ->add('hidden', CheckboxType::class, ['required' => false])
            ->end()
            ->with('Submitters')
                ->add('submitters', CollectionType::class, [
                    'required' => false,
                ], [
                    'edit' => 'inline',
                    'inline' => 'table',
    //                'sortable' => 'position',
                ])
            ->end()
            ->with('Data', ['description' => 'Instance-specific data of the event'])
                ->add('data', ImmutableArrayType::class, [
                    'keys' => [
                        ['content', TextareaType::class, [
                            'sonata_help' => 'Set the content'
                        ]],
                        ['public', CheckboxType::class, []],
                    ]
                ])
            ->end()
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('team')
            ->addIdentifier('title')
            ->add('status')
            ->add('scheduled')
            ->add('hidden')
        ;
    }
}
