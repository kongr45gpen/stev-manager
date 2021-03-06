<?php

namespace App\Admin;


use App\Entity\Instance;
use App\Entity\Repetition;
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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->tab('General')
                // TODO: Disable editing instance if we're not creating a new event
                ->with('Instance')
                    ->add('instance', EntityType::class, [
                        'class' => Instance::class,
        //                'disabled' => true
                    ])
                ->end()
                ->with('Basic Information', ['class' => 'col-md-9'])
                    ->add('unique_id', TextType::class, ['required' => false, 'disabled' => true])
                    ->add('team', TextType::class, ['required' => false])
                    ->add('title', TextType::class)
                    ->add('space', ModelListType::class, [
                        'class' => Space::class,
                        'required' => false
                    ])
                ->end()
                ->with('Status information', ['class' => 'col-md-3'])
                    ->add('type', TextType::class, ['required' => false])
                    ->add('status', NumberType::class, ['required' => false])
                    ->add('scheduled', NumberType::class, ['required' => false])
                    ->add('hidden', CheckboxType::class, ['required' => false])
                ->end()
                ->with('Short Description', ['class' => 'col-md-5'])
                    ->add('short_description', TextareaType::class, ['required' => false])
                ->end()
                ->with('Long Description', ['class' => 'col-md-7'])
                    ->add('long_description', TextareaType::class, ['required' => false])
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
            ->end()
            ->tab('Data', ['description' => 'Instance-specific data of the event'])
                ->with('Data', ['description' => 'Instance-specific data of the event'])
                    ->add('data', ImmutableArrayType::class, [
                        'required' => false,
                        'keys' => [
                            ['audience', TextType::class, ['sonata_help'=>'Event ages','required'=>false]],
                            ['comment',TextType::class,['required'=>false]],
                            ['categories',ImmutableArrayType::class,[
                                'required'=>false,
                                'keys' => [
                                    ['experiment',CheckboxType::class,['required'=>false]],
                                    ['observation',CheckboxType::class,['required'=>false]],
                                    ['lab',CheckboxType::class,['required'=>false]],
                                    ['presentation',CheckboxType::class,['required'=>false]],
                                    ['game',CheckboxType::class,['required'=>false]],
                                    ['demonstration',CheckboxType::class,['required'=>false]]
                                ]
                            ]],
                            ['volunteers',ImmutableArrayType::class,[
                                'required'=>false,
                                'keys' => [
                                    ['cooperator_count',TextType::class,['required'=>false]],
                                    ['student_count',TextType::class,['required'=>false]],
                                    ['volunteer_count',TextType::class,['required'=>false]],
                                ]
                            ]],
                            ['time',ImmutableArrayType::class,[
                                'required'=>false,
                                'keys' => [
                                    ['start',TextType::class,['required'=>false]],
                                    ['finish',TextType::class,['required'=>false]],
                                    ['duration',TextType::class,['required'=>false,'sonata_help'=>'Duration in minutes']],
                                    ['repetition_count',TextType::class,['required'=>false]],
                                    ['other',TextareaType::class,['required'=>false]],
                                ]
                            ]],
                            ['details',ImmutableArrayType::class,[
                                'required'=>false,
                                'keys' => [
                                    ['equipment',TextareaType::class,['required'=>false]],
                                    ['cost',TextareaType::class,['required'=>false]],
                                ]
                            ]]
                        ]
                    ])
                ->end()
            ->end()
            ->tab('Repetitions')
                ->add('repetitions', CollectionType::class, [
                        'required' => false,
                    ], [
                        'edit' => 'inline',
                        'inline' => false,
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
