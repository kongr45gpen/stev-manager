<?php

namespace App\Admin;


use App\Entity\Event;
use App\Entity\Instance;
use App\Entity\Space;
use App\Entity\Submitter;
use Doctrine\DBAL\Types\DecimalType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelHiddenType;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RepetitionAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Basics', ['class' => 'col-md-6'])
                ->add('event', ModelHiddenType::class)
                ->add('date', DateTimeType::class)
                ->add('time', CheckboxType::class, ['required' => false])
                ->add('end_date', DateTimeType::class, ['required' => false])
                ->add('duration', NumberType::class, ['required' => false])
            ->end()
            ->with('Optional Details', ['class' => 'col-md-6'])
                ->add('extra', TextType::class, ['required' => false])
                ->add('separate', CheckboxType::class, ['required' => false])
                ->add('space_override', EntityType::class, [
                    'required' => false,
                    'class' => Space::class
                ])
            ->end()
        ;
    }

//    protected function configureListFields(ListMapper $listMapper)
//    {
//        $listMapper
//            ->add('id')
//            ->addIdentifier('name')
//            ->add('address')
//            ->add('capacity')
//            ->add('contact_name')
//            ->add('contact_email')
//            ->add('contact_phone')
//        ;
//    }
}
