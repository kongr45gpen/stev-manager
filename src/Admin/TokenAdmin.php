<?php

namespace App\Admin;

use App\Entity\Event;
use App\Entity\Instance;
use App\Entity\Space;
use App\Entity\Submitter;
use Doctrine\DBAL\Types\DecimalType;
use PhpOffice\Common\Text;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelHiddenType;
use Sonata\AdminBundle\Form\Type\ModelType;
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

class TokenAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('code', TextType::class, ['disabled' => true])
            ->add('user', ModelType::class)
            ->add('type', TextType::class, ['disabled' => true])
            ->add('expiry', DateTimeType::class, ['required' => false])
            ->add('active', CheckboxType::class, ['required' => false])
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('code')
            ->add('user')
            ->add('type')
            ->add('expiry')
            ->add('active')
        ;
    }
}
