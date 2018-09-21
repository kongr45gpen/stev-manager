<?php

namespace App\Admin;


use App\Entity\Instance;
use App\Entity\Space;
use App\Entity\Submitter;
use DoctrineExtensions\Query\Mysql\Date;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InstanceAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', TextType::class)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Student Week' => Instance::STUDENT_WEEK,
                    'Professor Week' => Instance::PROFESSOR_WEEK,
                    'City Week' => Instance::CITY_WEEK]
            ])
            ->add('start_date', DateTimeType::class, ['required' => false])
            ->add('end_date', DateTimeType::class, ['required' => false])
            ->add('colour', ChoiceType::class, [
                'choices' => [
                    'blue' => 'blue',
                    'indigo' => 'indigo',
                    'purple' => 'purple',
                    'pink' => 'pink',
                    'red' => 'red',
                    'orange' => 'orange',
                    'yellow' => 'yellow',
                    'green' => 'green',
                    'teal' => 'teal',
                    'primary' => 'primary',
                    'secondary' => 'secondary',
                    'success' => 'success',
                    'info' => 'info',
                    'warning' => 'warning',
                    'danger' => 'danger',
                    'light' => 'light',
                    'dark' => 'dark',
                ]
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('name')
            ->add('type')
            ->add('start_date')
            ->add('end_date');
    }
}
