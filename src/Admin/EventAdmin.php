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

class EventAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            // TODO: Disable editing instance if we're not creating a new event
            ->add('instance', EntityType::class, [
                'class' => Instance::class,
//                'disabled' => true
            ])
            ->add('team', TextType::class, ['required' => false])
            ->add('title', TextType::class)
            ->add('space', EntityType::class, [
                'class' => Space::class,
                'required' => false
            ])
            ->add('status', NumberType::class, ['required' => false])
            ->add('scheduled', NumberType::class, ['required' => false])
            ->add('hidden', CheckboxType::class, ['required' => false])
            ->add('submitters', EntityType::class, [
                'class' => Submitter::class,
                'multiple' => true,
                'required' => false
            ])
//            ->add('data', TextareaType::class, ['disabled' => true])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('team')
            ->add('title')
            ->add('status')
            ->add('scheduled')
            ->add('hidden')
        ;
    }
}
